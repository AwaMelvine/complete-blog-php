<?php 
	function getPost($slug){

		global $conn;

		// Get single post slug
		$post_slug = $_GET['post-slug'];

		$sql = "SELECT * FROM posts WHERE slug='$post_slug' AND published=true";
		$result = mysqli_query($conn, $sql);

		// fetch query results as associative array.
		$post = mysqli_fetch_assoc($result);

		// get the topic to which this post belongs
		$post['topic'] = getPostTopic($post['id']);

		// echo "<pre>", var_dump($post) , "</pre>";
		// die();
		return $post;
	}


	/* * * * * * * * * * * * * 
	*
	*	POSTS FOR INDEX PAGE
	*
	* * * * * * * * * * * * * */
	
	/**
	* This function returns all published posts
	*/
	function getPublishedPosts() {
		global $conn;

		$sql = "SELECT * FROM posts WHERE published=true";
		$result = mysqli_query($conn, $sql);

		// fetch all posts as an associative array called $posts
		$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$final_posts = array();

		foreach ($posts as $post) {
			$post['topic'] = getPostTopic($post['id']); 
			array_push($final_posts, $post);
		}

		return $final_posts;
	}

	/**
	* This function returns the id, name and slug of a 
	* category in an array
	*/
	function getPostTopic($post_id){
		global $conn;

		$sql = "SELECT * FROM topics WHERE id=
				(SELECT topic_id FROM post_topic WHERE post_id=$post_id LIMIT 1) LIMIT 1";

		$result = mysqli_query($conn, $sql);
		$topic = mysqli_fetch_assoc($result);

		return $topic;
	}

	/**
	* This function returns the name and slug of a 
	* category in an array
	*/
	function getPublishedPostsByTopic($topic_id) {
		global $conn;

		$sql = "SELECT * FROM posts ps 
				WHERE ps.id IN 
				(SELECT pt.post_id FROM post_topic pt 
					WHERE pt.topic_id=$topic_id GROUP BY pt.post_id 
					HAVING COUNT(1) = 1)";

		$result = mysqli_query($conn, $sql);

		// fetch all posts as an associative array called $posts
		$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$final_posts = array();

		foreach ($posts as $post) {
			$post['topic'] = getPostTopic($post['id']); 
			array_push($final_posts, $post);
		}

		return $final_posts;
	}


	/* 
	*  Recieve topic id as parameter
	*  Returns topic name
	*/
	function getTopicNameById($id)
	{
		global $conn;

		$sql = "SELECT name FROM topics WHERE id=$id";
		$result = mysqli_query($conn, $sql);

		$topic = mysqli_fetch_assoc($result);

		return $topic['name'];
	}

	/* 
	*  Returns all topics
	*/
	function getAllTopics()
	{
		global $conn;

		$sql = "SELECT * FROM topics";
		$result = mysqli_query($conn, $sql);

		$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);

		// echo "<pre>", var_dump($topics), "</pre>";
		// die();

		return $topics;
	}


?>