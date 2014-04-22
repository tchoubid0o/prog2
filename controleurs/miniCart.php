<?php

require_once('./modeles/miniCart.php');

require_once('./modeles/basket.php');
$basket = getBasket($auth);

require_once('./vues/miniCart.php');

?>