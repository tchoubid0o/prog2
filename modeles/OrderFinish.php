<?php

function confirmOrder($auth, $date, $comment) {
    $prixTotal = 0;
    $getClient = $auth->prepare('SELECT * FROM user WHERE id = :id AND mail = :mail');
    $getClient->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $getClient->bindValue(":mail", $_SESSION['mail'], PDO::PARAM_STR);
    $getClient->execute();
    $client = $getClient->fetch();
    $getClient->closeCursor();
    $mail = "Bonjour!<br/>
					 
	Nous vous confirmons la bonne prise en compte de votre commande passée ce jour sur notre site. <br/>
        
        Vous trouverez ci-dessous le récapitulatif de vos coordonnées ainsi que de vos achats. <br/><br/>
        
        ".ucfirst($client['adresse'])."<br/>
        ".ucfirst($client['mail'])."<br/><br/>
            
        <strong>Vos Achats:</strong>
        <table style='text-align: center;border-collapse:collapse;'>
            <tr>
                <th style='padding: 5px; border: 1px solid black;'>N° commande</th>
                <th style='padding: 5px; border: 1px solid black;'>Référence</th>
                <th style='padding: 5px; border: 1px solid black;'>Produit</th>
                <th style='padding: 5px; border: 1px solid black;'>Qté</th>
                <th style='padding: 5px; border: 1px solid black;'>Prix U</th>
                <th style='padding: 5px; border: 1px solid black;'>Prix</th>
            </tr>";
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
        
        $tempPrice = $price['prixProduit']*$donnees['qteProduit'];
        $prixTotal += $tempPrice;
        
        $mail .= "<tr>
                    <td style='padding: 5px; border: 1px solid black;'>".$key."</td>
                    <td style='padding: 5px; border: 1px solid black;'>".$price['codeProduit']."</td>
                    <td style='padding: 5px; border: 1px solid black;'>".$price['libelleProduit']."</td>
                    <td style='padding: 5px; border: 1px solid black;'>".$donnees['qteProduit']."</td>
                    <td style='padding: 5px; border: 1px solid black;'>".$price['prixProduit']."€</td>
                    <td style='padding: 5px; border: 1px solid black;'>".$tempPrice."€</td>
                </tr>
                ";

        $i++;
    }
    
    $mail .= "</table>";
    $mail .= "<br/><br/>Total: ".$prixTotal."€<br/>";
    $mail .= "<a href='getInvoice.php?id=".$key."'>Télécharger la facture</a>";
    
    //Quand tout est commandé, alors on supprime le panier

    $del = $auth->prepare('DELETE FROM panier WHERE idUser = :id');
    $del->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $del->execute();
    $del->closeCursor();
    
    //On récupère lemail de l'admin
    
    $getAdm = $auth->query('SELECT * FROM user WHERE adm = 1 ORDER BY id ASC LIMIT 1');
    $getAdmMail = $getAdm->fetch();
    $getAdm->closeCursor();
    
    //Envoi d'un mail nouveau message
	$siteWebMail = $getAdmMail['mail'];
	$destinataire = $siteWebMail.", ".$_SESSION['mail']."";
	$sujet = "Virolle: Suivi de votre commande" ;
	$headers = 'From: '.$getAdmMail['mail'].'' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type:text/html;charset=utf-8' . "\r\n" .
                    'Bcc:' . $_SESSION['mail'] . '';
				 
	mail($destinataire, $sujet, $mail, $headers) ;
}

function generateOrderKey($auth){
    $time = time();
    $rand = mt_rand(11111111, 99999999);
    return $time.$rand;
}
?>