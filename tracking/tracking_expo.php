<?php
header('Access-Control-Allow-Origin: *');
$listMuseums = array();

require "dbo_connect.php";
require "exposition.php";
require "functions_expo.php";

$environnement = "test"; //test

$connexion = connect($environnement);  

if($connexion!=null){
	parseAllExpositions($connexion, true);
}

?>

