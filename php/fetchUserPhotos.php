<?php
session_start();
// Database connection
include 'connectDatabase.php';

//if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
   // echo "User is logged in.";
//}
// Check if 'owner_id' is passed as a query parameter (for example: ?owner_id=1)
$ownerId = 1;
//$_SESSION['user_id'];  // Replace with dynamic value as needed, for example, from $_GET['owner_id']

// Prepare the SQL statement with placeholders
$sql = "SELECT image_path FROM gallery WHERE owner_id = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameter (integer type for owner_id)
$stmt->bind_param("i", $ownerId);

// Execute the prepared statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Array to store image paths
$imagePaths = [];

if ($result->num_rows > 0) {
    // Fetch the image paths and store them in an array
    while ($row = $result->fetch_assoc()) {
        $imagePaths[] = $row['image_path'];
    }
}

// Convert the image paths array to JSON format
echo json_encode($imagePaths);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
