<?php
function inscription($auth, $prenom, $nom, $password, $verification, $email) {
	if(!isset($_SESSION['id'])) {
		if(isset($password) && isset($prenom) && isset($nom) && isset($verification) && isset($email)){

			$inscription['message_prenom'] = "";
			$inscription['message_nom'] = "";
			$inscription['message_email'] = "";
			$inscription['message_password'] = "";

			// Validation email
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
				$inscription['message_email'] .= "/!\ Vous devez entrer une adresse email valide.";
			}
			if(empty($inscription['message_email'])) {
				$retour_check_email = $auth->prepare('SELECT COUNT(*) AS nb FROM user WHERE mail = :email');
				$retour_check_email->bindValue(':email', $email, PDO::PARAM_STR);
				$retour_check_email->execute();
				$nb_email = $retour_check_email->fetch();

				if($nb_email['nb'] != 0) {
					$inscription['message_email'] .= "/!\ L'adresse email que vous avez entré est déjâ utilisée<br />";
				}
				else {
					$_SESSION['inscription_email'] = $email;
				}

				$retour_check_email->closeCursor();
			}

			// Validation mot de passe
			if($password != $verification) { $inscription['message_password'] .= "/!\ Les deux mots de passe ne correspondent pas.<br />"; }
			if(empty($inscription['message_password'])) {
				if(strlen($password) < 4) {
					$inscription['message_password'] .= "/!\ Votre mot de passe doit faire au moins 4 caractères.";
				}
			}

			$fichier = "1383611218_green-35";
			$extension = ".png";
			// Tout est bon, on ajoute dans la base de donnée et on affiche un message de succès
			if(empty($inscription['message_email']) && empty($inscription['message_password']) && empty($inscription['message_adresse'])) {
				$ip=$_SERVER["REMOTE_ADDR"];
				$req_insert = $auth->prepare("INSERT INTO user(`prenom`, `nom`, `password`, `ip`, `mail`, `imgAvatar`)
											VALUES (:prenom, :nom, :password, :ip, :email, :avatar);");
				$req_insert->bindValue(':prenom', $prenom, PDO::PARAM_STR);
				$req_insert->bindValue(':nom', $nom, PDO::PARAM_STR);
				$req_insert->bindValue(':password', md5("web".(sha1($password))."site"), PDO::PARAM_STR);
				$req_insert->bindValue(':ip', $ip, PDO::PARAM_STR);
				$req_insert->bindValue(':email', $email, PDO::PARAM_STR);
				$req_insert->bindValue(':avatar', $fichier.$extension, PDO::PARAM_STR);
				$req_insert->execute();
				$req_insert->closeCursor();
				
				$id = $auth->lastInsertId();
				
				//Génération clée d'activation
				$clee = md5(microtime(TRUE)*100000);
				
				$req_insert2 = $auth->prepare("INSERT INTO activation(`idUser`, `clee`, `actif`)
											VALUES (:id, :clee, 0);");
				$req_insert2->bindValue(':id', $id, PDO::PARAM_INT);
				$req_insert2->bindValue(':clee', $clee, PDO::PARAM_INT);
				$req_insert2->execute();
				$req_insert2->closeCursor();
				
				//Envoi du mail d'activation
				$destinataire = $email;
				$sujet = "Activer votre compte" ;
				$entete = "From: confirmation@jirai-stocker-chez-vous.com" ;
				 
				$message = 'Bienvenue sur J\'irai stocker chez vous,
				 
Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.
				 
http://'.$_SERVER['HTTP_HOST'].'/activation.'.urlencode($id).'&'.urlencode($clee).'.html
				 
				 
--------------------------------------------------------------------------------------------------------------
Ceci est un mail automatique, Merci de ne pas y répondre.';
				 
				 
				mail($destinataire, $sujet, $message, $entete) ;

				$inscription['message_global'] = "<span class=\"success\">Votre compte a été créé, veuillez l'activer avant de vous connecter!</span>";
				$inscription['ok'] = 1;
			}			
		}
		else {
			$inscription['message_global'] = "<span class=\"error\">/!\ Vous devez remplir tous les champs.</span>";
		}
	}
	else {
		$inscription['message_global'] = "<span class=\"error\">/!\ Vous êtes déjâ inscrit !</span>";
	}
	return $inscription;
	
		
}
?>