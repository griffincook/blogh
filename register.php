<?php
session_start();
//set rank for menu bar
$rank = 0;
//send to login
if(ISSET($_POST['login'])){
	header("location:login.php");
}
//presets
$error = '';
$full = 0;
//before form
if(!ISSET($_POST['submit'])){
	$username = '';
	$password = '';
	$email = '';
}
//filled in form
elseif(ISSET($_POST['submit'])){
	//vars
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	//set array of usernames
	$taken = array();
    //db users
	require('db.php');
	$query = "SELECT * FROM `users`";
	$result = mysqli_query($conn, $query) or DIE('bad query');
	while($row = mysqli_fetch_array($result)) {	
		array_push($taken, $row['username']);
	}
	//check for empty fields
	if(empty($_POST['username'])){
		$error = $error.'no username<br>';
	} else {
		if(in_array($username, $taken)){
			$error = $error. '*username already taken*<br>';
		} else{
			$full = $full+1;
		}
	}
	if(empty($_POST['password'])){
		$error = $error.'no password<br>';
	} else {
		$full = $full+1;
	}
	if(empty($_POST['email'])){
		$error = $error.'no email<br>';
	} else {
		$full = $full+1;
	}
    //check to make sure everything is filled out
    if($full == 3) {
        //hash
        $password = password_hash($password, PASSWORD_DEFAULT);
        //insert into db
        $query = "INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `rank_id`, `active`, `picture`) VALUES (NULL, '".$username."', '".$password."', '".$email."', '1', '1', 'http://dk6kcyuwrpkrj.cloudfront.net/wp-content/uploads/sites/45/2014/05/avatar-blank.jpg')";
        $result = mysqli_query($conn, $query) or DIE('bad query1');
        //set session['username'] for login form
        $_SESSION['username'] = $username;
        //success redirect to login
        header("location:login.php");
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

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/people.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1 style="color:white">Register</h1>
                        <hr class="small">
                        <span class="subheading">Join the fastest growing blog today</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
	<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p style="color:red"> <?php echo $error; ?> </p>
				<table>
					<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<tr>
							<td> Username: </td>
							<td> <input type="text" name="username" value="<?php echo $username; ?>"> </td>
						</tr>
						<tr>
							<td> Password: </td>
							<td> <input type="text" name="password" value="<?php echo $password; ?>"> </td>
						</tr>
						<tr>
							<td> Email: </td>
							<td> <input type="text" name="email" value="<?php echo $email; ?>"> </td>
						</tr>
						<tr>
							<td> <input type="submit" name="submit" value="Sign Up"> </td>
						</tr>
						<tr>
							<td> Already have an account? </td>
							<td> <input type="submit" name="login" value="Login"> </td>
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
