<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:login.php");
}
//check for article click
if(ISSET($_GET['article'])){
	$_SESSION['articleid'] = $_GET['article'];
	header("Location:userEditArticle.php");
}
//connect to db
$userid = $_SESSION['user'];
require('db.php');
$query = "SELECT users.user_id, users.username, users.email, users.rank_id, users.picture FROM users WHERE users.user_id='".$userid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select1');
$userInfo = mysqli_fetch_array($result);
//create vars for array values
$username = $userInfo['username'];
$email = $userInfo['email'];
$picture = $userInfo['picture'];
$rank = $userInfo['rank_id'];

//display all of the user's articles if they're an author
if($rank >= 2){	
	$query = "SELECT articles.article_id, articles.title, articles.author_id, articles.date, articles.category_id, articles.article, users.username, categories.category, articles.published FROM articles INNER JOIN `users` ON articles.author_id=users.user_id INNER JOIN categories ON articles.category_id=categories.category_id WHERE articles.author_id='$userid' ORDER BY articles.date DESC";
	$resultArt = mysqli_query($conn, $query) or DIE('bad query select2');
}
?>
<html lang="en">

<head>
    
    <!-- fancybox -->
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css" /> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>

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
    <header class="intro-header" style="background-image: url('img/profile.webp')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1><?php echo $username; ?></h1>
                        <hr class="small">
                        <span class="subheading">Your personal profile page</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
	<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <h1>Your info:</h1>
                <table>
					<tr>
						<td> Profile Picture </td>
						<td> <a href="<?php echo $picture; ?>" data-fancybox> <img src="<?php echo $picture; ?>" height="100" width="100"> </a> </td>
	                </tr>
	                <tr>
	                	<td> Username </td>
	                	<td> <?php echo $username; ?> </td>
					</tr>
					<tr>
						<td> Email </td>
						<td> <?php echo $email; ?> </td>
					</tr>
					<tr>
						<td> <a href="userEditProfile.php">edit your information</a> </td>
					</tr>
				</table>
					<hr>
					
                    <!-- for author's -->
                    <?php
                    if($rank >= 2){  
                        echo "<h1> Edit your articles: </h1>";
                        echo "<hr>";
                        while($row = mysqli_fetch_array($resultArt)) { 
    		                if($row['published'] == 1) {
    		                    echo '<div class="post-preview">';  
    		                        //strip tags from article and limit for snippet
    		                        $article = strip_tags($row['article'],"<p><strong><br><em><u><s><sub><sup><span>");
    		                        $article = substr($article, 0, 200);
    		                        //hyperlink for article
    		                        echo "<a href='". $_SERVER['PHP_SELF'] ."?article=". $row['article_id'] ."'>";
    		                            echo "<h4 class='post-title'>".$row['title']."</h4>";
    		                            echo "<h4 class='post-subtitle'>".$article."</h4>";
    		                        echo "</a>";
    		                        //author, date, category, and user pic
    		                        echo '<p class="post-meta">Posted on '.$row['date'].' | Category: '.$row['category'].'</p>';
    		                    echo '</div>';
    		                    echo '<hr>';
    		                }
    		            }
		            }
                    ?>
		        </table>
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
