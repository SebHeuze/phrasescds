<?php

    $findSQL = $file_db->prepare("SELECT * FROM `collaborateur` WHERE `id_collaborateur` = ?");
    $findSQL->execute(array($_GET['id']));
    $rows_sql = $findSQL->fetchAll();


if(count($rows_sql)==0){   
    $_SESSION['message'] = array("type"=>"danger", "message" => "Collaborateur non trouvé");

    header('Location: ?page=collaborateur&action=list');   
} else {

    $initial_data = array(
		'prenom' => $rows_sql[0]['prenom'], 
		'nom' => $rows_sql[0]['nom'], 
		'couleur' => $rows_sql[0]['couleur'], 

    );


    if(isset($_POST['prenom'])){


        $updateSQL = $file_db->prepare("UPDATE `collaborateur` SET `prenom` = ?, `nom` = ?, `couleur` = ? WHERE `id_collaborateur` = ?");
        $updateSQL->execute(array($_POST['prenom'],$_POST['nom'],$_POST['couleur'],$_POST['id_collaborateur']));

        $_SESSION['message'] = array("type"=>"success", "message" => "Collaborateur modifié");

        header('Location: ?page=collaborateur&action=list');   
        
    }

    echo $twig->render('collaborateur/edit.html.twig', array(
        "id" => $rows_sql[0]['id_collaborateur'],
        "initial_data" => $initial_data
    ));
}