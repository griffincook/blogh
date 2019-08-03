<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:login.php");
} elseif($_SESSION['rank'] < 2){
    header("location:index.php");
}
//set timezone
date_default_timezone_set('America/Toronto');
//set rank for menu bar
$rank = $_SESSION['rank'];
//query for cateogories
require('db.php');
$query = "SELECT * FROM `categories`";
$resultcat = mysqli_query($conn, $query) or DIE('bad query');

//post
if(!ISSET($_POST['submit'])){
    $title = "";
    $article = "";
    $error= "";
} else {
    $title = $_POST['title'];
    $article = $_POST['article'];
    $category = $_POST['category'];
    //replace ' and " for query
    $title = str_replace("'", "\'", $title);
    $article = str_replace("'", "\'", $article);
    $title = str_replace('"', '\"', $title);
    $article = str_replace('"', '\"', $article);
    //make sure everything is filled out
    if(!empty($title)){
        if(!empty($article)){
            if(!empty($category)){
                //insert
                $date = date('Y-m-d G:i:s');
                $userid = $_SESSION['user'];
                $query = "INSERT INTO `articles` (`article_id`, `title`, `author_id`, `article`, `category_id`, `date`, `published`) VALUES (NULL, '".$title."', '".$userid."', '".$article."', '".$category."', '".$date."', '1')";
                $result = mysqli_query($conn, $query) or DIE('bad query1');
                header("location:index.php");
            } else {
                $error = "Please make sure to select a category for your article";
            }
        } else {
            $error = "If you are going to try and post an article, it is probably a good idea to actually write the article and not just leave it blank...";
        }
    } else {
        $error = "Please give your post a title";
    }
}
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
    <header class="intro-header" style="background-image: url('img/write.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1 style="color:white">Author's Place</h1>
                        <hr class="small">
                        <span class="subheading">Write and publish an article</span>
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
                    <p style="color:red"> <?php echo $error; ?> </p>
                    Title<input type="textarea" name="title" value="<?php echo $title; ?>">
                    <br>
                    Category<select name="category">
                        <option value=""></option>
                        <?php
                        while($row = mysqli_fetch_array($resultcat)) {  
                            echo '<option value="'.$row['category_id'].'">'.$row['category'].'</option>';
                        }
                        ?>
                    </select>
                    <br>
                    <textarea id-"article" name="article"> <?php echo $article; ?> </textarea>
                    <script>
                        CKEDITOR.replace('article');
                    </script>
                    <br>
                    <p> please make sure every field is filled out before attempting to post</p>
                    <input type="submit" name="submit" value="Publish Article">
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
