<?php 
	// Admin user variables
	$admin_id = 0;
	$isEditingUser = false;
	$username = "";
	$email = "";

	// Topics variables
	$topic_id = 0;
	$isEditingTopic = false;
	$topic_name = "";

	// general variables
	$errors = [];

	/* - - - - - - - - - - 
	-
	-  Admin users actions
	-
	- - - - - - - - - - -*/

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

	// if user clicks the update admin button
	if (isset($_POST['update_admin'])) {
		updateAdmin($_POST);
	}

	// if user clicks the Delete admin button
	if (isset($_GET['delete-admin'])) {
		$admin_id = $_GET['delete-admin'];
		deleteAdmin($admin_id);
	}



	/* - - - - - - - - - - 
	-
	-  Topic actions
	-
	- - - - - - - - - - -*/

	// if user clicks the create topic button
	if (isset($_POST['create_topic'])) {
		createTopic($_POST);
	}

	// if user clicks the Edit topic button
	if (isset($_GET['edit-topic'])) {
		$isEditingTopic = true;
		$topic_id = $_GET['edit-topic'];
		editTopic($topic_id);
	}

	// if user clicks the update topic button
	if (isset($_POST['update_topic'])) {
		updateTopic($_POST);
	}

	// if user clicks the Delete topic button
	if (isset($_GET['delete-topic'])) {
		$topic_id = $_GET['delete-topic'];
		deleteTopic($topic_id);
	}


	/* - - - - - - - - - - - -
	-
	-  Admin users functions
	-
	- - - - - - - - - - - - -*/

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
		global $conn, $roles;

		$sql = "SELECT * FROM users";
		$result = mysqli_query($conn, $sql);
		$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $users;
	}



	/* - - - - - - - - - - 
	-
	-  Topics functions
	-
	- - - - - - - - - - -*/

	// get all topics from DB
	function getAllTopics()
	{
		global $conn;
		$sql = "SELECT * FROM topics";

		$result = mysqli_query($conn, $sql);
		$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $topics;
	}

	function createTopic($request_values)
	{
		global $conn, $errors, $topic_name;

		$topic_name = esc($request_values['topic_name']);

		// create slug: if topic is "Life Advice", return "life-advice" as slug
		$topic_slug = makeSlug($topic_name);

		// validate form
		if (empty($topic_name)) { 
			array_push($errors, "Topic name required"); 
		}

		// Ensure that no topic is saved twice. 
		$topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";

		$result = mysqli_query($conn, $topic_check_query);

		if (mysqli_num_rows($result) > 0) { // if topic exists
			array_push($errors, "Topic already exists");
		}

		// register topic if there are no errors in the form
		if (count($errors) == 0) {
			$query = "INSERT INTO topics (name, slug) 
					  VALUES('$topic_name', '$topic_slug')";
			mysqli_query($conn, $query);


			$_SESSION['message'] = "Topic created successfully";
			header('location: topics.php');
			exit(0);
		}
	}


	/* * * * * * * * * * * * * * * * * * * * *
	* - Takes topic id as parameter
	* - Fetches the topic from database
	* - sets topic fields on form for editing
	* * * * * * * * * * * * * * * * * * * * * */
	function editTopic($topic_id)
	{
		global $conn, $topic_name, $isEditingTopic, $topic_id;

		$sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$topic = mysqli_fetch_assoc($result);

		// set form values ($topic_name) on the form to be updated
		$topic_name = $topic['name'];
	}

	function updateTopic($request_values)
	{
		global $conn, $errors, $topic_name, $topic_id;

		$topic_name = esc($request_values['topic_name']);
		$topic_id = esc($request_values['topic_id']);

		// create slug: if topic is "Life Advice", return "life-advice" as slug
		$topic_slug = makeSlug($topic_name);

		// validate form
		if (empty($topic_name)) { 
			array_push($errors, "Topic name required"); 
		}

		// register topic if there are no errors in the form
		if (count($errors) == 0) {
			$query = "UPDATE topics SET name='$topic_name', slug='$topic_slug' WHERE id=$topic_id";
			mysqli_query($conn, $query);


			$_SESSION['message'] = "Topic updated successfully";
			header('location: topics.php');
			exit(0);
		}
	}


	// delete topic 
	function deleteTopic($topic_id)
	{
		global $conn;
		$sql = "DELETE FROM topics WHERE id=$topic_id";

		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "Topic successfully deleted";
			header("location: topics.php");
			exit(0);
		}
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

	function makeSlug(String $string)
	{
		$string = strtolower($string);
		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

		return $slug;
	}


?>