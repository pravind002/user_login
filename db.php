<?php
// db.php - Database connection file

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'renu';

$conn = new mysqli($host, $user, $pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>