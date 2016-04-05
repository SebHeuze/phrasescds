<?php

if(isset($_GET['id'])){

    $rowTotal = $file_db->prepare("SELECT count(1) as count FROM `collaborateur` WHERE `id_collaborateur` = ?");
    $rowTotal->execute(array($_GET['id']));
    $items = $rowTotal->fetch();


    if($items['count']>=1){
        $rowTotal = $file_db->prepare("SELECT count(1) as count FROM `phrase` WHERE `id_collaborateur` = ?");
        $rowTotal->execute(array($_GET['id']));
        $items = $rowTotal->fetch();

        if($items['count']==0){
            $rowTotal = $file_db->prepare("DELETE FROM `collaborateur` WHERE `id_collaborateur` = ?");
            $rowTotal->execute(array($_GET['id']));

            $_SESSION['message'] = array("type"=>"success", "message" => "Collaborateur supprimé");   
        } else {
            $_SESSION['message'] = array("type"=>"danger", "message" => "Le collaborateur a au moins une phrase d'enregistrée");
        }
    }
    else{
        $_SESSION['message'] = array("type"=>"danger", "message" => "Impossible de supprimer le collaborateur!");
    }
} 


 header('Location: ?page=collaborateur&action=list');  
