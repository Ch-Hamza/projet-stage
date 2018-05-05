<?php

function connect($environnement){
    try 
    {
        //test connexion
        if($environnement=="test")
            $dbh = new PDO('mysql:host=localhost;dbname=test_museum_db', "root", "");
        
        //prod connexion
        if($environnement=="prod")
            $dbh = new PDO('mysql:host=localhost;dbname=applicat_pharmac', "applicat_pharmac", "");

        $dbh->exec("set names utf8");
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //foreach($dbh->query('SELECT * from pharmacie') as $row) {
            //echo ($row["nom_prenom"])."<br>";
        //}
        return $dbh;
    } catch (PDOException $e) {
        print "Error connexion data base !: " . $e->getMessage() . "<br/>";
        return null;
    }
}

function insert($connexion, $request){
    $stmt = $connexion->prepare($request);
    $stmt->execute();
}

function getTunPharmaByNameIfExiste($connexion, $name){
    $selectQuery =  "SELECT * FROM pharmacie WHERE nom_prenom = '".addslashes($name)."'";
    foreach  ($connexion->query($selectQuery) as $row) {
        return $row;
    }
    return null;
}

function getMaxGardeId($connexion){
    $selectQuery =  "SELECT COALESCE(MAX(id),1) as id FROM `garde`";
    foreach  ($connexion->query($selectQuery) as $row) {
        return $row["id"];
    }
    return "1";
}

function getGarde($connexion, $query){
    foreach  ($connexion->query($query) as $row) {
        return $row;
    }
    return null;
}

?>