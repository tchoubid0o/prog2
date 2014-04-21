<?php

require_once('./modeles/societe.php');
if (isset($_POST['idSociete'])) {
    $_GET['act'] = $_POST['idSociete'];
}
if (isset($_GET['act'])) {
    $nomSociete = getNomSociete($auth, $_GET['act']);
    $donnees = getSociete($auth, $_GET['act']);
    if (isset($_POST['idCategorie']) && isset($_POST['idSociete'])) {
        $recupProduits = recupProduits($auth, $_POST['idCategorie'], $_POST['idSociete']);
        // Ici le GET attend du JSON, pas du HTML, donc on lui renvoie la même info sous forme de JSON pure.
        if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == FALSE ) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {

            echo json_encode($recupProduits);
        }
    }
    if (isset($_GET['param1'])) {
        $recupProduits2 = recupProduits($auth, $_GET['param2'], $_GET['act']);
        // Ici le GET attend du JSON, pas du HTML, donc on lui renvoie la même info sous forme de JSON pure.
        if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == FALSE ) && ($_SERVER['REQUEST_METHOD'] == 'GET')) {

            echo json_encode($recupProduits2);
        }
    }
    
}
if(isset($_POST['add2Cart'])){
    $add2Cart = add2Cart($auth, $_POST['quantiteProduit'], $_POST['idProduit'], $_POST['idSociete']);
}
if(isset($_POST['searchProduct'])){
    searchAndAdd($auth, $_POST['refSearch'], $_POST['qteSearch']);
}
if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == TRUE)) {
    require_once('./vues/societe.php');
}
?>