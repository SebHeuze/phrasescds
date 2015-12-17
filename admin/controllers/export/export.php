<?php

$objPHPExcel = PHPExcel_IOFactory::load("resources/xlsx/TemplateBoulettesChouquettes.xlsx");
$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', "No")
                            ->setCellValue('B2', "Name")
                            ->setCellValue('C2', "Email")
                            ->setCellValue('D2', "Phone")
                            ->setCellValue('E2', "Address");

$resultSheet = clone  $objPHPExcel->getActiveSheet(); // instance of PHPExcel_Worksheet
$resultSheet->setTitle("test");

 $objPHPExcel->getActiveSheet()->getParent()->addSheet($resultSheet,1);

// envoi du fichier au navigateur
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="nomfichier.xlsx"'); 
header('Cache-Control: max-age=0'); 
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
$writer->save('php://output');
