<?php  include('includes/public/config.php'); ?>
<?php  include('includes/public/registration_login.php'); ?>
<?php  include('includes/all_functions.php'); ?>
<?php 
	
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}

	$topics = getAllTopics();

	// echo "<pre>", var_dump($topics), "</pre>";
	// die();

?>

<?php include('includes/public/head_section.php'); ?>

<title> <?php echo $post['title'] ?> | LifeBlog</title>

</head>
<body>

<div class="container">

	<!-- Navbar -->
		<?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
	<!-- // Navbar -->
	
	<div class="content" >
		<!-- full post div -->
		<div class="full-post-div">
			<h2 class="post-title"><?php echo $post['title']; ?></h2>
			<div class="post-body-div">
				<?php echo $post['body']; ?>
			</div>
		</div>
		<!-- // full post div -->

		<!-- post sidebar -->
		<div class="post-sidebar">

			<div class="card">
				<div class="card-header">
					<h2>Topics</h2>
				</div>
				<div class="card-content">
					<?php foreach ($topics as $topic): ?>
						<a 
							href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
							<?php echo $topic['name']; ?>
						</a> 
					<?php endforeach ?>
				</div>
			</div>

		</div>
		<!-- // post sidebar -->

	</div>

</div>
<!-- // content -->


<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>
