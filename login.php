<?php
session_start();
//set rank for menu bar
$rank = 0;
//click to register
if(ISSET($_POST['register'])){
	header("Location:register.php");
}
//set errors blank to start
$error1 = "";
$error2 = "";
$error3 = "";
$error4 = "";
//after login attempt
if(ISSET($_POST['submit'])){	
	if(!empty($_POST['username'])){
		$username = $_POST['username'];
		if(!empty($_POST['password'])){
			$password = $_POST['password'];
			//database
			require('db.php');
			$query = "SELECT * FROM `users`";
			$result = mysqli_query($conn, $query) or DIE('bad query');
			while($row = mysqli_fetch_array($result)) {	
				if($row['username'] == $username){
					if(password_verify($password, $row['password'])){
						
						//make sure it's an active account
						if($row['active'] == 1){
							//set session
                            $_SESSION['user'] = $row['user_id'];
                            ?>
                            <html>
                            <!-- successful login, close fancybox -->
                            <head>
                                <!-- fancybox -->
                                <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
                                <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.css" /> 
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.min.js"></script>
                            </head>
                            <script type="text/javascript">
                                $.fancybox.close();
                                parent.location.reload(true);
                            </script>
                            </html>
                            <?php
						} elseif($row['active'] != 1) {
							$error2 = "This account is no longer active";
						}
					//if password doesn't match db
					} else {
						$error3 = "Incorrect password";
					}
				//if username doesn't match db
				} else {
					$error4 = "There is no account with that username";
				}
			}
		//if no password is imputed
		} else{
			$error1 = "Please input a password";
		}	
	//if no username is inputed
	} else {
		$error1 = "Please input a username";
	}
} else {

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

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1 style="color:white">Login</h1>
                        <hr class="small">
                        <span class="subheading">Welcome Back</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
	<div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <p style="color:red"> 
				<?php
				//error 4 is always set therefore we must check if others are empty
				if(!empty($error1 or $error2 or $error3)){ 
					echo $error1.$error2.$error3; 
				} else { 
					echo $error4; 
				} ?> 
				</p>
				<table>
	                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<tr>
							<td> Username:</td> 
							<td><input type="text" name="username" value="<?php if(ISSET($username)){ echo $username; } elseif(ISSET($_SESSION['username'])){ echo $_SESSION['username']; } ?>"></td>
						</tr>
						<tr>
							<td> Password: </td>
							<td> <input type="text" name="password"> </td>
						</tr>
						<tr>
							<td> <input type="submit" name="submit" value="Login"> <td>
						</tr>
						<tr>
							<td> Don't have an account? </td>
							<td> <input type="submit" name="register" value="Register"> </td>
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
    <!-- <script src="vendor/jquery/jquery.min.js"></script> -->

    <!-- Bootstrap Core JavaScript -->
    <!-- <script src="vendor/bootstrap/js/bootstrap.min.js"></script> -->

    <!-- Contact Form JavaScript
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    Theme JavaScript 
    <script src="js/clean-blog.min.js"></script>
	-->
</body>

</html>
