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
                    <td><?php echo $produit['codeProduit']; ?></td>
                    <td>
                        <form method="post" action="basket.html">
                            <input type="hidden" name="idProduit" value="<?php echo $produit['idProduit']; ?>">
                            <input name="qteProduit" style="font-size: 13px; width: 70px;" class="qteProd" id="spinner_<?php echo $produit['codeProduit']; ?>" value="<?php echo $produit['qteProduit']; ?>"/>
                            <script>
                                $(function() {
                                    $('#spinner_<?php echo $produit['codeProduit']; ?>').spinner({
                                        min: <?php echo $produit['minQte']; ?>,
                                        max: <?php echo $produit['quantiteProduit']; ?>,
                                        step: <?php echo $produit['minQte']; ?>});
                                });
                            </script>
                        </form>
                        <form class="deleteProduit" action="basket.html" method="post">
                            <input type="hidden" name="idProduit" value="<?php echo $produit['idProduit']; ?>">
                            <input type="hidden" name="deleteProduit" value="1">
                            <input type="submit" style="cursor: pointer;margin: 0;padding: 0;line-height: 100%;border: 0;background: transparent;" value="supprimer"/>
                        </form>
                    </td>
                    <td class="prixU"><?php echo $produit['prixProduit']; ?></td>
                    <td class="prix"><?php
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
        <a href="OrderProcess.html" class="classButton right">Valider ma commande</a>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".ui-button-text").click(function(event) {
            $(this).parent().parent().parent().submit(event);
            event.preventDefault();

            var prixU = parseFloat($(this).parent().parent().parent().parent().parent().find("td.prixU").text());
            var oldPrixProduct = parseFloat($(this).parent().parent().parent().parent().parent().find("td.prix").text());
            var qte = parseFloat($(this).parent().parent().find("input").val());
            var newProductPrice = parseFloat(prixU) * parseFloat(qte);
            $(this).parent().parent().parent().parent().parent().find("td.prix").html(newProductPrice);

            var oldCartPrice = $("#cartPrice").text();

            if (oldPrixProduct >= newProductPrice) {
                var newCartPrice = parseFloat(oldCartPrice) - (parseFloat(oldPrixProduct) - parseFloat(newProductPrice));
                $("#cartPrice").html(newCartPrice);
            }
            else {
                var newCartPrice = parseFloat(oldCartPrice) + (parseFloat(newProductPrice) - parseFloat(oldPrixProduct));
                $("#cartPrice").html(newCartPrice);
            }

            $.ajax({url: "basket.html",
                type: "POST",
                dataType: "json",
                data: $(this).parent().parent().parent().serialize()
            }).done(function(data) {
                $("#previewMinicart").html(data.miniCart);
            });
        });
        $(".deleteProduit").click(function(event) {
            event.preventDefault();
            
            var oldPrixProduct = parseFloat($(this).parent().parent().find("td.prix").text());

            var oldCartPrice = $("#cartPrice").text();

            var newCartPrice = parseFloat(oldCartPrice) - (parseFloat(oldPrixProduct));
            $("#cartPrice").html(newCartPrice);
            
            $.ajax({url: "basket.html", 
                type: "POST",
                dataType: "json",
                data: $(this).serialize()
                        //On envoie le formulaire qui contient: idProduit et qteProduit
            }).done(function(data) {
                $("#previewMinicart").html(data.miniCart);
            });
            $(this).parent().parent().hide("slow");
        });
    });
</script>