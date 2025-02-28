<?php
session_start();
// Database connection
include 'connectDatabase.php';
if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";
} else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}
// Example user data (this should be fetched from the database)
$user_id = $_SESSION['user_id']; // Assuming a session variable with user_id
if ($user_id) {
    // Fetch user information from the database
    // Assuming a database connection $conn is already established
    $stmt = $conn->prepare("SELECT firstName, online FROM registration WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstName, $online);
    $stmt->fetch();
    
    // Return user data as JSON
    echo json_encode([
        'firstName' => $firstName,  // Corrected this line
        'online' => $online
    ]);
} else {
    echo json_encode([
        'firstName' => 'Guest',
        'online' => false
    ]);
}
?>
