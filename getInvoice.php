<?php
require 'lib/PHPExcel.php';

session_start();

try {
	$auth = new PDO('mysql:host=localhost;dbname=virolle', 'root', '');
}
catch (Exception $e) {
	die('Erreur de connexion à la base de donn&eacute;e : ' . $e->getMessage());
}
$auth->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$auth->query("SET NAMES 'utf8'");

date_default_timezone_set('Europe/Paris');
$auth->query("SET `time_zone` = '".date('P')."'");

$prixPanier = 0;

$objPHPExcel = new PHPExcel();

$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');

$objPHPExcel->getProperties()->setCreator("Pierre VIROLLE")
							 ->setLastModifiedBy("Pierre VIROLLE")
							 ->setTitle("Bon de Commande")
							 ->setSubject("Bon de Commande")
							 ->setDescription("Bon de Commande")
							 ->setKeywords("Bon de Commande")
							 ->setCategory("Bon de Commande");


$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:F6');
$objPHPExcel->getActiveSheet()->mergeCells('A7:C7');
$objPHPExcel->getActiveSheet()->mergeCells('D7:F7');	

$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Bon de Commande');

$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(14);


$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setSize(14);


$objPHPExcel->getActiveSheet()->getStyle('A5')->getFont()->setSize(14);


$objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setSize(14);


$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'Montant total de la commande');

$checkIsMyOrder = $auth->prepare('SELECT COUNT(*) AS nb FROM commande WHERE idUser = :id AND keyOrder = :keyOrder');
$checkIsMyOrder->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
$checkIsMyOrder->bindValue(":keyOrder", $_GET['id'], PDO::PARAM_STR);
$checkIsMyOrder->execute();
$checkResult = $checkIsMyOrder->fetch();

if ($checkResult['nb'] > 0) {
    //La commande nous appartient
    //On récupère donc toutes les données de la commande
    $select = $auth->prepare('SELECT * FROM commande CMD INNER JOIN orderdetails DET ON CMD.id = DET.idOrder INNER JOIN produit PROD ON DET.idProduct = PROD.idProduit WHERE idUser = :id AND keyOrder = :keyOrder');
    $select->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $select->bindValue(":keyOrder", $_GET['id'], PDO::PARAM_STR);
    $select->execute();
    
    $clients = $auth->query('SELECT * FROM user WHERE id = '.$_SESSION['id'].'');
    $client=$clients->fetch();
    
    $i = 0;
    
    while ($order = $select->fetch()) {
        $result[$i]['quantity'] = $order['quantity'];
        $result[$i]['libelleProduit'] = $order['libelleProduit'];
        $result[$i]['codeProduit'] = $order['codeProduit'];
        $result[$i]['prixProduit'] = $order['prixProduit'];
        $result[$i]['dateCommande'] = $order['dateCommande'];
        $result[$i]['dateLivraison'] = $order['dateLivraison'];
        $i++;
    }
    
    $date = substr($result[0]['dateCommande'],8,2)."/".substr($result[0]['dateCommande'],5,2)."/".substr($result[0]['dateCommande'],0,4);
    
    $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Client : '.$client['societe']);
    $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Adresse : '.$client['adresse']);
    $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Date de commande: '.$date);
    $objPHPExcel->getActiveSheet()->setCellValue('A6', 'Date de livraison souhaitée : '.$result[0]['dateLivraison']);
    
    
    $ligneBegin = 10;

    foreach ($result as $produit) {
        $prixTot = $produit['prixProduit'] * $produit['quantity'];

        $objPHPExcel->getActiveSheet()->setCellValue('A'.$ligneBegin, $produit['codeProduit']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$ligneBegin, $produit['quantity']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$ligneBegin, $produit['libelleProduit']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$ligneBegin, $produit['prixProduit']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$ligneBegin, 0);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$ligneBegin, $prixTot);

        $ligneBegin++;
    }
    
}



$currencyFormat = '#,#0.00## \€;[Red]-#,#0.00## \€';
// number format, with thousands separator and two decimal points.
//$numberFormat = '#,#0.##;[Red]-#,#0.##';

$objPHPExcel->getActiveSheet()->getStyle('D7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->setCellValue('D7', '=SUM(F10:F29)');
//$objPHPExcel->getActiveSheet()->getStyle('D7')->getNumberFormat()->setFormatCode("#,##0 _€");
//$objPHPExcel->getActiveSheet()->getStyle('D7:F7')->getNumberFormat()->setFormatCode($numberFormat);
$objPHPExcel->getActiveSheet()->getStyle('D7:F7')->getNumberFormat()->setFormatCode($currencyFormat);
$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A9', 'Code:');
$objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->getColor()->applyFromArray( array('rgb' => '008080') );

$objPHPExcel->getActiveSheet()->setCellValue('B9', 'Quantité:');
//code couleur HTML
$objPHPExcel->getActiveSheet()->getStyle('B9')->getFont()->getColor()->applyFromArray( array('rgb' => '008080') );

$objPHPExcel->getActiveSheet()->setCellValue('C9', 'Description:');

$objPHPExcel->getActiveSheet()->setCellValue('D9', 'Prix unité');
$objPHPExcel->getActiveSheet()->getStyle('D9')->getFont()->getColor()->applyFromArray( array('rgb' => 'FF0000') );

$objPHPExcel->getActiveSheet()->setCellValue('E9', 'Remise');
$objPHPExcel->getActiveSheet()->getStyle('E9')->getFont()->getColor()->applyFromArray( array('rgb' => '008080') );

$objPHPExcel->getActiveSheet()->setCellValue('F9', 'Prix Total:');
$objPHPExcel->getActiveSheet()->getStyle('F9')->getFont()->getColor()->applyFromArray( array('rgb' => '0000FF') );

//Bordures

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$styleBordersTopBot = array(
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);


$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($styleBordersTopBot);
$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->applyFromArray($styleBordersTopBot);
$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->applyFromArray($styleBordersTopBot);
$objPHPExcel->getActiveSheet()->getStyle('A6:F6')->applyFromArray($styleBordersTopBot);
$objPHPExcel->getActiveSheet()->getStyle('A7:F7')->applyFromArray($styleBordersTopBot);

$objPHPExcel->getActiveSheet()->getStyle('A9:F29')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A9:G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);						 

$objPHPExcel->getActiveSheet()->setTitle('Facture');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save(str_replace('.html', '.xls', __FILE__));

        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="commande'.$_GET['id'].'.xls"');

        // Write file to the browser
        $objWriter->save('php://output');