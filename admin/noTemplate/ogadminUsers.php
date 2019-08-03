<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//if admin choses to edit a user
if(ISSET($_POST['edit'])){
	$_SESSION['userid'] = $_POST['edit'];
	header("Location:editUser.php");
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
	$order_by = 'ORDER BY user_id ASC';
} elseif($sort == 1) {
	$order_by = 'ORDER BY username ASC';
} elseif($sort == 2) {
	$order_by = 'ORDER BY email ASC';
} elseif($sort == 3) {
	$order_by = 'ORDER BY rank_id DESC';
} elseif($sort == 4) {
	$order_by = 'ORDER BY rank ASC';
} elseif($sort == 5) {
	$order_by = 'ORDER BY active DESC';
} else {
	$order_by = "";
}
//links
echo '<a href="adminHome.php"> Back to admin home page </a> <br>';
echo '<a href="../index.php"> Back to main page </a> <br><br>';
//form for search
echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
	echo "Search Users <input name='usersearch' type='text'>";
	echo "<input name='submit' type='submit'>";
	echo '	<a href="adminUsers.php">Reset/View All</a>';
echo "</form>";
//table	
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT users.user_id, users.username, users.email, users.rank_id, users.active, rank.rank FROM users INNER JOIN rank ON users.rank_id=rank.rank_id WHERE users.user_id LIKE '%$usersearch%' OR users.username LIKE '%$usersearch%' OR users.password LIKE '%$usersearch%' OR users.email LIKE '%$usersearch%' OR users.rank_id LIKE '%$usersearch%' OR users.active LIKE '%$usersearch%'". $order_by;
$result = mysqli_query($conn, $query) or DIE('bad query');
echo "<table border='1'>";
	echo "<tr>";
		//hyperlinks for sorting
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>User_ID</a> / EDIT </th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=1'>Username</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=2'>Email</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=3'>Rank_ID</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=4'>Rank</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=5'>Active (1=yes, 0=no)</a></th>";
	echo "</tr>";	
//form for edit buttons
echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
//fill table with data from db
while($row = mysqli_fetch_array($result)) {	
	echo "<tr>";
		echo '<td> <input type="submit" name="edit" value="'.$row['user_id'].'"</td>';
		echo "<td>".$row['username']."</td>";
		echo "<td>".$row['email']."</td>";
		echo "<td>".$row['rank_id']."</td>";
		echo "<td>".$row['rank']."</td>";
		echo "<td>".$row['active']."</td>";
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
