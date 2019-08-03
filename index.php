<?php
session_start();
//check for article click
if(ISSET($_GET['readmore'])){
    $_SESSION['articleid'] = $_GET['readmore'];
    header("Location:fullArticle.php");
}
//db
require('db.php');
//once logged in
if(ISSET($_SESSION['user'])){
    //retrieve user rank
    $userid = $_SESSION['user'];
    $query = "SELECT users.rank_id FROM users WHERE users.user_id=$userid";
    $result = mysqli_query($conn, $query) or DIE('bad query rank'.$_SESSION['user']); 
    $row = mysqli_fetch_array($result);
    $rank = $row['rank_id'];
    $_SESSION['rank'] = $rank;
} else {
    $rank = 0;
}
//retrieve categories
$query = "SELECT * FROM categories WHERE categories.active=1";
$resultCat = mysqli_query($conn, $query) or DIE('bad query'); 
//set usersearch
if(ISSET($_GET['usersearch'])){
    $usersearch =  $_GET['usersearch'];
} else {
    $usersearch = "";
}
//aricles queries depending on category choice and search
if(ISSET($_GET['sort'])){   
    $sort = $_GET['sort'];
    //category is chosen
    if($sort != 0){
        $query = "SELECT articles.article_id, articles.title, articles.author_id, articles.date, articles.category_id, articles.article, users.username, users.picture, categories.category, articles.published FROM articles INNER JOIN `users` ON articles.author_id=users.user_id INNER JOIN categories ON articles.category_id=categories.category_id WHERE articles.category_id='$sort' ORDER BY articles.date DESC";
    } 
    //category isn't chosen
    else {
        $query = "SELECT articles.article_id, articles.title, articles.author_id, articles.date, articles.category_id, articles.article, users.username, users.picture, categories.category, articles.published FROM articles INNER JOIN `users` ON articles.author_id=users.user_id INNER JOIN categories ON articles.category_id=categories.category_id WHERE articles.title LIKE '%$usersearch%' OR articles.date LIKE '%$usersearch%' OR articles.article LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR categories.category LIKE '%$usersearch%' ORDER BY articles.date DESC";
    }
} else {
    $query = "SELECT articles.article_id, articles.title, articles.author_id, articles.date, articles.category_id, articles.article, users.username, users.picture, categories.category, articles.published FROM articles INNER JOIN `users` ON articles.author_id=users.user_id INNER JOIN categories ON articles.category_id=categories.category_id WHERE articles.title LIKE '%$usersearch%' OR articles.date LIKE '%$usersearch%' OR articles.article LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR categories.category LIKE '%$usersearch%' ORDER BY articles.date DESC";
}
$resultArt = mysqli_query($conn, $query) or DIE('bad query');
?>
<!DOCTYPE html>
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
                            echo '<a data-fancybox="gallery" href="login.php" style="color:grey">Login</a>';
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
                            echo '<a data-fancybox="gallery" href="register.php" style="color:grey">Register</a>';    
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
    <header class="intro-header" style="background-image: url('img/forest.jpeg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Blogh</h1>
                        <hr class="small">
                        <span class="subheading">A Blog Created by Griffin Cook</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1"> 
                <?php
                echo "<h3> Categories: </h3>";
                echo "<a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>All</a><br>";
                while($row = mysqli_fetch_array($resultCat)) { 
                    echo "<a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=".$row['category_id']."'>".$row['category']."</a>";
                    echo "<br>";
                }
                echo "<hr>";
                while($row = mysqli_fetch_array($resultArt)) { 
                    if($row['published'] == 1) {
                        echo '<div class="post-preview">';  
                            //strip tags from article and limit for snippet
                            $article = strip_tags($row['article'],"<p><strong><br><em><u><s><sub><sup><span>");
                            $article = substr($article, 0, 200);
                            //hyperlink for article
                            echo "<a href='". $_SERVER['PHP_SELF'] ."?readmore=". $row['article_id'] ."'>";
                                echo "<h2 class='post-title'>".$row['title']."</h2>";
                                echo "<h3 class='post-subtitle'>".$article."</h3>";
                            echo "</a>";
                            //make date look nice
							$date = preg_replace("/[^0-9,.]/", "", $row['date']);
							$date = date('l F d, Y', strtotime($date));
							//author, date, category, and user pic
                            echo '<p class="post-meta">Posted by '.$row['username'].' <a href="'.$row['picture'].'" data-fancybox> <img src="'.$row['picture'].'" height="30" width="30"> </a> on '.$date.' | Category: '.$row['category'].'</p>';
                        echo '</div>';
                        echo '<hr>';
                    }
                }
                ?> 
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='get'>
                    Search Articles <input name='usersearch' type='text'>
                    <input name='submit' type='submit' value='search'>
                    <a href="index.php">Reset/View All</a>
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
