<?php include('includes/public/config.php'); ?>
<?php include('includes/public/head_section.php'); ?>

<title>LifeBlog | Home </title>

</head>
<body>

<div class="container">

<!-- Navbar -->
	<?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
<!-- // Navbar -->

<!-- Banner -->
	<?php include( ROOT_PATH . '/includes/public/banner.php'); ?>
<!-- // Banner -->

<!-- Banner -->
	<?php include( ROOT_PATH . '/includes/public/messages.php'); ?>
<!-- // Banner -->

<!-- content -->
	<div class="content">
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


<!-- Footer -->
	<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>
<!-- // Footer -->

</body>
</html>
