<?php

$objPHPExcel = PHPExcel_IOFactory::load("resources/xlsx/TemplateBoulettesChouquettes.xlsx");



$findSQL = $file_db->prepare("SELECT * FROM categorie");
$findSQL->execute();
$categories = $findSQL->fetchAll();

foreach($categories as $categorie){
	$resultSheet = clone  $objPHPExcel->getActiveSheet(); 
	$resultSheet->setTitle($categorie['nom']);
	$resultSheet->setCellValue('A1', $categorie['nom']);

	$phrasesSQL = $file_db->prepare("SELECT boulette.*, phrase.* FROM phrase, boulette WHERE boulette.id_boulette = phrase.id_boulette AND id_categorie = ? ORDER BY boulette.timestamp");
	$phrasesSQL->execute(array($categorie['id_categorie']));
	$phrases = $phrasesSQL->fetchAll();
	$boulettes = array();
	foreach($phrases as $phrase){
		if(!isset($boulettes[$phrase['id_boulette']])) {
			$boulettes[$phrase['id_boulette']] = array();
		}
		$boulettes[$phrase['id_boulette']][] = $phrase['message'];
	}
	$sheetIndex = START_INDEX_PHRASES_XSLS;
	foreach($boulettes as $boulette){
		$resultSheet->setCellValue('A'.$sheetIndex, implode("\n",$boulette));
		$sheetIndex++;
	}
	$objPHPExcel->getActiveSheet()->getParent()->addSheet($resultSheet,1);
}


$objPHPExcel->removeSheetByIndex(0);

// envoi du fichier au navigateur
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Votes - Boulettes-Chouquettes - TRANSPORTEUR.xlsx"'); 
header('Cache-Control: max-age=0'); 
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$writer->save('php://output');
