<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/includes/admin_functions.php'); ?>

<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

<?php 
	// Get all roles from DB
	$roles = getAllRoles();	
?>

	<title>Admin | Manage Roles</title>
</head>
<body>

	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>

	<div class="container content">
		
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>

		<!-- Middle form - to create and edit -->
		<div class="action">
			<h1 class="page-title">Create/Edit Roles</h1>

			<form method="post" action="<?php echo BASE_URL . 'admin/roles.php'; ?>" >

				<!-- validation errors for the form -->
				<?php include(ROOT_PATH . '/includes/public/errors.php') ?>

				<!-- if editing role, the id is required to identify that role -->
				<?php if ($isEditingRole === true): ?>
					<input type="hidden" name="role_id" value="<?php echo $role_id; ?>">
				<?php endif ?>

				<input 
					type="text"
					name="role_name"
					value="<?php echo $role_name; ?>" 
					placeholder="Role">

				
				<!-- if editing user, display the update button instead of create button -->
				<?php if ($isEditingRole === true): ?> 
					<button type="submit" class="btn" name="update_role">UPDATE</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_role">Save Role</button>
				<?php endif ?>

			</form>
		</div>
		<!-- // Middle form - to create and edit -->

		<!-- Display records from DB-->
		<div class="table-div">

			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/public/messages.php') ?>

			<?php if (empty($roles)): ?>
				<h1>No roles in the database.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Role Name</th>
						<th colspan="2">Action</th>
					</thead>
					<tbody>
					<?php foreach ($roles as $key => $role): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td><?php echo $role['name']; ?></td>
							<td>
								<a class="btn edit"
									href="roles.php?edit-role=<?php echo $role['id'] ?>">
									edit
								</a>
							</td>
							<td>
								<a class="btn delete"								
									href="roles.php?delete-role=<?php echo $role['id'] ?>">delete
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
