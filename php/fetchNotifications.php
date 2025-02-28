<?php
session_start();

// Include your database connection file (adjust path as necessary)
include('connectDatabase.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $user_id =$_SESSION['user_id'];

    // Query to fetch unread notifications for the logged-in user
    $query = "SELECT message, created_at FROM notifications WHERE receiver_id = ? AND status = 'unread' ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    // Return notifications as JSON
    echo json_encode(['notifications' => $notifications]);

} else {
    echo json_encode(['error' => 'User not logged in']);
}

?>
