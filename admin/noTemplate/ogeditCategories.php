<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//cancel
if(ISSET($_POST['cancel'])){
	header("Location:adminCategories.php");
}
//if update is pushed
if(ISSET($_POST['update'])){
	$categoryid = $_SESSION['categoryid'];
	$category = $_POST['category'];
	$active = $_POST['active'];
	//replace ' for query
	$category = str_replace("'", "\'", $category);
	//update db
	$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
	if(is_numeric($categoryid)){
		$query = "UPDATE `blog`.`categories` SET `category` = '$category', `active` = '$active' WHERE `categories`.`category_id` = '$categoryid'";
	} else {
		$query = "INSERT INTO `categories` (`category_id`, `category`, `active`) VALUES (NULL, '".$category."', '".$active."')";
	}
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	//send back
	header("Location:adminCategories.php");
}
//get requested article info
$categoryid = $_SESSION['categoryid'];
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT categories.category_id, categories.category, categories.active FROM categories WHERE categories.category_id='".$categoryid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$category = $row['category'];
$active = $row['active'];
?>
<html>
<body>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Category <input type="text" name="category" value="<?php echo $category; ?>"> <br>
 	Active
 	<select name="active">
 		<option value="<?php echo $active; ?>"> <?php echo $active; ?> </option>
		<option value="0"> No (0) </option>
		<option value="1"> Yes (1) </option>
	</select> 
	<br>
	Once updated, you cannot retrieve the old data
	<br><br>
	<input type="submit" name="update" value="update">
	<input type="submit" name="cancel" value="don't update">
</form>
</body>
</html>