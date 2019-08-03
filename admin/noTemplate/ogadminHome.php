<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
?>
<html>
<body>
<h3> Welcome Admin! Please select what you would like to edit. </h3>
<a href="adminArticles.php"> Articles</a> |
<a href="adminUsers.php"> Users</a> |
<a href="adminComments.php"> Comments</a> |
<a href="adminCategories.php"> Categories</a>
<br><br>
<a href="../index.php"> Back to main page </a>
</body>
</html>