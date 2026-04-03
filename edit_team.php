<?php session_start(); ?>

<?php
// Check if user is logged in
if(!isset($_SESSION['valid']) || !$_SESSION['valid']) {
    header("Location: login.php");
    exit();
}
?>

<?php
// including the database connection file
include_once("connection.php");

// Get dropdown data
$divisions = mysqli_query($mysqli, "SELECT * FROM division ORDER BY division_name");
$stadiums = mysqli_query($mysqli, "SELECT * FROM stadium ORDER BY stadium_name");

if(isset($_POST['update'])) {
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
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
		//updating the table
		$result = mysqli_query($mysqli, "UPDATE team SET team_name='$team_name', city='$city', abbreviation='$abbreviation', founded_year='$founded_year', division_id='$division_id', stadium_id='$stadium_id' WHERE team_id=$id");

		//redirecting to the display page (index.php in our case)
		header("Location: teams.php");
	}
}
?>
<?php
//getting id from url
$id = $_GET['id'];

//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM team WHERE team_id=$id");

while($res = mysqli_fetch_array($result)) {
	$team_name = $res['team_name'];
	$city = $res['city'];
	$abbreviation = $res['abbreviation'];
	$founded_year = $res['founded_year'];
	$division_id = $res['division_id'];
	$stadium_id = $res['stadium_id'];
}
?>
<html>
<head>
	<title>Edit Team</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="teams.php">View Teams</a>
	<br/><br/>

	<form name="form1" method="post" action="edit_team.php">
		<table border="0">
			<tr>
				<td>Team Name</td>
				<td><input type="text" name="team_name" value="<?php echo $team_name;?>"></td>
			</tr>
			<tr>
				<td>City</td>
				<td><input type="text" name="city" value="<?php echo $city;?>"></td>
			</tr>
			<tr>
				<td>Abbreviation</td>
				<td><input type="text" name="abbreviation" value="<?php echo $abbreviation;?>" maxlength="3"></td>
			</tr>
			<tr>
				<td>Founded Year</td>
				<td><input type="number" name="founded_year" value="<?php echo $founded_year;?>" min="1920" max="2030"></td>
			</tr>
			<tr>
				<td>Division</td>
				<td>
					<select name="division_id">
						<option value="">Select Division</option>
						<?php
						mysqli_data_seek($divisions, 0); // Reset pointer
						while($division = mysqli_fetch_array($divisions)) {
							$selected = ($division['division_id'] == $division_id) ? 'selected' : '';
							echo "<option value='{$division['division_id']}' $selected>{$division['division_name']}</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Stadium</td>
				<td>
					<select name="stadium_id">
						<option value="">Select Stadium</option>
						<?php
						mysqli_data_seek($stadiums, 0); // Reset pointer
						while($stadium = mysqli_fetch_array($stadiums)) {
							$selected = ($stadium['stadium_id'] == $stadium_id) ? 'selected' : '';
							echo "<option value='{$stadium['stadium_id']}' $selected>{$stadium['stadium_name']}</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><input type="hidden" name="id" value="<?php echo $_GET['id'];?>"></td>
				<td><input type="submit" name="update" value="Update"></td>
			</tr>
		</table>
	</form>
</body>
</html>