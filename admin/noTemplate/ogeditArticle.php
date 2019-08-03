<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//cancel
if(ISSET($_POST['cancel'])){
	header("Location:adminArticles.php");
}
//if update is pushed
if(ISSET($_POST['update'])){
	$articleid = $_SESSION['articleid'];
	$title = $_POST['title'];
	$date = $_POST['date'];
	$category = $_POST['category'];
	$published = $_POST['published'];
	$article = $_POST['article'];
	//replace ' and " for query
	$title = str_replace("'", "\'", $title);
	$article = str_replace("'", "\'", $article);
	$title = str_replace('"', '\"', $title);
	$article = str_replace('"', '\"', $article);
	//update db
	$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
	$query = "UPDATE `blog`.`articles` SET `title` = '$title', `date` = '$date', `category_id` = '$category', `published` = '$published', `article` = '$article' WHERE `articles`.`article_id` = '$articleid'";
	$result = mysqli_query($conn, $query) or DIE('bad query update');
	//send back
	header("Location:adminArticles.php");
}
//get requested article info
$articleid = $_SESSION['articleid'];
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT articles.article_id, articles.title, articles.author_id, users.username, articles.article, articles.date, articles.category_id, categories.category, articles.published FROM articles INNER JOIN categories ON articles.category_id=categories.category_id INNER JOIN users ON articles.author_id=users.user_id WHERE articles.article_id='".$articleid."'";
$result = mysqli_query($conn, $query) or DIE('bad query select');
$row = mysqli_fetch_array($result);
//create vars for array values
$title = $row['title'];
$author = $row['username'];
$article = $row['article'];
$date = $row['date'];
$category = $row['category'];
$categoryid = $row['category_id'];
$published = $row['published'];
//get values for categories
$query = "SELECT * FROM `categories`";
$resultcat = mysqli_query($conn, $query) or DIE('bad query categories');
?>
<html>
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
<body>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Title <input type="text" name="title" value="<?php echo $title; ?>"> <br>
	Author (Can't change): <?php echo $author; ?> <br>
	Date <input type="text" name="date" value="<?php echo $date; ?>"> <br>
	Category
	<select name="category">
 		<option value="<?php echo $categoryid; ?>"> <?php echo $category; ?> </option>
 		<?php
 		while($row = mysqli_fetch_array($resultcat)) {	
			echo '<option value="'.$row['category_id'].'">'.$row['category'].'</option>';
		}
 		?>
 	</select> <br>
 	Published 
 	<select name="published">
 		<option value="<?php echo $published; ?>"> <?php echo $published; ?> </option>
		<option value="0"> No (0) </option>
		<option value="1"> Yes (1) </option>
	</select> 
	<br>
	Article:<textarea id-"article" name="article"> <?php echo $article; ?> </textarea>
 	<script>
        CKEDITOR.replace('article');
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