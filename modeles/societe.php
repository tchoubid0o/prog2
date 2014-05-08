<?php

function getProductQty($auth, $ref) {
    $getinfos = $auth->query('SELECT * FROM produit WHERE codeProduit = "' . $ref . '"');
    $getinfo = $getinfos->fetch();
    $getinfos->closeCursor();

    if (!empty($getinfo)) {
        return $getinfo;
    }
}

function getAllRef($auth, $idSociete) {
    $get = $auth->query('SELECT codeProduit FROM produit WHERE idSociete = ' . $idSociete . '');

    $liste = array();
    $i = 0;
    while ($donnees = $get->fetch()) {
        $d[$i]['codeProduit'] = $donnees['codeProduit'];

        $i++;
    }
    $nb = 0;
    if(isset($d)){
    foreach ($d as $ref) {
        $liste[$nb] = $ref['codeProduit'];
        $nb++;
    }

    $scriptReturn = '<script>$(function() {
                        
                        var availableTags = ' . json_encode($liste) . ';
                        $("#refSearch").autocomplete({
                            source: availableTags,
                            select: function(event, ui){
                                $("#refSearch").attr("value",ui.item.value);
                                
                                $.ajax({url: "' . ROOTPATH . '/societe.html",
                                    type: "POST",
                                    dataType: "json",
                                    data: "codeProduit="+ui.item.value+"&idPostFormFastSearch=1"
                                }).done(function(data) {
                                    var o = 1;
                                    newContentV = "";
                                    do {
                                        j = o * data.minQte;
                                        newContentV += "<option value="+j+">"+j+"</option>";
                                        o++;
                                    } while (j < data.quantiteProduit);
                                    $("#valueSearchQte").html(newContentV);
                                });

                            }
                        });
                    });
                     </script>';
    }

    if (!empty($scriptReturn)) {
        return $scriptReturn;
    }
}

function searchAndAdd($auth, $refSearch, $qteSearch) {
    $searchAndAdd['message'] = "";

    $checkRef = $auth->prepare('SELECT COUNT(*) AS nb FROM produit WHERE codeProduit = :refSearch');
    $checkRef->bindValue(":refSearch", $refSearch, PDO::PARAM_STR);
    $checkRef->execute();
    $checkNb = $checkRef->fetch();
    $checkRef->closeCursor();
    if ($checkNb['nb'] > 0) {
        //Le produit existe
        //On vérifie l'id de la société correspondant au produit et on vérifie qu'on a pas un autre panier d'une société différente.
        $getSociete = $auth->prepare('SELECT * FROM produit WHERE codeProduit = :refSearch');
        $getSociete->bindValue(":refSearch", $refSearch, PDO::PARAM_STR);
        $getSociete->execute();
        $item = $getSociete->fetch();
        $getSociete->closeCursor();
        //$item['idSociete'] retourne l'id de la société correspondant au produit

        $checkPanier = $auth->prepare('SELECT COUNT(*) AS nb FROM panier WHERE idUser = :idUser AND idSociete != :idSociete');
        $checkPanier->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
        $checkPanier->bindValue(":idSociete", $item['idSociete'], PDO::PARAM_INT);
        $checkPanier->execute();
        $returnNb = $checkPanier->fetch();
        $checkPanier->closeCursor();
        if ($returnNb['nb'] == 0) {
            //On peut ajouter le produit au panier
            $idSearch = $auth->prepare('SELECT * FROM produit WHERE codeProduit = :refSearch');
            $idSearch->bindValue(":refSearch", $refSearch, PDO::PARAM_STR);
            $idSearch->execute();
            $idProd = $idSearch->fetch();
            $idSearch->closeCursor();

            $check2 = $auth->prepare('SELECT * FROM panier WHERE idUser = :idUser AND idSociete = :idSociete AND idProduit = :idProduit');
            $check2->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
            $check2->bindValue(":idSociete", $item['idSociete'], PDO::PARAM_INT);
            $check2->bindValue(":idProduit", $idProd['idProduit'], PDO::PARAM_INT);
            $check2->execute();
            $checkResult2 = $check2->fetch();
            $check2->closeCursor();

            if ($checkResult2['qteProduit'] == 0) {
                //L'objet n'existe pas déjà dans notre panier
                $insert = $auth->prepare('INSERT INTO panier(`idUser`, `idProduit`, `idSociete`, `qteProduit`) VALUES(:idUser, :idProduit, :idSociete, :qteProduit)');
                $insert->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
                $insert->bindValue(":idProduit", $idProd['idProduit'], PDO::PARAM_INT);
                $insert->bindValue(":idSociete", $item['idSociete'], PDO::PARAM_INT);
                $insert->bindValue(":qteProduit", $qteSearch, PDO::PARAM_INT);
                $insert->execute();
                $insert->closeCursor();

                $searchAndAdd['message'] = "L'objet a bien été ajouté au panier.";
            } else {
                $qteNow = $checkResult2['qteProduit'] + $qteSearch;
                $insert = $auth->prepare('UPDATE panier SET qteProduit = :qteNow WHERE idUser = :idUser AND idProduit = :idProduit AND idSociete = :idSociete');
                $insert->bindValue(":qteNow", $qteNow, PDO::PARAM_INT);
                $insert->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
                $insert->bindValue(":idProduit", $idProd['idProduit'], PDO::PARAM_INT);
                $insert->bindValue(":idSociete", $item['idSociete'], PDO::PARAM_INT);
                $insert->execute();
                $insert->closeCursor();

                $searchAndAdd['message'] = "L'objet a bien été ajouté au panier.";
            }
        }
    }
    return $searchAndAdd;
}

function add2Cart($auth, $qteProduit, $idProduit, $idSociete) {
    //On vérifie qu'on a pas déjà un panier avec des objets d'une autre société
    //Si c'est le cas, alors on dit à l'utilisateur qu'il doit d'abord valider son précédent panier
    //On ajoute au panier la quantité d'objet voulue
    //Il faut vérifier si l'objet n'est pas déjà présent dans le panier, si c'est le cas, on ajoute au stock précédent
    $add2Cart['message'] = "";

    $check = $auth->prepare('SELECT COUNT(*) AS nb FROM panier WHERE idUser = :idUser AND idSociete != :idSociete');
    $check->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
    $check->bindValue(":idSociete", $idSociete, PDO::PARAM_INT);
    $check->execute();
    $checkResult = $check->fetch();
    $check->closeCursor();

    if ($checkResult['nb'] == 0) {
        $check2 = $auth->prepare('SELECT * FROM panier WHERE idUser = :idUser AND idSociete = :idSociete AND idProduit = :idProduit');
        $check2->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
        $check2->bindValue(":idSociete", $idSociete, PDO::PARAM_INT);
        $check2->bindValue(":idProduit", $idProduit, PDO::PARAM_INT);
        $check2->execute();
        $checkResult2 = $check2->fetch();
        $check2->closeCursor();

        if ($checkResult2['qteProduit'] == 0) {
            //L'objet n'existe pas déjà dans notre panier
            $insert = $auth->prepare('INSERT INTO panier(`idUser`, `idProduit`, `idSociete`, `qteProduit`) VALUES(:idUser, :idProduit, :idSociete, :qteProduit)');
            $insert->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
            $insert->bindValue(":idProduit", $idProduit, PDO::PARAM_INT);
            $insert->bindValue(":idSociete", $idSociete, PDO::PARAM_INT);
            $insert->bindValue(":qteProduit", $qteProduit, PDO::PARAM_INT);
            $insert->execute();
            $insert->closeCursor();

            $add2Cart['message'] = "L'objet a bien été ajouté au panier.";
        } else {
            $qteNow = $checkResult2['qteProduit'] + $qteProduit;
            $insert = $auth->prepare('UPDATE panier SET qteProduit = :qteNow WHERE idUser = :idUser AND idProduit = :idProduit AND idSociete = :idSociete');
            $insert->bindValue(":qteNow", $qteNow, PDO::PARAM_INT);
            $insert->bindValue(":idUser", $_SESSION['id'], PDO::PARAM_INT);
            $insert->bindValue(":idProduit", $idProduit, PDO::PARAM_INT);
            $insert->bindValue(":idSociete", $idSociete, PDO::PARAM_INT);
            $insert->execute();
            $insert->closeCursor();

            $add2Cart['message'] = "L'objet a bien été ajouté au panier.";
        }
    } else {
        $add2Cart['message'] .= "Vous devez d'abord valider votre ancien panier avant de pouvoir en refaire un.";
    }

    return $add2Cart;
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
        $d[$i]['codeCat'] = $donnees['codeCat'];

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

    foreach ($array AS $noeud) {

        if ($parent == $noeud['idParent']) {

            if ($niveau_precedent < $niveau) {
                if ($niveau == 1) {
                    $html .= "\n</form><ul class='sub-menu'>\n";
                } else {
                    $html .= "\n</form><ul class='sub-menu" . $niveau . "' style='display:none;'>\n";
                }
            }

            if ($niveau == 0) {
                $html .= '<li><form class="menuCategorie" id="cat' . $niveau . '" style="border-bottom: 1px solid black; border-top: 1px solid black;" method="post" ><input type="hidden" name="submitSearch" value="' . $noeud['idCategorie'] . '"><input class="formvalid" type="submit" name="idCategorie" value="' . $noeud['libelleCategorie'] . '" />';
            } else {
                $html .= '<li><form class="menuCategorie" method="post" ><input type="hidden" name="submitSearch" value="' . $noeud['idCategorie'] . '"><input class="formvalid" type="submit" name="idCategorie" value="' . $noeud['libelleCategorie'] . '" />';
            }
            $niveau_precedent = $niveau;

            $html .= afficher_menu($noeud['idCategorie'], ($niveau + 1), $array);
            $html .= "<span style=\"display: none;\" id=\"currentCategory\"></span>";
        }
    }

    if (($niveau_precedent == $niveau) && ($niveau_precedent != 0))
        $html .= "</ul>\n</li>\n";
    else if ($niveau_precedent == $niveau)
        $html .= "</ul>\n";
    else
        $html .= "</form></li>\n";

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
    foreach($d as $fils){
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
    foreach($sons as $son){
        if($nbSon == 0){
            $getCode = $auth->query('SELECT codeCat FROM categorie WHERE idCategorie = '.$son.'');
            $code = $getCode->fetch();
            $andRequest = "idCategorie = '".$code['codeCat']."'";
        }
        else{
            $getCode = $auth->query('SELECT codeCat FROM categorie WHERE idCategorie = '.$son.'');
            $code = $getCode->fetch();
            $andRequest .= " OR idCategorie = '".$code['codeCat']."'";
        }
        $nbSon++;
    }

    if ($checkP['nb'] >= 1) {
        $selectProduct = $auth->prepare('SELECT * FROM produit WHERE idSociete = :idSociete AND ('.$andRequest.') ORDER BY idProduit ASC LIMIT ' . $minIdProduct . ',' . $nbProduct . '');
        $selectProduct->bindValue(':idSociete', $idSociete, PDO::PARAM_INT);
        //$selectProduct->bindValue(':idCategorie', $idCategorie, PDO::PARAM_INT);
        $selectProduct->execute();

        $i = 0;
        while ($donnees = $selectProduct->fetch()) {

            $countProducts = $auth->prepare('SELECT COUNT(*) AS nb FROM produit WHERE idSociete = :idSociete AND ('.$andRequest.') ORDER BY idProduit ASC');
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