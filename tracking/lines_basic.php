<?php

require('dbo_connect.php');

$connexion = connect("test");

$sql = "SELECT * FROM user";
$result = $connexion->query($sql);

$rows = $result->fetchAll();
$num_rows = count($rows);

$myFile = "../web/assets/demo_data/d3/lines/lines_basic.tsv";
$fh = fopen($myFile, 'a') or die("can't open file");

$date = date("j-M-y");
$stringData = $date."\t".$num_rows."\n";
fwrite($fh, $stringData);
fclose($fh);

?>