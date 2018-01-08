<?php 

	// variable declaration
	$username = "";
	$email    = "";
	$errors = array(); 

	// REGISTER USER
	if (isset($_POST['reg_user'])) {
	  // receive all input values from the form
	  $username = esc($_POST['username']);
	  $email = esc($_POST['email']);
	  $password_1 = esc($_POST['password_1']);
	  $password_2 = esc($_POST['password_2']);


	  // form validation: ensure that the form is correctly filled
	  if (empty($username)) { 
	  	array_push($errors, "Uhmm...We gonna need your username"); 
	  }
	  if (empty($email)) { 
	  	array_push($errors, "Oops.. Email is missing"); 
	  }
	  if (empty($password_1)) { 
	  	array_push($errors, "uh-oh you forgot the password"); 
	  }
	  if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	  }

	  // Ensure no user registers twice
	 //  $sql_name = "SELECT * FROM users WHERE username='$username'";
	 //  $sql_email = "SELECT * FROM users WHERE email='$email'";

	 //  $result_name = mysqli_query($conn, $sql_name);
	 //  $result_email = mysqli_query($conn, $sql_email);

	 //  if (mysqli_num_rows($result_name) > 0) {
		// array_push($errors, "Username already exists");
	 //  }
	 //  if (mysqli_num_rows($result_email) > 0) {
		// array_push($errors, "Email already exits");
	 //  }

	  // register user if there are no errors in the form
	  if (count($errors) == 0) {
	  	$password = md5($password_1);//encrypt the password before saving in the database
	  	$query = "INSERT INTO users (username, email, password, created_at, updated_at) 
	  			  VALUES('$username', '$email', '$password', now(), now())";
	  	mysqli_query($conn, $query);

	  	$reg_user_id = mysqli_insert_id($conn); // get id of created user

	  	$_SESSION['user'] = getUserById($reg_user_id); // put logged in user into session array
	  	
	  	$_SESSION['message'] = "You are now logged in";
	  	header('location: index.php');
	  }
	}



	// escape value from form
	function esc(String $value)
	{	
		// bring the global db connect object into function
		global $conn;

		$val = trim($value); // remove empty space sorrounding string
		$val = mysqli_real_escape_string($conn, $value);

		return $val;
	}

	// Get user info from user id
	function getUserById($id)
	{
		global $conn;
		$sql = "SELECT * FROM users WHERE id=$id LIMIT 1";

		$result = mysqli_query($conn, $sql);
		$user = mysqli_fetch_assoc($result);


		return $user;
	}

?>