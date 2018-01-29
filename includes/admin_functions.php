<?php 
	$username = "";
	$email = "";
	$errors = [];


	function getAdminRoles()
	{
		global $conn;

		$sql = "SELECT * FROM roles";
		$result = mysqli_query($conn, $sql);
		$roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
		return $roles;
	}

?>