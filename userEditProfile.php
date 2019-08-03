<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:login.php");
}
//db
require('db.php');
//set rank
$rank = $_SESSION['rank'];
//delete
if(ISSET($_POST['delete'])){
	$userid = $_SESSION['user'];
	$query = "UPDATE `users` SET `active` = '0' WHERE `users`.`user_id` = '$userid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	header("location:index.php");
}
//make $msg blank if not updated
$msg = "";
//if update is pushed
if(ISSET($_POST['update'])){
	$userid = $_SESSION['user'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$picture = $_POST['picture'];
	//replace ' for query
	$username = str_replace("'", "\'", $username);
	$password = str_replace("'", "\'", $password);
	//update db
	if(!empty($password)){
		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = "UPDATE `users` SET `username` = '$username', `password` = '$password', `email` = '$email', `picture` = '$picture' WHERE `users`.`user_id` = '$userid'";
	} else {
		$query = "UPDATE `users` SET `username` = '$username', `email` = '$email', `picture` = '$picture' WHERE `users`.`user_id` = '$userid'";
	}
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	header("location:profile.php");
}
//get requested user info
$userid = $_SESSION['user'];
$query = "SELECT users.user_id, users.username, users.email, users.picture FROM users WHERE users.user_id='".$userid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$username = $row['username'];
$email = $row['email'];
$picture = $row['picture'];
?>
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
    <header class="intro-header" style="background-image: url('img/editprofile.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1 style="color:white">Edit Profile Info</h1>
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
                <table>
	                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<tr>
							<td> Username </td>
							<td> <input type="text" name="username" value="<?php echo $username; ?>"> </td>
						</tr>
						<tr>
							<td> Password </td>
							<td> <input type="text" name="password"> (your current password isn't dispalayed for security) </td>
						</tr>
						<tr>
							<td> Email </td>
							<td> <input type="text" name="email" value="<?php echo $email; ?>"> </td>
						</tr>
						<tr>
							<td> Current profile picture </td>
							<td> <img src="<?php echo $picture; ?>" height="50" width="50"> </td>
						</tr>
						<tr>
							<td> Change profile picture (change URL) </td>
							<td> <input type="text" name="picture" value="<?php echo $picture; ?>"> </td>
						</tr>
						<tr>
							<td> </td>
							<td> <input type="submit" name="update" value="update"> </td>
						</tr>
						<tr>
                            <td> <hr> </td>
                            <td> <hr> </td>
                        </tr>
                        <tr>
							<td> Notice: You cannot undo deleting your account </td>
						</tr>
                        <tr>
                            <td> <hr> </td>
                            <td> <hr> </td>
                        </tr>
						<tr>
							<td> <input type="submit" name="delete" value="permanently delete account"> </td>
						</tr>
					</form>
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
