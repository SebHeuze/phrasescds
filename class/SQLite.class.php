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
		                id_categorie INTEGER,
		                timestamp  DATETIME DEFAULT (strftime('%s', 'now')))");

		// Table des phrases
		$file_db->exec("CREATE TABLE IF NOT EXISTS phrase (
		                id_phrase INTEGER PRIMARY KEY AUTOINCREMENT, 
		                id_collaborateur INTEGER, 
		                id_boulette INTEGER,
		                message TEXT)");

		// Table des phrases
		$file_db->exec("CREATE TABLE IF NOT EXISTS categorie (
		                id_categorie INTEGER PRIMARY KEY AUTOINCREMENT, 
		                nom TEXT)");

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
		* CATEGORIES                          *
		**************************************/

		$categories = array(
			array('id_categorie' => 0, 'nom' => 'Non Classée'),
	        array('id_categorie' => 1, 'nom' => 'Non Sens'),
	        array('id_categorie' => 2, 'nom' => 'Sexuelle'),
	        array('id_categorie' => 3, 'nom' => 'Pensées et Réflexions'),
	        array('id_categorie' => 4, 'nom' => 'Techno-blagues'),
	        array('id_categorie' => 5, 'nom' => 'Retourne à l\'école')
		);


		$insert = "INSERT INTO categorie (id_categorie, nom) 
		            VALUES (:id_categorie, :nom)";
		$stmt = $file_db->prepare($insert);

		$stmt->bindParam(':nom', $nom);
		$stmt->bindParam(':id_categorie', $id_categorie);

		foreach ($categories as $cat) {
			// Set values to bound variables
			$nom = $cat['nom'];
 			$id_categorie = $cat['id_categorie'];
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
		                    'timestamp' => 1327301464,
		                    'id_categorie' => 5),
		              array('phrases' => array(
		              							array('id_collaborateur' => 2,  'message' => '(à Gildas) Tu vas te marier ? Encore avec la même personne ?'),
		              						),
		                    'timestamp' => 1327301464,
		                    'id_categorie' => 3),
		              array('phrases' =>  array(
		              							array('id_collaborateur' => 1, 'message' => '(debout) Je voulais faire un truc… ah oui m\'assoir !'),
		              						),
		                    'timestamp' => 1327301464,
		                    'id_categorie' => 1),
		              array('phrases' =>   array(
		              							array('id_collaborateur' => 2, 'message' => 'Ha ! Désolé elle est pas open, Audrey'),
		              							array('id_collaborateur' => 4, 'message' => 'Audrey elle est pas open ?'),
		              							array('id_collaborateur' => 2, 'message' => 'Ha ! Non Audrey elle est pas open !'),
		              						),
		                    'timestamp' => 1327301464,
		                    'id_categorie' => 1),
		              array('phrases' =>  array(
		              							array('id_collaborateur' => 3, 'message' => 'Il devrait passer avant tout à l\'heure. Avant tout à l\'heure c\'est quelque part entre maintenant et plus tard'),
		              						),
		                    'timestamp' => 1327301464,
		                    'id_categorie' => 3),
		            );


		$insert_b = "INSERT INTO boulette (id_categorie) values (:id_categorie)";

		$insert_p = "INSERT INTO phrase (id_collaborateur, message, id_boulette) 
		            VALUES (:id_collaborateur, :message, :id_boulette)";  


		$stmt_b = $file_db->prepare($insert_b);
		$stmt_b->bindParam(':id_categorie', $id_categorie);

		$stmt_p = $file_db->prepare($insert_p);

		$stmt_p->bindParam(':id_collaborateur', $id_collaborateur);
		$stmt_p->bindParam(':message', $message);
		$stmt_p->bindParam(':id_boulette', $id_boulette);


		//On insère tout
		foreach ($boulettes as $b) {
			$id_categorie = $b['id_categorie'];
			$stmt_b->execute();	
			$id_boulette = $file_db->lastInsertId();
			foreach ($b['phrases'] as $p) {
		      	$id_collaborateur = $p['id_collaborateur'];
		      	$message = $p['message'];
		 
		     	 // Execute statement
		     	$stmt_p->execute();
		     	$id_phrase = $file_db->lastInsertId();
		 	}
		}

	}
	    
}
?>
