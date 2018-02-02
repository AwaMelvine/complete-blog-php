<?php include('config.php'); ?>

<?php include('includes/public/registration_login.php'); ?>

<?php include('includes/all_functions.php'); ?>

<?php include('includes/public/head_section.php'); ?>

<?php 
	
	$posts = getPublishedPosts();

?>

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

<!-- Messages -->
	<?php include( ROOT_PATH . '/includes/public/messages.php'); ?>
<!-- // Messages -->

<!-- content -->
<div class="content">
	<h2 class="content-title">Recent Articles</h2>
	<hr>

	<?php foreach ($posts as $post): ?>
		<div class="post" style="margin-left: 0px;">
			<img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">

			<?php if (isset($post['topic']['name'])): ?>
				<a 
					href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $post['topic']['id'] ?>"
					class="btn category">
					<?php echo $post['topic']['name'] ?>
				</a>
			<?php endif ?>

			<a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
				<div class="post_info">
					<h3><?php echo $post['title'] ?></h3>
					<div class="info">
						<span><?php echo date("F j, Y ", strtotime($post["created_at"])); ?></span>
						<span class="read_more">Read more...</span>
					</div>
				</div>
			</a>
		</div>
	<?php endforeach ?>



</div>
<!-- // content -->


</div>
<!-- // container -->


<!-- Footer -->
	<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>
<!-- // Footer -->
