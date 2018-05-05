<?php

$ch = curl_init();

	/*curl_setopt ($ch, CURLOPT_URL, 'https://www.museums.ch/org/fr/Besucherzentrum-Axporama');
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $details = curl_exec($ch);
    curl_close($ch);
    $dom = new DOMDocument();
	@$dom->loadHTML($details);
	$xpath = new DOMXPath($dom);*/

    $ch = curl_init();
    $params = array(
            'bleibleer'                 =>  '',
            'museensearch[art]'         =>  'museum',
            'museensearch[name]'        =>  '', 
            'museensearch[plz]'         =>  '', 
            'museensearch[ort]'         =>  '', 
            'museensearch[schwerpunkt]' =>  '', 
            'museensearch[kanton]'      =>  '', 
            'museensearch[stichwort]'   =>  '', 
            'submitbtn'                 => 'Recherche',
            'museensearch[order]'       => 'org_name' , 
            'museensearch[sort]'        => 'ASC'
        );
    curl_setopt ($ch, CURLOPT_URL, 'https://www.museums.ch/fr/au-musee/recherche-de-mus%C3%A9es/resultat-de-recherche-de-mus%C3%A9es.html');
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'authority: www.museums.ch',
			'method: POST',
			'path: /fr/au-musee/recherche-de-mus%C3%A9es/resultat-de-recherche-de-mus%C3%A9es.html',
			'scheme: https',
			'origin: https://www.museums.ch',
			'referer: https://www.museums.ch/fr/au-musee/recherche-de-mus%C3%A9es/'
    ));

    $output = curl_exec($ch);
    curl_close($ch);
    echo $output;

    //echo $details;

    $nom = "N/A";
    $description = "";
    $images = array();
    $horaire = "N/A";
    $lieu = "N/A";
    $categories = "N/A";
    $canton = "N/A";
    $adresse = "N/A";
    $directions = "N/A";
    $criteres = array();
    $prix = "N/A";
    $contact = "";
    $categories = array();
    	
    $maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
        foreach ($maincol as $balise) {
            
            
            if($balise->nodeType == 3){
                continue;
            }

            if($balise->tagName == 'h1'){
                $titre = $balise->nodeValue;
            }

            if($balise->tagName == 'p'){
                $description = $description."<br>".$balise->nodeValue;
            }

            if($balise->tagName == 'figure'){
                //var_dump($balise->childNodes[0]->attributes[0]->nodeValue);
                $url = $balise->childNodes[0]->attributes[0]->nodeValue;
                copy($url, 'img/test1.jpg');
                array_push($images, $balise->childNodes[0]->attributes[0]->nodeValue);
            }
        }

        $sideinfo = $xpath->query('//section[@class="sideinfo  tabs"]')[0]->childNodes[0]->childNodes;
        //var_dump($sideinfo[0]->childNodes[0]);
        
        //criteres
        foreach ($sideinfo[0]->childNodes as $oneLi) {
            //var_dump($oneLi);
            if($oneLi->nodeType == 3){
                continue;
            }

            if($oneLi->tagName == 'li'){
                //var_dump($oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                
                // this was added to prevent empty critere at https://www.museums.ch/org/fr/SBBHistoric
                if($oneLi->childNodes[1]->childNodes[2]->nodeValue != ""){
                    $critereName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                    $critereImage = $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue;
                    $critere = array('name' => $critereName, 'image' => $critereImage);
                    array_push($criteres, $critere);
                }
            }
        }

        foreach ($sideinfo as $value) {

            if($value->nodeType == 3){
                continue;
            }

            if($value->tagName == 'ul'){
                if($value->attributes[0]->nodeValue == 'kriterien_icons'){
                    //categories
                    foreach ($value->childNodes as $oneLi) {
                        //var_dump($oneLi);
                        if($oneLi->nodeType == 3){
                            continue;
                        }

                        if($oneLi->tagName == 'li'){

                            if($oneLi->childNodes[1]->childNodes[2]->nodeType != 3){
                                //var_dump($oneLi->childNodes[1]->childNodes[2]);
                                $categoryName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                                $categoryImage = $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue;
                                $category = array('name' => $categoryName, 'image' => $categoryImage);
                                array_push($categories, $category);
                            }
                        }
                    }
                }
            }

            if($value->tagName == 'div'){
                //var_dump($value->childNodes[1]->nodeValue);
                if($value->childNodes[1]->nodeValue == 'Situation / Plan d\'accès '){
                    //directions
                    $tmp = explode("    ", $value->childNodes[2]->nodeValue);
                    $directions = end($tmp);
                    //var_dump($value->childNodes[1]->nodeValue);
                }

                if($value->childNodes[1]->nodeValue == 'Entrée '){
                    //prix
                    echo "-----------------------tt";
                    $tmp = explode("    ", $value->childNodes[2]->nodeValue);
                    //var_dump($value->childNodes[2]);
                    
                    $prix = $tmp[0];
                }

                if($value->childNodes[1]->nodeValue == 'Contact '){
                    //contact
                    foreach ($value->childNodes as $val) {
                        $tmp = explode("    ", $val->nodeValue);
                        $contact = $contact."<br>".end($tmp);

                    }
                }
            }   
        }

        //echo getDetailsFromUrl($link);

        echo ' Nom --> '.$nom;
        echo '<br>';
        echo ' adresse --> '.$adresse;
        echo '<br>';
        echo ' horaire --> '.$horaire;
        echo '<br>';
        echo ' lieu --> '.$lieu;
        echo '<br>';
        echo ' canton --> '.$canton;
        echo '<br>';
        echo ' description --> '.$description;
        echo '<br>';
        echo ' images --> <br>';var_dump($images);
        echo '<br>';
        echo ' directions --> '.$directions;
        echo '<br>';
        echo ' criteres --> <br>';var_dump($criteres);
        echo '<br>';
        echo ' prix --> '.$prix;
        echo '<br>';
        echo ' contact --> '.$contact;
        echo '<br>';
        echo ' categories --> <br>';var_dump($categories);
        echo '<br>';
        echo '--------------------------------------------------------------------------------------------------------------<br>';
?>