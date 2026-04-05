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
		$errors = [];
		if(empty($first_name)) {
			$errors[] = "First Name is required.";
		}
		if(empty($last_name)) {
			$errors[] = "Last Name is required.";
		}
		if(empty($date_of_birth)) {
			$errors[] = "Date of Birth is required.";
		}
		if(empty($years_experience)) {
			$errors[] = "Years Experience is required.";
		}
		$error = implode(' ', $errors);
	} else {
		// if all the fields are filled (not empty)

		//insert data to database
		$result = mysqli_query($mysqli, "INSERT INTO coach(first_name,last_name,date_of_birth,years_experience) VALUES('$first_name','$last_name','$date_of_birth','$years_experience')");

		if($result) {
			$success = "Coach added successfully. <a href='coaches.php'>View Coaches</a>";
		} else {
			$error = "Unable to add coach. Please try again.";
		}
	}
}
?>

<html>
<head>
	<title>Add Coach</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Coach Management</p>
                    <h2>Add New Coach</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-secondary" href="coaches.php">Back to Coaches</a>
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
            <form action="add_coach.php" method="post" name="form1">
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
                        <label>Years Experience</label>
                        <input type="number" name="years_experience" min="0">
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" name="Submit" value="Add Coach" class="btn btn-primary">
                    <a href="coaches.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>