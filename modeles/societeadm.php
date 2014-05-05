<?php

function addNewCat($auth, $idParent, $idSociete, $libelleCat) {
    $ins = $auth->prepare('INSERT INTO categorie(`idSociete`, `idParent`, `libelleCategorie`) VALUES(:idSociete, :idParent, :libelleCat)');
    $ins->bindValue(":idSociete", $idSociete, PDO::PARAM_INT);
    $ins->bindValue(":idParent", $idParent, PDO::PARAM_INT);
    $ins->bindValue(":libelleCat", $libelleCat, PDO::PARAM_STR);
    $ins->execute();
}

function getProductQty($auth, $ref) {
    $getinfos = $auth->query('SELECT * FROM produit WHERE codeProduit = ' . $ref . '');
    $getinfo = $getinfos->fetch();
    $getinfos->closeCursor();

    if (!empty($getinfo)) {
        return $getinfo;
    }
}

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

    if (!$niveau && !$niveau_precedent)
        $html .= "\n<ul class='accordion'>\n";

    if (isset($array)) {
        foreach ($array AS $noeud) {

            if ($parent == $noeud['idParent']) {

                if ($niveau_precedent < $niveau) {
                    if ($niveau == 1) {
                        //Catégorie enfant de la main
                        $html .= "\n</form><ul class='sub-menu subCat'>\n";
                    } else {
                        //chaque sous-catégorie
                        $html .= "\n</form><ul class='sub-menu" . $niveau . " subCat' style='display:none;'>\n";
                    }
                }
                //Catégories parent (main categories)
                if ($niveau == 0) {
                    $html .= '<li><form class="menuCategorie" id="cat' . $niveau . '" style="border-bottom: 1px solid black; border-top: 1px solid black;" method="post" ><input type="hidden" name="submitSearch" value="' . $noeud['idCategorie'] . '"><input class="formvalid" type="submit" name="idCategorie" value="' . $noeud['libelleCategorie'] . '" />';
                }
                //Catégories enfants
                else {
                    $html .= '<li><form class="menuCategorie" method="post" ><input type="hidden" name="submitSearch" value="' . $noeud['idCategorie'] . '"><input class="formvalid" type="submit" name="idCategorie" value="' . $noeud['libelleCategorie'] . '" />';
                }
                $niveau_precedent = $niveau;



                $html .= afficher_menu($noeud['idCategorie'], ($niveau + 1), $array);
                $html .= "<span style=\"display: none;\" id=\"currentCategory\"></span>";
                if ($noeud['idParent'] == "0") {
                    //Première visible
                    $html .="&nbsp;&nbsp;&nbsp;<a class='addSsCat' href='" . ROOTPATH . "/societeadm." . $_GET['param1'] . "&addCat&" . $noeud['idCategorie'] . ".html'>Ajouter une sous catégorie</a><br/><form method='post' action='import.html'><input type='hidden' name='idSociete' value='".$_GET['param1']."'><input type='hidden' name='idCat' value='".$noeud['idCategorie']."' ><input type='submit' value='Importer des produits' style='border: none; padding: 0px; margin: 0px; color: black;background-color: white;padding-left: 12px;text-decoration: underline; cursor: pointer;'></form>";
                } else {
                    $html .="&nbsp;&nbsp;&nbsp;<a class='addSsCat' href='" . ROOTPATH . "/societeadm." . $_GET['param1'] . "&addCat&" . $noeud['idCategorie'] . ".html'>Ajouter une sous catégorie</a><br/><form method='post' action='import.html'><input type='hidden' name='idSociete' value='".$_GET['param1']."'><input type='hidden' name='idCat' value='".$noeud['idCategorie']."' ><input type='submit' value='Importer des produits' style='border: none; padding: 0px; margin: 0px; color: black;background-color: white;padding-left: 12px;text-decoration: underline; cursor: pointer;'></form>";
                }
            }
        }
    }

    if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) {
        //$html .="<a class='addSsCat' href='".ROOTPATH."/societeadm.".$_GET['param1']."&addCat&0.html'>Ajouter une sous catégorie</a>";
        $html .= "</ul>\n</li>\n";
        //$html .="&nbsp;&nbsp;&nbsp;<a class='addSsCat' href='".ROOTPATH."/societeadm.".$_GET['param1']."&addCat&0.html'>Ajouter une sous catégorie</a><br/>";
    } else if ($niveau_precedent == $niveau) {
        $html .="<a class='addSsCat' href='" . ROOTPATH . "/societeadm." . $_GET['param1'] . "&addCat&0.html'>Ajouter une catégorie</a>";
        $html .= "</ul>\n";
    } else {
        $html .= "</form></li>\n";
    }

    return $html;
}

function findSons($auth, $parent, $reset) {
    static $fil;

    if ($reset == true) {
        $fil = array();
        $fil[] = $parent;
    }
    $d = array();
    $sons = $auth->query('SELECT idCategorie FROM categorie WHERE idParent = ' . $parent . '');

    $i = 0;
    while ($donnees = $sons->fetch()) {

        $d[$i]['idCategorie'] = $donnees['idCategorie'];
        $fil[] = $donnees['idCategorie'];

        $i++;
    }
    foreach ($d as $fils) {
        findSons($auth, $fils['idCategorie'], false);
    }

    return $fil;
}

function recupProduits($auth, $idCategorie, $idSociete, $nbProduct, $idPage) {
    $minIdProduct = ($idPage - 1) * $nbProduct;
    $maxIdProduct = ($idPage * $nbProduct);
    $checkPerm = $auth->query('SELECT COUNT(*) AS nb FROM accessociete WHERE idUser = ' . $_SESSION['id'] . ' AND idSociete = ' . $idSociete . '');
    $checkP = $checkPerm->fetch();

    //Si on a les accès
    //Il faut aussi récupérer les sociétés filles s'il y en a
    $sons = findSons($auth, $idCategorie, true);
    $nbSon = 0;
    $andRequest = "";
    foreach ($sons as $son) {
        if ($nbSon == 0) {
            $andRequest = "idCategorie = " . $son . "";
        } else {
            $andRequest .= " OR idCategorie = " . $son . "";
        }
        $nbSon++;
    }

    if ($checkP['nb'] >= 1) {
        $selectProduct = $auth->prepare('SELECT * FROM produit WHERE idSociete = :idSociete AND (' . $andRequest . ') ORDER BY idProduit ASC LIMIT ' . $minIdProduct . ',' . $nbProduct . '');
        $selectProduct->bindValue(':idSociete', $idSociete, PDO::PARAM_INT);
        //$selectProduct->bindValue(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $selectProduct->execute();

        $i = 0;
        while ($donnees = $selectProduct->fetch()) {

            $countProducts = $auth->prepare('SELECT COUNT(*) AS nb FROM produit WHERE idSociete = :idSociete AND (' . $andRequest . ') ORDER BY idProduit ASC');
            $countProducts->bindValue(':idSociete', $idSociete, PDO::PARAM_INT);
            //$countProducts->bindValue(':idCategorie', $idCategorie, PDO::PARAM_INT);
            $countProducts->execute();
            $countProduct = $countProducts->fetch();

            $d[$i]['idProduit'] = $donnees['idProduit'];
            $d[$i]['idSociete'] = $donnees['idSociete'];
            $d[$i]['idCategorie'] = $donnees['idCategorie'];
            $d[$i]['prixProduit'] = $donnees['prixProduit'];
            $d[$i]['minQte'] = $donnees['minQte'];
            $d[$i]['quantiteProduit'] = $donnees['quantiteProduit'];
            $d[$i]['imgProduit'] = $donnees['imgProduit'];
            $d[$i]['codeProduit'] = $donnees['codeProduit'];
            $d[$i]['nbProduit'] = $countProduct['nb'];

            $i++;
        }
    }
    if (!empty($d)) {
        return $d;
    }
}

?>