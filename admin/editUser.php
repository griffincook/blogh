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
	header("Location:adminUsers.php");
}
//if update is pushed
if(ISSET($_POST['update'])){
	$userid = $_SESSION['userid'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$rankid = $_POST['rank_id'];
	$active = $_POST['active'];
	//replace ' for query
	$username = str_replace("'", "\'", $username);
	//update db
	$query = "UPDATE `users` SET `username` = '$username', `email` = '$email', `rank_id` = '$rankid', `active` = '$active' WHERE `users`.`user_id` = '$userid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update'.$query);
	//send back
	header("Location:adminUsers.php");
}
//get requested user info
$userid = $_SESSION['userid'];
$query = "SELECT users.user_id, users.username, users.email, users.rank_id, users.active FROM users WHERE users.user_id='".$userid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$username = $row['username'];
$email = $row['email'];
$rank_id = $row['rank_id'];
$active = $row['active'];
//get values for rank
$query = "SELECT * FROM `rank`";
$resultRank = mysqli_query($conn, $query) or DIE('bad query rank');
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
                        <h1>Edit User</h1>
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
					Username <input type="text" name="username" value="<?php echo $username; ?>"> <br>
					Email <input type="text" name="email" value="<?php echo $email; ?>"> <br>
					Rank_ID
					<select name="rank_id">
				 		<option value="<?php echo $rank_id; ?>"> <?php echo $rank_id; ?> </option>
				 		<?php
				 		while($row = mysqli_fetch_array($resultRank)) {	
							echo '<option value="'.$row['rank_id'].'">'.$row['rank'].'('.$row['rank_id'].')</option>';
						}
				 		?>
				 	</select> <br>
				 	Active
				 	<select name="active">
				 		<option value="<?php echo $active; ?>"> <?php echo $active; ?> </option>
						<option value="0"> No (0) </option>
						<option value="1"> Yes (1) </option>
					</select> 
					<br>
					<br>
					Once updated, you cannot retrieve the old data
					<br><br>
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