<?php session_start(); ?>

<?php
// Check if user is logged in
if(!isset($_SESSION['valid']) || !$_SESSION['valid']) {
    header("Location: login.php");
    exit();
}
?>

<?php
//including the database connection file
include_once("connection.php");
include_once("functions.php");

//fetching data in descending order (latest entry first)
$result = mysqli_query($mysqli, "SELECT *, TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age FROM coach ORDER BY last_name, first_name");
?>

<html>
<head>
	<title>Coaches Management</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="add_coach.php">Add New Coach</a>
	<br/><br/>

	<table width='100%' border=1>
		<tr bgcolor='#CCCCCC'>
			<td>Name</td>
			<td>Date of Birth</td>
			<td>Age</td>
			<td>Years Experience</td>
			<td>Actions</td>
		</tr>
		<?php
		while($res = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
			echo "<td>".formatDate($res['date_of_birth'])."</td>";
			echo "<td>".$res['age']."</td>";
			echo "<td>".$res['years_experience']."</td>";
			echo "<td><a href=\"edit_coach.php?id=$res[coach_id]\">Edit</a> | <a href=\"delete_coach.php?id=$res[coach_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
		}
		?>
	</table>
</body>
</html>