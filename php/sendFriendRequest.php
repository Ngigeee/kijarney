<?php
session_start();
include('connectDatabase.php'); // Assuming you have a database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get the logged-in user's ID
$loggedInUserId = $_SESSION['user_id'];

// Get receiver user ID from POST
$receiverId = $_POST['receiver_id'];

// Make sure the receiver and sender are not the same
if ($loggedInUserId == $receiverId) {
    echo json_encode(['status' => 'error', 'message' => 'Cannot send friend request to yourself']);
    exit;
}

// Query to get sender's first name (use prepared statements to avoid SQL injection)
$query = "SELECT firstName FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();
$sender = $result->fetch_assoc();
$senderName = $sender['firstName'];

// Create notification message
$message = "$senderName sent you a friend request";

// Insert the notification into the database (notifications table)
$notificationQuery = "INSERT INTO notifications (sender_id, receiver_id, type, message) 
                       VALUES (?, ?, 'friend_request', ?)";
$stmt = $conn->prepare($notificationQuery);
$stmt->bind_param("iis", $loggedInUserId, $receiverId, $message);
$stmt->execute();

// Respond back to JavaScript
echo json_encode(['status' => 'success']);
?>
