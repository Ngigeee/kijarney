<?php
session_start();

// Database connection
include 'connectDatabase.php';

if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";
}
else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}
// Assuming we are getting the owner_id from the session or a fixed ID for now
$owner_id = $_SESSION['user_id']; // Change this to get dynamic user id, like from session

// Prepare the SQL query to fetch profile picture
$query = "SELECT profile_pic FROM user_details WHERE owner_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$stmt->bind_result($profilePic);

// Fetch the profile picture URL
$profilePicUrl = null;
if ($stmt->fetch()) {
    $profilePicUrl = $profilePic;
}

// Return the result as JSON
echo json_encode(['profilePic' => $profilePicUrl]);

// Close statement and connection
$stmt->close();
$conn->close();
?>
