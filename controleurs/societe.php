<?php

require_once('./modeles/societe.php');


if(isset($_POST['idPostFormFastSearch'])){
    echo json_encode(getProductQty($auth, $_POST['codeProduit']));
}


if (isset($_POST['add2Cart'])) {
    $add2Cart = add2Cart($auth, $_POST['quantiteProduit'], $_POST['idProduit'], $_POST['idSociete']);
    //echo json_encode($add2Cart);

    ob_start();
    include './controleurs/miniCart.php';
    $view = ob_get_clean();
    ob_end_flush();
    
    $return_array = array($add2Cart, 'miniCart' => $view);
        
    echo json_encode($return_array);
}

if (isset($_POST['idSociete'])) {
    $_GET['act'] = $_POST['idSociete'];
}
if (isset($_GET['act'])) {
    //Récupère les références produits de la société pour la recherche rapide
    $allRefs = getAllRef($auth, $_GET['act']);
    $nomSociete = getNomSociete($auth, $_GET['act']);
    $donnees = getSociete($auth, $_GET['act']);
    
    //On clique sur une catégorie
    if (isset($_POST['idCategorie']) && isset($_POST['idSociete'])) {
        $recupProduits = recupProduits($auth, $_POST['idCategorie'], $_POST['idSociete'], $_POST['nbProduct'], $_POST['idPage']);
        // Ici le GET attend du JSON, pas du HTML, donc on lui renvoie la même info sous forme de JSON pure.
        if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == FALSE ) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {

            echo json_encode($recupProduits);
        }
    }
    //index.php?page=$1&act=$2&nbProduct=$3&idCat=$4&idPage=$5

    if (isset($_GET['nbProduct'])) {
        $recupProduits2 = recupProduits($auth, $_GET['idCat'], $_GET['act'], $_GET['nbProduct'], $_GET['idPage']);
        // Ici le GET attend du JSON, pas du HTML, donc on lui renvoie la même info sous forme de JSON pure.
        if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == FALSE ) && ($_SERVER['REQUEST_METHOD'] == 'GET')) {

            echo json_encode($recupProduits2);
        }
    }
}

if (isset($_POST['searchProduct'])) {
    searchAndAdd($auth, $_POST['refSearch'], $_POST['qteSearch']);
    ob_start();
    include './controleurs/miniCart.php';
    $view = ob_get_clean();
    ob_end_flush();
    
    $return_array = array('miniCart' => $view);
        
    echo json_encode($return_array);
}
if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == TRUE)) {
    require_once('./vues/societe.php');
}
?>