<?php  include('includes/public/config.php'); ?>
<?php  include('includes/public/registration_login.php'); ?>
<?php  include('includes/all_functions.php'); ?>
<?php 
	
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}

?>

<?php include('includes/public/head_section.php'); ?>

<title> <?php echo $post['title'] ?> | LifeBlog</title>

</head>
<body>

<div class="container">

<!-- Navbar -->
	<?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
<!-- // Navbar -->
	
<div class="content" style="min-height: 400px; border: 1px solid red; ">

	<div class="full-post">
		<h2 style="text-align: center;"><?php echo $post['slug']; ?></h2>
	</div>

</div>



</div>
<!-- // content -->


<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>
