	<?php
	if(isset($inscription['ok'])) {
		echo '<br/><center><div class="registerm">' . $inscription['message_global'] . '<br /><br />
		Accueil : <a class="red" href="' . ROOTPATH . '/index.html">Cliquez ici</a>.<br /></div></center>';
	}
	else {
		if(isset($_SESSION['id'])) {
			echo '<br/><center><div class="registerm"><span class="red">Vous &ecirc;tes d&eacute;j&agrave; inscrit !</span><br /><br /> 
				Se d&eacute;connecter : <a class="red" href="' . ROOTPATH . '/deconnexion.html">Cliquez ici</a>.<br /></div></center>';
		}
		else {
		?>
		<h2 style="background-color: #00ba84; padding: 20px; text-align: center; color: white;margin-top: -3px;">Inscription</h2>
		
		<div class="globalregister" style="background-color: white; text-align: center; padding: 20px;">
			<div class="registerm">
				<?php
				if(!empty($inscription['message_global'])) { echo "<center>".$inscription['message_global']."</center>"; }
				if(!empty($inscription['message_password'])) { echo "<center>".$inscription['message_password']."</center>"; }
				if(!empty($inscription['message_email'])) { echo "<center>".$inscription['message_email']."</center>"; }
		if(!isset($inscription['ok'])) {
				?>
				<br />
			</div>
				<center>
				<form action="<?php echo ROOTPATH; ?>/inscription.html" enctype="multipart/form-data" method="post">
					<br/>						
					<label for="prenom" style="text-align: left;">Votre prénom</label><br/>
					<input type="text"  placeholder="prénom" name="prenom" id="prenom" required/><br />
						
					<label for="nom">Votre nom</label><br/>
					<input type="text"  placeholder="nom" name="nom" id="nom" required/><br />
						
					<label for="email">Votre adresse mail</label><br/>
					<input type="email" placeholder="Email" name="email" id="usermail" required/><br />
						
					<label for="password">Votre mot de passe</label><br/>
					<input type="password"  placeholder="Password..." name="password" id="userpass" required/><br />

					<label for="verification">Confirmez votre mot de passe</label><br/>
					<input type="password"  placeholder="Password..." name="verification" id="userpassconfirm" required/><br /><br />
						
					<input type="hidden" name="inscription" value="1" />
					<div style="width: 71px; margin: auto;">
						<input class="submit" type="submit" value="Valider" id="submitregister" style="color: #fff;border-radius: 4px;padding: 10px;background-color: #00ba84;text-transform: none;text-decoration: none;font-weight: 600;-moz-transition: background-color 0.35s linear;-webkit-transition: background-color 0.35s linear;transition: background-color 0.35s linear;" />	
					</div>
				</form>
				</center>
		</div>
			<?php
		}
			?>
		
		<?php
		}
	}
	?>