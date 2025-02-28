<?php
// checkIfEmailExists.php

// Database connection
include('connectDatabase.php');

$data = json_decode(file_get_contents("php://input"));  // Get the POST data

$email = $data->email;  // Extract email from request

// Check if email exists in the database
$query = "SELECT * FROM registration WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['exists' => true]);  // Email exists
} else {
    echo json_encode(['exists' => false]);  // Email doesn't exist
}

$stmt->close();
$conn->close();
?>
