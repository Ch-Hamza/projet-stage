<?php

function getDetailsFromUrl($url){
	$ch = curl_init();

	curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;

}

$link_fr = "https://www.museums.ch/org/fr/Afrika-Museum";
$link_en = "https://www.museums.ch/org/en/Afrika-Museum";
$link_it = "https://www.museums.ch/org/it/Afrika-Museum";
$link_de = "https://www.museums.ch/org/de/Afrika-Museum";

$nom = "N/A";
$description_fr = "";
$description_en = "";
$description_it = "";
$description_de = "";
$images = array();
$horaire = "N/A";
$lieu = "N/A";
$categories = "N/A";
$canton = "N/A";
$adresse = "N/A";
$rue = "N/A";
$code_postal = "N/A";
$directions = "N/A";
$criteres = array();
$prix = "N/A";
$email = "N/A";
$fax = "N/A";
$phone = "N/A";
$email = "N/A";
$website = "N/A";
$categories = array(); 

$details = getDetailsFromUrl($link_fr);
$dom = new DOMDocument();
@$dom->loadHTML($details);
$xpath = new DOMXPath($dom);
$maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
foreach ($maincol as $balise) {
	$details = getDetailsFromUrl($link_en);
	$dom = new DOMDocument();
	@$dom->loadHTML($details);
	$xpath = new DOMXPath($dom);
	$maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
	foreach ($maincol as $balise) {
	    if($balise->nodeType == 3){
	        continue;
	    }
	    
	    if($balise->tagName == 'p'){
	        $description_en = $description_en." ".$balise->nodeValue;
	    }
	}


	$details = getDetailsFromUrl($link_it);
	$dom = new DOMDocument();
	@$dom->loadHTML($details);
	$xpath = new DOMXPath($dom);
	$maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
	foreach ($maincol as $balise) {
	    if($balise->nodeType == 3){
	        continue;
	    }

	    if($balise->tagName == 'p'){
	        $description_it = $description_it." ".$balise->nodeValue;
	    }
	}

	$details = getDetailsFromUrl($link_de);
	$dom = new DOMDocument();
	@$dom->loadHTML($details);
	$xpath = new DOMXPath($dom);
	$maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
	foreach ($maincol as $balise) {
	    if($balise->nodeType == 3){
	        continue;
	    }

	    if($balise->tagName == 'p'){
	        $description_de = $description_de." ".$balise->nodeValue;
	    }
	}

		$details = getDetailsFromUrl($link_fr);
	$dom = new DOMDocument();
	@$dom->loadHTML($details);
	$xpath = new DOMXPath($dom);
		
		$maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
	foreach ($maincol as $balise) {
		
		if($balise->nodeType == 3){
			continue;
		}

		if($balise->tagName == 'h1'){
			$titre = $balise->nodeValue;
		}

		if($balise->tagName == 'p'){
			$description_fr = $description_fr." ".$balise->nodeValue;
		}

		if($balise->tagName == 'figure'){
			array_push($images, $balise->childNodes[0]->attributes[0]->nodeValue);
		}
	}
}

echo ' Nom --> '.$nom;
echo '<br>';
echo ' rue --> '.$rue;
echo '<br>';
echo ' code_postal --> '.$code_postal;
echo '<br>';
echo ' adresse --> '.$adresse;
echo '<br>';
echo ' horaire --> '.$horaire;
echo '<br>';
echo ' lieu --> '.$lieu;
echo '<br>';
echo ' canton --> '.$canton;
echo '<br>';
echo ' Link --> '.$link_fr;
echo '<br>';
echo ' Link --> '.$link_en;
echo '<br>';
echo ' Link --> '.$link_it;
echo '<br>';
echo ' Link --> '.$link_de;
echo '<br>';
echo ' description --> '.$description_fr;
echo '<br>';
echo ' description --> '.$description_en;
echo '<br>';
echo ' description --> '.$description_it;
echo '<br>';
echo ' description --> '.$description_de;
echo '<br>';
echo ' images --> <br>';echo '<pre>' . var_dump($images) . '</pre>';
echo '<br>';
echo ' directions --> '.$directions;
echo '<br>';
echo ' criteres --> <br>';echo '<pre>' . var_dump($criteres) . '</pre>';
echo '<br>';
echo ' prix --> '.$prix;
echo '<br>';
echo ' phone --> '.$phone;
echo '<br>';
echo ' fax --> '.$fax;
echo '<br>';
echo ' email --> '.$email;
echo '<br>';
echo ' website --> '.$website;
echo '<br>';
echo ' categories --> <br>';echo '<pre>' . var_dump($categories) . '</pre>';
echo '<br>';
echo '--------------------------------------------------------------------------------------------------------------<br>';
?>