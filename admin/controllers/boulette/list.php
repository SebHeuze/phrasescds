<?php

$table_columns = array(
	'id_boulette', 
	'timestamp', 

);

$primary_key = "id_boulette";	

if(isset($_SESSION['message'])){
	echo $twig->render('boulette/list.html.twig', array(
		"table_columns" => $table_columns,
	    "primary_key" => $primary_key,
	    "alertes" => $_SESSION['message']
	));
	unset($_SESSION['message']);
} else {
	echo $twig->render('boulette/list.html.twig', array(
		"table_columns" => $table_columns,
	    "primary_key" => $primary_key
	));
}