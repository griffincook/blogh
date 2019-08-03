<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//if admin choses to edit an article
if(ISSET($_POST['edit'])){
	$_SESSION['commentid'] = $_POST['edit'];
	header("Location:editComment.php");
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
	$order_by = 'ORDER BY comment_id ASC';
} elseif($sort == 1) {
	$order_by = 'ORDER BY comment ASC';
} elseif($sort == 2) {
	$order_by = 'ORDER BY user_id ASC';
} elseif($sort == 3) {
	$order_by = 'ORDER BY users.username ASC';
} elseif($sort == 4) {
	$order_by = 'ORDER BY article_id ASC';
} elseif($sort == 5) {
	$order_by = 'ORDER BY articles.title ASC';
} elseif($sort == 6) {
	$order_by = 'ORDER BY time DESC';
} elseif($sort == 7) {
	$order_by = 'ORDER BY published DESC';
} else {
	$order_by = "";
}
//links
echo '<a href="adminHome.php"> Back to admin home page </a> <br>';
echo '<a href="../index.php"> Back to main page </a> <br><br>';
//form
echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
	echo "Search Comments <input name='usersearch' type='text'>";
	echo "<input name='submit' type='submit'>";
	echo '	<a href="adminComments.php">Reset/View All</a>';
echo "</form>";
//table	
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT comments.comment_id, comments.comment, comments.user_id, users.username, comments.article_id, articles.title, comments.time, comments.published FROM comments INNER JOIN articles ON comments.article_id=articles.article_id INNER JOIN users ON comments.user_id=users.user_id WHERE comments.comment_id LIKE '%$usersearch%' OR comments.comment LIKE '%$usersearch%' OR comments.user_id LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR comments.article_id LIKE '%$usersearch%' OR articles.title LIKE '%$usersearch%' OR comments.time LIKE '%$usersearch%' OR comments.published LIKE '%$usersearch%'". $order_by;
$result = mysqli_query($conn, $query) or DIE('bad query');
echo "<table border='1'>";
	echo "<tr>";
		//hyperlinks for sorting
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>Comment_ID</a> / EDIT </th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=1'>Comment</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=2'>User_ID</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=3'>Username</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=4'>Article_ID</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=5'>Article Title</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=6'>Time</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=7'>Published (1=yes, 0=no)</a></th>";
	echo "</tr>";	
//form for edit buttons
echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
//fill table with data from db
while($row = mysqli_fetch_array($result)) {	
	echo "<tr>";
		echo '<td> <input type="submit" name="edit" value="'.$row['comment_id'].'"</td>';
		echo "<td>".$row['comment']."</td>";
		echo "<td>".$row['user_id']."</td>";
		echo "<td>".$row['username']."</td>";
		echo "<td>".$row['article_id']."</td>";
		echo "<td>".$row['title']."</td>";
		echo "<td>".$row['time']."</td>";
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