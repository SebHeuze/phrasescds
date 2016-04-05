<?php
// Si la constante n'est pas defini on bloque l'execution du fichier
if(!defined('INDEX_LOCK') || @INDEX_LOCK != 'ok') 
{
die('Erreur 404 - Le fichier n\'a pas été trouvé');
}

class Constantes
{
	public static function bdd()
	{
		define('DB_FILE', 'bouletteschouquettes.sqlite3');
	}
	
	public static function repertoires()
	{
		define('WW_TEMPLATES', "views/");
		define('WW_PLUGINS', "vendor/");
		define('WW_BDD', "data/");
	}

	public static function config()
	{
		define('MAX_PHRASES_DIALOGUE', 5);
		define('START_INDEX_PHRASES_XSLS', 9);
		define('NB_BOULETTES_PAGE', 5);
	}
        
        
}
?>