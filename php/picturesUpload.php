<?php
session_start();
include 'connectDatabase.php';

// Get the owner_id (lastId) from the POST data
$owner_id = isset($_POST['lastId']) ? $_POST['lastId'] : null;

if ($owner_id) {
    // Set session user_id to owner_id
    $_SESSION['user_id'] = $owner_id;
    echo "The original last ID is: " . htmlspecialchars($owner_id);
} else {
    echo "No last ID was submitted.";
    exit; // Stop the script if no last ID is provided
}

// Get the email from the session (if needed)
// $owner_email = $_SESSION['email'] ?? null;

// Define the directory to store uploaded images
$upload_dir = '../uploads/';

// Ensure the upload directory exists with proper permissions
if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        echo "Error: Unable to create the upload directory.";
        exit;
    }
}

// Check if the form was submitted with files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['additionalPics'])) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; // Allowed image extensions
    $files = $_FILES['additionalPics'];
    $uploaded_images = [];

    // Loop through all files in the array
    foreach ($files['name'] as $index => $file_name) {
        $tmp_name = $files['tmp_name'][$index];
        $file_size = $files['size'][$index];
        $file_error = $files['error'][$index];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Get file extension

        // Check for file errors
        if ($file_error === UPLOAD_ERR_OK) {
            // Validate file size (optional)
            if ($file_size > 5000000) { // 5MB limit (adjust as needed)
                echo "Error: File '$file_name' is too large.";
                continue;
            }

            // Validate file extension
            if (!in_array($file_ext, $allowed_extensions)) {
                echo "Error: File '$file_name' has an invalid extension.";
                continue;
            }

            // Define the file path using a secure approach (consider using a hash or random string)
            $file_path = $upload_dir . uniqid('', true) . '.' . $file_ext;

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($tmp_name, $file_path)) {
                $uploaded_images[] = $file_path;

                // Insert the file path into the database
                $stmt = $conn->prepare("INSERT INTO gallery (owner_id, image_path) VALUES (?, ?)");
                $stmt->bind_param("is", $owner_id, $file_path);

                // Execute the statement
                if (!$stmt->execute()) {
                    echo "Error inserting record: " . $stmt->error;
                    continue; // Skip this image if insertion fails
                }
                $stmt->close();
            } else {
                echo "Error uploading file: $file_name.";
            }
        } else {
            echo "File error with '$file_name'. Error code: $file_error.";
        }
    }

    // Check if any images were uploaded
    if (count($uploaded_images) > 0) {
        // Redirect after successful upload
        header("Location: ../html/profile.html");
        exit; // Ensure the script stops here after redirection
    } else {
        echo "No valid images uploaded.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "No files uploaded.";
}
?>
