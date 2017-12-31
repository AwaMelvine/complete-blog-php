<!DOCTYPE html>
<html>
<head>
<title>LifeBlog | Home</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Averia+Serif+Libre|Noto+Serif|Tangerine" rel="stylesheet">

<!-- Styling for public area -->
<link rel="stylesheet" href="static/css/public_styling.css">

</head>
<body>

<div class="container">

<!-- Navbar -->
<div class="navbar">
	<div class="logo_div">
		<h1>LifeBlog</h1>
	</div>
	<ul>
	  <li><a class="active" href="#home">Home</a></li>
	  <li><a href="#news">News</a></li>
	  <li><a href="#contact">Contact</a></li>
	  <li><a href="#about">About</a></li>
	</ul>
</div>
<!-- // Navbar -->

<!-- Banner -->
<div class="banner">
	<div class="welcome_msg">
		<h1>Today's Inspiration</h1>
		<p> 
		    One day your life <br> 
		    will flash before your eyes. <br> 
		    Make sure it's worth watching. <br>
			<span>~ Gerard Way</span>
		</p>
		<a href="#" class="btn">Join us!</a>
	</div>

	<div class="login_div">
		<form action="" >
			<h2>Login</h2>
			<input type="text" name="username" placeholder="Username">
			<input type="password" name="password"  placeholder="Password"> 
			<button class="btn" type="submit">Sign in</button>
		</form>
	</div>
</div>
<!-- // Banner -->


<!-- content -->
	<div class="content">

		<div class="message">
			Welcome to our site!
		</div>

		<h2 class="content-title">Recent Articles</h2>
		<hr>

		<div class="post" style="margin-left: 0px;">
			<img src="static/images/post_image1.jpg" class="post_image" alt="">
			<a href="#" class="btn category">Inspiration</a>
			<a href="#">
				<div class="post_info">
					<h3>One day your life will flash before your eyes. Make sure it's worth watching</h3>
					<div class="info">
						<span>Dec 25, 2017</span>
						<span class="read_more">Read more...</span>
					</div>
				</div>
			</a>
		</div>
		<div class="post" >
			<img src="static/images/post_image2.jpg" class="post_image" alt="">
			<a href="#" class="btn category">Self-Help</a>
			<a href="#">
				<div class="post_info">
					<h3>One day your life will flash before your eyes. Make sure it's worth watching</h3>
					<div class="info">
						<span>Dec 25, 2017</span>
						<span class="read_more">Read more...</span>
					</div>
				</div>
			</a>
		</div>
		<div class="post" style="margin-right: 0px;">
			<img src="static/images/post_image3.jpg" class="post_image" alt="">
			<a href="#" class="btn category">Reading</a>
			<a href="#">
				<div class="post_info">
					<h3>One day your life will flash before your eyes. Make sure it's worth watching</h3>
					<div class="info">
						<span>Dec 25, 2017</span>
						<span class="read_more">Read more...</span>
					</div>
				</div>
			</a>
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
