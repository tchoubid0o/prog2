<?php

function addSociete($auth, $nom){
    $add = $auth->prepare('INSERT INTO societe(`nomSociete`) VALUES(:nom)');
    $add->bindValue(":nom", $nom, PDO::PARAM_STR);
    $add->execute();

    $add->closeCursor();
}

function countNbClients($auth) {
    $countNb = $auth->query('SELECT COUNT(*) AS nb FROM user');
    $nb = $countNb->fetch();
    $countNb->closeCursor();

    if (!empty($nb)) {
        return $nb;
    }
}

function checkIsAdmin($auth) {
    $checkAdmin = $auth->query('SELECT COUNT(*) AS nb FROM user WHERE id = ' . $_SESSION['id'] . ' AND adm = 1');
    $checkAdm = $checkAdmin->fetch();
    $checkAdmin->closeCursor();

    return $checkAdm;
}

function getClients($auth) {
    $checkAdm = checkIsAdmin($auth);
    if ($checkAdm['nb'] >= 1) {
        $getClients = $auth->query('SELECT * FROM user');

        $i = 0;
        while ($donnees = $getClients->fetch()) {
            $d[$i]['id'] = $donnees['id'];
            $d[$i]['mail'] = $donnees['mail'];

            $i++;
        }
        if (!empty($d)) {
            return $d;
        }
    }
}

function getSocietes($auth) {
    $checkAdm = checkIsAdmin($auth);
    if ($checkAdm['nb'] >= 1) {
        $getClients = $auth->query('SELECT * FROM societe');

        $i = 0;
        while ($donnees = $getClients->fetch()) {
            $d[$i]['idSociete'] = $donnees['idSociete'];
            $d[$i]['nomSociete'] = $donnees['nomSociete'];

            $i++;
        }
        if (!empty($d)) {
            return $d;
        }
    }
}

function deleteData($auth, $type, $id) {
    $checkAdm = checkIsAdmin($auth);
    $deleteReturn['message'] = "";
    if ($checkAdm['nb'] >= 1) {
        if ($type == "user") {
            $delete = $auth->prepare('DELETE FROM ' . $type . ' WHERE id = :id');
            $delete->bindValue(':id', $id, PDO::PARAM_INT);
            $delete->execute();
            $delete->closeCursor();

            $delete2 = $auth->prepare('DELETE FROM accessociete WHERE idUser = :id');
            $delete2->bindValue(':id', $id, PDO::PARAM_INT);
            $delete2->execute();
            $delete2->closeCursor();
        } else {
            $delete = $auth->prepare('DELETE FROM ' . $type . ' WHERE idSociete = :id');
            $delete->bindValue(':id', $id, PDO::PARAM_INT);
            $delete->execute();
            $delete->closeCursor();
        }


        if ($type == "user") {
            $deleteReturn['message'] .= "Le compte client a bien été supprimé";
        } else {
            $deleteReturn['message'] .= "La société a bien été supprimée";
        }
    }
}

function getInfoClient($auth, $id) {
    $checkAdm = checkIsAdmin($auth);
    if ($checkAdm['nb'] >= 1) {
        $getInfo = $auth->query('SELECT * FROM user WHERE id = ' . $id . '');
        $infoClient = $getInfo->fetch();
        $getInfo->closeCursor();

        return $infoClient;
    }
}

function getAllSocietes($auth) {
    $gets = $auth->query('SELECT * FROM societe');
    $i = 0;
    while ($donnees = $gets->fetch()) {
        $d[$i]['idSociete'] = $donnees['idSociete'];
        $d[$i]['nomSociete'] = $donnees['nomSociete'];
        $i++;
    }
    if (!empty($d)) {
        return $d;
    }
}

function getOrdersClient($auth, $id, $idSociete) {
    $checkAdm = checkIsAdmin($auth);
    if ($checkAdm['nb'] >= 1) {
        $getOrders = $auth->prepare('SELECT * FROM commande WHERE idUser = :id AND idSociete = :idSociete');
        $getOrders->bindValue(":id", $id, PDO::PARAM_INT);
        $getOrders->bindValue(":idSociete", $idSociete, PDO::PARAM_INT);
        $getOrders->execute();
        $i = 0;
        while ($donnees = $getOrders->fetch()) {
            $d[$i]['dateCommande'] = $donnees['dateCommande'];
            $d[$i]['dateLivraison'] = $donnees['dateLivraison'];
            $d[$i]['commentOrder'] = $donnees['commentOrder'];
            $d[$i]['keyOrder'] = $donnees['keyOrder'];
            $i++;
        }
        if (!empty($d)) {
            return $d;
        }
    }
}

function getAccesClient($auth, $id) {
    $getAcces = $auth->prepare('SELECT idSociete FROM accessociete WHERE idUser = :id');
    $getAcces->bindValue(':id', $id, PDO::PARAM_INT);
    $getAcces->execute();

    $i = 0;
    while ($acces = $getAcces->fetch()) {
        $a[$i]['idSociete'] = $acces['idSociete'];
        $i++;
    }

    $getAcces->closeCursor();
    if (!empty($a))
        return $a;
}

function updateClient($auth, $id, $mail, $adresse,$societe, $password, $accesSociete) {
    $checkAdm = checkIsAdmin($auth);
    if ($checkAdm['nb'] >= 1) {
        $updateClient = $auth->prepare('UPDATE user SET password = :password, mail = :mail, adresse = :adresse, societe = :societe WHERE id = :id');
        $updateClient->bindValue(':password', $password, PDO::PARAM_STR);
        $updateClient->bindValue(':mail', $mail, PDO::PARAM_STR);
        $updateClient->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $updateClient->bindValue(':societe', $societe, PDO::PARAM_STR);
        $updateClient->bindValue(':id', $id, PDO::PARAM_INT);
        $updateClient->execute();
        $updateClient->closeCursor();

        $delAcces = $auth->query('DELETE FROM accessociete WHERE idUser = ' . $id . '');
        $delAcces->closeCursor();

        if ($accesSociete == -999) {
            
        } else {

            foreach ($accesSociete as $acces) {
                if (!isset($espaceArr)) {
                    $espaceArr = $acces;
                } else {
                    $espaceArr .= "," . $acces . "";
                }
            }
            $acces = explode(',', $espaceArr);
            $maxArray = sizeof($acces);

            for ($i = 0; $i < $maxArray; $i++) {
                $insertAcces = $auth->prepare('INSERT INTO accessociete(`idUser`, `idSociete`) VALUES(:idUser, :idSociete)');
                $insertAcces->bindValue(':idUser', $id, PDO::PARAM_INT);
                $insertAcces->bindValue(':idSociete', $acces[$i], PDO::PARAM_INT);
                $insertAcces->execute();
                $insertAcces->closeCursor();
            }
        }
    }
}

function insertClient($auth, $password, $mail, $adresse,$societe, $accesSociete) {
    $checkAdm = checkIsAdmin($auth);
    if ($checkAdm['nb'] >= 1) {
        $insertClient = $auth->prepare('INSERT INTO user(`password`, `mail` , `adresse`, `societe`) VALUES(:password, :mail, :adresse, :societe)');
        $insertClient->bindValue(':password', md5("web" . (sha1($password)) . "site"), PDO::PARAM_STR);
        $insertClient->bindValue(':mail', $mail, PDO::PARAM_STR);
        $insertClient->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $insertClient->bindValue(':societe', $societe, PDO::PARAM_STR);
        $insertClient->execute();
        $insertClient->closeCursor();

        $id = $auth->lastInsertId();
        if ($accesSociete == -999) {
            
        } else {

            foreach ($accesSociete as $acces) {
                if (!isset($espaceArr)) {
                    $espaceArr = $acces;
                } else {
                    $espaceArr .= "," . $acces . "";
                }
            }
            $acces = explode(',', $espaceArr);
            $maxArray = sizeof($acces);

            for ($i = 0; $i < $maxArray; $i++) {
                $insertAcces = $auth->prepare('INSERT INTO accessociete(`idUser`, `idSociete`) VALUES(:idUser, :idSociete)');
                $insertAcces->bindValue(':idUser', $id, PDO::PARAM_INT);
                $insertAcces->bindValue(':idSociete', $acces[$i], PDO::PARAM_INT);
                $insertAcces->execute();
                $insertAcces->closeCursor();
            }
        }
    }
}

?>