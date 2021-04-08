<?php
	$host = "localhost"; 
	$user = "azb25";
	$password = "4187735";
	$dbname = "azb25";
	$connect = mysqli_connect($host, $user, $password, $dbname);
	if(mysqli_connect_errno()){
		die("Database connection failed: ".
			mysqli_connect_error() . 
			" (" . mysqli_connect_errno(). ")"
			);
	}
?>
