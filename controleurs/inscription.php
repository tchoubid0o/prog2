<?php
require_once('./modeles/inscription.php');

if(isset($_POST['inscription'])) {
	$inscription = inscription($auth, $_POST['prenom'], $_POST['nom'], $_POST['password'], $_POST['verification'], $_POST['email']);
}

require_once('./vues/inscription.php');
?>