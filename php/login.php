<?php
session_start();

header('Content-Type: application/json');

include("connectDatabase.php");

$data = json_decode(file_get_contents('php://input'), true);
$studentemail = $data['useremail'];
$studentpassword = $data['userpassword'];

// Fetch user data from the database
$stmt = $conn->prepare("SELECT email, password,ID FROM registration WHERE email = ?");
$stmt->bind_param("s", $studentemail);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    $stmt->bind_result($db_email, $db_password, $db_ID);
    $stmt->fetch();
    //if (password_verify($studentpassword, $db_password)) {
    
    $_SESSION['user_id'] = $db_ID;
$_SESSION['email'] = $db_email;

    echo json_encode(['email' => $db_email, 'password' => $db_password, 'ID' => $db_ID]);
    // } else {
    // echo json_encode(['error' => 'invalid password']);
    // }

    // Verify the password
} else {
    echo json_encode(['error' => 'invalid email address']);
}
$stmt->close();
$conn->close();
?>