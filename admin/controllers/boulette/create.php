<?php

$initial_data = array(
		//'timestamp' => time(),
);


//$form = $form->add('timestamp', 'text', array('required' => true));

$options = array();
$findexternal_sql = 'SELECT `id_collaborateur`, `nom`, prenom FROM `collaborateur` ORDER BY prenom';
$findexternal_query = $file_db->prepare($findexternal_sql, array());
$findexternal_query->execute();
$collaborateurs = $findexternal_query->fetchAll();


$options = array();
$findexternal_sql = 'SELECT `id_categorie`, `nom` FROM `categorie` ORDER BY nom';
$findexternal_query = $file_db->prepare($findexternal_sql, array());
$findexternal_query->execute();
$categories = $findexternal_query->fetchAll();

foreach($collaborateurs as $collaborateur){
    $options[$collaborateur['id_collaborateur']] = $collaborateur['prenom'].' '.$collaborateur['nom'];
}

if(isset($_POST['phrase1'])){
    $update_query = "INSERT INTO `boulette` (`timestamp`,id_categorie, archive) VALUES (?,?, ?)";          
    $qry = $file_db->prepare($update_query);
	$qry->execute(array(time(),$_POST['id_categorie'],0));

    $id_boulette = $file_db->lastInsertId();

    for ($i =1; $i <=MAX_PHRASES_DIALOGUE; $i++){
        if(trim($_POST['phrase'.$i])!="" && $_POST['id_collaborateur'.$i]>0){
        	$update_query = "INSERT INTO `phrase` (`id_collaborateur`,`id_boulette`,`message`) VALUES (?, ?, ?)";
   			$qry = $file_db->prepare($update_query);
			$qry->execute(array($_POST['id_collaborateur'.$i],$id_boulette,$_POST['phrase'.$i]));
        }
    }
    
    $_SESSION['message'] = array("type"=>"success", "message" => "boulette crée!");

    header('Location: ?page=boulette&action=list');      
}

echo $twig->render('boulette/create.html.twig', array(
	"collaborateurs" => $collaborateurs,
    "categories" => $categories,
	"nb_phrases"=>MAX_PHRASES_DIALOGUE
));
