<?php
function connexion($auth, $mail, $password) {
	if(!isset($_SESSION['id'])) {
		if(isset($mail) && !empty($mail) && isset($password) && !empty($password)) {
			$connexion['message_mail'] = "";
			$connexion['message_password'] = "";
			$connexion['message_actif'] = "";

			// On vérifie si le mail existe dans la bdd
			if(empty($connexion['message_pseudo'])) {
				$retour_check_pseudo = $auth->prepare('SELECT COUNT(*) AS nb FROM user WHERE mail = :mail');
				$retour_check_pseudo->bindValue(':mail', $mail, PDO::PARAM_STR);
				$retour_check_pseudo->execute();
				$nb_mail = $retour_check_pseudo->fetch();

				if($nb_mail['nb'] != 1) {
					$connexion['message_mail'] .= "Le mail entr&eacute; n'existe pas.<br />";
				}
				else {
					$checkActif = $auth->prepare('SELECT actif FROM activation INNER JOIN user ON activation.idUser = user.id WHERE user.mail = :mail');
					$checkActif->bindValue(':mail', $mail, PDO::PARAM_STR);
					$checkActif->execute();
					$checkAct = $checkActif->fetch();
					$checkActif->closeCursor();
					
					if($checkAct['actif'] == 1){
						$_SESSION['connexion_mail'] = $mail;
						// Validation du password
						if(empty($connexion['message_password'])) {
							$retour_check_pass = $auth->prepare('SELECT *
												 FROM user WHERE mail = :mail');
							$retour_check_pass->bindValue(':mail', $mail, PDO::PARAM_STR);
							$retour_check_pass->execute();
							$mdp = $retour_check_pass->fetch();
							if($mdp['password'] != md5("web".(sha1($password))."site")) {
								$connexion['message_password'] .= "Vous avez entré un mauvais mot de pass!<br />";
							}
							else {
								// On remplit les sessions du joueur. 
								$connexion['ok'] = 1;
								$connexion['message_global'] = "<span class=\"red\">Vous êtes maintenant connecté !</span>";
								$_SESSION['id'] = $mdp['id'];
								$_SESSION['mail'] = $mail;
								$_SESSION['prenom'] = $mdp['prenom'];
								$_SESSION['nom'] = $mdp['nom'];
								$_SESSION['adm'] = $mdp['adm'];
							}

							$retour_check_pass->closeCursor();
						}
					}
					else{
						$connexion['message_actif'] .= "Le compte n'a pas encore été activé!";
					}
				}

				$retour_check_pseudo->closeCursor();
			}

			
		}
		else {
			$connexion['message_global'] = "<span class=\"red\">Vous devez remplir tous les champs.</span>";
		}
	}
	else {
		$connexion['message_global'] = "<span class=\"red\">Vous &ecirc;tes d&eacute;j&agrave; connect&eacute; !</span>";
	}
	return $connexion;
}
?>