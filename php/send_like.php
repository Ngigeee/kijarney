<?php
// Start the session to access user information
session_start();

// Include database connection (replace with your actual connection)
include('connectDatabase.php'); // You should replace this with the actual path to your DB connection file

// Check if the necessary data is present
if (isset($_POST['receiver_id'])) {
    $sender_id = $_SESSION['user_id'];
 // Assuming you have the logged-in user's ID in the session
    $receiver_id = $_POST['receiver_id'];

    // Check if the sender and receiver are the same (no self-liking allowed)
    if ($sender_id === $receiver_id) {
        echo json_encode(['status' => 'error', 'message' => 'You cannot like yourself.']);
        exit;
    }

    // Insert the like into the database
    $stmt = $conn->prepare("INSERT INTO likes (sender_id, receiver_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $sender_id, $receiver_id);

    if ($stmt->execute()) {
        // Create a notification for the receiver
        // First, fetch the sender's first name to include in the notification message
        $stmt_name = $conn->prepare("SELECT firstName FROM registration WHERE ID = ?");
        $stmt_name->bind_param("i", $sender_id);
        $stmt_name->execute();
        $result = $stmt_name->get_result();
        $sender_name = $result->fetch_assoc()['firstName'];
        
        // Notification message
        $message = "$sender_name liked your profile!";
        
        // Insert the notification into the notifications table
        $stmt_notify = $conn->prepare("INSERT INTO notifications (sender_id, receiver_id, type, message) VALUES (?, ?, 'like', ?)");
        $stmt_notify->bind_param("iis", $sender_id, $receiver_id, $message);
        
        if ($stmt_notify->execute()) {
            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'You liked the user!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error creating notification.']);
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error liking the user.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing receiver_id.']);
}

// Close the database connection
$conn->close();
?>
