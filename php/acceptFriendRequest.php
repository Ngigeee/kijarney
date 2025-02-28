// accept_request.php
session_start();
include 'connectDatabase.php';

if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $stmt = $conn->prepare("SELECT sender_id, receiver_id FROM friend_requests WHERE id = ? AND status = 'pending'");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sender_id = $row['sender_id'];
        $receiver_id = $row['receiver_id'];

        // Update the request status to accepted
        $stmt = $conn->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();

        // Add both users to each other's friends list
        $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?), (?, ?)");
        $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
        $stmt->execute();

        echo "Friend request accepted!";
    }
}
