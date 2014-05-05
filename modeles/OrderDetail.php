<?php

function getOrderDetail($auth, $keyOrder) {
    $result['message'] = "";
    $checkIsMyOrder = $auth->prepare('SELECT COUNT(*) AS nb FROM commande WHERE idUser = :id AND keyOrder = :keyOrder');
    $checkIsMyOrder->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $checkIsMyOrder->bindValue(":keyOrder", $keyOrder, PDO::PARAM_STR);
    $checkIsMyOrder->execute();
    $checkResult = $checkIsMyOrder->fetch();

    if ($checkResult['nb'] > 0) {
        //La commande nous appartient
        //On récupère donc toutes les données de la commande
        $select = $auth->prepare('SELECT * FROM commande WHERE idUser = :id AND keyOrder = :keyOrder');
        $select->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $select->bindValue(":keyOrder", $keyOrder, PDO::PARAM_STR);
        $select->execute();
        $result = $select->fetch();
    } else {
        $result['message'] .= "La commande numérotée(" . $keyOrder . ") n'a pas pu être trouvée sur votre compte ou vous n'êtes pas autorisé à l'afficher sans être connecter.";
    }
    return $result;
}

function getProductsOrder($auth, $keyOrder) {
    $checkIsMyOrder = $auth->prepare('SELECT COUNT(*) AS nb FROM commande WHERE idUser = :id AND keyOrder = :keyOrder');
    $checkIsMyOrder->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $checkIsMyOrder->bindValue(":keyOrder", $keyOrder, PDO::PARAM_STR);
    $checkIsMyOrder->execute();
    $checkResult = $checkIsMyOrder->fetch();

    if ($checkResult['nb'] > 0) {
        //La commande nous appartient
        //On récupère donc toutes les données de la commande
        $select = $auth->prepare('SELECT * FROM commande CMD INNER JOIN orderdetails DET ON CMD.id = DET.idOrder INNER JOIN produit PROD ON DET.idProduct = PROD.idProduit WHERE idUser = :id AND keyOrder = :keyOrder');
        $select->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $select->bindValue(":keyOrder", $keyOrder, PDO::PARAM_STR);
        $select->execute();
        $i = 0;
        while ($order = $select->fetch()) {
            $result[$i]['quantity'] = $order['quantity'];
            $result[$i]['libelleProduit'] = $order['libelleProduit'];
            $result[$i]['codeProduit'] = $order['codeProduit'];
            $result[$i]['prixProduit'] = $order['prixProduit'];
            $i++;
        }
        return $result;
    }
}

function getInvoice($auth) {
    header('Content-Type: application/csv-tab-delimited-table');
//nommage du fichier avec la date du jour
    header('Content-disposition: filename=monfichier_' . date('Ymd') . '.csv');

//Première ligne avec le noms des colonnes
    echo '"Nom";"Prénom";"Email"' . "\n"; {
        //Pour chaque ligne, création d'une ligne dans le csv.
        //Les champs sont entourés de guillemets, séparés par des points-virgules
        //Les lignes sont terminées par un retour-chariot.
        echo '"RUPP";"Michaël";"michrupp@free.fr"';
    }
}

?>