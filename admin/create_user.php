<?php  include('../includes/public/config.php'); ?>
<?php  include(ROOT_PATH . '/includes/admin_functions.php'); ?>

	<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

	<?php 
	
	$roles = getAdminRoles();	

	// echo "<pre>", var_dump($roles), "</pre>";
	// die();

	?>


	<title>Admin | Create Admin user</title>
</head>
<body>

	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>

	<div class="container content">
		
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<div class="action">
			<h1 class="page-title">Create Admin User</h1>

			<form method="post" action="create_user.php" >
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>
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
				 name="password_1"
				 placeholder="Password">

				<input 
				 type="password"
				 name="password_2"
				 placeholder="Password confirmation">

				<select name="role_id">
					<option value="" selected disabled>Assign role</option>
					<?php foreach ($roles as $role): ?>
						<option value="<?php echo $role['id']; ?>">
							<?php echo $role['name']; ?>
						</option>
					<?php endforeach ?>
				</select>

				<button type="submit" class="btn" name="reg_user">Save User</button>
			</form>

			

			<table class="table">
				<thead>
					<th>N</th>
					<th>Name</th>
					<th>Email</th>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>Awa</td>
						<td>melvineawa@gmail.com</td>
					</tr>
				</tbody>
			</table>





		</div>


	</div>


</body>
</html>
