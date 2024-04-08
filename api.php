<?php

global $file_db;
define('INDEX_LOCK', 'ok');
// Reporte toutes les erreurs PHP
error_reporting(-1);

// Même chose que error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

// Constantes nécéssaires au fonctionnement du script
define("WW_CLASS", "class/");

require_once (WW_CLASS . 'Constantes.class.php');
Constantes::repertoires();

/* * ********************************************************************** */
/* Classes nécéssaires au fonctionnement général du site    			 */
/* * ********************************************************************** */
require_once (WW_CLASS . 'SQLite.class.php');
require_once (WW_CLASS . 'BouletteManager.class.php');

require_once (WW_CLASS . 'Collaborateur.class.php');
require_once (WW_CLASS . 'Phrase.class.php');
require_once (WW_CLASS . 'Boulette.class.php');
require_once (WW_CLASS . 'Categorie.class.php');
require_once (WW_API . 'BouletteApi.php');
require_once (WW_API . 'CategorieApi.php');
require_once (WW_API . 'CollaborateurApi.php');


require_once (WW_CLASS . 'RandomColor.class.php');
require_once (WW_PLUGINS . 'autoload.php');

Constantes::config();

// Contrôle de la clé API
if (!isset($_GET['key']) || $_GET['key'] != API_KEY) {
    header('HTTP/1.0 403 Forbidden');
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid API key']);
    exit;
}

//Connection a la base de donnée 
Constantes::bdd();
SQLite::connect();

switch ($_GET['type']) {
    case 'boulette':
        $bouletteAPI = new BouletteAPI($file_db);
        $bouletteAPI->handleRequest();
        break;
    case 'categorie':
        $categorieAPI = new CategorieAPI($file_db);
        $categorieAPI->handleRequest();
        break;
    case 'collaborateur':
        $collaborateurAPI = new CollaborateurAPI($file_db);
        $collaborateurAPI->handleRequest();
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        break;
}

?>