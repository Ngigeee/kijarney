<?php
// Assuming your database connection is already established
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kijarney";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$owner_id = $_SESSION['user_id'];  // Assume the user ID is stored in the session

$data = json_decode(file_get_contents('php://input'), true);
$interests = $data['interests'];

// Update the interests in the database
$query = "UPDATE user_details SET interests = ? WHERE owner_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $interests, $owner_id);

$response = [];

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

echo json_encode($response);
?>
