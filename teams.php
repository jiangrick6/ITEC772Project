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
$result = mysqli_query($mysqli, "SELECT t.*, d.division_name, s.stadium_name FROM team t LEFT JOIN division d ON t.division_id = d.division_id LEFT JOIN stadium s ON t.stadium_id = s.stadium_id ORDER BY t.team_name");
?>

<html>
<head>
	<title>Teams Management</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Management</p>
                    <h2>Teams</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-primary" href="add_team.php">+ Add New Team</a>
                    <a class="btn btn-secondary" href="index.php">Back to Home</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table width='100%'>
                    <tr>
                        <td>Team Name</td>
                        <td>City</td>
                        <td>Abbreviation</td>
                        <td>Founded Year</td>
                        <td>Division</td>
                        <td>Stadium</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    while($res = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>".$res['team_name']."</td>";
                        echo "<td>".$res['city']."</td>";
                        echo "<td>".$res['abbreviation']."</td>";
                        echo "<td>".$res['founded_year']."</td>";
                        echo "<td>".$res['division_name']."</td>";
                        echo "<td>".$res['stadium_name']."</td>";
                        echo "<td><div class='action-buttons'><a class=\"btn btn-primary\" href=\"edit_team.php?id=$res[team_id]\">Edit</a><a class=\"btn btn-danger\" href=\"delete_team.php?id=$res[team_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></div></td>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>