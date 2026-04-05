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
		$errors = [];
		if(empty($team_name)) {
			$errors[] = "Team name is required.";
		}
		if(empty($city)) {
			$errors[] = "City is required.";
		}
		if(empty($abbreviation)) {
			$errors[] = "Abbreviation is required.";
		}
		if(empty($founded_year)) {
			$errors[] = "Founded year is required.";
		}
		$error = implode(' ', $errors);
	} else {
		// if all the fields are filled (not empty)
		//insert data to database
		$result = mysqli_query($mysqli, "INSERT INTO team(team_name, city, abbreviation, founded_year, division_id, stadium_id) VALUES('$team_name', '$city', '$abbreviation', '$founded_year', '$division_id', '$stadium_id')");

		if($result) {
			$success = "Team added successfully. <a href='teams.php'>View Teams</a>";
		} else {
			$error = "Unable to add team. Please try again.";
		}
	}
}
?>

<html>
<head>
	<title>Add Team</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Team Management</p>
                    <h2>Add New Team</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-secondary" href="teams.php">Back to Teams</a>
                </div>
            </div>
        </div>

        <div class="card">
            <?php if(isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
            <?php if(isset($success)) { ?>
                <div class="success"><?php echo $success; ?></div>
            <?php } ?>
            <form action="add_team.php" method="post" name="form1">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Team Name</label>
                        <input type="text" name="team_name" required>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" required>
                    </div>
                    <div class="form-group">
                        <label>Abbreviation</label>
                        <input type="text" name="abbreviation" maxlength="3" required>
                    </div>
                    <div class="form-group">
                        <label>Founded Year</label>
                        <input type="number" name="founded_year" min="1920" max="2030" required>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <input type="text" name="division_id" placeholder="Type division name or ID">
                    </div>
                    <div class="form-group">
                        <label>Stadium</label>
                        <input type="text" name="stadium_id" placeholder="Type stadium name or ID">
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" name="Submit" value="Add Team" class="btn btn-primary">
                    <a href="teams.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>