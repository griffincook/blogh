<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
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
	$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
	//put this in later: `rank_id` = '$rank_id'
	$query = "UPDATE `blog`.`users` SET `username` = '$username', `email` = '$email', `rank_id` = '$rankid', `active` = '$active' WHERE `users`.`user_id` = '$userid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update'.$query);
	//send back
	header("Location:adminUsers.php");
}
//get requested user info
$userid = $_SESSION['userid'];
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
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
<html>
<body>
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
</body>
</html>