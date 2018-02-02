<?php include('config.php'); ?>

<?php include('includes/all_functions.php'); ?>

<?php include('includes/public/head_section.php'); ?>

<?php 
	
	if (isset($_GET['topic'])) {
		$topic_id = $_GET['topic'];

		$posts = getPublishedPostsByTopic($topic_id);
		
	}

	// echo "<pre>", var_dump($posts) , "</pre>";
	// die();

?>

<title>LifeBlog | Home </title>

</head>
<body>

<div class="container">

<!-- Navbar -->
	<?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
<!-- // Navbar -->

<!-- Messages -->
	<?php include( ROOT_PATH . '/includes/public/messages.php'); ?>
<!-- // Messages -->

<!-- content -->
<div class="content">
	<h2 class="content-title">
		Articles on <u><?php echo getTopicNameById($topic_id); ?></u>
	</h2>
	<hr>

	<?php foreach ($posts as $post): ?>
		<div class="post" style="margin-left: 0px;">
			<img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">

			<?php if (isset($post['topic']['name'])): ?>
				<a 
					href="posts_by_topics.php?slug=<?php echo $post['topic']['slug'] ?>" 
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
