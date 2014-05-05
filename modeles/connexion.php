<?php

function connexion($auth, $mail, $password) {
    if (!isset($_SESSION['id'])) {
        if (isset($mail) && !empty($mail) && isset($password) && !empty($password)) {
            $connexion['message_global'] = "";

            // On vérifie si le mail existe dans la bdd
            if (empty($connexion['message_pseudo'])) {
                $retour_check_pseudo = $auth->prepare('SELECT COUNT(*) AS nb FROM user WHERE mail = :mail');
                $retour_check_pseudo->bindValue(':mail', $mail, PDO::PARAM_STR);
                $retour_check_pseudo->execute();
                $nb_mail = $retour_check_pseudo->fetch();

                if ($nb_mail['nb'] != 1) {
                    $connexion['message_global'] .= "<span class=\"red\">Le mail entr&eacute; n'existe pas.</span>";
                } else {

                    $_SESSION['connexion_mail'] = $mail;
                    // Validation du password
                    if (empty($connexion['message_password'])) {
                        $retour_check_pass = $auth->prepare('SELECT * FROM user WHERE mail = :mail');
                        $retour_check_pass->bindValue(':mail', $mail, PDO::PARAM_STR);
                        $retour_check_pass->execute();
                        $mdp = $retour_check_pass->fetch();
                        if ($mdp['password'] != $password) {
                            $connexion['message_global'] .= "<span class=\"red\">Vous avez entré un mauvais mot de passe!</span>";
                        } else {
                            $connexion['ok'] = 1;
                            $connexion['message_global'] = "<script>document.location.href='".ROOTPATH."/index.html'</script>";
                            $_SESSION['id'] = $mdp['id'];
                            $_SESSION['mail'] = $mail;
                            $_SESSION['adm'] = $mdp['adm'];
                            
                        }

                        $retour_check_pass->closeCursor();
                    }
                }

                $retour_check_pseudo->closeCursor();
            }
        } else {
            $connexion['message_global'] = "<span class=\"red\">Vous devez remplir tous les champs.</span>";
        }
    } else {
        $connexion['message_global'] = "<script>document.location.href='".ROOTPATH."/index.html'</script>";
    }
    return $connexion;
}
?>