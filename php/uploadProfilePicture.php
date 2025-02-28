<?php
// Start the session to access the user_id
session_start();

// Database connection settings
include("connectDatabase.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profilePic"])) {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // Get the uploaded file information
        $file = $_FILES["profilePic"];
        $fileName = $_FILES["profilePic"]["name"];
        $fileTmpName = $_FILES["profilePic"]["tmp_name"];
        $fileSize = $_FILES["profilePic"]["size"];
        $fileError = $_FILES["profilePic"]["error"];
        $fileType = $_FILES["profilePic"]["type"];

        // Check if there was an error with the file upload
        if ($fileError === 0) {
            // Validate file type (allow only image files)
            $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (in_array($fileType, $allowedTypes)) {
                // Set a unique file name to avoid conflicts
                $uniqueFileName = uniqid("profile_", true) . "." . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $uploadDirectory = "../uploads/"; // Folder where to store the uploaded profile pictures
                $fileDestination = $uploadDirectory . $uniqueFileName;

                // Move the uploaded file to the designated folder
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Get the logged-in user's ID
                    $userId = $_SESSION['user_id'];

                    // Insert the file path into the database with the user_id
                    $sql = "UPDATE user_details SET profile_picture = '$fileDestination' WHERE user_id = '$userId'"; 
                    if ($conn->query($sql) === TRUE) {
                        echo "Profile picture uploaded successfully!";
                    } else {
                        echo "Error saving to database: " . $conn->error;
                    }
                } else {
                    echo "Failed to upload the file. Please try again.";
                }
            } else {
                echo "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
            }
        } else {
            echo "Error with file upload: " . $fileError;
        }
    } else {
        echo "User not logged in.";
    }
}

// Close the database connection
$conn->close();
?>use  prepared statement
