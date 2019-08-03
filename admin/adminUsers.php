<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//if admin choses to edit a user
if(ISSET($_POST['edit'])){
	$_SESSION['userid'] = $_POST['edit'];
	header("Location:editUser.php");
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
	$order_by = 'ORDER BY user_id ASC';
} elseif($sort == 1) {
	$order_by = 'ORDER BY username ASC';
} elseif($sort == 2) {
	$order_by = 'ORDER BY email ASC';
} elseif($sort == 3) {
	$order_by = 'ORDER BY rank_id DESC';
} elseif($sort == 4) {
	$order_by = 'ORDER BY rank ASC';
} elseif($sort == 5) {
	$order_by = 'ORDER BY active DESC';
} else {
	$order_by = "";
}
//db
require('../db.php');
$query = "SELECT users.user_id, users.username, users.email, users.rank_id, users.active, rank.rank FROM users INNER JOIN rank ON users.rank_id=rank.rank_id WHERE users.user_id LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR users.password LIKE '%$usersearch%' OR users.email LIKE '%$usersearch%' OR users.rank_id LIKE '%$usersearch%' OR users.active LIKE '%$usersearch%'". $order_by;
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
                        <h1>Users</h1>
                        <hr class="small">
                        <span class="subheading">Edit all users</span>
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
	            //form for search
				echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
					echo "Search Users <input name='usersearch' type='text'>";
					echo "<input name='submit' type='submit'>";
					echo '	<a href="adminUsers.php">Reset/View All</a>';
				echo "</form>";
                echo "<hr>";
				//table	
				$query = "SELECT users.user_id, users.username, users.email, users.rank_id, users.active, rank.rank FROM users INNER JOIN rank ON users.rank_id=rank.rank_id WHERE users.user_id LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR users.password LIKE '%$usersearch%' OR users.email LIKE '%$usersearch%' OR users.rank_id LIKE '%$usersearch%' OR users.active LIKE '%$usersearch%'". $order_by;
				$result = mysqli_query($conn, $query) or DIE('bad query');
				echo "<table border='1'>";
					echo "<tr>";
						//hyperlinks for sorting
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>User_ID</a> / EDIT </th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=1'>Username</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=2'>Email</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=3'>Rank_ID</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=4'>Rank</a></th>";
						echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=5'>Active (1=yes, 0=no)</a></th>";
					echo "</tr>";	
				//form for edit buttons
				echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
				//fill table with data from db
				while($row = mysqli_fetch_array($result)) {	
					echo "<tr>";
						echo '<td> <input type="submit" name="edit" value="'.$row['user_id'].'"></td>';
						echo "<td>".$row['username']."</td>";
						echo "<td>".$row['email']."</td>";
						echo "<td>".$row['rank_id']."</td>";
						echo "<td>".$row['rank']."</td>";
						echo "<td>".$row['active']."</td>";
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

