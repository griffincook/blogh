<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//db
require('../db.php');
//cancel
if(ISSET($_POST['cancel'])){
	header("Location:adminComments.php");
}
//if update is pushed
if(ISSET($_POST['update'])){
	$commentid = $_SESSION['commentid'];
	$comment = $_POST['comment'];
	$time = $_POST['time'];
	$published = $_POST['published'];
	//replace ' and " for query
	$comment = str_replace("'", "\'", $comment);
	$comment = str_replace('"', '\"', $comment);
	//update db
	$query = "UPDATE `comments` SET `comment` = '$comment', `time` = '$time', `published` = '$published' WHERE `comments`.`comment_id` = '$commentid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	//send back
	header("Location:adminComments.php");
}
//get requested comment info
$commentid = $_SESSION['commentid'];
$query = "SELECT comments.comment_id, comments.comment, comments.user_id, users.username, comments.article_id, articles.title, comments.time, comments.published FROM comments INNER JOIN articles ON comments.article_id=articles.article_id INNER JOIN users ON comments.user_id=users.user_id WHERE comments.comment_id='".$commentid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$comment = $row['comment'];
$username = $row['username'];
$article = $row['title'];
$time = $row['time'];
$published = $row['published'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blogh</title>

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
                <a class="navbar-brand" href="../index.php" style="color:green">Blogh</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">                    
                    <li>
                    <a href="adminHome.php">Admin Home</a>
					</li>                    
                    <li>
                        <a href="adminArticles.php">View All Articles</a>                        
                    </li>
                    <li>
                        <a href="adminUsers.php">View All Users</a>                        
                    </li>
                    <li>
                        <a href="adminComments.php">View All Comments</a>                        
                    </li>
                    <li>
                        <a href="adminCategories.php">View All Categories</a>                        
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/adminEdit.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Edit Comment</h1>
                        <hr class="small">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
	            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					Username (Can't change here): <?php echo $username; ?> <br>
					Article Title (Can't change here): <?php echo $article; ?> <br>
					Time <input type="text" name="time" value="<?php echo $time; ?>"> <br>
				 	Published 
				 	<select name="published">
				 		<option value="<?php echo $published; ?>"> <?php echo $published; ?> </option>
						<option value="0"> No (0) </option>
						<option value="1"> Yes (1) </option>
					</select> 
					<br>
					Comment:<textarea id-"comment" name="comment"> <?php echo $comment; ?> </textarea>
				 	<script>
				        CKEDITOR.replace('comment');
				    </script>
					<br>
					<br>
					Once updated, you cannot retrieve the old data
					<br>
					<input type="submit" name="update" value="update">
					<input type="submit" name="cancel" value="don't update">
				</form>
            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
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