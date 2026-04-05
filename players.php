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

//fetching data in descending order (latest entry first)
$result = mysqli_query($mysqli, "SELECT p.*, pos.position_name, t.team_name FROM player p LEFT JOIN position pos ON p.position_id = pos.position_id LEFT JOIN team t ON p.current_team_abbreviation = t.abbreviation ORDER BY p.last_name, p.first_name");
?>

<html>
<head>
	<title>Players Management</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Management</p>
                    <h2>Players</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-primary" href="add_player.php">+ Add New Player</a>
                    <a class="btn btn-secondary" href="index.php">Back to Home</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table width='100%'>
                    <tr>
                        <td>Name</td>
                        <td>Position</td>
                        <td>Team</td>
                        <td>Jersey #</td>
                        <td>Height</td>
                        <td>Weight</td>
                        <td>College</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    while($res = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
                        echo "<td>".$res['position_name']."</td>";
                        echo "<td>".$res['team_name']."</td>";
                        echo "<td>".$res['jersey_number']."</td>";
                        echo "<td>".$res['height']."</td>";
                        echo "<td>".$res['weight']."</td>";
                        echo "<td>".$res['college']."</td>";
                        echo "<td><div class='action-buttons'><a class=\"btn btn-primary\" href=\"edit_player.php?id=$res[player_id]\">Edit</a><a class=\"btn btn-danger\" href=\"delete_player.php?id=$res[player_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></div></td>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>