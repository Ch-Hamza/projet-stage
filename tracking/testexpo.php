<?php

$ch = curl_init();

$base_url= "https://www.museums.ch/fr/au-musee/expositions.html";
curl_setopt ($ch, CURLOPT_URL, $base_url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 

$output = curl_exec($ch);
curl_close($ch);

$dom = new DOMDocument();
@$dom->loadHTML($output);
$xpath = new DOMXPath($dom);

$nbr_pages = ($xpath->query('//a[@class="pagelinks pages "]')->length)/2;
$pages = $xpath->query('//a[@class="pagelinks pages "]');
$links = array();

echo '--------------------------------------------nbr_pages = '.$nbr_pages.'-------------------------------------------<br>';
array_push($links, 'au-musee/expositions.html?&searchstring=&orderby=aus_von&offset=0&orderdir=DESC&count_result=246');
$i=0;
foreach ($pages as $page) {
    if($i < $nbr_pages){
        $link = $page->attributes[1]->nodeValue;
        array_push($links, $link);
    }
    $i++;
}
var_dump($links);

foreach ($links as $page) {
	$base_url= "https://www.museums.ch/fr/";
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $base_url.$page);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 

	$output = curl_exec($ch);
	curl_close($ch);

	$dom = new DOMDocument();
	@$dom->loadHTML($output);
	$xpath = new DOMXPath($dom);

	//echo $output;

	$div = $xpath->query('//div[@class="fix group"]');

        foreach($div as $node) {

            if(!$node->childNodes[1]->nodeValue == ''){
                //echo '<pre>' ; var_dump($node->childNodes[1]->childNodes[1]->attributes[0]->nodeValue) ;echo '</pre>';
                
                $link = $node->childNodes[1]->childNodes[1]->attributes[0]->nodeValue;
                echo "<br>link = ".$link."<br>";

        }}
}

?>