<?php
// Start the session to access session variables
session_start();

// Check if the session is valid or if specific session variables are set
if (isset($_SESSION['username']) && isset($_SESSION['email'])) {
    // Return the session data as a JSON response
    echo json_encode([
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'userId' => $_SESSION['myId']
    ]);
} else {
    // If session data does not exist, return an empty response or an error message
    echo json_encode([
        'error' => 'Session not found'
    ]);
}
?>
