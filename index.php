<?php
session_start();

try {
	$auth = new PDO('mysql:host=localhost;dbname=virolle', 'root', 'root');
}
catch (Exception $e) {
	die('Erreur de connexion à la base de donn&eacute;e : ' . $e->getMessage());
}
$auth->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$auth->query("SET NAMES 'utf8'");

date_default_timezone_set('Europe/Paris');
$auth->query("SET `time_zone` = '".date('P')."'");


if(!isset($_GET['page'])) {
	$_GET['page'] = 'index';
}

$page = preg_replace('#\.\./#', '', $_GET['page']);
function page2title($page) {
        $title = strtoupper($page[0]);
        for($i = 1; $i < strlen($page); $i++) {
                if($page[$i] == "_") { $title .= " "; }
                else { $title .= $page[$i]; }
        }
}

if(isset($page) && !empty($page) && is_file('vues/' . $page . '.php')) {
	include('includes/config.php');
	include('global.php');
}

else {
	include('includes/config.php');
	include('global.php');
}
?>
