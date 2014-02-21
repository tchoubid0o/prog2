<?php
require_once('./modeles/connexion.php');

if(isset($_POST['connexion'])) {
	$connexion = connexion($auth, $_POST['mail'], $_POST['password']);
}

require_once('./vues/connexion.php');
?>