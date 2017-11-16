<?php
	$conn = new mysqli("localhost", "root", "", "azcambodia");
	if($conn->connect_error) {
		die("Connection fail :". $conn->connect_error);
	}
	
	if(isset($_POST['upload'])) {
		$file_name = $_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"]>0) {
			$file = fopen($file_name, "r");
			while(($emap_data = fgetcsv($file, 100000, ",")) != FALSE) {
				$sql = "INSERT INTO tblTest (name, gender) VALUES ('$emap_data[0]','$emap_data[1]')";
				$res = $conn->query($sql);
			}
			fclose($file);
			echo "CSV has been successfully imported!!!";
		} else {
			echo "Invalid import: Please Upload CSV file!!!";
		}
	}
?>