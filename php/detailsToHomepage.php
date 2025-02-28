<?php
session_start(); // Start the session at the top of your script
// Database connection
include 'connectDatabase.php';


// Check if a specific session variable is set
if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";



// Example: Get the user details based on user_id (you can modify this according to your need)
$userId = $_SESSION['user_id']; // You can modify this or get it from a GET/POST parameter

// Prepare and execute the SQL query
$sql = "SELECT profile_pic,personality,music,dob FROM user_details WHERE owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId); // Bind the user_id parameter (integer)
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if we found a user
if ($result->num_rows > 0) {
    // Fetch user data
    $userDetails = $result->fetch_assoc();
    
    // Return user details as JSON
    echo json_encode($userDetails);
} else {
    echo json_encode(['error' => 'User not found']);
}

// Close the database connection
$stmt->close();
$conn->close();
} else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}
?>
