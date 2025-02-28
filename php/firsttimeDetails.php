<?php
// Retrieve the raw POST data (which is in JSON format)
$data = json_decode(file_get_contents('php://input'), true);

// Check if the data is valid
if ($data) {
    // Extract data from the decoded JSON
    $userInterests = $data['userInterests'];
    $dob = $data['dob'];
    $firstDate = $data['firstDate'];
    $hobby = $data['hobby'];
    $personality = $data['personality'];
    $pet = $data['pet'];
    $smoking = $data['smoking'];
    $travel = $data['travel'];
    $music = $data['music'];
    $favouriteFood = $data['favouriteFood'];
    $favouriteColor = $data['favouriteColor'];

    // Example: You can insert the data into the database
    include 'connectDatabase.php';  // Assuming you have a database connection here
    
    // Corrected SQL query
    $stmt = $conn->prepare("INSERT INTO user_details (dob, first_date, hobby, personality, pet, smoking, travel, music, favorite_food, interests, favColor) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssssssssss", $dob, $firstDate, $hobby, $personality, $pet, $smoking, $travel, $music, $favouriteFood, $userInterests, $favouriteColor);

    // Execute the statement and handle the response
    if ($stmt->execute()) {
        // Send a success response back to the client
        echo json_encode(['message' => 'Record successfully inserted']);
    } else {
        // Send an error response back to the client
        echo json_encode(['message' => 'Error: ' . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['message' => 'Invalid JSON data']);
}
?>
