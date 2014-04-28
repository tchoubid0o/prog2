<?php

require_once('./modeles/mySpace.php');
    $orders = getOrders($auth);
    $infos = getMyInfos($auth);
require_once('./vues/mySpace.php');
?>