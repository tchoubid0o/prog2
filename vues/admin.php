<?php
if (isset($_SESSION['id'])) {
    if ($_SESSION['adm'] == 1) {
        ?>
        <div style="height: 45px;background-color: white;border-top: 2px solid #2db3e8;border-bottom-color: rgba(0,0,0,0.2);border-bottom: 1px solid rgba(0,0,0,0.1);">
            <div style="width: 1000px; margin: auto; padding-top: 10px;">
        <a href="<?php echo ROOTPATH; ?>/index.html">Index &nbsp;</a>
        <a href="<?php echo ROOTPATH; ?>/admin.html">Administration &nbsp;</a>
        <?php
        if (isset($_GET['act']) && $_GET['act'] == "modifyclient") {
            echo "> Client n°" . $_GET['param1'] . "&nbsp;";
        }
        if (isset($_GET['act']) && $_GET['act'] == "adduser") {
            $nb = countNbClients($auth);
            echo "> Client n°" . ($nb['nb'] + 1) . "&nbsp;";
        }
        if (isset($_GET['act']) && $_GET['act'] == "OrderDetail") {
            echo "> <a href='admin-modifyclient&".$_GET['param1'].".html'>Client n°" . $_GET['param1'] . "&nbsp;</a> > commande du " . date("d/m/Y", strtotime($order['dateCommande'])) . "&nbsp;";
        }
        if (isset($_GET['act']) && $_GET['act'] == "societe") {
            echo "> Société n°" . $_GET['param1'] . "&nbsp;";
        }
        if (!isset($_GET['act'])) {
            ?>
            </div></div>
            <!--Page d'Index du panneau d'Administration-->
            <div class="menuAccordion" style="padding: 0px; margin-top: 20px;color: #56595E; width: 1000px; margin: auto; margin-top: 20px;">
            <h4 style="text-align: center;">Accueil Administration</h4>
            </div>
            <div style="width:440px; margin: auto; margin-top: 20px;">
                <div id="clientsAdm" class="menuAccordion" style="width: 150px;float: left; padding: 20px;margin-right: 20px;">
                    <h5 style="color: #56595E; text-align: center;">Mes Clients</h5>
                    <div style="overflow-x: hidden; overflow-y: scroll;height: 300px;">
                        <?php
                        foreach ($clients as $client) {
                            echo "<a href='" . ROOTPATH . "/admin-modifyclient&" . $client['id'] . ".html' style='color: black;'>" . $client['societe'] . "</a><form style='display: inline;' action='" . ROOTPATH . "/admin.html' enctype='multipart/form-data' method='post'>
                        <input type='hidden' name='idClient' value='" . $client['id'] . "' />
                        <input style='width: 15px; display: inline;' type='image' src='" . ROOTPATH . "/img/1387754326_001_05.png' alt='submit' name='submitDeleteClient" . $client['id'] . "' />
                        </form><br/>

                        ";
                        }
                        ?>
                    </div>
                    <a style="color: #2db3e8;" href="<?php echo ROOTPATH; ?>/admin-adduser.html">Ajouter un client</a>
                </div>
                <div id="societesAdm" class="menuAccordion" style="width: 150px;float: left; padding: 20px;">
                    <h5 style="color: #56595E; text-align: center;">Mes Sociétés</h5>
                    <div style="overflow-x: hidden; overflow-y: scroll;height: 300px;">
                        <?php
                        if(isset($societes)){
                            foreach ($societes as $societe) {
                                echo "<a href='" . ROOTPATH . "/societeadm." . $societe['idSociete'] . ".html' style='color: black;'>" . $societe['nomSociete'] . "</a><form style='display: inline;' action='" . ROOTPATH . "/admin.html' enctype='multipart/form-data' method='post'>
                            <input type='hidden' name='idSociete' value='" . $societe['idSociete'] . "' />
                            <input style='width: 15px; display: inline;' type='image' src='" . ROOTPATH . "/img/1387754326_001_05.png' alt='submit' name='submitDeleteSociete" . $societe['idSociete'] . "' />
                            </form><br/>

                            ";
                            }
                        }
                        else{
                            echo "Aucune société.";
                        }
                        ?>
                    </div>
                    <a style="color: #2db3e8;" href="<?php echo ROOTPATH; ?>/admin-addsociete.html">Ajouter une société</a>
                </div>
                <div style="clear:both;"></div>
            </div>
            <?php
        } else {
            if ($_GET['act'] == "modifyclient") {
                ?>
                <!--Modification d'un utilisateur-->
                
                <div class="menuAccordion" style="padding: 0px; color: #56595E; width: 1000px; margin: auto; margin-top: 30px;">
                    <h4 style="text-align: center;">Client n°<?php echo $_GET['param1']; ?></h4>
                </div>
                <form action="<?php echo ROOTPATH; ?>/admin-modifyclient&<?php echo $_GET['param1']; ?>.html" method="post" class="menuAccordion" style="padding: 0px; margin-top: 20px; padding-bottom: 20px;" enctype="multipart/form-data">
                    <div style="width:720px; margin: auto;">
                        <div id="clientsAdm" style="width: 200px;text-align: center;float: left; padding: 20px;">
                            <h5>Son Profil</h5>
                            <div style="height: 300px;border: 1px solid black;">


                                <label for="societe">Société</label><br/>
                                <input type="text" value="<?php
                                if (!empty($infoClient['societe'])) {
                                    echo $infoClient['societe'];
                                }
                                ?>" name="societe" id="societe" /><br/><br/>

                                <label for="mail">E-mail</label><br/>
                                <input type="text" value="<?php
                                if (!empty($infoClient['mail'])) {
                                    echo $infoClient['mail'];
                                }
                                ?>" name="mail" id="mail"/><br/><br/>

                                <label for="adresse">Adresse</label><br />
                                <input type="text" name="adresse" id="adresse" value="<?php
                                if (!empty($infoClient['adresse'])) {
                                    echo $infoClient['adresse'];
                                }
                                ?>" /><br/><br/>

                                <label for="password">Mot de passe</label><br/>
                                <input type="text" value="<?php
                                if (!empty($infoClient['password'])) {
                                    echo $infoClient['password'];
                                }
                                ?>" name="password" id="password" maxlength="20"/><br /><br />
                            </div>
                        </div>
                        <div id="societesAdm" style="width: 200px;text-align: center;float: left; padding: 20px;">
                            <h5>Accès Sociétés</h5>
                            <div style="overflow-x: hidden; overflow-y: scroll;height: 300px;border: 1px solid black;">
                                <?php
                                $accesClients = getAccesClient($auth, $_GET['param1']);
                                if (!empty($societes)) {
                                    foreach ($societes as $societe) {
                                        echo "<input type='checkbox' name='accesSociete[]' value='" . $societe['idSociete'] . "'";
                                        if (!empty($accesClients)) {
                                            foreach ($accesClients as $accesClient) {
                                                if ($accesClient['idSociete'] == $societe['idSociete']) {
                                                    echo "checked";
                                                }
                                            }
                                        }
                                        echo "> " . $societe['nomSociete'] . "<br/>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div id="historiqueAdm" style="width: 200px;text-align: center;float: left; padding: 20px;">
                            <h5>Historique des Commandes</h5>
                            <div style="overflow-x: hidden; overflow-y: scroll;height: 300px;border: 1px solid black;">
                                <?php
                                $allSocietes = getAllSocietes($auth);
                                //On récupère toutes les sociétés
                                if(isset($allSocietes)){
                                    foreach ($allSocietes as $allSociete) {
                                        $orders = getOrdersClient($auth, $_GET['param1'], $allSociete['idSociete']);
                                        if (!empty($orders)) {
                                            echo "" . $allSociete['nomSociete'] . "<br/>";
                                            foreach ($orders as $order) {
                                                date_default_timezone_set("Europe/Paris");
                                                $order['date'] = date("d/m/Y", strtotime($order['dateCommande']));
                                                echo "<a href='admin-OrderDetail&" . $_GET['param1'] . "&" . $order['keyOrder'] . ".html'>Commande du " . $order['date'] . "</a><br/>";
                                            }
                                        }
                                    }
                                }
                                else{
                                    echo "Aucune commande.";
                                }
                                ?>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <center>
                            <input type="hidden" name="modifyClient" value="1" />
                            <input class="classButton" type="submit" value="Valider" id="submit" /></center>
                    </div>
                </form>
                <?php
            }
            if ($_GET['act'] == "adduser") {
                $nbUser = countNbClients($auth);
                ?>
                <!--Ajout d'un utilisateur-->
                <div class="menuAccordion" style="padding: 0px; color: #56595E; width: 1000px; margin: auto; margin-top: 30px;">
                    <h4 style="text-align: center;">Client n°<?php echo ($nbUser['nb'] + 1); ?></h4>
                </div>
                <form action="<?php echo ROOTPATH; ?>/admin.html" method="post" class="menuAccordion" style="padding: 0px; margin-top: 20px; padding-bottom: 20px;" enctype="multipart/form-data">
                    <div style="width:720px; margin: auto;">
                        <div id="clientsAdm" style="width: 200px;text-align: center;float: left; padding: 20px;">
                            <h5>Son Profil</h5>
                            <div style="height: 300px;border: 1px solid black;">


                                <label for="societe">Société</label><br/>
                                <input type="text" value="" name="societe" id="societe" /><br/><br/>

                                <label for="mail">E-mail</label><br/>
                                <input type="text" value="" name="mail" id="mail"/><br/><br/>

                                <label for="adresse">Adresse</label><br />
                                <input type="text" name="adresse" id="adresse" value="" /><br/><br/>

                                <label for="password">Mot de passe</label><br/>
                                <input type="password" placeholder="**********" name="password" id="password"/><br /><br />
                            </div>
                        </div>
                        <div id="societesAdm" style="width: 200px;text-align: center;float: left; padding: 20px;">
                            <h5>Accès Sociétés</h5>
                            <div style="overflow-x: hidden; overflow-y: scroll;height: 300px;border: 1px solid black;">
                                <?php
                                if (!empty($societes)) {
                                    foreach ($societes as $societe) {
                                        echo "<input type='checkbox' name='accesSociete[]' value='" . $societe['idSociete'] . "'";
                                        echo "> " . $societe['nomSociete'] . "<br/>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div id="historiqueAdm" style="width: 200px;text-align: center;float: left; padding: 20px;">
                            <h5>Historique des Commandes</h5>
                            <div style="overflow-x: hidden; overflow-y: scroll;height: 300px;border: 1px solid black;">

                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <center>
                            <input type="hidden" name="addClient" value="1" />
                            <input class="classButton" type="submit" value="Valider" id="submit" /></center>
                    </div>
                </form>
                <?php
            }
            if($_GET['act'] == "addsociete"){?>
                <!--Ajout d'une société-->
                <div class="menuAccordion" style="padding: 0px; color: #56595E; width: 1000px; margin: auto; margin-top: 30px;margin-bottom: 20px;">
                    <h4 style="text-align: center;">Ajout d'une nouvelle société</h4>
                </div>
                <form method="post" action="<?php echo ROOTPATH; ?>/admin.html" class="menuAccordion" style="padding: 20px; padding-bottom: 20px; text-align: center; margin: auto;">
                    <label for="addNewSociete">Nom de la société:</label>
                    <input type="text" name="addNewSociete" id="addNewSociete" required/><br/>
                    <input type="submit" class="classButton" value="Ajouter la société" />
                </form>
                <?php
            }
            if ($_GET['act'] == "OrderDetail") {
                ?>
                <!--Détail d'une commande client-->
                <div class="menuAccordion" style="padding: 0px; margin-top: 30px;color: #56595E;">
                <div class="width800">
                    <?php
                    date_default_timezone_set("Europe/Paris");
                    if (empty($order['message'])) {
                        ?>
                        <h1 class="center underline">Client n°<?php echo $_GET['param1']; ?></h1>
                        <h3>Commande du <?php echo date("d/m/Y", strtotime($order['dateCommande'])); ?></h3> <br/>
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
                                <a href="getInvoice.php?id=<?php echo $_GET['param2']; ?>">Exporter sous excel</a>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "<div style='margin-top: 25px;'>" . $order['message'] . "</div>";
                    }
                    ?>
                </div>
                </div>
                <?php
            }
            if ($_GET['act'] == "import") {
                include('import.php');
            }
            if ($_GET['act'] == "societe") {
                
            }
        }
    }
}
?>
