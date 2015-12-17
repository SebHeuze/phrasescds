<?php

if(isset($_GET['id'])){

    $rowTotal = $file_db->prepare("SELECT count(1) as count FROM `categorie` WHERE `id_categorie` = ?");
    $rowTotal->execute(array($_GET['id']));
    $items = $rowTotal->fetch();


    if($items['count']>=1){
        $rowTotal = $file_db->prepare("SELECT count(1) as count FROM `boulette` WHERE `id_categorie` = ?");
        $rowTotal->execute(array($_GET['id']));
        $items = $rowTotal->fetch();

        if($items['count']==0){
            $rowTotal = $file_db->prepare("DELETE FROM `categorie` WHERE `id_categorie` = ?");
            $rowTotal->execute(array($_GET['id']));

            $_SESSION['message'] = array("type"=>"success", "message" => "Categorie supprimé");   
        } else {
            $_SESSION['message'] = array("type"=>"danger", "message" => "La categorie est associée à au moins une boulette");
        }
    }
    else{
        $_SESSION['message'] = array("type"=>"danger", "message" => "Impossible de supprimer la categorie!");
    }
} 


 header('Location: ?page=categorie&action=list');  
