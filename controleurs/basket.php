<?php
require_once('./modeles/basket.php');
    $basket = getBasket($auth);
    if(isset($_POST['qteProduit'])){modifyCart($auth, $_POST['idProduit'], $_POST['qteProduit']);};
    if(isset($_POST['deleteProduit'])){deleteItem($auth, $_POST['idProduit']);};
require_once('./vues/basket.php');
?>