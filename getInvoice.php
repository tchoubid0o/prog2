<?php
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

header('Content-Type: application/csv-tab-delimited-table');
//nommage du fichier avec la date du jour
header('Content-disposition: filename=monfichier_' . date('Ymd') . '.csv');

//Première ligne avec le noms des colonnes
echo '"Produit";"Référence";"Quantité";"Prix U";"Prix"' . "\n";

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
    $i = 0;
    while ($order = $select->fetch()) {
        $result[$i]['quantity'] = $order['quantity'];
        $result[$i]['libelleProduit'] = $order['libelleProduit'];
        $result[$i]['refProduit'] = $order['refProduit'];
        $result[$i]['prixProduit'] = $order['prixProduit'];
        $i++;
    }
}

foreach ($result as $produit) {
    $prixTot = $produit['prixProduit'] * $produit['quantity'];
    echo '"'.$produit['libelleProduit'].'";"'.$produit['refProduit'].'";"'.$produit['quantity'].'";"'.$produit['prixProduit'].'";"'.$prixTot.'"'."\n";
    
    $prixPanier += $prixTot;
}
    
echo "\n";
echo "\n";
echo '"";"";"'.$prixPanier.'";"";""'."\n";
//Pour chaque ligne, création d'une ligne dans le csv.
//Les champs sont entourés de guillemets, séparés par des points-virgules
//Les lignes sont terminées par un retour-chariot.
?>