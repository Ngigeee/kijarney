// reject_request.php
session_start();
include 'connectDatabase.php';

if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Update the request status to rejected
    $stmt = $conn->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    echo "Friend request rejected.";
}
