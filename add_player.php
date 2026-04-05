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
		$errors = [];
		if(empty($first_name)) {
			$errors[] = "First name is required.";
		}
		if(empty($last_name)) {
			$errors[] = "Last name is required.";
		}
		$error = implode(' ', $errors);
	} else {
		// if all the fields are filled (not empty)
		//insert data to database
		$result = mysqli_query($mysqli, "INSERT INTO player(first_name, last_name, date_of_birth, height, weight, college, jersey_number, position_id, current_team_abbreviation) VALUES('$first_name', '$last_name', '$date_of_birth', '$height', '$weight', '$college', '$jersey_number', '$position_id', '$current_team_abbreviation')");

		if($result) {
			$success = "Player added successfully. <a href='players.php'>View Players</a>";
		} else {
			$error = "Unable to add player. Please try again.";
		}
	}
}
?>

<html>
<head>
	<title>Add Player</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Player Management</p>
                    <h2>Add New Player</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-secondary" href="players.php">Back to Players</a>
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
            <form action="add_player.php" method="post" name="form1">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth">
                    </div>
                    <div class="form-group">
                        <label>Height</label>
                        <input type="text" name="height" placeholder="6'2&quot;">
                    </div>
                    <div class="form-group">
                        <label>Weight (lbs)</label>
                        <input type="number" name="weight" min="150" max="400">
                    </div>
                    <div class="form-group">
                        <label>College</label>
                        <input type="text" name="college">
                    </div>
                    <div class="form-group">
                        <label>Jersey Number</label>
                        <input type="number" name="jersey_number" min="1" max="99">
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <select name="position_id">
                            <option value="">Select Position</option>
                            <?php while($position = mysqli_fetch_array($positions)) { ?>
                                <option value="<?php echo $position['position_id']; ?>"><?php echo $position['position_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Current Team</label>
                        <select name="current_team_abbreviation">
                            <option value="">Select Team</option>
                            <?php while($team = mysqli_fetch_array($teams)) { ?>
                                <option value="<?php echo $team['abbreviation']; ?>"><?php echo $team['team_name']; ?> (<?php echo $team['abbreviation']; ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" name="Submit" value="Add Player" class="btn btn-primary">
                    <a href="players.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>