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

if(isset($_POST['update']))
{
	$id = mysqli_real_escape_string($mysqli, $_POST['id']);

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
	} else {
		//updating the table
		$result = mysqli_query($mysqli, "UPDATE coach SET first_name='$first_name',last_name='$last_name',date_of_birth='$date_of_birth',years_experience='$years_experience' WHERE coach_id=$id");

		//redirectig to the display page. In our case, it is index.php
		header("Location: coaches.php");
	}
}
?>
<?php
//getting id from url
$id = $_GET['id'];

//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM coach WHERE coach_id=$id");

while($res = mysqli_fetch_array($result))
{
	$first_name = $res['first_name'];
	$last_name = $res['last_name'];
	$date_of_birth = $res['date_of_birth'];
	$years_experience = $res['years_experience'];
}
?>
<html>
<head>
	<title>Edit Coach</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Coach Management</p>
                    <h2>Edit Coach</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-secondary" href="coaches.php">Back to Coaches</a>
                </div>
            </div>
        </div>

        <div class="card">
            <form name="form1" method="post" action="edit_coach.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo $first_name;?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?php echo $last_name;?>">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" value="<?php echo $date_of_birth;?>">
                    </div>
                    <div class="form-group">
                        <label>Years Experience</label>
                        <input type="number" name="years_experience" value="<?php echo $years_experience;?>" min="0">
                    </div>
                </div>
                <div class="form-actions">
                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                    <input type="submit" name="update" value="Update" class="btn btn-primary">
                    <a href="coaches.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>