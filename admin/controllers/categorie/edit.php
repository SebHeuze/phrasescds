<?php

    $findSQL = $file_db->prepare("SELECT * FROM `categorie` WHERE `id_categorie` = ?");
    $findSQL->execute(array($_GET['id']));
    $rows_sql = $findSQL->fetchAll();


if(count($rows_sql)==0){   
    $_SESSION['message'] = array("type"=>"danger", "message" => "Categorie non trouvée");

    header('Location: ?page=categorie&action=list');   
} else {

    $initial_data = array(
        'nom' => $rows_sql[0]['nom'],
    );


    if(isset($_POST['nom'])){


        $updateSQL = $file_db->prepare("UPDATE `categorie` SET `nom` = ? WHERE `id_categorie` = ?");
        $updateSQL->execute(array($_POST['nom'],$_POST['id_categorie']));

        $_SESSION['message'] = array("type"=>"success", "message" => "Categorie modifié");

        header('Location: ?page=categorie&action=list');   
        
    }

    echo $twig->render('categorie/edit.html.twig', array(
        "id" => $rows_sql[0]['id_categorie'],
        "initial_data" => $initial_data
    ));
}