<?php
// Database connection for users database
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'nfl_users';
$port = 3306;

// Create connection
$user_conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($user_conn->connect_error) {
    die("Connection failed: " . $user_conn->connect_error);
}

// Set charset
$user_conn->set_charset("utf8mb4");
?>