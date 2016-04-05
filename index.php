<?php

/* Page index 
  @author Sébastien HEUZE
  @version 1.0 */


define('INDEX_LOCK', 'ok');
// Reporte toutes les erreurs PHP
error_reporting(-1);

// Même chose que error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

// Constantes nécéssaires au fonctionnement du script
define("WW_CLASS", "class/");

require_once(WW_CLASS . 'Constantes.class.php');
Constantes::repertoires();

/* * ********************************************************************** */
/* Classes nécéssaires au fonctionnement général du site    			 */
/* * ********************************************************************** */
require_once(WW_CLASS.'SQLite.class.php');
require_once(WW_CLASS.'BouletteManager.class.php');

require_once(WW_CLASS.'Collaborateur.class.php');
require_once(WW_CLASS.'Phrase.class.php');
require_once(WW_CLASS.'Boulette.class.php');
require_once(WW_CLASS.'Categorie.class.php');

require_once(WW_CLASS.'RandomColor.class.php');
require_once(WW_PLUGINS.'autoload.php');

Constantes::config();

//Connection a la base de donnée
Constantes::bdd();
SQLite::connect();

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
  //'cache' => 'cache',
));

if(isset($_GET['page']) && intval($_GET['page'])>1){
	$page = intval($_GET['page']);
} else {
	$page = 1;
}
$boulettes = BouletteManager::getBoulettes($page);
echo $twig->render('index.html', array('name' => 'Boulettes Chouquettes', 'boulettes' => $boulettes, 'page' => $page));
?>
