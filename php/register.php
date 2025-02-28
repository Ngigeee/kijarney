<?php

include 'connectDatabase.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = trim($_POST['studentfirstName']);
    $lastName = trim($_POST['studentlastName']);
    $studentID = trim($_POST['studentID']);
    $email = trim($_POST['studentemail']);
    $password = trim($_POST['studentpassword']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }

    // Secure password hashing
    $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

    // Check for duplicate email
    $emailCheckStmt = $conn->prepare("SELECT email FROM registration WHERE email = ?");
    if ($emailCheckStmt === false) {
        die("Error preparing email check query: " . $conn->error);
    }
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckStmt->store_result();
    if ($emailCheckStmt->num_rows > 0) {
        $emailCheckStmt->close();
        die('Email already exists.');
    }
    $emailCheckStmt->close();

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO registration (id_number, password, firstName, lastName, email) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing insert query: " . $conn->error);
    }

    $stmt->bind_param("sssss", $studentID, $hashedPassword, $firstName, $lastName, $email);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;  // Get the inserted ID
       

        // Map of digits to corresponding strings
        $replacementMap = [
            '1' => 'tqwrgfkskmoslzfsdswsq',
            '2' => 'wbczeancdejblbqdufkhi',
            '3' => 'abcdefghijklmnopqrst',
            '4' => 'sdfgertyy3klrslkdfd21sd',
            '5' => 'aabbccddzzxxyyqqww22ppqq',
            '6' => 'asdsdsdsdsdsdsdsdsdsdsd',
            '7' => 'dsaafdsfaasffaqweeewqwr',
            '8' => 'qwertertertstsdflkj34sd',
            '9' => 'szxswefcwerq2sdqrt2e11k'
        ];

        // Function to replace digits with corresponding strings
        function replaceDigitsWithString($input, $replacementMap) {
            $output = "";
            
            // Iterate through each character in the string
            for ($i = 0; $i < strlen($input); $i++) {
                $digit = $input[$i];
                
                // Check if the character is a digit and exists in the replacement map
                if (isset($replacementMap[$digit])) {
                    // Append the corresponding string to the output
                    $output .= $replacementMap[$digit];
                } else {
                    // If it's not a digit, just append it as is (but there should only be digits here)
                    $output .= $digit;
                }
            }
            
            return $output;
        }

        // Convert the last_id (which is a number) to a string, and then replace digits with mapped strings
        $replacedString = replaceDigitsWithString((string)$last_id, $replacementMap);

        // Generate a random number and replace digits using the map
        $randomNumber = rand(1, 10000000);
        $otherString = replaceDigitsWithString((string)$randomNumber, $replacementMap);

        // URL-encode the resulting string to make it URL safe
        $urlSafeData = urlencode($replacedString);

        // Send welcome email
        $subject = "Welcome to Kijarney Dating Site!";
        $message = "
            <html>
            <head>
                <title>Welcome to Kijarney</title>
            </head>
            <body>
                <p>Dear $firstName,</p>
                <p>Welcome to Kijarney! We're excited to have you join our dating community. Start meeting new people and exploring exciting opportunities today!</p>
                <p>Click the link below to confirm your email and start your journey:</p>
                <p><a href='http://localhost/kijarney/html/pictureUpload.html?q=$otherString&data=$urlSafeData'>Confirm your email and visit Kijarney</a></p>
            </body>
            </html>
        ";

        // Set content-type header for HTML emails
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@kijarney.com" . "\r\n";

        // Send the email
        if (mail($email, $subject, $message, $headers)) {
            echo "Registration successful and welcome email sent.";
        } else {
            echo "Error sending email.";
        }

        // Redirect the user to the confirmation page with `q` first, followed by `data`
        header("Location: http://localhost/kijarney/html/confirmEmail.html?q=$otherString&data=$urlSafeData");
        exit();

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
