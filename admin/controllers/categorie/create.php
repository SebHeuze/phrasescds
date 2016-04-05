<?php

$initial_data = array(
);


$options = array();

if(isset($_POST['nom'])){
    $update_query = "INSERT INTO `categorie` (`nom`) VALUES (?)";          
    $qry = $file_db->prepare($update_query);
	$qry->execute(array($_POST['nom']));

    $_SESSION['message'] = array("type"=>"success", "message" => "Categorie crée!");

    header('Location: ?page=categorie&action=list');      
}

echo $twig->render('categorie/create.html.twig', array(
));
