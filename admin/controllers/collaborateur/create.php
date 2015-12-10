<?php

$initial_data = array(
);


$options = array();

if(isset($_POST['prenom'])){
    $update_query = "INSERT INTO `collaborateur` (`prenom`,`nom`,`couleur`) VALUES (?,?,?)";          
    $qry = $file_db->prepare($update_query);
	$qry->execute(array($_POST['prenom'], $_POST['nom'], RandomColor::one()));

    $_SESSION['message'] = array("type"=>"success", "message" => "collaborateur crée!");

    header('Location: ?page=collaborateur&action=list');      
}

echo $twig->render('collaborateur/create.html.twig', array(
));
