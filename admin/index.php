<?php

session_start();
define('INDEX_LOCK', 'ok');



// Constantes nécéssaires au fonctionnement du script
define("WW_CLASS", "class/");

require_once '../'.WW_CLASS . 'Constantes.class.php';
Constantes::repertoires();

/* * ********************************************************************** */
/* Classes nécéssaires au fonctionnement général de l'admin     			 */
/* * ********************************************************************** */
require_once '../'.WW_CLASS.'SQLite.class.php';
require_once '../'.WW_CLASS.'BouletteManager.class.php';

require_once '../'.WW_CLASS.'Collaborateur.class.php';
require_once '../'.WW_CLASS.'Phrase.class.php';
require_once '../'.WW_CLASS.'Boulette.class.php';

require_once '../'.WW_CLASS.'RandomColor.class.php';


require_once '../'.WW_PLUGINS.'autoload.php';

//Connection a la base de donnée
Constantes::bdd();
SQLite::connect("../");

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
  //'cache' => 'cache',
));

Constantes::config();
if(!isset($_GET['page'])){
	$_GET['page'] = "dashboard";
}
switch($_GET['page']){
	case "boulette":
		require_once __DIR__.'/controllers/boulette/index.php';
	break;
	case "collaborateur":
		require_once __DIR__.'/controllers/collaborateur/index.php';
	break;
	case "categorie":
		require_once __DIR__.'/controllers/categorie/index.php';
	break;
	case "export":
		require_once __DIR__.'/controllers/export/index.php';
	break;
	default:
		echo $twig->render('dashboard.html.twig', array());
	break;
}

