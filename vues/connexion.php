<?php if (!isset($_SESSION['id'])) {
    ?>
    <div id="logintop">
        <div class="loginh">	
            <center>
                <h2 style="font-size: 1.75rem;line-height: 1.6em;text-align: center;color: #223340;font-weight: bold;margin: 2rem 0 0;margin-top: 0px; margin-bottom: 10px; text-decoration: underline;">Identifiez-Vous</h2>
                <form action="<?php echo ROOTPATH; ?>/connexion.html" method="post">
                    <label for="mail" class="labmail" style="width: 100px; float: left;">e-mail :</label>
                    <div class="input_content" style="display: inline;"><input class="input_insc" id="mail" type="text" name="mail" placeholder="Adresse email" /></div><br />

                    <label for="password" style="width: 100px; float: left;">Mot de passe :</label>
                    <div class="input_content" style="display: inline;"><input class="input_insc" id="password" type="password" name="password" placeholder="Password..."/></div><br />

                    <br />
                    <input type="hidden" name="connexion" value="1" />
                    <div style="margin: auto;">
                        <input class="submit" type="submit" value="Identifiez-vous" style="cursor: pointer;color: #fff;border-radius: 4px;padding: 10px;background-color: #2db3e8;text-transform: none;text-decoration: none;font-weight: 600;-moz-transition: background-color 0.35s linear;-webkit-transition: background-color 0.35s linear;transition: background-color 0.35s linear;" id="submit" />	
                        <br/><br/><a href="<?php echo ROOTPATH; ?>/recover.html">Mot de passe oublié</a>
                            <?php
                        if(isset($connexion['message_global'])){
                        echo "<br/><br/>".$connexion['message_global'];
                        }
                        ?>
                    </div>
                </form>
            </center>
        </div>
    </div>
    <?php
} else {
    echo "<script>document.location.href='".ROOTPATH."/index.html'</script>";
}?>