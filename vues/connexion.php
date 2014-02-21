<?php if(!isset($_SESSION['id'])) { ?>
    <div id="logintop">
        <div class="loginh">	
            <center>
                <h2 style="font-size: 1.75rem;line-height: 1.6em;text-align: center;color: #223340;font-weight: bold;margin: 2rem 0 0;margin-top: 0px;">Connexion</h2>
                <form action="<?php echo ROOTPATH; ?>/connexion.html" method="post">
                    <label class="labmail">Mail :</label><br />
                    <div class="input_content"><input class="input_insc" type="text" name="mail" placeholder="Adresse email" /></div><br />

                    <label>Mot de passe :</label><br />
                    <div class="input_content"><input class="input_insc" type="password" name="password" placeholder="Password..."/></div><br />

                    <br />
                    <input type="hidden" name="connexion" value="1" />
                    <div style="margin: auto;">
                        <input class="submit" type="submit" value="Identifiez-vous" style="cursor: pointer;color: #fff;border-radius: 4px;padding: 10px;background-color: #2db3e8;text-transform: none;text-decoration: none;font-weight: 600;-moz-transition: background-color 0.35s linear;-webkit-transition: background-color 0.35s linear;transition: background-color 0.35s linear;" id="submit" />	
                    </div>
                </form>
            </center>
        </div>
    </div>
<?php
} else {
    echo "Vous êtes déjà connecté!<br/><a href='".ROOTPATH."/index.html'>Retour vers l'index</a>";
}?>