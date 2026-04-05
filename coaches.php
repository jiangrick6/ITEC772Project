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
include_once("functions.php");

//fetching data in descending order (latest entry first)
$result = mysqli_query($mysqli, "SELECT *, TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age FROM coach ORDER BY last_name, first_name");
?>

<html>
<head>
	<title>Coaches Management</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="page-wrap">
        <div class="card">
            <div class="report-header">
                <div>
                    <p class="eyebrow">Management</p>
                    <h2>Coaches</h2>
                </div>
                <div class="report-actions">
                    <a class="btn btn-primary" href="add_coach.php">+ Add New Coach</a>
                    <a class="btn btn-secondary" href="index.php">Back to Home</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table width='100%'>
                    <tr>
                        <td>Name</td>
                        <td>Date of Birth</td>
                        <td>Age</td>
                        <td>Years Experience</td>
                        <td>Actions</td>
                    </tr>
                    <?php
                    while($res = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>".$res['first_name']." ".$res['last_name']."</td>";
                        echo "<td>".formatDate($res['date_of_birth'])."</td>";
                        echo "<td>".$res['age']."</td>";
                        echo "<td>".$res['years_experience']."</td>";
                        echo "<td><div class='action-buttons'><a class=\"btn btn-primary\" href=\"edit_coach.php?id=$res[coach_id]\">Edit</a><a class=\"btn btn-danger\" href=\"delete_coach.php?id=$res[coach_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></div></td>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>