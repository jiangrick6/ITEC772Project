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
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Team Management</p>
                    <h2>Edit Team</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-secondary" href="teams.php">Back to Teams</a>
                </div>
            </div>
        </div>

        <div class="card">
            <form name="form1" method="post" action="edit_team.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Team Name</label>
                        <input type="text" name="team_name" value="<?php echo $team_name;?>">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" value="<?php echo $city;?>">
                    </div>
                    <div class="form-group">
                        <label>Abbreviation</label>
                        <input type="text" name="abbreviation" value="<?php echo $abbreviation;?>" maxlength="3">
                    </div>
                    <div class="form-group">
                        <label>Founded Year</label>
                        <input type="number" name="founded_year" value="<?php echo $founded_year;?>" min="1920" max="2030">
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <input type="text" name="division_id" value="<?php echo $division_id;?>" placeholder="Type division name or ID">
                    </div>
                    <div class="form-group">
                        <label>Stadium</label>
                        <input type="text" name="stadium_id" value="<?php echo $stadium_id;?>" placeholder="Type stadium name or ID">
                    </div>
                </div>
                <div class="form-actions">
                    <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                    <input type="submit" name="update" value="Update" class="btn btn-primary">
                    <a href="teams.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>