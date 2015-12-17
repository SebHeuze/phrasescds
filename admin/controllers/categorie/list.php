<?php

$table_columns = array(
	'id_categorie', 
	'nom'
);

$primary_key = "id_categorie";	

if(isset($_SESSION['message'])){
	echo $twig->render('categorie/list.html.twig', array(
		"table_columns" => $table_columns,
	    "primary_key" => $primary_key,
	    "alertes" => $_SESSION['message']
	));
	unset($_SESSION['message']);
} else {
	echo $twig->render('categorie/list.html.twig', array(
		"table_columns" => $table_columns,
	    "primary_key" => $primary_key
	));
}