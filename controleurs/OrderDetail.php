<?php
require_once('./modeles/OrderDetail.php');
    if(isset($_GET['act'])){
        $order = getOrderDetail($auth, $_GET['act']);
        $produits = getProductsOrder($auth, $_GET['act']);
    }
require_once('./vues/OrderDetail.php');
?>