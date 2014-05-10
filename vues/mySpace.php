<div class="menuAccordion" style="padding: 0px; margin-top: 20px;color: #56595E;">
    <div class="width1000">
        <h1 class="underline center">Mon compte</h1>
        <div class="width500 left" style="margin-top: 20px;">
            <div style="width: 300px; margin: auto;" class="menuAccordion">
                <h2 class="center" style="margin: 0px;">Mon profil</h2>
                <div style="padding: 15px; height: 250px;">
                    <form id="userSettings">
                        <div class="spaceBot">
                            <label for="societe">Société: </label>
                            <input type="text" id="societe" name="societe" value="<?php if(!empty($infos['societe'])){echo $infos['societe'];} ?>" disabled><br/>
                        </div>

                        <div class="spaceBot">
                        <label for="e-mail">e-mail: </label>
                        <input type="email" id="email" name="email" value="<?php if(!empty($infos['mail'])){echo $infos['mail'];} ?>" disabled><br/>
                        </div>

                        <div class="spaceBot">
                        <label for="adresse">adresse: </label>
                        <input type="text" id="adresse" name="adresse" value="<?php if(!empty($infos['adresse'])){echo $infos['adresse'];} ?>" disabled><br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="width500 left" style="margin-top: 20px;">
            <div style="width: 300px; margin: auto;" class="menuAccordion">
                <h2 class="center" style="margin: 0px;">Mes commandes</h2>
                <div style="padding: 15px; height: 250px;">
                    <?php
                        if(isset($orders)){
                            foreach($orders as $order){
                                echo "<a href='OrderDetail-".$order['keyOrder'].".html'>Commande du ".$order['date']."</a> <span class='right'>".$order['priceCmd']."€</span><br/>";
                            }
                        }
                        else{
                            echo "<div style='text-align: center'>Aucune commande.</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>