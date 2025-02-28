<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kijarney";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and handle error if it occurs
if ($conn->connect_error) {
    // Optional: Log the error for debugging purposes
    // error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// You can include this file wherever needed like:
// include 'db_connectDatabase.php';
?>
