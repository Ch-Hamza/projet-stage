<?php
set_time_limit(0);
function getContentFromUrl($url){

    $ch = curl_init();
    $params = array(
    		'bleibleer'					=> 	'',
    		'museensearch[art]'			=> 	'museum',
        	'museensearch[name]' 		=> 	'', 
        	'museensearch[plz]' 		=> 	'', 
        	'museensearch[ort]' 		=> 	'', 
        	'museensearch[schwerpunkt]' => 	'', 
        	'museensearch[kanton]' 		=> 	'', 
        	'museensearch[stichwort]' 	=> 	'', 
        	'submitbtn'					=> 'Recherche',
        	'museensearch[order]' 		=> 'org_name' , 
        	'museensearch[sort]' 		=> 'ASC'
        );
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

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

function getSize($url){

    $html =  getContentFromUrl($url);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $div = $xpath->query('//div[@class="fix group"]');
    
    return $div->length;
}

function parseAllMuseums($connexion, $state){

    //$language = "fr";
    //if($language == 'fr'){
    $base_url= "https://www.museums.ch/fr/au-musee/recherche-de-mus%C3%A9es/resultat-de-recherche-de-mus%C3%A9es.html";
    //}
    /*if($language == 'it'){
    	$base_url = "https://www.museums.ch/it/al-museo/ricerca-di-musei/risultati-di-ricerca-nei-dati-dei-musei.html";
    }
    if($language == 'de'){
    	$base_url = "https://www.museums.ch//ins-museum/museumssuche/sucheresultate-museen.html";
    }
    if($language == 'en'){
    	$base_url = "https://www.museums.ch/en/museums/museum-finder/search-results.html";
    }*/
    
    $size = getSize($base_url);
    
    echo '------------ ALL -----------------------------------------------------------------------------------------------<br>';
    echo '<br>--------- '. $base_url.' ----------<br>';
    echo '-----------------------------------------'.$size.'-------------------------------------------------------------<br><br>';
    
    if(!$state)
        return;

    $html =  getContentFromUrl($base_url);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $div = $xpath->query('//div[@class="fix group"]');

    foreach($div as $node) {
        $divListe = $node->getElementsByTagName('div');
        
        $nom = "N/A";
        $description_fr = "";
        $description_en = "";
        $description_it = "";
        $description_de = "";
        $images = array();
        $horaire = "N/A";
        $lieu = "N/A";
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

        if($divListe[0]->childNodes[1]->childNodes[1]->nodeValue != ""){
            $nom = $divListe[0]->childNodes[1]->childNodes[1]->nodeValue;
        }
        if($divListe[0]->childNodes[1]->childNodes[3]->nodeValue != ""){
            $rue = $divListe[0]->childNodes[1]->childNodes[3]->childNodes[0]->nodeValue;
            $code_postal = explode(" ", $divListe[0]->childNodes[1]->childNodes[3]->childNodes[2]->nodeValue)[0];
        }
        if($divListe[0]->childNodes[1]->childNodes[5]->nodeValue != ""){
            $horaire = $divListe[0]->childNodes[1]->childNodes[5]->nodeValue;
        }
        if($divListe[1]->nodeValue != ""){
            $lieu = $divListe[1]->nodeValue;
        }
        if($divListe[2]->nodeValue != ""){
            $canton = $divListe[2]->nodeValue;
        }
        if($divListe[0]->childNodes[1]->attributes[0]->nodeValue != ""){
            $link_fr = $divListe[0]->childNodes[1]->attributes[0]->nodeValue;
            $link_en = preg_replace("/fr/", "en", $link_fr);
            $link_it = preg_replace("/fr/", "it", $link_fr);
            $link_de = preg_replace("/fr/", "de", $link_fr);
        }

        $details = getDetailsFromUrl($link_en);
        $dom = new DOMDocument();
        @$dom->loadHTML($details);
        $xpath = new DOMXPath($dom);
        $maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
        $tester_critere = array();
        $tester_category = array();
        foreach ($maincol as $balise) {
            if($balise->nodeType == 3){
                continue;
            }
            if($balise->tagName == 'p'){
                $description_en = $description_en." ".$balise->nodeValue;
            }
        }
        $sideinfo = $xpath->query('//section[@class="sideinfo  tabs"]')[0]->childNodes[0]->childNodes;
        foreach ($sideinfo[0]->childNodes as $oneLi) {
            if($oneLi->nodeType == 3){
                continue;
            }
            if($oneLi->tagName == 'li'){ 
                // this was added to prevent empty critere at https://www.museums.ch/org/fr/SBBHistoric
                $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                $imageName1 = end($imageName);
                if($oneLi->childNodes[1]->childNodes[2]->nodeValue != ""){
                    $critereName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                    array_push($tester_critere, $imageName);
                    $criteres[$imageName1]['image'] = $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue;
                    $criteres[$imageName1]['name_en'] = $critereName;
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
                        if($oneLi->nodeType == 3){
                            continue;
                        }
                        if($oneLi->tagName == 'li'){
                            $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                            $imageName1 = end($imageName);
                            if($oneLi->childNodes[1]->childNodes[2]->nodeType != 3){
                                $categoryName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                                array_push($tester_category, $imageName);
                                $categories[$imageName1]['image'] = $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue;
                                $categories[$imageName1]['name_en'] = $categoryName;
                            }
                        }
                    }
                }
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
        $sideinfo = $xpath->query('//section[@class="sideinfo  tabs"]')[0]->childNodes[0]->childNodes;
        foreach ($sideinfo[0]->childNodes as $oneLi) {
            if($oneLi->nodeType == 3){
                continue;
            }
            if($oneLi->tagName == 'li'){ 
                // this was added to prevent empty critere at https://www.museums.ch/org/fr/SBBHistoric
                $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                $imageName1 = end($imageName);
                if($oneLi->childNodes[1]->childNodes[2]->nodeValue != ""){
                    $critereName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                    array_push($tester_critere, $imageName);
                    $criteres[$imageName1]['image'] = $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue;
                    $criteres[$imageName1]['name_it'] = $critereName;
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
                        if($oneLi->nodeType == 3){
                            continue;
                        }
                        if($oneLi->tagName == 'li'){
                            $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                            $imageName1 = end($imageName);
                            if($oneLi->childNodes[1]->childNodes[2]->nodeType != 3){
                                $categoryName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                                array_push($tester_category, $imageName);
                                $categories[$imageName1]['name_it'] = $categoryName;
                            }
                        }
                    }
                }
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
        $sideinfo = $xpath->query('//section[@class="sideinfo  tabs"]')[0]->childNodes[0]->childNodes;
        foreach ($sideinfo[0]->childNodes as $oneLi) {
            if($oneLi->nodeType == 3){
                continue;
            }
            if($oneLi->tagName == 'li'){ 
                // this was added to prevent empty critere at https://www.museums.ch/org/fr/SBBHistoric
                $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                $imageName1 = end($imageName);
                if($oneLi->childNodes[1]->childNodes[2]->nodeValue != ""){
                    $critereName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                    array_push($tester_critere, $imageName);
                    $criteres[$imageName1]['name_de'] = $critereName;
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
                        if($oneLi->nodeType == 3){
                            continue;
                        }
                        if($oneLi->tagName == 'li'){
                            $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                            $imageName1 = end($imageName);
                            if($oneLi->childNodes[1]->childNodes[2]->nodeType != 3){
                                $categoryName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                                array_push($tester_category, $imageName);
                                $categories[$imageName1]['name_de'] = $categoryName;
                            }
                        }
                    }
                }
            }
        }

        $details = getDetailsFromUrl($link_fr);
        $dom = new DOMDocument();
        @$dom->loadHTML($details);
        $xpath = new DOMXPath($dom);
        $maincol = $xpath->query('//section[@class="maincol"]')[0]->childNodes[0]->childNodes[2]->childNodes;
        $tester_critere = array();
        $tester_category = array();
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
        $sideinfo = $xpath->query('//section[@class="sideinfo  tabs"]')[0]->childNodes[0]->childNodes;
        foreach ($sideinfo[0]->childNodes as $oneLi) {
            if($oneLi->nodeType == 3){
                continue;
            }
            if($oneLi->tagName == 'li'){ 
                // this was added to prevent empty critere at https://www.museums.ch/org/fr/SBBHistoric
                $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                $imageName1 = end($imageName);
                if($oneLi->childNodes[1]->childNodes[2]->nodeValue != ""){
                    $critereName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                    array_push($tester_critere, $imageName);
                    $criteres[$imageName1]['name_fr'] = $critereName;
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
                        if($oneLi->nodeType == 3){
                            continue;
                        }
                        if($oneLi->tagName == 'li'){
                            $imageName = explode("/", $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue);
                            $imageName1 = end($imageName);
                            if($oneLi->childNodes[1]->childNodes[2]->nodeType != 3){
                                $categoryName = $oneLi->childNodes[1]->childNodes[2]->nodeValue;
                                array_push($tester_category, $imageName);
                                $categories[$imageName1]['image'] = $oneLi->childNodes[1]->childNodes[1]->attributes[1]->nodeValue;
                                $categories[$imageName1]['name_fr'] = $categoryName;
                            }
                        }
                    }
                }
            }
            if($value->tagName == 'div'){

                if($value->childNodes[1]->nodeValue == 'Situation / Plan d\'accès ' || $value->childNodes[1]->nodeValue == 'Ubicazione/Arrivo ' || $value->childNodes[1]->nodeValue == 'Lage/Anreise ' || $value->childNodes[1]->nodeValue == 'Location/directions '){
                    //directions
                    $tmp = explode("    ", $value->childNodes[2]->nodeValue);
                    $directions = end($tmp);
                }

                if($value->childNodes[1]->nodeValue == 'Entrée ' || $value->childNodes[1]->nodeValue == 'Ingresso ' || $value->childNodes[1]->nodeValue == 'Eintritt ' || $value->childNodes[1]->nodeValue == 'Admission '){
                    //prix
                    $tmp = explode("    ", $value->nodeValue);
                    $prix = end($tmp);
                }

                if($value->childNodes[1]->nodeValue == 'Contact ' || $value->childNodes[1]->nodeValue == 'Contatto ' || $value->childNodes[1]->nodeValue == 'Kontakt '){
                    //contact
                    foreach ($value->childNodes as $value) {
                        if($value->nodeType == 3){
                            if($value->nodeValue != ""){
                                $text = $value->nodeValue;
                                $tel_table = explode(" ", $text);
                                $nature = $tel_table[count($tel_table)-2];
                                if($nature == "Téléphone"){
                                    $phone = "";
                                    for ($i=0; $i <= (count($tel_table)-3); $i++) { 
                                        $phone = $phone." ".$tel_table[$i];
                                    } 
                                }
                                if($nature == "Fax"){
                                    $fax = "";
                                    for ($i=0; $i <= (count($tel_table)-3); $i++) { 
                                        $fax = $fax." ".$tel_table[$i];
                                    }
                                }
                            }
                        }
                        if($value->nodeType == 1){
                            if($value->tagName == 'a'){
                                $href = $value->attributes[0]->nodeValue;
                                $href_table = explode(":", $href);
                                if($href_table[0] == "mailto") {
                                    $email = $value->nodeValue;
                                }
                                if($href_table[0] == "http") {
                                    $website = $value->nodeValue;
                                } 
                            }
                        }
                    }
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

        //die("test");
        $oneMuseum = createMuseum($nom, $description_fr, $description_en, $description_it, $description_de, $images, $horaire, $lieu, $categories, $canton, $rue, $code_postal, $directions, $criteres, $prix, $phone, $fax, $email, $website);
        $listMuseums[]=$oneMuseum;
    }
}

function createMuseum($nom, $description_fr, $description_en, $description_it, $description_de, $images, $horaire, $lieu, $categories, $canton, $rue, $code_postal, $directions, $criteres, $prix, $phone, $fax, $email, $website)
{
    $oneMuseum = new Museum();

    $nom = trim(addslashes($nom));
    $oneMuseum->setNom($nom);
    $oneMuseum->setDescription_fr($description_fr);
    $oneMuseum->setDescription_en($description_en);
    $oneMuseum->setDescription_it($description_it);
    $oneMuseum->setDescription_de($description_de);
    $oneMuseum->setHoraire($horaire);
    $oneMuseum->setLieu($lieu);
    $oneMuseum->setRue($rue);
    $oneMuseum->setCode_postal($code_postal);
    $oneMuseum->setDirections($directions);
    $oneMuseum->setPrix($prix);
    $oneMuseum->setPhone($phone);
    $oneMuseum->setFax($fax);
    $oneMuseum->setEmail($email);
    $oneMuseum->setWebsite($website);

    $canton_id = intval(getCantonID($canton));
    $oneMuseum->setCanton($canton_id);

    $connexion = connect("test");
	//fetch museum by name or create museum
	$sql = "SELECT * FROM museum WHERE `name`='".addslashes($nom)."'";
    //echo "requete = ".$sql;
    $result = $connexion->query($sql);
    $rows = $result->fetchAll();
    $num_rows = count($rows);
	if ($num_rows > 0) {
	    // output data of each row
	    $id = $rows[0]['id'];
	    $stmt = $connexion->prepare("UPDATE museum SET name='".addslashes($nom)."', description_fr='".addslashes($description_fr)."', description_en='".addslashes($description_en)."', description_it='".addslashes($description_it)."', description_de='".addslashes($description_de)."', horaire='".addslashes($horaire)."', price='".addslashes($prix)."', place='".addslashes($lieu)."', rue='".addslashes($rue)."', code_postal='".$code_postal."', directions='".addslashes($directions)."', phone='".$phone."', fax='".$fax."', email='".$email."', website='".$website."', canton_id='".$canton_id."' WHERE id='".$id."'");
	    $stmt->execute();
        
        $stmt_h = $connexion->prepare("INSERT INTO history_museum__history (museum_id, action, date) 
            VALUES (:museum_id, 'Update', NOW())");
        $stmt_h->bindParam(':museum_id', $id);
        $stmt_h->execute();

        $sql = "SELECT * FROM museum_image WHERE museum_id=".$id;
        $result = $connexion->query($sql);
        $rows = $result->fetchAll();
        $num_rows = count($rows);
        if($num_rows != count($images)){
            $sql = "DELETE FROM museum_image WHERE museum_id=".$id;
            $connexion->exec($sql);
            foreach ($images as $img) {
                createImage($img, $id);
            }
        }
        /*var_dump($num_rows);
        var_dump(count($images));
        die('test');*/

        echo "<br>-----------------------------------------------------------ERRORS-----------------------------------------------------------<br>";
        var_dump($stmt);
        echo "\nPDO::errorInfo():\n";
        print_r($connexion->errorInfo());
        echo "<br>----------------------------------------------------------------------------------------------------------------------------<br>";

	    return $id;
	} else {
		$mId = insertMuseum($oneMuseum);
	}

	
    foreach ($images as $img) {
    	createImage($img, $mId);
    }

    foreach ($categories as $category) {
    	createCategory($category, $mId);
    }

    foreach ($criteres as $critere) {
    	createCritere($critere, $mId);
    }

    return $oneMuseum;
}

function insertMuseum($museum){
	$connexion = connect("test");
	$stmt = $connexion->prepare("INSERT INTO museum (name, description_fr, description_en, description_it, description_de, horaire, price, place, rue, code_postal, directions, phone, fax, email, website, enabled, canton_id) 
	    VALUES (:name, :description_fr, :description_en, :description_it, :description_de, :horaire, :price, :place, :rue, :code_postal, :directions, :phone, :fax, :email, :website, false, :canton_id)");

    $nom = $museum->getNom();
    $description_fr = addslashes($museum->getDescription_fr());
    $description_en = addslashes($museum->getDescription_en());
    $description_it = addslashes($museum->getDescription_it());
    $description_de = addslashes($museum->getDescription_de());
    $horaire = addslashes($museum->getHoraire());
    $prix = addslashes($museum->getPrix());
    $lieu = addslashes($museum->getLieu());
    $rue = addslashes($museum->getRue());
    $code_postal = addslashes($museum->getCode_postal());
    $directions = addslashes($museum->getDirections());
    $phone = addslashes($museum->getPhone());
    $fax = addslashes($museum->getFax());
    $email = addslashes($museum->getEmail());
    $website = addslashes($museum->getWebsite());
    $canton = $museum->getCanton();
    //var_dump($museum);

    $stmt->bindParam(':name', $nom);
    $stmt->bindParam(':description_fr', $description_fr);
    $stmt->bindParam(':description_en', $description_en);
    $stmt->bindParam(':description_it', $description_it);
    $stmt->bindParam(':description_de', $description_de);
    $stmt->bindParam(':horaire', $horaire);
    $stmt->bindParam(':price', $prix);
    $stmt->bindParam(':place', $lieu);
    $stmt->bindParam(':rue', $rue);
    $stmt->bindParam(':code_postal', $code_postal);
    $stmt->bindParam(':directions', $directions);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':fax', $fax);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':website', $website);
    $stmt->bindParam(':canton_id', $canton);
    $stmt->execute();
    $last_id = $connexion->lastInsertId();

    $stmt = $connexion->prepare("INSERT INTO history_museum__history (museum_id, action, date) 
        VALUES (:museum_id, 'Insert', NOW())");
    $stmt->bindParam(':museum_id', $last_id);
    $stmt->execute();

    echo "<br>-----------------------------------------------------------ERRORS-----------------------------------------------------------<br>";
    var_dump($stmt);
    echo "\nPDO::errorInfo():\n";
    print_r($connexion->errorInfo());
    echo "<br>----------------------------------------------------------------------------------------------------------------------------<br>";
    return $last_id;
}

function getCantonID($canton){
	$connexion = connect("test");
	//fetch canton by name or create canton
	$sql = "SELECT * FROM canton WHERE title='".addslashes($canton)."'";
	$result = $connexion->query($sql);
    $rows = $result->fetchAll();
    $num_rows = count($rows);
	if ($num_rows > 0) {
	    // output data of each row
	    $id = $rows[0]['id'];
	    return $id;
	} else {
	    $stmt = $connexion->prepare("INSERT INTO canton (id, title, image, updated_at) 
	    VALUES (null, :title, null, NOW())");
	    $stmt->bindParam(':title', $canton);
	    $stmt->execute();
	    $last_id = $connexion->lastInsertId();

        $stmt = $connexion->prepare("INSERT INTO history_canton__history (canton_id, action, date) 
            VALUES (:canton_id, 'Insert', NOW())");
        $stmt->bindParam(':canton_id', $last_id);
        $stmt->execute();

	    return $last_id;
	}
}

function createCritere($critere, $id){
	$connexion = connect("test");
	//create critere if not exists and add both id_museum,id_critere in table museum_critere
	$sql = "SELECT * FROM critere WHERE title_fr='".addslashes($critere['name_fr'])."' OR title_en='".addslashes($critere['name_en'])."' OR title_it='".addslashes($critere['name_it'])."' OR title_de='".addslashes($critere['name_de'])."'";
	$result = $connexion->query($sql);

	$rows = $result->fetchAll();
    $num_rows = count($rows);
	if ($num_rows > 0) {
	    // output data of each row
        $critere_id = $rows[0]["id"];

        //to prevent duplicate critere error on https://www.museums.ch/org/fr/MoulinRod-1350
        $sql = "SELECT * FROM museum_critere WHERE museum_id='".$id."' AND critere_id='".$critere_id."'";
		$result = $connexion->query($sql);

		$rows = $result->fetchAll();
	    $num_rows = count($rows);

        if($num_rows <= 0){
        	$stmt = $connexion->prepare("INSERT INTO museum_critere (museum_id, critere_id) 
    			VALUES (:museum_id, :critere_id)");
		    $stmt->bindParam(':museum_id', $id);
		    $stmt->bindParam(':critere_id', $critere_id);
		    $stmt->execute();
        }
	} else {
	    $stmt = $connexion->prepare("INSERT INTO critere (id, title_fr, title_en, title_it, title_de, image, updated_at) 
	    VALUES (null, :title_fr, :title_en, :title_it, :title_de, :image, NOW())");
	    $stmt->bindParam(':title_fr', $critere['name_fr']);
        $stmt->bindParam(':title_en', $critere['name_en']);
        $stmt->bindParam(':title_it', $critere['name_it']);
        $stmt->bindParam(':title_de', $critere['name_de']);
	    $stmt->bindParam(':image', $critere['image']);
	    $stmt->execute();
	    $last_id = $connexion->lastInsertId();

        $stmt = $connexion->prepare("INSERT INTO history_critere__history (critere_id, action, date) 
            VALUES (:critere_id, 'Insert', NOW())");
        $stmt->bindParam(':critere_id', $last_id);
        $stmt->execute();

	    $stmt = $connexion->prepare("INSERT INTO museum_critere (museum_id, critere_id) 
	        	VALUES (:museum_id, :critere_id)");
	    $stmt->bindParam(':museum_id', $id);
	    $stmt->bindParam(':critere_id', $last_id);
	    $stmt->execute();
	}
}

function createImage($img, $id){
	$connexion = connect("test");
	//create image and add both id_museum,id_image in table museum_image
	$stmt = $connexion->prepare("INSERT INTO museum_image (id, museum_id, image, updated_at) 
	    VALUES (null, :museum_id, :image, NOW())");
    $stmt->bindParam(':museum_id', $id);
    $stmt->bindParam(':image', $img);
    $stmt->execute();
}

function createCategory($category, $id){
	$connexion = connect("test");
	//create Category if not exists and add both id_museum,id_Category in table museum_Category
	$sql = "SELECT * FROM category WHERE title_fr='".addslashes($category['name_fr'])."'";
	$result = $connexion->query($sql);

	$rows = $result->fetchAll();
    $num_rows = count($rows);
	if ($num_rows > 0) {
	    // output data of each row
        $category_id = $rows[0]["id"];
        $stmt = $connexion->prepare("INSERT INTO museum_category (museum_id, category_id) 
        	VALUES (:museum_id, :category_id)");
	    $stmt->bindParam(':museum_id', $id);
	    $stmt->bindParam(':category_id', $category_id);
	    $stmt->execute();
	} else {
	    $stmt = $connexion->prepare("INSERT INTO category (title_fr, title_en, title_it, title_de, image, updated_at) 
        VALUES (:title_fr, :title_en, :title_it, :title_de, :image, NOW())");
        $stmt->bindParam(':title_fr', $category['name_fr']);
        $stmt->bindParam(':title_en', $category['name_en']);
        $stmt->bindParam(':title_it', $category['name_it']);
        $stmt->bindParam(':title_de', $category['name_de']);
	    $stmt->bindParam(':image', $category['image']);
	    $stmt->execute();
	    $last_id = $connexion->lastInsertId();

        $stmt = $connexion->prepare("INSERT INTO history_category__history (category_id, action, date) 
            VALUES (:category_id, 'Inser', NOW())");
        $last_id = $connexion->lastInsertId();
        $stmt->bindParam(':category_id', $last_id);
        $stmt->execute();

	    $stmt = $connexion->prepare("INSERT INTO museum_category (museum_id, category_id) 
	        	VALUES (:museum_id, :category_id)");
	    $stmt->bindParam(':museum_id', $id);
	    $stmt->bindParam(':category_id', $last_id);
	    $stmt->execute();
	    
	}
}

?>