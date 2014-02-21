<?php
	header('Content-Type: application/csv-tab-delimited-table');
	//nommage du fichier avec la date du jour
	header('Content-disposition: filename=monfichier_'.date('Ymd').'.csv');
	 
	//Première ligne avec le noms des colonnes
	echo '"Nom";"Prénom";"Email"'."\n";
	 
	//selection des champs souhaités dans la base      
	/*
	$rq='select nom, prenom, email from matable order by nom';
	$res_rq=mysql_query($rq) or die (mysql_error());
	while($res=mysql_fetch_assoc($res_rq))
	{
	//Pour chaque ligne, création d'une ligne dans le csv.
	//Les champs sont entourés de guillemets, séparés par des points-virgules
	//Les lignes sont terminées par un retour-chariot.
	echo '"'.$res['nom'].'";"'.$res['prenom'].'";"'.$res['email'].'"'."\n";
	}
	*/
	echo '"RUPP";"Michael";"michaelrupp@hei.fr"'."\n";
 ?>
