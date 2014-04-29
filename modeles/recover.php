<?php

function recoverPassword($auth, $mail){
    $recover['message'] = "";
    $checkMail = $auth->prepare('SELECT COUNT(*) AS nb FROM user WHERE mail = :mail');
    $checkMail->bindValue(':mail', $mail, PDO::PARAM_STR);
    $checkMail->execute();
    $nbMail = $checkMail->fetch();
    
    if($nbMail['nb']>0){
        //On récupère le mot de passe
        $getPass = $auth->prepare('SELECT password FROM user WHERE mail = :mail');
        $getPass->bindValue(":mail", $mail, PDO::PARAM_STR);
        $getPass->execute();
        $pass = $getPass->fetch();
        $getPass->closeCursor();
        
        //Envoi d'un mail nouveau message
	$siteWebMail = "messagerie@virolle.fr";
	$destinataire = $siteWebMail;
	$sujet = "Virolle: Récupération de mot de passe" ;
	$entete = 'From: messagerie@virolle.fr';
	$headers  = 'MIME-Version: 1.0' . '\r\n';
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n" .
                    'Content-Transfer-Encoding: 7bit' . "\r\n" .
                    'From: messagerie@virolle.fr' . "\r\n" .
                    'Bcc:' . $mail . '';
					 
	$message = 'Bonjour!
					 
	Voici le rappel de votre login et mot de passe:
        
        E-mail: '.$mail.'
        Mot de passe: '.$pass['password'].'
            
        Veuillez conserver précieusement ces informations, car sans mot de passe vous ne pourrez pas utiliser votre compte.
					 
	http://'.$_SERVER['HTTP_HOST'].'/
					 
					 
	--------------------------------------------------------------------------------------------------------------
	Ceci est un mail automatique, Merci de ne pas y répondre.';
					 
					 
	mail($destinataire, $sujet, $message, $headers) ;
        
        $recover['message'] .= "Votre mot de passe vient de vous être envoyé par e-mail.";
    }
    else{
        $recover['message'] .= "<span class='red'>L'adresse e-mail n'est pas enregistrée.</span>";
    }
    
    return $recover;
}

?>