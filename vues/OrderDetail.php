<div class="menuAccordion" style="padding: 0px; margin-top: 20px;color: #56595E;">
<?php if(isset($_GET['act']) && !empty($_GET['act'])){?>
<div class="width800">
    <?php date_default_timezone_set("Europe/Paris");
    if(empty($order['message'])){
    ?>
    <h1 class="center">Commande du <?php echo date("d/m/Y", strtotime($order['dateCommande'])); ?></h1> <br/>
    <div>
        <table id="basketTable">
            <tr>
                <th>Produit</th>
                <th>Référence</th>
                <th>Quantité</th>
                <th>Prix U</th>
                <th>Prix</th>
            </tr>
            <?php
            $prixPanier = 0;
            if (isset($produits)) {
                foreach ($produits as $produit) {
                    ?>
                    <tr>
                        <td><?php echo $produit['libelleProduit']; ?></td>
                        <td><?php echo $produit['codeProduit']; ?></td>
                        <td><?php echo $produit['quantity']; ?></td>
                        <td><?php echo $produit['prixProduit']; ?></td>
                        <td><?php
                            $prixTot = $produit['prixProduit'] * $produit['quantity'];
                            $prixPanier += $prixTot;
                            echo $prixTot;
                            ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <h3 class="center">
            <span class="underline">Total:</span> 
            <span id="cartPrice"><?php echo $prixPanier; ?></span>€
        </h3>
        <div class="center">
            <a href="getInvoice.php?id=<?php echo $_GET['act']; ?>">Exporter sous excel</a>
        </div>
    </div>
    <?php }else{
        echo "<div style='margin-top: 25px;'>".$order['message']."</div>";
    }
    ?>
</div>
<?php } ?>
</div>