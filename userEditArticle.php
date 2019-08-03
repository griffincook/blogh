<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:login.php");
} elseif($_SESSION['rank'] < 2){
	header("location:index.php");
}
//db
require('db.php');
//set rank
$rank = $_SESSION['rank'];
//if update is pushed
if(ISSET($_POST['update'])){
	$articleid = $_SESSION['articleid'];
	$title = $_POST['title'];
	$category = $_POST['category'];
	$published = $_POST['published'];
	$article = $_POST['article'];
	//replace ' and " for query
	$title = str_replace("'", "\'", $title);
	$article = str_replace("'", "\'", $article);
	$title = str_replace('"', '\"', $title);
	$article = str_replace('"', '\"', $article);
	//update db
	$query = "UPDATE `articles` SET `title` = '$title', `category_id` = '$category', `published` = '$published', `article` = '$article' WHERE `articles`.`article_id` = '$articleid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	//send back
	if($published == 1){
		header("Location:fullArticle.php");
	} else {
		header("Location:index.php");
	}
}
//get requested article info
$articleid = $_SESSION['articleid'];
$query = "SELECT articles.article_id, articles.title, articles.author_id, users.username, articles.article, articles.date, articles.category_id, categories.category, articles.published FROM articles INNER JOIN categories ON articles.category_id=categories.category_id INNER JOIN users ON articles.author_id=users.user_id WHERE articles.article_id='".$articleid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$title = $row['title'];
$author = $row['username'];
$article = $row['article'];
$date = $row['date'];
$category = $row['category'];
$categoryid = $row['category_id'];
$published = $row['published'];
//get values for categories
$query = "SELECT * FROM `categories`";
$resultcat = mysqli_query($conn, $query) or DIE('bad query categories');
?>
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
    <header class="intro-header" style="background-image: url('img/edit.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1 style="color:white">Edit Article</h1>
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
					Title <input type="text" name="title" value="<?php echo $title; ?>"> <br>
					Category
					<select name="category">
				 		<option value="<?php echo $categoryid; ?>"> <?php echo $category; ?> </option>
				 		<?php
				 		while($row = mysqli_fetch_array($resultcat)) {	
							echo '<option value="'.$row['category_id'].'">'.$row['category'].'</option>';
						}
				 		?>
				 	</select> 
					<br>
					Edit Article:<textarea id-"article" name="article"> <?php echo $article; ?> </textarea>
				 	<script>
				        CKEDITOR.replace('article');
				    </script>
				    <br>
				 	Delete Article Altogether? It cannot be retrieved if you chose delete.
				 	<select name="published">
						<option value="1"> Keep Published </option>
						<option value="0"> Delete </option>
					</select> 
					<br>
					<br>
					Once updated, you cannot retrieve the old data
					<br>
					<input type="submit" name="update" value="update">
				</form>
            </div>
        </div>
    </div>    	                     

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
