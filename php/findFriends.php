<?php
// get_users.php - Fetch users from the database using MySQLi and include profile picture from user_details

// Database connection settings
include 'connectDatabase.php';



// SQL query to join the registration table with the user_details table
$query = "
    SELECT r.ID, r.firstName, r.email, u.profile_pic 
    FROM registration r
    LEFT JOIN user_details u ON r.ID = u.owner_id
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch users and output as JSON
    $users = [];
    while ($row = $result->fetch_assoc()) {
        // Check if profile_pic exists, if not, set a default placeholder
        $row['profile_pic'] = $row['profile_pic'] ? $row['profile_pic'] : 'https://via.placeholder.com/150';
        $users[] = $row;
    }

    // Set the content type to JSON and output the data
    header('Content-Type: application/json');
    echo json_encode($users);
} else {
    echo json_encode([]); // Return an empty array if no users found
}

// Close the connection
$conn->close();
?>
