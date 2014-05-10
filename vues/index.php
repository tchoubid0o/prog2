
<?php if (!empty($_SESSION['id'])) { ?> 
    <div style="width: 400px; margin: auto;">
    <div class="menuAccordion" style="padding: 0px; margin-top: 20px; color: #56595E;">
        <div>
            <h1 style="text-align: center;">Mes Catalogues</h1>
        </div>
        <div style="margin-top: 55px;">
            <?php
            if(isset($societes)){
                foreach ($societes as $societe) {
                    echo "<h3 style='text-align: center;'><a href='" . ROOTPATH . "/societe-" . $societe['idSociete'] . ".html'> " . $societe['nomSociete'] . "</a></h3><br/>";
                }
            }
    }
    ?>
        </div>
    </div>
    </div>