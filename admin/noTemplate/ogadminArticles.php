<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//if admin choses to edit an article
if(ISSET($_POST['edit'])){
	$_SESSION['articleid'] = $_POST['edit'];
	header("Location:editArticle.php");
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
	$order_by = 'ORDER BY article_id ASC';
} elseif($sort == 1) {
	$order_by = 'ORDER BY title ASC';
} elseif($sort == 2) {
	$order_by = 'ORDER BY username ASC';
} elseif($sort == 3) {
	$order_by = 'ORDER BY author_id ASC';
} elseif($sort == 4) {
	$order_by = 'ORDER BY date ASC';
} elseif($sort == 5) {
	$order_by = 'ORDER BY category ASC';
} elseif($sort == 6) {
	$order_by = 'ORDER BY article ASC';
} elseif($sort == 7) {
	$order_by = 'ORDER BY published ASC';
} else {
	$order_by = "";
}
//links
echo '<a href="adminHome.php"> Back to admin home page </a> <br>';
echo '<a href="../index.php"> Back to main page </a> <br><br>';
//form
echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
	echo "Search Articles <input name='usersearch' type='text'>";
	echo "<input name='submit' type='submit'>";
	echo '	<a href="adminArticles.php">Reset/View All</a>';
echo "</form>";
//table	
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT articles.article_id, articles.title, articles.author_id, users.username, articles.article, articles.date, articles.category_id, categories.category, articles.published FROM articles INNER JOIN categories ON articles.category_id=categories.category_id INNER JOIN users ON articles.author_id=users.user_id WHERE articles.article_id LIKE '%$usersearch%' OR articles.title LIKE '%$usersearch%' OR articles.article LIKE '%$usersearch%' OR articles.date LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR categories.category LIKE '%$usersearch%'". $order_by;
$result = mysqli_query($conn, $query) or DIE('bad query');
echo "<table border='1'>";
	echo "<tr>";
		//hyperlinks for sorting
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>Article_ID</a> / EDIT </th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=1'>Title</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=2'>Author Name</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=3'>Author_ID</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=4'>Date</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=5'>Category</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=6'>Article</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=7'>Published (1=yes, 0=no)</a></th>";
	echo "</tr>";	
//form for edit buttons
echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
//fill table with data from db
while($row = mysqli_fetch_array($result)) {	
	echo "<tr>";
		echo '<td> <input type="submit" name="edit" value="'.$row['article_id'].'"</td>';
		echo "<td>".$row['title']."</td>";
		echo "<td>".$row['username']."</td>";
		echo "<td>".$row['author_id']."</td>";
		echo "<td>".$row['date']."</td>";
		echo "<td>".$row['category']."</td>";
		$article = strip_tags($row['article'],"<p><strong><br><em><u><s><sub><sup><span>");
		$article = substr($article, 0, 500);
		echo "<td>".$article."</td>";
		echo "<td>".$row['published']."</td>";
	echo "</tr>";
}
echo "</form>";
echo "</table>";
?>
<html>
<body>
<br><br>
<a href="adminHome.php"> Back to admin home page </a>
<br>
<a href="../index.php"> Back to main page </a>
</body>
</html>