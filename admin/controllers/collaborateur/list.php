<?php

$table_columns = array(
	'id_collaborateur', 
	'prenom', 
	'nom',
	'couleur',

);

$primary_key = "id_collaborateur";	

if(isset($_SESSION['message'])){
	echo $twig->render('collaborateur/list.html.twig', array(
		"table_columns" => $table_columns,
	    "primary_key" => $primary_key,
	    "alertes" => $_SESSION['message']
	));
	unset($_SESSION['message']);
} else {
	echo $twig->render('collaborateur/list.html.twig', array(
		"table_columns" => $table_columns,
	    "primary_key" => $primary_key
	));
}