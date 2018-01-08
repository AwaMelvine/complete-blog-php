<?php include('includes/public/head_section.php'); ?>

<title>LifeBlog | Sign up </title>

</head>
<body>

<div class="container">

<!-- Navbar -->
	<?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
<!-- // Navbar -->


<div style="width: 40%; margin: 20px auto;">
	<form method="post" action="register.php" >
		<h2>Register on LifeBlog</h2>
		<input type="text" name="username" value="" placeholder="Username">
		<input type="email" name="email" value="" placeholder="Email">
		<input type="password" name="password_1" placeholder="Password">
		<input type="password" name="password_2" placeholder="Password confirmation">
		<button type="submit" class="btn" name="reg_user">Register</button>
		<p>
			Already a member? <a href="login.php">Sign in</a>
		</p>
	</form>
</div>


	</div>
<!-- // content -->


</div>
<!-- // container -->

<div class="footer">
	<p>MyViewers &copy; <?php echo date('Y'); ?></p>
</div>

</body>
</html>
