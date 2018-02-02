<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/includes/admin_functions.php'); ?>

<?php 



// //get info from database
// $result = mysql_query($conn, "SELECT COLUMN_TYPE FROM information_schema.`COLUMNS` WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'role'");

//     if (!$result) {
//         echo("<p>Error performing query:" . mysql_error() . "<p>");
//         exit();
//     }

// //put data in menu
// while ( $row = mysql_fetch_array($result)) {
//     $my_column = $row["role"];
//     echo ($my_column);
//     echo "<option name='$my_column' value='$my_column'>$my_column</option>";
// }

				


?>

<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

<?php 
	// Get all admin roles from DB
	$roles = getAdminRoles();	

	// Get all admin users from DB
	$admins = getAdminUsers();	
?>

	<title>Admin | Manage users</title>
</head>
<body>

	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>

	<div class="container content">
		
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Middle form - to create and edit  -->
		<div class="action">
			<h1 class="page-title">Create/Edit Admin User</h1>

			<form method="post" action="<?php echo BASE_URL . 'admin/users.php'; ?>" >

				<!-- validation errors for the form -->
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>

				<!-- if editing user, the id is required to identify that user -->
				<?php if ($isEditingUser === true): ?>
					<input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
				<?php endif ?>

				<input 
					type="text"
					name="username"
					value="<?php echo $username; ?>" 
					placeholder="Username">

				<input 
					type="email"
					name="email"
					value="<?php echo $email ?>" 
					placeholder="Email">

				<input 
					type="password"
					name="password"
					placeholder="Password">

				<input 
					type="password"
					name="passwordConfirmation"
					placeholder="Password confirmation">

				<select name="role_id">
					<option value="" selected disabled>Assign role</option>
					<?php foreach ($roles as $role): ?>
						<option value="<?php echo $role['id']; ?>">
							<?php echo $role['name']; ?>
						</option>
					<?php endforeach ?>
				</select>
				
				<!-- if editing user, display the update button instead of create button -->
				<?php if ($isEditingUser === true): ?> 
					<button type="submit" class="btn" name="update_admin">UPDATE</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_admin">Save User</button>
				<?php endif ?>

			</form>
		</div>
		<!-- // Middle form - to create and edit -->

		<!-- Display records from DB-->
		<div class="table-div">

			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/public/messages.php') ?>

			<?php if (empty($admins)): ?>
				<h1>No admins in the database.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Admin</th>
						<th>Role</th>
						<th colspan="2">Action</th>
					</thead>
					<tbody>
					<?php foreach ($admins as $key => $admin): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td>
								<?php echo $admin['username']; ?>, &nbsp;
								<?php echo $admin['email']; ?>	
							</td>
							<td><?php echo $admin['role']; ?></td>
							<td>
								<a class="fa fa-pencil btn edit"
									href="users.php?edit-admin=<?php echo $admin['id'] ?>">
								</a>
							</td>
							<td>
								<a class="fa fa-trash btn delete"								href="users.php?delete-admin=<?php echo $admin['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>
</html>
