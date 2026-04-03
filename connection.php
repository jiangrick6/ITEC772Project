<?php
// Database connection
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'nfldatabase';
$port = 3306;

// Create connection
$mysqli = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset
$mysqli->set_charset("utf8mb4");
?>