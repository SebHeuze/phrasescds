<?php


if(isset($_SESSION['message'])){
	echo $twig->render('export/settings.html.twig', array(
	    "alertes" => $_SESSION['message']
	));
	unset($_SESSION['message']);
} else {
	echo $twig->render('export/settings.html.twig', array());
}