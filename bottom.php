<?php 
if (!empty($_SESSION['id'])) {
    if($_GET['page'] != "admin"){?>     
        <div style="width: 250px; margin-left: 750px;">
            <div style="position: absolute; top: 12px;"><div id="miniCart" class="miniCart"><a href="<?php echo ROOTPATH; ?>/basket.html">Mon panier</a><div id="previewMinicart"><?php include("controleurs/miniCart.php"); ?></div></div></div>
        </div>
        <?php 
        
    }
}?>
</div>
</body>
</html>