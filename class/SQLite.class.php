<?php

// Si la constante n'est pas defini on bloque l'execution du fichier
if(!defined('INDEX_LOCK') || @INDEX_LOCK != 'ok') 
{
	die('Erreur 404 - Le fichier n\'a pas été trouvé');
}

class SQLite
{
	//On se connecte a la BDD
	public static function connect($cur_dir="")
	{
		global $file_db;

		$db_exist = file_exists ($cur_dir.WW_BDD.DB_FILE);

	    $file_db = new PDO('sqlite:'.$cur_dir.WW_BDD.DB_FILE);

	    $file_db->setAttribute(PDO::ATTR_ERRMODE, 
	                            PDO::ERRMODE_EXCEPTION);
	 
	 
	 	if(!$db_exist){
	 		SQLite::initData();
	 	}
	}

	public static function initData()
	{
		global $file_db;
		/**************************************
		* Creer tables                        *
		**************************************/

			// Table des boulettes
		$file_db->exec("CREATE TABLE IF NOT EXISTS boulette (
		                id_boulette INTEGER PRIMARY KEY AUTOINCREMENT, 
		                timestamp  DATETIME DEFAULT CURRENT_TIMESTAMP)");

		// Table des phrases
		$file_db->exec("CREATE TABLE IF NOT EXISTS phrase (
		                id_phrase INTEGER PRIMARY KEY AUTOINCREMENT, 
		                id_collaborateur INTEGER, 
		                message TEXT)");

		// Table ONE TO MANY Boulette-Phrases
		$file_db->exec("CREATE TABLE IF NOT EXISTS boulette_phrase (
		                id_phrase INTEGER, 
		                id_boulette INTEGER)");


		// Table des collaborateurs
		$file_db->exec("CREATE TABLE IF NOT EXISTS collaborateur (
		                id_collaborateur INTEGER PRIMARY KEY AUTOINCREMENT, 
		                prenom TEXT, 
		                nom TEXT,
		                couleur TEXT)");



		/**************************************
		* COLLABORATEURS                      *
		**************************************/

			$collaborateurs = array(
		              array('prenom' => 'Charles',
		              		'nom' => 'Dupont'),
		              array('prenom' => 'Jean',
		              		'nom' => 'Miche'),
		              array('prenom' => 'Test',
		              		'nom' => 'Dupont'),
		              array('prenom' => 'Sébastien',
		              		'nom' => 'Heuzé'),
		);


		$insert = "INSERT INTO collaborateur (prenom, nom, couleur) 
		            VALUES (:prenom, :nom, :couleur)";
		$stmt = $file_db->prepare($insert);

		$stmt->bindParam(':prenom', $prenom);
		$stmt->bindParam(':nom', $nom);
		$stmt->bindParam(':couleur', $couleur);

		//On insère tout
		foreach ($collaborateurs as $c) {
		  // Set values to bound variables
		  $prenom = $c['prenom'];
		  $nom = $c['nom'];
		  $couleur = RandomColor::one();

		  // Execute statement
		  $stmt->execute();
		}


		/**************************************
		* BOULETTES                           *
		**************************************/
		$boulettes = array(
		              array('phrases' =>  array(
		              							array('id_collaborateur' => 1, 'message' => 'Le Beschrelle ça s\'écrit comment ? Je connaissais pas comme site'),
					                        ),
		                    'timestamp' => 1327301464),
		              array('phrases' => array(
		              							array('id_collaborateur' => 2, 'message' => '(à Gildas) Tu vas te marier ? Encore avec la même personne ?'),
		              						),
		                    'timestamp' => 1327301464),
		              array('phrases' =>  array(
		              							array('id_collaborateur' => 1, 'message' => '(debout) Je voulais faire un truc… ah oui m\'assoir !'),
		              						),
		                    'timestamp' => 1327301464),
		              array('phrases' =>   array(
		              							array('id_collaborateur' => 2, 'message' => 'Ha ! Désolé elle est pas open, Audrey'),
		              							array('id_collaborateur' => 4, 'message' => 'Audrey elle est pas open ?'),
		              							array('id_collaborateur' => 2, 'message' => 'Ha ! Non Audrey elle est pas open !'),
		              						),
		                    'timestamp' => 1327301464),
		              array('phrases' =>  array(
		              							array('id_collaborateur' => 3, 'message' => 'Il devrait passer avant tout à l\'heure. Avant tout à l\'heure c\'est quelque part entre maintenant et plus tard'),
		              						),
		                    'timestamp' => 1327301464),
		            );


		$insert_b = "INSERT INTO boulette DEFAULT VALUES";

		$insert_p = "INSERT INTO phrase (id_collaborateur, message) 
		            VALUES (:id_collaborateur, :message)";  

		$insert_bp = "INSERT INTO boulette_phrase (id_boulette, id_phrase) 
		            VALUES (:id_boulette, :id_phrase)";      

		$stmt_b = $file_db->prepare($insert_b);

		$stmt_p = $file_db->prepare($insert_p);

		$stmt_p->bindParam(':id_collaborateur', $id_collaborateur);
		$stmt_p->bindParam(':message', $message);

		$stmt_bp = $file_db->prepare($insert_bp);

		$stmt_bp->bindParam(':id_boulette', $id_boulette);
		$stmt_bp->bindParam(':id_phrase', $id_phrase);

		//On insère tout
		foreach ($boulettes as $b) {
			$stmt_b->execute();	
			$id_boulette = $file_db->lastInsertId();
			foreach ($b['phrases'] as $p) {
		      	$id_collaborateur = $p['id_collaborateur'];
		      	$message = $p['message'];
		 
		     	 // Execute statement
		     	$stmt_p->execute();
		     	$id_phrase = $file_db->lastInsertId();

		     	 // Execute statement
		     	$stmt_bp->execute();
		 	}
		}

	}
	    
}
?>
