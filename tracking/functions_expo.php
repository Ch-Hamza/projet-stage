<?php
set_time_limit(0);
function getContentFromUrl($url){
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
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

function parseAllExpositions($connexion, $state){

    $base_url= "https://www.museums.ch/fr/au-musee/expositions.html";
    //$base_url = "https://www.museums.ch/it/al-museo/esposizioni.html";
    //$base_url = "https://www.museums.ch/ins-museum/ausstellungen.html";
    //$base_url = "https://www.museums.ch/en/museums/exhibitions.html";
    
    $size = getSize($base_url);
    
    echo '------------ ALL -----------------------------------------------------------------------------------------------<br>';
    echo '<br>--------- '. $base_url.' ----------<br>';
    echo '-----------------------------------------'.$size.'-------------------------------------------------------------<br>';
    
    if(!$state)
        return;

    $html =  getContentFromUrl($base_url);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
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
        //$base_url = "https://www.museums.ch/it/";
        //$base_url = "https://www.museums.ch/";
        //$base_url = "https://www.museums.ch/en/";

        $ch = curl_init();
        $url =$base_url.$page;
        $url = trim(preg_replace('/tab/', '', $url));
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 

        $output = curl_exec($ch);
        curl_close($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $xpath = new DOMXPath($dom);
        //echo $html;
        $div = $xpath->query('//div[@class="fix group"]');

        foreach($div as $node) {

            if(!$node->childNodes[1]->nodeValue == ''){
                //echo '<pre>' ; var_dump($node->childNodes[1]->childNodes[1]->attributes[0]->nodeValue) ;echo '</pre>';
                
                $link = $node->childNodes[1]->childNodes[1]->attributes[0]->nodeValue;
                echo "<br>link = ".$link."<br>";
                $actual_name = $node->childNodes[1]->childNodes[1]->childNodes[1]->nodeValue;

                if(!$node->childNodes[1]->nodeValue == ''){
                    $output = getDetailsFromUrl($link);

                    $dom = new DOMDocument();
                    @$dom->loadHTML($output);
                    $xpath = new DOMXPath($dom);
                    $div1 = $xpath->query('//section[@class="ausstellungenDetail group"]');

                    $museum_name = $xpath->query('//h1[@class="titel"]')[0]->nodeValue;

                    foreach ($div1 as $node) {

                        foreach ($node->childNodes as $value) {
                            if($value->nodeType == 1){
                                if($value->attributes[0]->nodeValue == 'headAusstellung strichgold'){

                                    $name = "N/A";
                                    $start = "N/A";
                                    $finish = "N/A";
                                    $description = "N/A";
                                    $images = array();

                                    $name = $value->childNodes[1]->nodeValue;

                                    if($name != $actual_name){continue;}

                                    $date = explode('.', $value->childNodes[3]->nodeValue);
                                    $start = $date[2]."-".$date[1]."-".$date[0];

                                    $date = explode('.', $value->childNodes[5]->nodeValue);
                                    $finish = $date[2]."-".$date[1]."-".$date[0];

                                    $description = "";
    
                                    foreach ($value->nextSibling->nextSibling->childNodes as $desc) {
                                        if($desc->nodeType == 1 && $desc->tagName == 'figure'){
                                            //echo '<pre>' ; var_dump($desc->childNodes[0]->attributes[0]->nodeValue) ;echo '</pre>';
                                            array_push($images, $desc->childNodes[0]->attributes[0]->nodeValue);
                                        }

                                        if($desc->nodeType == 3){
                                            $description = $description."<br>".trim($desc->nodeValue);
                                        }
                                    }

                                    echo '<br>';
                                    echo "Museum-name --> ".$museum_name;
                                    echo '<br>';
                                    echo 'Link --> '.$link;
                                    echo '<br>';
                                    echo ' Nom --> '.$name;
                                    echo '<br>';
                                    echo ' Start --> '.$start;
                                    echo '<br>';
                                    echo ' Finish --> '.$finish;
                                    echo '<br>';
                                    echo ' Description --> '.$description;
                                    echo '<br>';
                                    echo ' images --> <br>';echo '<pre>' . var_dump($images) . '</pre>';
                                    echo '<br>';
                                    echo 'Museum-name --> '.$museum_name;
                                    echo '<br>';

                                    $oneExpo = createExpo($name, $description, $start, $finish, $images, $museum_name);
                                    $listExpo[]=$oneExpo;
                                }

                            }
                        }
                    }

                    

                }

        }
    }

    }

}

function createExpo($nom, $description, $start, $finish, $images, $museum_name)
{
    $oneExpo = new Exposition();

    $nom = trim(addslashes($nom));
    $museum_name = trim(addslashes($museum_name));

    $oneExpo->setNom($nom);
    $oneExpo->setDescription($description);
    $oneExpo->setStart($start);
    $oneExpo->setFinish($finish);

    $connexion = connect("test");

    $sql = "SELECT * FROM museum WHERE `name`='".addslashes($museum_name)."'";
    $result = $connexion->query($sql);
    $rows = $result->fetchAll();
    $num_rows = count($rows);
    echo "query = ";var_dump($sql);
    if ($num_rows > 0) {
        $museum_id = $rows[0]['id'];
        $oneExpo->setMuseumid($museum_id);
    }

	//fetch expo by name or create expo
	$sql = "SELECT * FROM exposition WHERE `name`='".addslashes($nom)."'";
    //echo "requete = ".$sql;
    $result = $connexion->query($sql);
    $rows = $result->fetchAll();
    $num_rows = count($rows);
	if ($num_rows > 0) {
	    // output data of each row
	    $id = $rows[0]['id'];
	    $stmt = $connexion->prepare("UPDATE exposition SET name='".addslashes($nom)."', description='".addslashes($description)."', start='".addslashes($start)."', finish='".addslashes($finish)."', hosting_museum_id='".$museum_id."' WHERE id='".$id."'");
	    $stmt->execute();

        $stmt_h = $connexion->prepare("INSERT INTO history_exposition__history (exposition_id, action, date) 
            VALUES (:exposition_id, 'Update', NOW())");
        $stmt_h->bindParam(':exposition_id', $id);
        $stmt_h->execute();

	    $sql = "SELECT * FROM exposition_image WHERE exposition_id=".$id;
        $result = $connexion->query($sql);
        $rows = $result->fetchAll();
        $num_rows = count($rows);
        if($num_rows != count($images)){
            $sql = "DELETE FROM exposition_image WHERE exposition_id=".$id;
            $connexion->exec($sql);
            foreach ($images as $img) {
                createImage($img, $id);
            }
        }

        echo "<br>-----------------------------------------------------------ERRORS-----------------------------------------------------------<br>";
        var_dump($stmt);
        echo "\nPDO::errorInfo():\n";
        print_r($connexion->errorInfo());
        echo "<br>----------------------------------------------------------------------------------------------------------------------------<br>";

        foreach ($images as $img) {
            createImage($img, $id);
        }

	    return $id;
	} else {
		$mId = insertExpo($oneExpo);
	}

	
    foreach ($images as $img) {
    	createImage($img, $mId);
    }

    return $oneExpo;
}

function insertExpo($exposition){
	$connexion = connect("test");
	$stmt = $connexion->prepare("INSERT INTO exposition (name, description, hosting_museum_id, start, finish, enabled) 
	    VALUES (:name, :description, :museum_id, :start, :finish, false)");

    $nom = $exposition->getNom();
    $description = addslashes($exposition->getDescription());
    $start = addslashes($exposition->getStart());
    $finish = addslashes($exposition->getFinish());
    $museum_id = $exposition->getMuseumid();

    $stmt->bindParam(':name', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':finish', $finish);
    $stmt->bindParam(':museum_id', $museum_id);
    $stmt->execute();
    $last_id = $connexion->lastInsertId();
    
    $stmt = $connexion->prepare("INSERT INTO history_exposition__history (exposition_id, action, date) 
        VALUES (:exposition_id, 'Insert', NOW())");
    $stmt->bindParam(':exposition_id', $last_id);
    $stmt->execute();

    echo "<br>-----------------------------------------------------------ERRORS-----------------------------------------------------------<br>";
    var_dump($stmt);
    echo "\nPDO::errorInfo():\n";
    print_r($connexion->errorInfo());
    echo "<br>----------------------------------------------------------------------------------------------------------------------------<br>";
    return $last_id;
}

function createImage($img, $id){
	$connexion = connect("test");
	$stmt = $connexion->prepare("INSERT INTO exposition_image (id, exposition_id, image, updated_at) 
	    VALUES (null, :exposition_id, :image, NOW())");
    $stmt->bindParam(':exposition_id', $id);
    $stmt->bindParam(':image', $img);
    $stmt->execute();
}

?>