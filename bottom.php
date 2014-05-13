<?php 
if (!empty($_SESSION['id'])) {
    if($_GET['page'] != "admin" && $_GET['page'] != "societeadm" && $_GET['page'] != "import"){?>     
        <div style="width: 1000px; margin: auto;">
        <div style="width: 250px; margin-left: 750px;">
            <div style="position: absolute; top: 12px;"><div id="miniCart" class="miniCart"><a href="<?php echo ROOTPATH; ?>/basket.html">Mon panier</a><div id="previewMinicart"><?php include("controleurs/miniCart.php"); ?></div></div></div>
        </div>
        </div>
        <?php 
        
    }
}?>
</div>
<div id="global"></div>
<?php
    if(isset($_SESSION['adm'])){
        if($_SESSION['adm'] == 1) {?>
           <a href="admin.html" style="position: fixed; bottom: 10px; right: 15px;">Administration</a>
           <?php
        }
    }
?>
</body>
</html>