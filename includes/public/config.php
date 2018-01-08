<?php 
	
	// connect to database
	$conn = mysqli_connect("localhost", "root", "", "blog");

	if (!$conn) {
		die("Error connecting to database: " . mysqli_connect_error());
	}

	define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/youtube/complete-blog-php");

?>