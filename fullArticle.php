<?php
session_start();
$articleid = $_SESSION['articleid'];
//retrieve user rank
if(ISSET($_SESSION['user'])){	
	$userid = $_SESSION['user'];
	$rank = $_SESSION['rank'];
} else {
	$rank = 0;
}
//set timezone
date_default_timezone_set('America/Toronto');
//retrieve article
require('db.php');
$query = $query = "SELECT articles.article_id, articles.title, articles.author_id, articles.date, articles.category_id, articles.article, users.username, users.picture, categories.category, articles.published FROM articles INNER JOIN `users` ON articles.author_id=users.user_id INNER JOIN categories ON articles.category_id=categories.category_id WHERE articles.article_id='$articleid' ORDER BY articles.date DESC";
$resultArt = mysqli_query($conn, $query) or DIE('bad query');
$articleArray = mysqli_fetch_array($resultArt);
//make date look nice
$date = preg_replace("/[^0-9,.]/", "", $articleArray['date']);
$date = date('l F d, Y', strtotime($date));
//insert comments
if(ISSET($_POST['submit'])){
	$userid = $_SESSION['user'];
	$time = date('Y-m-d G:i:s');
	$comment = $_POST['comment'];
	//fix ' and " for comment
	$comment = str_replace("'", "\'", $comment);
	$comment = str_replace('"', '\"', $comment);
	//db insert
	$query = "INSERT INTO `comments` (`comment_id`, `comment`, `user_id`, `article_id`, `time`, `published`) VALUES (NULL, '".$comment."', '".$userid."', '".$articleid."', '".$time."', '1')";
	$result = mysqli_query($conn, $query) or DIE('bad query inset comment');
}
//retrieve comments
$query = "SELECT comments.comment_id, comments.comment, comments.user_id, comments.article_id, comments.time, comments.published, users.username, users.picture FROM comments INNER JOIN users ON comments.user_id= users.user_id WHERE article_id='$articleid' ORDER BY comments.time DESC";
$resultCom = mysqli_query($conn, $query) or DIE('bad query comments');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	
    <!-- fancybox -->
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css" /> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>
    
    <!-- ckeditor -->
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Article</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.php" style="color:green">Blogh</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                   <li>
                        <?php
                        if(ISSET($_SESSION['user'])){
                            echo '<a href="profile.php" style="color:grey">View Profile</a>';
                        } else {
                            echo '<a href="login.php" style="color:grey">Login</a>';
                        }
                        ?>
                    </li>
                    <?php
                    if($rank >= 2) {
                        echo '<li>';
                        echo '<a href="write.php" style="color:grey">Write An Article</a>';
                        echo '</li>';
                    }
                    if($rank == 3) {
                        echo '<li>';
                        echo '<a href="admin/adminHome.php" style="color:grey">Admin Page</a>';
                        echo '</li>';
                    }
                    ?>
                    <li>
                        <?php 
                        if(ISSET($_SESSION['user'])){ 
                            echo '<a href="logout.php" style="color:grey">Logout</a>';     
                        } 
                        else { 
                            echo '<a href="register.php" style="color:grey">Register</a>';    
                        } 
                        ?>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/black.png')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1><?php echo $articleArray['title']; ?></h1>
                        <a href="<?php echo $articleArray['picture']; ?>" data-fancybox> <img src="<?php echo $articleArray['picture']; ?>" height= "100" width = "100"> </a>		
                        <span class="meta">By <?php echo $articleArray['username']; ?></span>
                   		<span class="meta"> <?php echo $date; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <?php
						if(ISSET($_SESSION['user'])){
							if($articleArray['author_id'] == $userid) {
								echo '<a href="userEditArticle.php"> edit your article</a><br>';
							}
						}
					?>
                    <p>
                    	<?php echo $articleArray['article']; ?>
                  	</p>
                </div>
            </div>
        </div>
    </article>

    <hr>

    <!-- Write Comments -->
    <h3> Comments: </h3>
    <?php
    if(ISSET($_SESSION['user'])){
	?>
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<textarea id="comment" name="comment"></textarea>
			<script>
	        	CKEDITOR.replace('comment');
	    	</script>
			<input type="submit" name="submit" value="Post">
		</form>
		<hr>
	<?php
	}
	?>
	
	<!-- Display Comments -->
	<?php
	while($row = mysqli_fetch_array($resultCom)){
		if($row['published'] == 1) { ?>	
			<a href="<?php echo $row['picture']; ?>" data-fancybox> <img src="<?php echo $row['picture']; ?>" height='30' width='30'> </a>
			<?php 
			//make time look nice
			$time = preg_replace("/[^0-9,.]/", "", $row['time']);
			$time = date('l F d, Y', strtotime($time));
			//display
			echo " ".$row['username']; ?>
			| Posted on <?php echo $time;
			if(ISSET($_SESSION['user'])){	
				if($row['user_id'] == $userid) {
					echo " | <a href='userEditComment.php?commentid=".$row['comment_id']."'>edit your comment</a>";
				}
			}
			?>
			<p> <?php echo $row['comment']; ?> </p>	
			<hr>
		<?php
		}
	}
	?>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                         <li>
                            <a href="https://twitter.com/g_go95?lang=en">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/griffin.cook.50">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/griffincook91/?hl=en">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-instagram fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Griffin Cook 2017</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>

</body>

</html>
