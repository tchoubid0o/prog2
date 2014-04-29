<?php if (!isset($_SESSION['id'])) {
    ?>
    <div id="logintop">
        <div class="loginh">	
            <center>
                <h2 style="font-size: 1.75rem;line-height: 1.6em;text-align: center;color: #223340;font-weight: bold;margin: 2rem 0 0;margin-top: 0px; margin-bottom: 10px; text-decoration: underline;">Mot de passe oublié</h2>
                <form action="<?php echo ROOTPATH; ?>/recover.html" method="post">
                    <label for="mail" class="labmail" style="width: 100px; float: left;">e-mail :</label>
                    <div class="input_content" style="display: inline;"><input class="input_insc" id="mail" type="text" name="mail" placeholder="Adresse email" /></div><br />

                    <br />
                    <div style="margin: auto;">
                        <input class="submit" type="submit" value="Envoyer" style="cursor: pointer;color: #fff;border-radius: 4px;padding: 10px;background-color: #2db3e8;text-transform: none;text-decoration: none;font-weight: 600;-moz-transition: background-color 0.35s linear;-webkit-transition: background-color 0.35s linear;transition: background-color 0.35s linear;" id="submit" />	
                        <?php
                        if(isset($recover['message'])){
                        echo "<br/><br/>".$recover['message'];
                        }
                        ?>
                    </div>
                </form>
            </center>
        </div>
    </div>
    <?php
} else {
    echo "Vous êtes déjà connecté!<br/><a href='" . ROOTPATH . "/index.html'>Retour vers l'index</a>";
}?>