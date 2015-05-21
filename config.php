<?php
	//Help with errors incase I need to see them
	if($_REQUEST['error']=='true'){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	//DB Connection
	$password = file_get_contents("db.password");
	$conn = new mysqli("localhost", "hcazblog", $password, "hcazblog_blog");
	if ($conn->connect_error){die("Connection failed: " . $conn->connect_error);}
	//Get global options from DB
	$result = $conn->query("SELECT `KEY`, `VALUE` FROM `GLOBAL`;");
	while($row = $result->fetch_assoc()) {
		$GLOBAL[$row['KEY']] = $row['VALUE'];
	}
	if ($GLOBAL['live']!="true"){$painerror = $GLOBAL['live']; require('themes/default/plainerror.php');die;}

	if ($_GET['sub']!=""){$sub = $_GET['sub'];}else{$sub = null;}
	if ($_GET['slug']!=""){$slug = $_GET['slug'];}else{$slug = null;}
?>