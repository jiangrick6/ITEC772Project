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

// getting id of the data from url
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: players.php");
    exit();
}

$id = mysqli_real_escape_string($mysqli, $_GET['id']);

// deleting the row from table
mysqli_query($mysqli, "DELETE FROM player WHERE player_id=$id");

// redirecting to the display page (players.php)
header("Location: players.php");
exit();
?>
