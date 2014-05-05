<!DOCTYPE html>
<html> 
    <head> 
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
        <title>Virolle-Prog</title> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!-- Géo Meta Tag -->
        <meta name="geo.region" content="FR-75" />
        <meta name="geo.placename" content="Lille" />
        <meta name="geo.position" content="50.62925;3.057256" />
        <meta name="ICBM" content="50.62925, 3.057256" />
        <!-- Auteur de la page -->
        <meta name="author" content="Virolle" />
        <!-- Description de la page -->
        <meta name="description" content="Virolle" />
        <!-- Mots-clés de la page -->
        <meta name="keywords" content="Virolle" />
        <!-- Adresse de contact -->
        <meta name="reply-to" content="" />
        <meta http-equiv="content-language" content="fr-FR" />
        <meta name="language" content="fr-FR" />
        <!-- Empêcher la mise en cache de la page par le navigateur -->
        <meta http-equiv="pragma" content="no-cache" />
        <!-- Lien vers le style de la page -->
        <link rel="stylesheet" media="screen" type="text/css" title="Design" href="<?php echo ROOTPATH; ?>/design.css" />
        <!-- On importe Jquery -->
        <script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    </head> 
    <body>
        <div id="content" style="width: 1000px;margin: auto;">
            <?php if (empty($_SESSION['id'])) {
                if ($_GET['page'] != "connexion" && $_GET['page'] != "recover") {
                    
                    include("./controleurs/connexion.php");
                    ?> 
                    
                <?php
                }
            } else {
                if ($_GET['page'] != "admin" && $_GET['page'] != "societeadm") {
                    ?>
                    <table style="width: 750px; margin: auto; float: left;">
                        <tr>
                            <td style="width: 250px;"><a href="<?php echo ROOTPATH; ?>/index.html">Accueil</a></td>
                            <td style="width: 250px;"><a href="<?php echo ROOTPATH; ?>/HowtoUse.html">Comment ça marche?</a></td>
                            <td style="width: 250px;"><a href="<?php echo ROOTPATH; ?>/mySpace.html">Mon compte</a></td>

                        </tr>
                    </table>
                    <div style="clear: both;"></div>
    <?php }
} ?>