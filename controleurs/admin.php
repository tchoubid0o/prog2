<?php

require_once('./modeles/admin.php');

if (isset($_SESSION['id'])) {
    if(isset($_POST['addNewSociete'])){
        addSociete($auth, $_POST['addNewSociete']);
    }
    
    if (isset($_POST['addClient'])) {
        if (!isset($_POST['accesSociete'])) {
            $_POST['accesSociete'] = -999;
        }
        insertClient($auth,$_POST['password'], $_POST['mail'], $_POST['adresse'], $_POST['accesSociete']);
    }
    if (isset($_POST['idClient'])) {
        $delete = deleteData($auth, "user", $_POST['idClient']);
    }
    if (isset($_POST['idSociete'])) {
        $delete = deleteData($auth, "societe", $_POST['idSociete']);
    }
    if (isset($_GET['act'])) {
        if ($_GET['act'] == "modifyclient") {
            if (isset($_POST['modifyClient'])) {
                if (!isset($_POST['accesSociete'])) {
                    $_POST['accesSociete'] = -999;
                }
                updateClient($auth, $_GET['param1'], $_POST['mail'], $_POST['adresse'], $_POST['password'], $_POST['accesSociete']);
            }
            $infoClient = getInfoClient($auth, $_GET['param1']);
        }
        if ($_GET['act'] == "OrderDetail") {
            require_once('./modeles/OrderDetail.php');
            $order = getOrderDetail($auth, $_GET['param2']);
            $produits = getProductsOrder($auth, $_GET['param2']);
        }
    }
    $clients = getClients($auth);
    $societes = getSocietes($auth);
}

require_once('./vues/admin.php');
?>