<?php
session_start();
if(!ISSET($_SESSION['user'])){
	header("location:../login.php");
} elseif($_SESSION['rank'] != 3){
	header("location:../index.php");
}
//if admin choses to edit a category
if(ISSET($_POST['edit'])){
	$_SESSION['categoryid'] = $_POST['edit'];
	header("Location:editCategories.php");
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
	$order_by = 'ORDER BY category_id ASC';
} elseif($sort == 1) {
	$order_by = 'ORDER BY category ASC';
} elseif($sort == 2) {
	$order_by = 'ORDER BY active DESC';
} else {
	$order_by = "";
}
//links
echo '<a href="adminHome.php"> Back to admin home page </a> <br>';
echo '<a href="../index.php"> Back to main page </a> <br><br>';
//form
echo "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
	echo "Search Categories <input name='usersearch' type='text'>";
	echo "<input name='submit' type='submit'>";
	echo '	<a href="adminCategories.php">Reset/View All</a>';
echo "</form>";
//table	
$conn = mysqli_connect('localhost','root','root','blog') or DIE('Bad conn');
$query = "SELECT categories.category_id, categories.category, categories.active FROM categories WHERE categories.category_id LIKE '%$usersearch%' OR categories.category LIKE '%$usersearch%' OR categories.active LIKE '%$usersearch%'". $order_by;
$result = mysqli_query($conn, $query) or DIE('bad query');
echo "<table border='1'>";
	echo "<tr>";
		//hyperlinks for sorting
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=0'>Category_ID</a> / EDIT </th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=1'>Category</a></th>";
		echo "<th> <a href='". $_SERVER['PHP_SELF'] ."?usersearch=". $usersearch ."&sort=2'>Active (1=yes, 0=no)</a></th>";
	echo "</tr>";	
//form for edit buttons
echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
//fill table with data from db
while($row = mysqli_fetch_array($result)) {	
	echo "<tr>";
		echo '<td> <input type="submit" name="edit" value="'.$row['category_id'].'"</td>';
		echo "<td>".$row['category']."</td>";
		echo "<td>".$row['active']."</td>";
	echo "</tr>";
}
	echo "<tr>";
		echo "<td> <input type='submit' name='edit' value='Create A New Category'> </td>";
	echo "</tr>";
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
