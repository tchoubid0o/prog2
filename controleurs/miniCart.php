<?php

require_once('./modeles/miniCart.php');

require_once('./modeles/basket.php');



if(isset($_POST['deleteMiniCart'])){
    deleteItem($auth, $_POST['idProduit']);
}

$basket = getBasket($auth);

require_once('./vues/miniCart.php');

?>