<?php 
	set_time_limit(0);
	require('dbo_connect.php');

	$connexion = connect("test");

	$sql = "SELECT * FROM category";
	$result = $connexion->query($sql);

	$rows = $result->fetchAll();
	$num_rows = count($rows);

	if($num_rows > 0){
		//var_dump($rows);
		$local_path = "../web/uploads/img/category_images/";
		foreach ($rows as $value) {
			if($value['image'] != $value['id'].".png")
			{
				//var_dump($value['image']);
				$new_link = $local_path.$value['id'].".png";
				copy($value['image'], $new_link);
				$stmt = $connexion->prepare("UPDATE category SET image='".$value['id'].".png"."' WHERE id='".$value['id']."'");
		    	$stmt->execute();
			}
		}
	}

	$sql = "SELECT * FROM critere";
	$result = $connexion->query($sql);

	$rows = $result->fetchAll();
	$num_rows = count($rows);

	if($num_rows > 0){
		//var_dump($rows);
		$local_path = "../web/uploads/img/critere_images/";
		foreach ($rows as $value) {
			if($value['image'] != $value['id'].".png")
			{
				//var_dump($value['image']);
				$new_link = $local_path.$value['id'].".png";
				copy($value['image'], $new_link);

				$stmt = $connexion->prepare("UPDATE critere SET image='".$value['id'].".png"."' WHERE id='".$value['id']."'");
		    	$stmt->execute();
			}
		}
	}

	$sql = "SELECT * FROM museum_image";
	$result = $connexion->query($sql);

	$rows = $result->fetchAll();
	$num_rows = count($rows);

	if($num_rows > 0){
		$local_path = "../web/uploads/img/museum_images/";
		foreach ($rows as $value) {
			if($value['image'] != $value['id']."_".$value['museum_id'].".jpg")
			{
				$new_link = $value['id']."_".$value['museum_id'].".jpg";
				$store_file = $local_path.$new_link;
				copy($value['image'], $store_file);
				echo $new_link;
				$stmt = $connexion->prepare("UPDATE museum_image SET image='".$new_link."' WHERE id='".$value['id']."'");
		    	$stmt->execute();
			}
		}
	}

	$sql = "SELECT * FROM exposition_image";
	$result = $connexion->query($sql);

	$rows = $result->fetchAll();
	$num_rows = count($rows);
	if($num_rows > 0){
		//var_dump($rows);
		$i=1;
		$local_path = "../web/uploads/img/exposition_images/";
		foreach ($rows as $value) {
			//var_dump($value['image']);
			if($value['image'] != $value['id']."_".$value['exposition_id'].".jpg")
			{
				$new_link = $value['id']."_".$value['exposition_id'].".jpg";
				$store_file = $local_path.$new_link;
				copy($value['image'], $store_file);
				echo $new_link;
				$stmt = $connexion->prepare("UPDATE exposition_image SET image='".$new_link."' WHERE id='".$value['id']."'");
		    	$stmt->execute();
		    	$i++;
			}
		}
	}

?>