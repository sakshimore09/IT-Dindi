<?php 
	// connection of database
	include_once('dbUtil/connect.php');

	if(isset($_POST['cityID'])) {
		$query = "SELECT * FROM areamaster WHERE cityID = '".$_POST['cityID']."'";
		$result = mysqli_query($conn, $query);
		$areaID = mysqli_fetch_all($result, MYSQLI_ASSOC);
		echo json_encode($areaID);
	}
		function loadCity() {
			global $conn;
			$query = "SELECT * FROM citymaster";
			$result = mysqli_query($conn, $query);
			$cityID = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
			return $cityID;
		}
    

 ?>  
