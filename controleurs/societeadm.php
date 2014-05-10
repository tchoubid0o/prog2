<?php

require_once('./modeles/societeadm.php');

if(isset($_POST['AddCat_SocieteId']) && isset($_POST['AddCat_libelleCat'])){
    addNewCat($auth, $_POST['AddCat_ParentId'], $_POST['AddCat_SocieteId'], $_POST['AddCat_libelleCat'], $_POST['AddCat_code']);
}

if(isset($_POST['idPostFormFastSearch'])){
    echo json_encode(getProductQty($auth, $_POST['codeProduit']));
}

if (isset($_POST['idSociete'])) {
    $_GET['param1'] = $_POST['idSociete'];
}
if (isset($_GET['param1'])) {
    //Récupère les références produits de la société pour la recherche rapide
    $nomSociete = getNomSociete($auth, $_GET['param1']);
    $donnees = getSociete($auth, $_GET['param1']);
    
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
        $recupProduits2 = recupProduits($auth, $_GET['idCat'], $_GET['param1'], $_GET['nbProduct'], $_GET['idPage']);
        // Ici le GET attend du JSON, pas du HTML, donc on lui renvoie la même info sous forme de JSON pure.
        if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == FALSE ) && ($_SERVER['REQUEST_METHOD'] == 'GET')) {

            echo json_encode($recupProduits2);
        }
    }
}

if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == TRUE)) {
    require_once('./vues/societeadm.php');
}
?>