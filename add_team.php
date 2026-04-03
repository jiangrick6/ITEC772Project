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

// Get dropdown data
$divisions = mysqli_query($mysqli, "SELECT * FROM division ORDER BY division_name");
$stadiums = mysqli_query($mysqli, "SELECT * FROM stadium ORDER BY stadium_name");

if(isset($_POST['Submit'])) {
	$team_name = mysqli_real_escape_string($mysqli, $_POST['team_name']);
	$city = mysqli_real_escape_string($mysqli, $_POST['city']);
	$abbreviation = mysqli_real_escape_string($mysqli, $_POST['abbreviation']);
	$founded_year = mysqli_real_escape_string($mysqli, $_POST['founded_year']);
	$division_id = mysqli_real_escape_string($mysqli, $_POST['division_id']);
	$stadium_id = mysqli_real_escape_string($mysqli, $_POST['stadium_id']);

	// checking empty fields
	if(empty($team_name) || empty($city) || empty($abbreviation) || empty($founded_year)) {
		if(empty($team_name)) {
			echo "<font color='red'>Team name field is empty.</font><br/>";
		}
		if(empty($city)) {
			echo "<font color='red'>City field is empty.</font><br/>";
		}
		if(empty($abbreviation)) {
			echo "<font color='red'>Abbreviation field is empty.</font><br/>";
		}
		if(empty($founded_year)) {
			echo "<font color='red'>Founded year field is empty.</font><br/>";
		}
		//link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else {
		// if all the fields are filled (not empty)
		//insert data to database
		$result = mysqli_query($mysqli, "INSERT INTO team(team_name, city, abbreviation, founded_year, division_id, stadium_id) VALUES('$team_name', '$city', '$abbreviation', '$founded_year', '$division_id', '$stadium_id')");

		//display success message
		echo "<font color='green'>Data added successfully.";
		echo "<br/><a href='teams.php'>View Result</a>";
	}
}
?>

<html>
<head>
	<title>Add Team</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="teams.php">View Teams</a>
	<br/><br/>

	<form action="add_team.php" method="post" name="form1">
		<table width="25%" border="0">
			<tr>
				<td>Team Name</td>
				<td><input type="text" name="team_name"></td>
			</tr>
			<tr>
				<td>City</td>
				<td><input type="text" name="city"></td>
			</tr>
			<tr>
				<td>Abbreviation</td>
				<td><input type="text" name="abbreviation" maxlength="3"></td>
			</tr>
			<tr>
				<td>Founded Year</td>
				<td><input type="number" name="founded_year" min="1920" max="2030"></td>
			</tr>
			<tr>
				<td>Division</td>
				<td>
					<select name="division_id">
						<option value="">Select Division</option>
						<?php while($division = mysqli_fetch_array($divisions)) { ?>
							<option value="<?php echo $division['division_id']; ?>"><?php echo $division['division_name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Stadium</td>
				<td>
					<select name="stadium_id">
						<option value="">Select Stadium</option>
						<?php while($stadium = mysqli_fetch_array($stadiums)) { ?>
							<option value="<?php echo $stadium['stadium_id']; ?>"><?php echo $stadium['stadium_name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="Submit" value="Add"></td>
			</tr>
		</table>
	</form>
</body>
</html>