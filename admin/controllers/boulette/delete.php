<?php

if(isset($_GET['id'])){

    $rowTotal = $file_db->prepare("SELECT count(1) as count FROM `boulette` WHERE `id_boulette` = ?");
    $rowTotal->execute(array($_GET['id']));
    $items = $rowTotal->fetchAll();
    $countrow = $items[0]['count'];


    if($countrow>=1){
        $rowTotal = $file_db->prepare("DELETE FROM `boulette` WHERE `id_boulette` = ?");
        $rowTotal->execute(array($_GET['id']));

        $rowTotal = $file_db->prepare("DELETE FROM `phrase` WHERE `id_boulette` = ?");
        $rowTotal->execute(array($_GET['id']));

        $_SESSION['message'] = array("type"=>"success", "message" => "Boulette supprimée");

        header('Location: ?page=boulette&action=list'); 
    }
    else{
        $_SESSION['message'] = array("type"=>"danger", "message" => "Impossible de supprimer la boulette!");

        header('Location: ?page=boulette&action=list');  
    }
} else {
    header('Location: ?page=boulette&action=list');  
}


