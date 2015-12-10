<?php

$initial_data = array(
		//'timestamp' => time(),
);


//$form = $form->add('timestamp', 'text', array('required' => true));

$options = array();
$findexternal_sql = 'SELECT `id_collaborateur`, `nom`, prenom FROM `collaborateur` ORDER BY prenom';
$findexternal_query = $file_db->prepare($findexternal_sql, array());
$findexternal_query = $file_db->prepare($findexternal_sql, array());
$findexternal_query->execute();
$findexternal_rows = $findexternal_query->fetchAll();

foreach($findexternal_rows as $findexternal_row){
    $options[$findexternal_row['id_collaborateur']] = $findexternal_row['prenom'].' '.$findexternal_row['nom'];
}

if(isset($_POST['phrase1'])){
    $update_query = "INSERT INTO `boulette` (`timestamp`) VALUES (?)";          
    $qry = $file_db->prepare($update_query);
	$qry->execute(array(time()));

    $id_boulette = $file_db->lastInsertId();

    for ($i =1; $i <=MAX_PHRASES_DIALOGUE; $i++){
        if(trim($_POST['phrase'.$i])!="" && $_POST['id_collaborateur'.$i]>0){
        	$update_query = "INSERT INTO `phrase` (`id_collaborateur`,`message`) VALUES (?, ?)";          
   			$qry = $file_db->prepare($update_query);
			$qry->execute(array($_POST['id_collaborateur'.$i],$_POST['phrase'.$i]));
			$id_phrase = $file_db->lastInsertId();

			$update_query = "INSERT INTO `boulette_phrase` (`id_boulette`,`id_phrase`) VALUES (?, ?)";          
   			$qry = $file_db->prepare($update_query);
			$qry->execute(array($id_boulette,$id_phrase));
        }
    }
    
    $_SESSION['message'] = array("type"=>"success", "message" => "boulette crée!");

    header('Location: ?page=boulette&action=list');      
}

echo $twig->render('boulette/create.html.twig', array(
	"collaborateurs" => $findexternal_rows,
	"nb_phrases"=>MAX_PHRASES_DIALOGUE
));
