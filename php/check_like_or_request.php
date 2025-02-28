<?php
session_start();
include('connectDatabase.php'); // Include your database connection

if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";

    $user_id = $_SESSION['user_id']; // The current logged-in user
    $target_user_id = $_GET['user_id'];

    // Check if user has liked the target user
    $stmt = $conn->prepare("SELECT 1 FROM likes WHERE sender_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $user_id, $target_user_id);
    $stmt->execute();
    $isLiked = $stmt->get_result()->num_rows > 0;

    // Check if user has sent a friend request to the target user
    $stmt2 = $conn->prepare("SELECT 1 FROM friend_requests WHERE sender_id = ? AND receiver_id = ?");
    $stmt2->bind_param("ii", $user_id, $target_user_id);
    $stmt2->execute();
    $isRequested = $stmt2->get_result()->num_rows > 0;

    // Return the result as JSON
    echo json_encode(['isLiked' => $isLiked, 'isRequested' => $isRequested]);

} else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}
$conn->close();
?>
