<?php
require_once('./modeles/OrderDetail.php');
    if(isset($_GET['act'])){
        $order = getOrderDetail($auth, $_GET['act']);
        $produits = getProductsOrder($auth, $_GET['act']);
    }
    if(isset($_POST['getInvoice'])){
        $getInvoice = getInvoice($auth);
    }
require_once('./vues/OrderDetail.php');
?>