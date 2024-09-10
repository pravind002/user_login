<?php
// db.php - Database connection file

$host = 'localhost';
$user = 'root';   // Replace with your database username
$pass = '';       // Replace with your database password
$db_name = 'userlogin'; // Replace with your database name

$conn = new mysqli($host, $user, $pass, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
