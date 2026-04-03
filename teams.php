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

//fetching data in descending order (latest entry first)
$result = mysqli_query($mysqli, "SELECT t.*, d.division_name, s.stadium_name FROM team t LEFT JOIN division d ON t.division_id = d.division_id LEFT JOIN stadium s ON t.stadium_id = s.stadium_id ORDER BY t.team_name");
?>

<html>
<head>
	<title>Teams Management</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="add_team.php">Add New Team</a>
	<br/><br/>

	<table width='100%' border=1>
		<tr bgcolor='#CCCCCC'>
			<td>Team Name</td>
			<td>City</td>
			<td>Abbreviation</td>
			<td>Founded Year</td>
			<td>Division</td>
			<td>Stadium</td>
			<td>Actions</td>
		</tr>
		<?php
		while($res = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>".$res['team_name']."</td>";
			echo "<td>".$res['city']."</td>";
			echo "<td>".$res['abbreviation']."</td>";
			echo "<td>".$res['founded_year']."</td>";
			echo "<td>".$res['division_name']."</td>";
			echo "<td>".$res['stadium_name']."</td>";
			echo "<td><a href=\"edit_team.php?id=$res[team_id]\">Edit</a> | <a href=\"delete_team.php?id=$res[team_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
		}
		?>
	</table>
</body>
</html>