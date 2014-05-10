<div class="minicart_con hidden" style="display: none;">
    <?php $prixPanier = 0; ?>
    <div class="center">Votre panier</div>
        <?php if (isset($basket)) { ?>
            <div class="minicart_list center">
                <ul class="minicart_item_list">
                    <?php
                    foreach ($basket as $produit) {
                        ?>
                        <li class="minicart_item" data-ref="<?php echo $produit['codeProduit']; ?>">
                            <?php echo $produit['libelleProduit']; ?> Qté <?php echo $produit['qteProduit']; ?> Prix <?php
                            $prixTot = $produit['prixProduit'] * $produit['qteProduit'];
                            $prixPanier += $prixTot;
                            echo $prixTot;
                            ?>
                            <form method="post" action="" style="display: inline;">
                                <input type="hidden" name="deleteMiniCart" value="1" />
                                <input type="hidden" name="idProduit" value="<?php echo $produit['idProduit']; ?>" />
                                <i class="fa fa-times deleteItemMiniCart" style="color: #2db3e8;"></i>
                            </form>
                        </li>
                    <?php }
                    ?>
                </ul>
            </div><br/>
            <div class="minicart_end center">
                TOTAL : <?php echo $prixPanier; ?>€<br/>
                <a class="btn_viewcart" href="basket.html">Passer ma commande</a>
            </div><?php
        } else {
            echo "<div class='center'>Votre panier est vide</div>";
        }
        ?>

</div>

<script type="text/javascript">
    var ROOTPATH = "/prog2/";
    
    $("#miniCart").mouseenter(function() {
        $(".minicart_con").show();
    });

    $("#miniCart").mouseleave(function() {
        $(".minicart_con").hide();
    });
    
    $(".deleteItemMiniCart").click(function(){
        $(this).parent().submit();
        //$.post(ROOTPATH+'miniCart.html', {idProduit: $(this).attr('data-idProduit')});
    });
</script>