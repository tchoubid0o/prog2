<?php

function confirmOrder($auth, $date, $comment) {
    $select = $auth->prepare('SELECT * FROM panier WHERE idUser = :id');
    $select->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $select->execute();

    $i = 0;
    while ($donnees = $select->fetch()) {

        //On récupère les infos du produit dans le panier
        $order[$i]['idProduit'] = $donnees['idProduit'];
        $order[$i]['idSociete'] = $donnees['idSociete'];
        $order[$i]['qteProduit'] = $donnees['qteProduit'];
        
        if ($i == 0) {
            //On crée une unique nouvelle commande
            
            //Clée unique de la commande:
            $key = generateOrderKey($auth);
            
            $insert = $auth->prepare('INSERT INTO commande(`idUser`, `idSociete`, `dateCommande`, `dateLivraison`, `commentOrder`, `keyOrder`) VALUES(:id, :idSociete, NOW(), :date, :comment, :key)');
            $insert->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
            $insert->bindValue(":idSociete", $donnees['idSociete'], PDO::PARAM_INT);
            $insert->bindValue(":date", $date, PDO::PARAM_STR);
            $insert->bindValue(":comment", $comment, PDO::PARAM_STR);
            $insert->bindValue(":key", $key, PDO::PARAM_STR);
            $insert->execute();
            
            $idCmd = $auth->lastInsertId();
        }
        
        $getPrice = $auth->prepare('SELECT * FROM produit WHERE idProduit = :idProduit');
        $getPrice->bindValue(":idProduit", $donnees['idProduit'], PDO::PARAM_INT);
        $getPrice->execute();
        $price = $getPrice->fetch();
        
        $insert2 = $auth->prepare('INSERT INTO orderdetails(`idOrder`, `idProduct`, `unitPrice`, `quantity`) VALUES(:idOrder, :idProduct, :unitPrice, :quantity)');
        $insert2->bindValue(":idOrder", $idCmd, PDO::PARAM_INT);
        $insert2->bindValue(":idProduct", $donnees['idProduit'], PDO::PARAM_INT);
        $insert2->bindValue(":unitPrice", $price['prixProduit'], PDO::PARAM_STR);
        $insert2->bindValue(":quantity", $donnees['qteProduit'], PDO::PARAM_INT);
        $insert2->execute();

        $i++;
    }

    //Quand tout est commandé, alors on supprime le panier

    $del = $auth->prepare('DELETE FROM panier WHERE idUser = :id');
    $del->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $del->execute();
    $del->closeCursor();
}

function generateOrderKey($auth){
    $time = time();
    $rand = mt_rand(11111111, 99999999);
    return $time.$rand;
}
?>