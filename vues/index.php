
<?php if (!empty($_SESSION['id'])) { ?> 

    <div>
        <h1 style="text-align: center;">Mes Catalogues</h1>
    </div>
    <div style="margin-top: 55px;">
        <?php
        foreach ($societes as $societe) {
            echo "<h3 style='text-align: center;'><a href='" . ROOTPATH . "/societe-" . $societe['idSociete'] . ".html'> " . $societe['nomSociete'] . "</a></h3><br/>";
        }
    }
    ?>
</div>