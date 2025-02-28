<?php
session_start(); 
// Database connection
include 'connectDatabase.php';

// Check if the user is logged in
//if (!isset($_SESSION['user_id'])) {
   // echo json_encode(['error' => 'User is not logged in']);
    //exit;
//}else{
$owner_id =1;
//}

// Get the logged-in user's ID

//$_SESSION['user_id'];

// Prepare the SQL query to fetch user data excluding profile_pic, owner_id, and email
$query = "SELECT dob, first_date, hobby, personality, pet, smoking, travel, music, favorite_food, interests, favColor,occupation,location,age FROM user_details WHERE owner_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$stmt->bind_result($dob, $firstDate, $hobby, $personality, $pet, $smoking, $travel, $music, $favoriteFood, $interests,$favouriteColor,$occupation,$location,$age);

// Fetch the data
$data = [];
if ($stmt->fetch()) {
    $data = [
        'dob' => $dob,
        'firstDate' => $firstDate,
        'hobby' => $hobby,
        'personality' => $personality,
        'pet' => $pet,
        'smoking' => $smoking,
        'travel' => $travel,
        'music' => $music,
        'favoriteFood' => $favoriteFood,
        'interests' => $interests,
        'favColor' => $favouriteColor,
        'occupation' => $occupation,
        'location' => $location,
        'age' => $age,
     
    ];
} else {
    // No data found for the user
    $data = ['error' => 'No user data found'];
}

// Return the result as JSON
echo json_encode($data);

// Close statement and connection
$stmt->close();
$conn->close();
?>
