<?php

function getNomSociete($auth, $id) {

    $reqInfo = $auth->query('SELECT nomSociete FROM societe WHERE idSociete = ' . $id . '');
    $reqNom = $reqInfo->fetch();

    $reqInfo->closeCursor();
    return $reqNom;
}

function getSociete($auth, $id) {

    $req = $auth->query('SELECT * FROM categorie WHERE idSociete = ' . $id . '');

    $i = 0;
    while ($donnees = $req->fetch()) {
        $d[$i]['idCategorie'] = $donnees['idCategorie'];
        $d[$i]['idSociete'] = $donnees['idSociete'];
        $d[$i]['idParent'] = $donnees['idParent'];
        $d[$i]['libelleCategorie'] = $donnees['libelleCategorie'];

        $i++;
    }
    if (!empty($d)) {
        return $d;
    }
}

function afficher_menu($parent, $niveau, $array) {
 
$html = "";
$niveau_precedent = 0;
 
if (!$niveau && !$niveau_precedent) $html .= "\n<ul class='accordion'>\n";
 
foreach ($array AS $noeud) {
 
	if ($parent == $noeud['idParent']) {
 
	if ($niveau_precedent < $niveau){
		if($niveau == 1){$html .= "\n</form><ul class='sub-menu'>\n";}
		else{$html .= "\n</form><ul class='sub-menu".$niveau."' style='display:none;'>\n";}
	}

        if($niveau == 0){
            $html .= '<li><form class="menuCategorie" style="border-bottom: 1px solid black; border-top: 1px solid black;" method="post" ><input type="hidden" name="submitSearch" value="'.$noeud['idCategorie'].'"><input class="formvalid" type="submit" name="idCategorie" value="'.$noeud['libelleCategorie'].'" />';
        }
        else{
            $html .= '<li><form class="menuCategorie" method="post" ><input type="hidden" name="submitSearch" value="'.$noeud['idCategorie'].'"><input class="formvalid" type="submit" name="idCategorie" value="'.$noeud['libelleCategorie'].'" />';
        }
	$niveau_precedent = $niveau;
 
	$html .= afficher_menu($noeud['idCategorie'], ($niveau + 1), $array);
 
	}
}
 
if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
else $html .= "</form></li>\n";
 
return $html;
 
}

function recupProduits($auth, $idCategorie, $idSociete){
	$checkPerm = $auth->query('SELECT COUNT(*) AS nb FROM accessociete WHERE idUser = '.$_SESSION['id'].' AND idSociete = '.$idSociete.'');
	$checkP=$checkPerm->fetch();
	
	//Si on a les accès
	if($checkP['nb']>=1){
		$selectProduct = $auth->prepare('SELECT * FROM produit WHERE idSociete = :idSociete AND idCategorie = :idCategorie');
		$selectProduct->bindValue(':idSociete', $idSociete, PDO::PARAM_INT);
		$selectProduct->bindValue(':idCategorie', $idCategorie, PDO::PARAM_INT);
		$selectProduct->execute();
		
		$i = 0;
		while ($donnees = $selectProduct->fetch()) {
			$d[$i]['idProduit'] = $donnees['idProduit'];
			$d[$i]['idSociete'] = $donnees['idSociete'];
			$d[$i]['idCategorie'] = $donnees['idCategorie'];
			$d[$i]['prixProduit'] = $donnees['prixProduit'];
			$d[$i]['minQte'] = $donnees['minQte'];
			$d[$i]['quantiteProduit'] = $donnees['quantiteProduit'];
                        $d[$i]['imgProduit'] = $donnees['imgProduit'];
                        $d[$i]['refProduit'] = $donnees['refProduit'];
			$i++;
		}
	}
	if (!empty($d)) {
		return $d;
	}
}
?>