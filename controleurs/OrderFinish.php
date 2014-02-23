<?php
require_once('./modeles/OrderFinish.php');
    if(isset($_POST['orderConfirm'])){
        $confirm = confirmOrder($auth, $_POST['dateOrder'], $_POST['commentOrder']);
    }
require_once('./vues/OrderFinish.php');
?>