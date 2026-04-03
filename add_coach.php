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

if(isset($_POST['Submit'])) {
	$first_name = mysqli_real_escape_string($mysqli, $_POST['first_name']);
	$last_name = mysqli_real_escape_string($mysqli, $_POST['last_name']);
	$date_of_birth = mysqli_real_escape_string($mysqli, $_POST['date_of_birth']);
	$years_experience = mysqli_real_escape_string($mysqli, $_POST['years_experience']);

	// checking empty fields
	if(empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($years_experience)) {

		if(empty($first_name)) {
			echo "<font color='red'>First Name field is empty.</font><br/>";
		}

		if(empty($last_name)) {
			echo "<font color='red'>Last Name field is empty.</font><br/>";
		}

		if(empty($date_of_birth)) {
			echo "<font color='red'>Date of Birth field is empty.</font><br/>";
		}

		if(empty($years_experience)) {
			echo "<font color='red'>Years Experience field is empty.</font><br/>";
		}

		//link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else {
		// if all the fields are filled (not empty)

		//insert data to database
		$result = mysqli_query($mysqli, "INSERT INTO coach(first_name,last_name,date_of_birth,years_experience) VALUES('$first_name','$last_name','$date_of_birth','$years_experience')");

		//display success message
		echo "<font color='green'>Data added successfully.";
		echo "<br/><a href='coaches.php'>View Result</a>";
	}
}
?>

<html>
<head>
	<title>Add Coach</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="coaches.php">View Coaches</a>
	<br/><br/>

	<form action="add_coach.php" method="post" name="form1">
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
				<td>Years Experience</td>
				<td><input type="number" name="years_experience" min="0"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="Submit" value="Add"></td>
			</tr>
		</table>
	</form>
</body>
</html>