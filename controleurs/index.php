<?php

require_once('./modeles/index.php');
if (!empty($_SESSION['id'])) {
    $societes = afficheSociete($auth);
}
require_once('./vues/index.php');

?>