<?php session_start(); ?>

<?php
// Check if user is logged in
if(!isset($_SESSION['valid']) || !$_SESSION['valid']) {
    header("Location: login.php");
    exit();
}
?>

<html>
<head>
	<title>NFL Database Management</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="header">
		NFL Database Management System
	</div>

	<?php
	// Show welcome message and logout link
	if(isset($_SESSION['valid'])) {
		echo "Welcome " . $_SESSION['username'] . " ! <a href='logout.php'>Logout</a><br/><br/>";
	}
	?>

	<a href="teams.php">Manage Teams</a> |
	<a href="players.php">Manage Players</a> |
	<a href="coaches.php">Manage Coaches</a> |
	<a href="reports.php">View Reports</a>

	<br/><br/>

	<div class="dashboard">
		<h2>Database Overview</h2>
		<?php
		include_once("connection.php");

		// Count records in each table
		$teams_count = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM team");
		$teams = mysqli_fetch_assoc($teams_count);

		$players_count = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM player");
		$players = mysqli_fetch_assoc($players_count);

		$coaches_count = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM coach");
		$coaches = mysqli_fetch_assoc($coaches_count);

		echo "<p><strong>Teams:</strong> " . $teams['count'] . "</p>";
		echo "<p><strong>Players:</strong> " . $players['count'] . "</p>";
		echo "<p><strong>Coaches:</strong> " . $coaches['count'] . "</p>";
		?>
	</div>
</body>
</html>
