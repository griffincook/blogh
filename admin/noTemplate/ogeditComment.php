<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//cancel
if(ISSET($_POST['cancel'])){
	header("Location:adminComments.php");
}
//if update is pushed
if(ISSET($_POST['update'])){
	$commentid = $_SESSION['commentid'];
	$comment = $_POST['comment'];
	$time = $_POST['time'];
	$published = $_POST['published'];
	//replace ' and " for query
	$comment = str_replace("'", "\'", $comment);
	$comment = str_replace('"', '\"', $comment);
	//update db
	$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
	$query = "UPDATE `blog`.`comments` SET `comment` = '$comment', `time` = '$time', `published` = '$published' WHERE `comments`.`comment_id` = '$commentid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	//send back
	header("Location:adminComments.php");
}
//get requested comment info
$commentid = $_SESSION['commentid'];
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT comments.comment_id, comments.comment, comments.user_id, users.username, comments.article_id, articles.title, comments.time, comments.published FROM comments INNER JOIN articles ON comments.article_id=articles.article_id INNER JOIN users ON comments.user_id=users.user_id WHERE comments.comment_id='".$commentid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$comment = $row['comment'];
$username = $row['username'];
$article = $row['title'];
$time = $row['time'];
$published = $row['published'];
?>
<html>
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
<body>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Username (Can't change here): <?php echo $username; ?> <br>
	Article Title (Can't change here): <?php echo $article; ?> <br>
	Time <input type="text" name="time" value="<?php echo $time; ?>"> <br>
 	Published 
 	<select name="published">
 		<option value="<?php echo $published; ?>"> <?php echo $published; ?> </option>
		<option value="0"> No (0) </option>
		<option value="1"> Yes (1) </option>
	</select> 
	<br>
	Comment:<textarea id-"comment" name="comment"> <?php echo $comment; ?> </textarea>
 	<script>
        CKEDITOR.replace('comment');
    </script>
	<br>
	<br>
	Once updated, you cannot retrieve the old data
	<br>
	<input type="submit" name="update" value="update">
	<input type="submit" name="cancel" value="don't update">
</form>
</body>
</html>