<?php
session_start();
// Database connection
include 'connectDatabase.php';
if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";

// Example: Fetch image paths from the 'gallery' table for a specific owner_id
$ownerId = $_SESSION['user_id'];  // You can set this dynamically or fetch it from a GET/POST parameter

// Prepare and execute the SQL query with a bound parameter
$sql = "SELECT image_path FROM gallery WHERE owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ownerId); // Bind the owner_id as an integer
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Array to store image paths
$imagePaths = [];

if ($result->num_rows > 0) {
    // Fetch image paths and store them in an array
    while ($row = $result->fetch_assoc()) {
        $imagePaths[] = $row['image_path'];
    }
}

// Return image paths as JSON
echo json_encode($imagePaths);
} else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
