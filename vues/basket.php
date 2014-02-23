<!-- Vue Panier -->
<!-- !!!ATTENTION!!! IL FAULT ENCORE RECALCULER LE PRIX TOTAL DU PANIER APRES LES REQUETES AJAX!! -->
<div class="width1000">
    <?php $prixPanier = 0; ?>
    <table id="basketTable">
        <tr>
            <th>Produit</th>
            <th>Référence</th>
            <th>Quantité</th>
            <th>Prix U</th>
            <th>Prix</th>
        </tr>
        <?php
        if (isset($basket)) {
            foreach ($basket as $produit) {
                ?>
                <tr>
                    <td><?php echo $produit['libelleProduit']; ?></td>
                    <td><?php echo $produit['refProduit']; ?></td>
                    <td>
                        <form method="post" action="basket.html">
                            <input type="hidden" name="idProduit" value="<?php echo $produit['idProduit']; ?>">
                            <input name="qteProduit" style="font-size: 13px; width: 70px;" class="qteProd" id="spinner_<?php echo $produit['refProduit']; ?>" value="<?php echo $produit['qteProduit']; ?>"/>
                            <script>
                                $(function() {
                                    $('#spinner_<?php echo $produit['refProduit']; ?>').spinner({
                                        min: <?php echo $produit['minQte']; ?>,
                                        max: <?php echo $produit['quantiteProduit']; ?>,
                                        step: <?php echo $produit['minQte']; ?>
                                    });
                                });
                            </script>
                        </form>
                        <form class="deleteProduit" action="basket.html" method="post">
                            <input type="hidden" name="idProduit" value="<?php echo $produit['idProduit']; ?>">
                            <input type="hidden" name="deleteProduit" value="1">
                            <input type="submit" style="cursor: pointer;margin: 0;padding: 0;line-height: 100%;border: 0;background: transparent;" value="supprimer"/>
                        </form>
                    </td>
                    <td><?php echo $produit['prixProduit']; ?></td>
                    <td><?php
                        $prixTot = $produit['prixProduit'] * $produit['qteProduit'];
                        $prixPanier += $prixTot;
                        echo $prixTot;
                        ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <div class="width800">
        <div class="right">Total: <span id="cartPrice"><?php echo $prixPanier; ?></span>€</div>
        <div style="clear: both;"></div>
        <a href="index.html" class="classButton left">Continuer mes achats</a>
        <a href="valide.html" class="classButton right">Valider ma commande</a>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".ui-button-text").click(function(event) {
            $(this).parent().parent().parent().submit(event);
            event.preventDefault();
            $.ajax({url: "basket.html", type: "POST",
                data: $(this).parent().parent().parent().serialize()
                //On envoie le formulaire qui contient: idProduit et qteProduit
            });
        });
        $(".deleteProduit").click(function(event){
            event.preventDefault();
            $.ajax({url: "basket.html", type: "POST",
                data: $(this).serialize()
                //On envoie le formulaire qui contient: idProduit et qteProduit
            });
            $(this).parent().parent().hide("slow");
        });
    });
</script>