<?php

$findSQL = $file_db->prepare("SELECT boulette.id_boulette, phrase.message, phrase.id_phrase, phrase.id_collaborateur FROM `boulette`, 'phrase' WHERE boulette.id_boulette = phrase.id_boulette AND boulette.id_boulette = ?");
$findSQL->execute(array($_GET['id']));
$rows_sql = $findSQL->fetchAll();

$listcollabsql = 'SELECT `id_collaborateur`, `nom`, prenom FROM `collaborateur` ORDER BY prenom';
$listcollabquery = $file_db->prepare($listcollabsql, array());
$listcollabquery->execute();
$listcollabrows = $listcollabquery->fetchAll();

if(count($rows_sql)==0){   
    $_SESSION['message'] = array("type"=>"danger", "message" => "Boulette non trouvé");

    header('Location: ?page=boulette&action=list');   
} else {
    if(isset($_POST['phrase1'])){

        $i = 1;
        while(isset($_POST['phrase'.$i])){
            $updateSQL = $file_db->prepare("UPDATE `phrase` SET `message` = ?, `id_collaborateur` = ? WHERE `id_phrase` = ?");
            $updateSQL->execute(array($_POST['phrase'.$i],$_POST['id_collaborateur'.$i],$_POST['id_phrase'.$i]));
            $i++;
        }
        $_SESSION['message'] = array("type"=>"success", "message" => "Collaborateur modifié");

        header('Location: ?page=boulette&action=list');   
        
    }

    echo $twig->render('boulette/edit.html.twig', array(
        "id" => $rows_sql[0]['id_boulette'],
        "phrases" => $rows_sql,
        "collaborateurs" => $listcollabrows
    ));
}