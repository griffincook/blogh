<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//if admin choses to edit an article
if(ISSET($_POST['edit'])){
	$_SESSION['commentid'] = $_POST['edit'];
	header("Location:editComment.php");
}
//gets for searching and ordering
if(ISSET($_GET['usersearch'])){
	$usersearch = $_GET['usersearch'];
} else {
	$usersearch = "";
}
if(ISSET($_GET['sort'])){
	$sort = $_GET['sort'];
} else{
	$sort = "";
}
//deciding how to order table based off user selection
if($sort == 0) {
	$order_by = 'ORDER BY comment_id ASC';
} elseif($sort == 1) {
	$order_by = 'ORDER BY comment ASC';
} elseif($sort == 2) {
	$order_by = 'ORDER BY user_id ASC';
} elseif($sort == 3) {
	$order_by = 'ORDER BY users.username ASC';
} elseif($sort == 4) {
	$order_by = 'ORDER BY article_id ASC';
} elseif($sort == 5) {
	$order_by = 'ORDER BY articles.title ASC';
} elseif($sort == 6) {
	$order_by = 'ORDER BY time DESC';
} elseif($sort == 7) {
	$order_by = 'ORDER BY published DESC';
} else {
	$order_by = "";
}
//db
require('../db.php');
$query = "SELECT comments.comment_id, comments.comment, comments.user_id, users.username, comments.article_id, articles.title, comments.time, comments.published FROM comments INNER JOIN articles ON comments.article_id=articles.article_id INNER JOIN users ON comments.user_id=users.user_id WHERE comments.comment_id LIKE '%$usersearch%' OR comments.comment LIKE '%$usersearch%' OR comments.user_id LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR comments.article_id LIKE '%$usersearch%' OR articles.title LIKE '%$usersearch%' OR comments.time LIKE '%$usersearch%' OR comments.published LIKE '%$usersearch%'". $order_by;
$result = mysqli_query($conn, $query) or DIE('bad query');
?>
<!DOCTYPE html>
<html lang="en">

<head>

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
    <header class="intro-header" style="background-image: url('img/admin.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Comments</h1>
                        <hr class="small">
                        <span class="subheading">Edit all comments</span>
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
	            //form
				echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
					echo "Search Comments <input name='usersearch' type='text'>";
					echo "<input name='submit' type='submit'>";
					echo '	<a href="adminComments.php">Reset/View All</a>';
				echo "</form>";
                echo "<hr>";
				//table	
				echo "<table border='1'>";
					echo "<tr>";
						//hyperlinks for sorting
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>Comment_ID</a> / EDIT </th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=1'>Comment</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=2'>User_ID</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=3'>Username</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=4'>Article_ID</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=5'>Article Title</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=6'>Time</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=7'>Published (1=yes, 0=no)</a></th>";
					echo "</tr>";	
				//form for edit buttons
				echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
				//fill table with data from db
				while($row = mysqli_fetch_array($result)) {	
					echo "<tr>";
						echo '<td> <input type="submit" name="edit" value="'.$row['comment_id'].'"</td>';
						echo "<td>".$row['comment']."</td>";
						echo "<td>".$row['user_id']."</td>";
						echo "<td>".$row['username']."</td>";
						echo "<td>".$row['article_id']."</td>";
						echo "<td>".$row['title']."</td>";
						echo "<td>".$row['time']."</td>";
						echo "<td>".$row['published']."</td>";
					echo "</tr>";
				}
				echo "</form>";
				echo "</table>";
				?>  
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

