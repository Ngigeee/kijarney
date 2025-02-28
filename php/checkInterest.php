<?php


session_start();
// Database connection
include 'connectDatabase.php';
if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";

$owner_id =$_SESSION['user_id'];  // Assume the user ID is stored in the session

$query = "SELECT interests FROM user_details WHERE owner_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$stmt->bind_result($interests);
$stmt->fetch();

$response = [];
if (empty($interests)) {
    $response['interests'] = '';
} else {
    $response['interests'] = $interests;
}

echo json_encode($response);
} else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}
?>
