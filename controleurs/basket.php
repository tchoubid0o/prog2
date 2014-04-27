<?php

require_once('./modeles/basket.php');
$basket = getBasket($auth);
if (isset($_POST['qteProduit'])) {
    modifyCart($auth, $_POST['idProduit'], $_POST['qteProduit']);
    ob_start();
    include './controleurs/miniCart.php';
    $view = ob_get_clean();
    ob_end_flush();

    $return_array = array('miniCart' => $view);
    
    echo json_encode($return_array);
}
if (isset($_POST['deleteProduit'])) {
    deleteItem($auth, $_POST['idProduit']);
    
    ob_start();
    include './controleurs/miniCart.php';
    $view = ob_get_clean();
    ob_end_flush();

    $return_array = array('miniCart' => $view);
    
    echo json_encode($return_array);
}
if ((strstr($_SERVER['HTTP_ACCEPT'], "html") == TRUE)) {
require_once('./vues/basket.php');
}
?>