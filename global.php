<?php
	if($page != "test"){
    if( (strstr($_SERVER['HTTP_ACCEPT'], "html") == TRUE))
		include('header.php');
	
	
	if(isset($page) && !empty($page) && is_file('vues/' . $page . '.php')) {
		include ('controleurs/' . $_GET['page'] . '.php');
	}
	else{
		include ('controleurs/404.php');
	}

	if( (strstr($_SERVER['HTTP_ACCEPT'], "html") == TRUE))
		include('bottom.php');
	}
?>