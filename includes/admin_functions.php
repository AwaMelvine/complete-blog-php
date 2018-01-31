<?php 
	$admin_id = 0;
	$username = "";
	$email = "";
	$isEditingUser = false;
	$errors = [];

	// if user clicks the create admin button
	if (isset($_POST['create_admin'])) {
		createAdmin($_POST);
	}

	// if user clicks the Edit admin button
	if (isset($_GET['edit-admin'])) {
		$isEditingUser = true;
		$admin_id = $_GET['edit-admin'];
		editAdmin($admin_id);
	}

	// if user clicks the create admin button
	if (isset($_POST['update_admin'])) {
		updateAdmin($_POST);
	}

	// if user clicks the Delete admin button
	if (isset($_GET['delete-admin'])) {
		$admin_id = $_GET['delete-admin'];
		deleteAdmin($admin_id);
	}


	/* * * * * * * * * * * * * * * * * * * * * * *
	* - Receives new admin data from form
	* - Create new admin user
	* - Returns all admin users with their roles 
	* * * * * * * * * * * * * * * * * * * * * * */
	function createAdmin($request_values)
	{
		global $conn, $errors, $username, $email;


		$username = esc($request_values['username']);
		$email = esc($request_values['email']);
		$password = esc($request_values['password']);
		$passwordConfirmation = esc($request_values['passwordConfirmation']);

		if(isset($request_values['role_id'])){
			$role_id = $request_values['role_id'];
		}

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { 
			array_push($errors, "Uhmm...We gonna need the username"); 
		}
		if (empty($email)) { 
			array_push($errors, "Oops.. Email is missing"); 
		}
		if (empty($role_id)) { 
			array_push($errors, "This is an admin user. So role is required"); 
		}
		if (empty($password)) { 
			array_push($errors, "uh-oh you forgot the password"); 
		}
		if ($password != $passwordConfirmation) {
			array_push($errors, "The two passwords do not match");
		}

		// Ensure that no user is registered twice. 
		// the email and usernames should be unique
		$user_check_query = "SELECT * FROM users WHERE username='$username' 
								OR email='$email' LIMIT 1";

		$result = mysqli_query($conn, $user_check_query);
		$user = mysqli_fetch_assoc($result);

		if ($user) { // if user exists
			if ($user['username'] === $username) {
			  array_push($errors, "Username already exists");
			}

			if ($user['email'] === $email) {
			  array_push($errors, "Email already exists");
			}
		}

		// echo "<pre>", var_dump($errors), "</pre>";
		// die();


		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password);//encrypt the password before saving in the database
			$query = "INSERT INTO users (username, email, password, created_at, updated_at) 
					  VALUES('$username', '$email', '$password', now(), now())";
			mysqli_query($conn, $query);

			// get id of created user
			$reg_user_id = mysqli_insert_id($conn);

			// Assign role to admin user
			if (isset($role_id)) {
				$role_sql = "INSERT INTO role_user (user_id, role_id) 
								VALUES ($reg_user_id, $role_id)";
				mysqli_query($conn, $role_sql);
			}

			$_SESSION['message'] = "Admin user created successfully";
			header('location: users.php');
			exit(0);
		}

	}

	/* * * * * * * * * * * * * * * * * * * * *
	* - Takes admin id as parameter
	* - Fetches the admin from database
	* - sets admin fields on form for editing
	* * * * * * * * * * * * * * * * * * * * * */
	function editAdmin($admin_id)
	{
		global $conn, $username, $isEditingUser, $admin_id, $email;

		$sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$admin = mysqli_fetch_assoc($result);

		// set form values ($username and $email) on the form to be updated
		$username = $admin['username'];
		$email = $admin['email'];
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	* - Receives admin request from form and updates in database
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	function updateAdmin($request_values)
	{
		global $conn, $errors, $username, $isEditingUser, $admin_id, $email;

		// get id of the admin to be updated
		$admin_id = $request_values['admin_id'];

		// set edit state to false
		$isEditingUser = false;


		$username = esc($request_values['username']);
		$email = esc($request_values['email']);
		$password = esc($request_values['password']);
		$passwordConfirmation = esc($request_values['passwordConfirmation']);

		if(isset($request_values['role_id'])){
			$role_id = $request_values['role_id'];
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			//encrypt the password (security purposes)
			$password = md5($password);

			$query = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id=$admin_id";
			mysqli_query($conn, $query);

			// Assign role to admin user
			if (isset($role_id)) {
				$role_sql = "UPDATE role_user SET role_id=$role_id WHERE user_id=$admin_id";
				mysqli_query($conn, $role_sql);
			}

			$_SESSION['message'] = "Admin user updated successfully";
			header('location: users.php');
			exit(0);
		}
	}

	// delete admin user 
	function deleteAdmin($admin_id)
	{
		global $conn;
		$sql = "DELETE FROM users WHERE id=$admin_id";

		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "User successfully deleted";
			header("location: users.php");
			exit(0);
		}
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
	* - Returns all admin users and their corresponding roles
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	function getAdminUsers()
	{
		global $conn;

		$sql = "SELECT * FROM users";
		$result = mysqli_query($conn, $sql);
		$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$finalList = array();

		foreach ($users as $user) {
			// modify single user by attaching role to it
			$user['role'] = getRoleByUserId($user['id']);

			// push modified user unto final list of users array
			array_push($finalList, $user);
		}

		// echo "<pre>", var_dump($finalList), "</pre>";
		// die();

		return $finalList;
	}


	/* * * * * * * * * * * * * *
	* - Returns all admin roles
	* * * * * * * * * * * * * */
	function getAdminRoles()
	{
		global $conn;

		$sql = "SELECT * FROM roles";
		$result = mysqli_query($conn, $sql);
		$roles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $roles;
	}

	/* * * * * * * * * * * * * * *
	* - Get admin role by their id
	* * * * * * * * * * * * * * * */
	function getRoleByUserId($user_id)
	{
		global $conn;

		$sql = "SELECT name FROM roles WHERE id=
					(SELECT role_id FROM role_user WHERE user_id=$user_id ) LIMIT 1";

		$result = mysqli_query($conn, $sql);
		$roles = mysqli_fetch_assoc($result);

		return $roles['name'];
	}

	/* * * * * * * * * * * * * * * * * * * * *
	* - Escapes form submitted value, hence, 
	*   preventing SQL injection
	* * * * * * * * * * * * * * * * * * * * * */
	function esc(String $value)
	{
		// bring the global db connect object into function
		global $conn;

		// remove empty space sorrounding string
		$val = trim($value); 
		$val = mysqli_real_escape_string($conn, $value);

		return $val;
	}


?>