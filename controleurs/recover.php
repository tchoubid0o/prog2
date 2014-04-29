<?php
require_once('./modeles/recover.php');

if(isset($_POST['mail'])) {
	$recover = recoverPassword($auth, $_POST['mail']);
}

require_once('./vues/recover.php');
?>