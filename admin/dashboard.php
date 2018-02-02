<?php  include('../config.php'); ?>

	<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

	<title>Admin | Dashboard</title>
</head>
<body>
	<div class="header">
		<div class="logo">
			<a href="<?php echo BASE_URL .'admin/dashboard.php' ?>">
				<h1>LifeBlog - Admin</h1>
			</a>
		</div>
		<div class="user-info">
			<span>Awa</span> &nbsp; &nbsp; <a href="#" class="logout-btn">logout</a>
		</div>
	</div>

	<div class="container dashboard">
		<h1>Welcome</h1>

		<div class="stats">
			<a href="users.php" class="first">
				<span>43</span> <br>
				<span>Newly registered users</span>
			</a>
			<a href="posts.php">
				<span>43</span> <br>
				<span>Published posts</span>
			</a>
			<a>
				<span>43</span> <br>
				<span>Published comments</span>
			</a>
		</div>

		<br><br><br>

		<div class="buttons">
			<a href="users.php">Add Users</a>
			<a href="posts.php">Add Posts</a>
		</div>

	</div>


</body>
</html>
