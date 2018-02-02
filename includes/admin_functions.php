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

	// Role variables
	$role_id = 0;
	$isEditingRole = false;
	$role_name = "";

	// Post variables
	// $post_id = 0;
	// $isEditingPost = false;
	// $published = 0;
	// $title = "";
	// $post_slug = "";
	// $body = "";
	// $featured_image = "";
	// $post_topic = "";

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

	/* - - - - - - - - - - 
	-
	-  Role actions
	-
	- - - - - - - - - - -*/

	// if user clicks the create role button
	if (isset($_POST['create_role'])) {
		createRole($_POST);
	}

	// if user clicks the Edit role button
	if (isset($_GET['edit-role'])) {
		$isEditingRole = true;
		$role_id = $_GET['edit-role'];
		editRole($role_id);
	}

	// if user clicks the update role button
	if (isset($_POST['update_role'])) {
		updateRole($_POST);
	}

	// if user clicks the Delete role button
	if (isset($_GET['delete-role'])) {
		$role_id = $_GET['delete-role'];
		deleteRole($role_id);
	}


	/* - - - - - - - - - - 
	-
	-  Post actions
	-
	- - - - - - - - - - -*/

	// if user clicks the create post button
	// if (isset($_POST['create_post'])) {
	// 	createPost($_POST);
	// }

	// // if user clicks the Edit post button
	// if (isset($_GET['edit-post'])) {
	// 	$isEditingPost = true;
	// 	$post_id = $_GET['edit-post'];
	// 	editPost($post_id);
	// }

	// // if user clicks the update post button
	// if (isset($_POST['update_post'])) {
	// 	updatePost($_POST);
	// }

	// // if user clicks the Delete post button
	// if (isset($_GET['delete-post'])) {
	// 	$post_id = $_GET['delete-post'];
	// 	deletePost($post_id);
	// }
	// // if user clicks the publish post button
	// if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
	// 	$message = "";
	// 	if (isset($_GET['publish'])) {
	// 		$message = "Post published successfully";
	// 		$post_id = $_GET['publish'];
	// 	} else if (isset($_GET['unpublish'])) {
	// 		$message = "Post successfully unpublished";
	// 		$post_id = $_GET['unpublish'];
	// 	}
	// 	togglePublishPost($post_id, $message);
	// }




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

		return $finalList;
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



	/* - - - - - - - - - - 
	-
	-  Role functions
	-
	- - - - - - - - - - -*/

	// get all roles from DB
	function getAllRoles()
	{
		global $conn;
		$sql = "SELECT * FROM roles";

		$result = mysqli_query($conn, $sql);
		$roles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $roles;
	}

	function createRole($request_values)
	{
		global $conn, $errors, $role_name;

		$role_name = esc($request_values['role_name']);

		// create slug: if role is "Super Admin", return "super-admin" as slug
		$role_slug = makeSlug($role_name);

		// validate form
		if (empty($role_name)) { 
			array_push($errors, "Role name required"); 
		}

		// Ensure that no role is saved twice. 
		$role_check_query = "SELECT * FROM roles WHERE slug='$role_slug' LIMIT 1";

		$result = mysqli_query($conn, $role_check_query);

		if (mysqli_num_rows($result) > 0) { // if role exists
			array_push($errors, "Role already exists");
		}

		// register topic if there are no errors in the form
		if (count($errors) == 0) {
			$query = "INSERT INTO roles (name, slug) 
					  VALUES('$role_name', '$role_slug')";
			mysqli_query($conn, $query);


			$_SESSION['message'] = "Role created successfully";
			header('location: roles.php');
			exit(0);
		}
	}


	/* * * * * * * * * * * * * * * * * * * * *
	* - Takes role id as parameter
	* - Fetches the role from database
	* - sets role fields on form for editing
	* * * * * * * * * * * * * * * * * * * * * */
	function editRole($role_id)
	{
		global $conn, $role_name, $isEditingRole, $role_id;

		$sql = "SELECT * FROM roles WHERE id=$role_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$role = mysqli_fetch_assoc($result);

		// set form values ($role_name) on the form to be updated
		$role_name = $role['name'];
	}

	function updateRole($request_values)
	{
		global $conn, $errors, $role_name, $role_id;

		$role_name = esc($request_values['role_name']);
		$role_id = esc($request_values['role_id']);

		// create slug: if role is "Super Admin", return "super-admin" as slug
		$role_slug = makeSlug($role_name);


		// validate form
		if (empty($role_name)) { 
			array_push($errors, "Role name required"); 
		}

		// register role if there are no errors in the form
		if (count($errors) == 0) {
			$query = "UPDATE roles SET name='$role_name', slug='$role_slug' WHERE id=$role_id";
			mysqli_query($conn, $query);

			$_SESSION['message'] = "Role updated successfully";
			header('location: roles.php');
			exit(0);
		}
	}


	// delete admin role 
	function deleteRole($role_id)
	{
		global $conn;
		$sql = "DELETE FROM roles WHERE id=$role_id";

		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "Role successfully deleted";
			header("location: roles.php");
			exit(0);
		}
	}


	// /* - - - - - - - - - - 
	// -
	// -  Post functions
	// -
	// - - - - - - - - - - -*/

	// // get all posts from DB
	// function getAllPosts()
	// {
	// 	global $conn;
	// 	$sql = "SELECT * FROM posts";

	// 	$result = mysqli_query($conn, $sql);
	// 	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	// 	return $posts;
	// }

	// function createPost($request_values)
	// {
	// 	global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;

	// 	$title = esc($request_values['title']);
	// 	$featured_image = esc($request_values['featured_image']);
	// 	$body = esc($request_values['body']);

	// 	if (isset($request_values['topic_id'])) {
	// 		$topic_id = esc($request_values['topic_id']);
	// 	}

	// 	// create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
	// 	$post_slug = makeSlug($title);

	// 	// validate form
	// 	if (empty($title)) { 
	// 		array_push($errors, "Post title is required"); 
	// 	}
	// 	// validate form
	// 	if (empty($featured_image)) { 
	// 		array_push($errors, "Featured image title is required"); 
	// 	}
	// 	// validate form
	// 	if (empty($body)) { 
	// 		array_push($errors, "Post body is required"); 
	// 	}
	// 	// validate form
	// 	if (empty($topic_id)) { 
	// 		array_push($errors, "Post topic is required"); 
	// 	}

	// 	// Ensure that no post is saved twice. 
	// 	$post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";

	// 	$result = mysqli_query($conn, $post_check_query);

	// 	if (mysqli_num_rows($result) > 0) { // if post exists
	// 		array_push($errors, "A post already exists with that title.");
	// 	}

	// 	// create post if there are no errors in the form
	// 	if (count($errors) == 0) {
	// 		$query = "INSERT INTO posts (user_id, title, slug, views, image, body, published, created_at, updated_at) VALUES(1, '$title', '$post_slug', 0, '$featured_image', '$body', $published, now(), now())";

	// 		mysqli_query($conn, $query);

	// 		$_SESSION['message'] = "Post created successfully";
	// 		header('location: posts.php');
	// 		exit(0);
	// 	}
	// }


	//  * * * * * * * * * * * * * * * * * * * *
	// * - Takes post id as parameter
	// * - Fetches the post from database
	// * - sets post fields on form for editing
	// * * * * * * * * * * * * * * * * * * * * * 
	// function editPost($role_id)
	// {
	// 	global $conn, $title, $post_slug, $body, $isEditingPost, $post_id;

	// 	$sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
	// 	$result = mysqli_query($conn, $sql);
	// 	$post = mysqli_fetch_assoc($result);

	// 	// set form values on the form to be updated
	// 	$title = $post['title'];
	// 	$body = $post['body'];
	// }

	// function updatePost($request_values)
	// {
	// 	global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

	// 	$title = esc($request_values['title']);
	// 	$featured_image = esc($request_values['featured_image']);
	// 	$body = esc($request_values['body']);
	// 	$post_id = esc($request_values['post_id']);

	// 	if (isset($request_values['topic_id'])) {
	// 		$topic_id = esc($request_values['topic_id']);
	// 	}

	// 	// create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
	// 	$post_slug = makeSlug($title);

	// 	// validate form
	// 	if (empty($title)) { 
	// 		array_push($errors, "Post title is required"); 
	// 	}
	// 	// validate form
	// 	if (empty($body)) { 
	// 		array_push($errors, "Post body is required"); 
	// 	}

	// 	// register topic if there are no errors in the form
	// 	if (count($errors) == 0) {
	// 		$query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
			
	// 		mysqli_query($conn, $query);

	// 		$_SESSION['message'] = "Post updated successfully";
	// 		header('location: posts.php');
	// 		exit(0);
	// 	}
	// }


	// // delete blog post
	// function deletePost($post_id)
	// {
	// 	global $conn;
	// 	$sql = "DELETE FROM posts WHERE id=$post_id";

	// 	if (mysqli_query($conn, $sql)) {
	// 		$_SESSION['message'] = "Post successfully deleted";
	// 		header("location: posts.php");
	// 		exit(0);
	// 	}
	// }

	// // delete blog post
	// function togglePublishPost($post_id, $message)
	// {
	// 	global $conn;
	// 	$sql = "UPDATE posts SET published=!published WHERE id=$post_id";
		
	// 	if (mysqli_query($conn, $sql)) {
	// 		$_SESSION['message'] = $message;
	// 		header("location: posts.php");
	// 		exit(0);
	// 	}
	// }




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

	function makeSlug(String $string)
	{
		$string = strtolower($string);
		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

		return $slug;
	}


?>