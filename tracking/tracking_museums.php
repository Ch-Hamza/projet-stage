<?php
header('Access-Control-Allow-Origin: *');
$listMuseums = array();

require "dbo_connect.php";
require "museum.php";
require "functions_musuems.php";

$environnement = "test"; //test

$connexion = connect($environnement);  

if($connexion!=null){
	parseAllMuseums($connexion, true);
}

?>

