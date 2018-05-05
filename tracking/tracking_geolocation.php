<?php

set_time_limit(0);
require('dbo_connect.php');

$connexion = connect("test");

$sql = "SELECT * FROM museum WHERE location_id IS NULL";
$result = $connexion->query($sql);

$rows = $result->fetchAll();
$num_rows = count($rows);

if($num_rows > 0){
	foreach ($rows as $value) {

		$rue = $value['rue'];
		$code = $value['code_postal'];
		$place = $value['place'];

		$api_key = 'AIzaSyBnIsSCHTrYA9CnxuR2sRId-3oBV7Tbs4w';

		$url_encode_rue = preg_replace('/\s+/', '', $rue);
		$url_encode_rue = preg_replace('/\n+/', '', $rue);
		$url_encode_rue = preg_replace('/\t+/', '', $rue);

		$url_encode_code = preg_replace('/\s+/', '', $code);
		$url_encode_code = preg_replace('/\n+/', '', $code);
		$url_encode_code = preg_replace('/\t+/', '', $code);

		$url_encode_place = preg_replace('/\s+/', '', $place);
		$url_encode_place = preg_replace('/\n+/', '', $place);
		$url_encode_place = preg_replace('/\t+/', '', $place);

		$address = $url_encode_rue."+".$url_encode_code."+".$url_encode_place;
		if($value['name'] == "Artillerie-Fort-Verein-Magletsch (AFOM)")
		{
			$address = '9479+Oberschan';
		}
		if($value['name'] == 'Museum Chasa Jaura Valchava'){
			$address = '7535+Valchava';
		}
		if($value['name'] == 'Stadtmuseum Bremgarten'){
			$address = 'Reussgasse+16+5620+Bremgarten';
		}
		
		$address = urlencode($address);

		$url = 'https://maps.google.com/maps/api/geocode/json?key='.$api_key.'&address='.$address;
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 

	    $output = curl_exec($ch);
	    curl_close($ch);
	    echo $url;
	    //echo "\n adresse = ".$address."\n";
	    echo $output;
	    
	    $json = json_decode($output, true);
	    $longitude = $json['results'][0]['geometry']['location']['lng'];
	    $lattitude = $json['results'][0]['geometry']['location']['lat'];
		
		$stmt = $connexion->prepare("INSERT INTO location (longitude, lattitude) VALUES (:lng, :lat)");
	    $stmt->bindParam(':lng', $longitude);
	    $stmt->bindParam(':lat', $lattitude);
	    $stmt->execute();
	    $last_id = $connexion->lastInsertId();

	    $stmt = $connexion->prepare("UPDATE museum SET location_id='".$last_id."' WHERE id='".$value['id']."'");
	    $stmt->execute();
	}
}

?>