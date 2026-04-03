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
$positions = mysqli_query($mysqli, "SELECT * FROM position ORDER BY position_name");
$teams = mysqli_query($mysqli, "SELECT abbreviation, team_name FROM team ORDER BY team_name");

if(isset($_POST['Submit'])) {
	$first_name = mysqli_real_escape_string($mysqli, $_POST['first_name']);
	$last_name = mysqli_real_escape_string($mysqli, $_POST['last_name']);
	$date_of_birth = mysqli_real_escape_string($mysqli, $_POST['date_of_birth']);
	$height = mysqli_real_escape_string($mysqli, $_POST['height']);
	$weight = mysqli_real_escape_string($mysqli, $_POST['weight']);
	$college = mysqli_real_escape_string($mysqli, $_POST['college']);
	$jersey_number = mysqli_real_escape_string($mysqli, $_POST['jersey_number']);
	$position_id = mysqli_real_escape_string($mysqli, $_POST['position_id']);
	$current_team_abbreviation = mysqli_real_escape_string($mysqli, $_POST['current_team_abbreviation']);

	// checking empty fields
	if(empty($first_name) || empty($last_name)) {
		if(empty($first_name)) {
			echo "<font color='red'>First name field is empty.</font><br/>";
		}
		if(empty($last_name)) {
			echo "<font color='red'>Last name field is empty.</font><br/>";
		}
		//link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else {
		// if all the fields are filled (not empty)
		//insert data to database
		$result = mysqli_query($mysqli, "INSERT INTO player(first_name, last_name, date_of_birth, height, weight, college, jersey_number, position_id, current_team_abbreviation) VALUES('$first_name', '$last_name', '$date_of_birth', '$height', '$weight', '$college', '$jersey_number', '$position_id', '$current_team_abbreviation')");

		//display success message
		echo "<font color='green'>Player added successfully.";
		echo "<br/><a href='players.php'>View Result</a>";
	}
}
?>

<html>
<head>
	<title>Add Player</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="players.php">View Players</a>
	<br/><br/>

	<form action="add_player.php" method="post" name="form1">
		<table width="25%" border="0">
			<tr>
				<td>First Name</td>
				<td><input type="text" name="first_name"></td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td><input type="text" name="last_name"></td>
			</tr>
			<tr>
				<td>Date of Birth</td>
				<td><input type="date" name="date_of_birth"></td>
			</tr>
			<tr>
				<td>Height</td>
				<td><input type="text" name="height" placeholder="6'2&quot;"></td>
			</tr>
			<tr>
				<td>Weight (lbs)</td>
				<td><input type="number" name="weight" min="150" max="400"></td>
			</tr>
			<tr>
				<td>College</td>
				<td><input type="text" name="college"></td>
			</tr>
			<tr>
				<td>Jersey Number</td>
				<td><input type="number" name="jersey_number" min="1" max="99"></td>
			</tr>
			<tr>
				<td>Position</td>
				<td>
					<select name="position_id">
						<option value="">Select Position</option>
						<?php while($position = mysqli_fetch_array($positions)) { ?>
							<option value="<?php echo $position['position_id']; ?>"><?php echo $position['position_name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Current Team</td>
				<td>
					<select name="current_team_abbreviation">
						<option value="">Select Team</option>
						<?php while($team = mysqli_fetch_array($teams)) { ?>
							<option value="<?php echo $team['abbreviation']; ?>"><?php echo $team['team_name']; ?> (<?php echo $team['abbreviation']; ?>)</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="Submit" value="Add Player"></td>
			</tr>
		</table>
	</form>
</body>
</html>