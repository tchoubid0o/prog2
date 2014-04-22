<div class="minicart_con hidden" style="display: none;">
    <?php $prixPanier = 0; ?>
    <div class="center">Votre panier</div>
    <ul class="minicart_item_list">
        <?php if (isset($basket)) { ?>
            <div class="minicart_list center">
                <ul class="minicart_item_list">
                    <?php
                    foreach ($basket as $produit) {
                        ?>
                        <li class="minicart_item" data-sku="294302">
                            <?php echo $produit['libelleProduit']; ?> Qté <?php echo $produit['qteProduit']; ?> Prix <?php
                            $prixTot = $produit['prixProduit'] * $produit['qteProduit'];
                            $prixPanier += $prixTot;
                            echo $prixTot;
                            ?>
                        </li>
                    <?php }
                    ?>
                </ul>
            </div><br/>
            <div class="minicart_end center">
                TOTAL : <?php echo $prixPanier; ?>€<br/>
                <a class="btn_viewcart" href="">Passer ma commande</a>
            </div><?php
        } else {
            echo "<div class='center'>Votre panier est vide</div>";
        }
        ?>

</div>

<script type="text/javascript">
    $("#miniCart").mouseenter(function() {
        $(".minicart_con").show();
    });

    $("#miniCart").mouseleave(function() {
        $(".minicart_con").hide();
    });
</script>