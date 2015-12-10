<?php

if(isset($_GET['id'])){

    $rowTotal = $file_db->prepare("SELECT count(1) as count FROM `collaborateur` WHERE `id_collaborateur` = ?");
    $rowTotal->execute(array($_GET['id']));
    $items = $rowTotal->fetchAll();
    $countrow = $items[0]['count'];


    if($countrow>=1){
        $rowTotal = $file_db->prepare("DELETE FROM `collaborateur` WHERE `id_collaborateur` = ?");
        $rowTotal->execute(array($_GET['id']));

        $_SESSION['message'] = array("type"=>"success", "message" => "Collaborateur supprimé");

        header('Location: ?page=collaborateur&action=list'); 
    }
    else{
        $_SESSION['message'] = array("type"=>"danger", "message" => "Impossible de supprimer le collaborateur!");

        header('Location: ?page=collaborateur&action=list');  
    }
} else {
    header('Location: ?page=collaborateur&action=list');  
}


