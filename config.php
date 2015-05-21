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
?>