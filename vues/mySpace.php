<div class="width1000">
    <h1 class="underline center">Mon compte</h1>
    <div class="width500 left">
        <div style="width: 300px; margin: auto;">
            <h2 class="center">Mon profil</h2>
            <div style="border: 1px solid black; padding: 15px; height: 250px;">
                <form method="post" id="userSettings" action="mySpace.html">
                    <div class="spaceBot">
                        <label for="societe">Société: </label>
                        <input type="text" id="societe" name="societe" value="<?php if(!empty($infos['societe'])){echo $infos['societe'];} ?>"><br/>
                    </div>
                    
                    <div class="spaceBot">
                    <label for="e-mail">e-mail: </label>
                    <input type="email" id="email" name="email" value="<?php if(!empty($infos['mail'])){echo $infos['mail'];} ?>"><br/>
                    </div>
                    
                    <div class="spaceBot">
                    <label for="adresse">adresse: </label>
                    <input type="text" id="adresse" name="adresse" value="<?php if(!empty($infos['adresse'])){echo $infos['adresse'];} ?>"><br/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="width500 left">
        <div style="width: 300px; margin: auto;">
            <h2 class="center">Mes commandes</h2>
            <div style="border: 1px solid black; padding: 15px; height: 250px;">
                <?php
                    foreach($orders as $order){
                        echo "<a href='OrderDetail-".$order['keyOrder'].".html'>Commande du ".$order['date']."</a> <span class='right'>".$order['priceCmd']."€</span><br/>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>