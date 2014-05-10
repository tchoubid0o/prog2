<?php

function getMyInfos($auth){
    $getinfos = $auth->prepare('SELECT * FROM user WHERE id = :id AND mail = :mail');
    $getinfos->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $getinfos->bindValue(":mail", $_SESSION['mail'], PDO::PARAM_STR);
    $getinfos->execute();
    $getInfo = $getinfos->fetch();
    $getinfos->closeCursor();
    
    if(!empty($getInfo)){
        return $getInfo;
    }
}

    function getOrders($auth){
        $getOrders = $auth->prepare('SELECT * FROM commande WHERE idUser = :idUser');
        $getOrders->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
        $getOrders->execute();
        
        $i = 0;
        // i = 1 commande
        while ($donnees = $getOrders->fetch()) {
            $order[$i]['priceCmd'] = 0;
            $order[$i]['id'] = $donnees['id'];
            date_default_timezone_set("Europe/Paris");
            $order[$i]['date'] = date("d/m/Y", strtotime($donnees['dateCommande']));
            $order[$i]['keyOrder'] = $donnees['keyOrder'];
            //On va calculer le prix de la commande
            $gets = $auth->prepare('SELECT * FROM orderdetails WHERE idOrder = :idOrder');
            $gets->bindValue(":idOrder", $donnees['id'], PDO::PARAM_INT);
            $gets->execute();
            $j = 0;
            while($donnees2 = $gets->fetch()){
                $order[$i]['priceCmd'] += $donnees2['unitPrice']*$donnees2['quantity'];
                
                $j++;
            }
            $i++;
        }
        if(!empty($order)){
            return $order;
        }
    }
?>