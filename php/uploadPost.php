<?php
session_start();
// Assuming you're connecting to a database (optional)
include 'connectDatabase.php';
if (isset($_SESSION['user_id'])) {
    // The session variable 'user_id' is set
    echo "User is logged in.";
} else {
    // The session variable 'user_id' is not set
    echo "User is not logged in.";
}
$response = array();

// Check if the photo and description are provided
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    // Define the upload directory
    $uploadDir = '../uploads/';
    $fileName = basename($_FILES['photo']['name']);
    $uploadFilePath = $uploadDir . $fileName;

    // Move the uploaded file to the desired location
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFilePath)) {
        // Get description from the form
        $description = isset($_POST['description']) ? $_POST['description'] : "No description provided.";

        // Assuming user is logged in and user_id is in session
        session_start();
        if (!isset($_SESSION['user_id'])) {
$userId = 1;
            //$response['status'] = 'error';
            //$response['message'] = 'User is not logged in.';
            //echo json_encode($response);
            //exit;
        }

        $userId = $_SESSION['user_id'];

        // Insert photo and description into the database
        $stmt = $conn->prepare("INSERT INTO user_posts (owner_id, photo_url, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $uploadFilePath, $description);
        $stmt->execute();
        $stmt->close();

        // Get the user's username from the registration table
        $stmt = $conn->prepare("SELECT firstName FROM registration WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();

        // Get the user's profile picture from the user_details table
        $stmt = $conn->prepare("SELECT profile_pic FROM user_details WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($profilePic);
        $stmt->fetch();
        $stmt->close();

        // Get the current time
        $time = date('i') . " min";  // You can format the time as needed

        // Respond with success and the uploaded photo details
        $response['status'] = 'success';
        $response['photo_url'] = $uploadFilePath;
        $response['photo_description'] = $description;
        $response['username'] = $username;
        $response['profile_pic'] = $profilePic;
        $response['time'] = $time;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to upload the photo.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'No photo uploaded.';
}

echo json_encode($response);
?>
