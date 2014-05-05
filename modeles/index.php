<?php

function afficheSociete($auth){
    $reqSociete = $auth->prepare('SELECT DISTINCT ac.idSociete,so.nomSociete FROM accessociete ac INNER JOIN societe so ON ac.idSociete = so.idSociete WHERE ac.idUser = :id ORDER BY ac.idSociete ASC');
    $reqSociete->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
    $reqSociete->execute();
    $i=0;
    while($donnees = $reqSociete->fetch()){
        $res[$i]['idSociete'] = $donnees['idSociete'];
        $res[$i]['nomSociete'] = $donnees['nomSociete'];
        
        $i++;
    }
    $reqSociete->closeCursor();
    
    if(!empty($res)){
        return $res;
    }
}
?>