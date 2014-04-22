<?php
    function getBasket($auth){
        $getBasket = $auth->prepare('SELECT * FROM panier PAN INNER JOIN produit PROD ON PAN.idProduit = PROD.idProduit WHERE PAN.idUser = :id');
        $getBasket->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $getBasket->execute();
        
        $i = 0;
        while($donnees = $getBasket->fetch()){
            $basket[$i]['idProduit'] = $donnees['idProduit'];
            $basket[$i]['idSociete'] = $donnees['idSociete'];
            $basket[$i]['qteProduit'] = $donnees['qteProduit'];
            $basket[$i]['libelleProduit'] = $donnees['libelleProduit'];
            $basket[$i]['refProduit'] = $donnees['refProduit'];
            $basket[$i]['prixProduit'] = $donnees['prixProduit'];
            $basket[$i]['minQte'] = $donnees['minQte'];
            $basket[$i]['quantiteProduit'] = $donnees['quantiteProduit'];
            
            $i++;
        }
        
        $getBasket->closeCursor();
        if(isset($basket)){
            return $basket;
        }
    }
    
    function modifyCart($auth, $idProduit, $qteProduit){
        $modify = $auth->prepare('UPDATE panier SET qteProduit = :qteProduit WHERE idUser = :id AND idProduit = :idProduit');
        $modify->bindValue(":qteProduit", $qteProduit, PDO::PARAM_INT);
        $modify->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $modify->bindValue(":idProduit", $idProduit, PDO::PARAM_INT);
        $modify->execute();
        $modify->closeCursor();
    }
    
    function deleteItem($auth, $idProduit){
        $delete = $auth->prepare('DELETE FROM panier WHERE idUser = :id AND idProduit = :idProduit');
        $delete->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
        $delete->bindValue(":idProduit", $idProduit, PDO::PARAM_INT);
        $delete->execute();
        $delete->closeCursor();
    }
?>