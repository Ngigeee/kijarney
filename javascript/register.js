function validatePassword(password) {
    const letterRegex = /[a-zA-Z]/;
    const numberRegex = /[0-9]/;
    const specialRegex = /[!@#$%^&*()_+{}"?|<>,.]/;
    const hasLetters = letterRegex.test(password);
    const hasNumbers = numberRegex.test(password);
    const hasSpecial = specialRegex.test(password);
    return hasLetters && hasNumbers && hasSpecial;
}

function validateEmail(email) {
    // Simple email regex pattern (you can improve this pattern if needed)
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailRegex.test(email);
}

let shouldPreventDefault = true;

document.getElementById("submitBtn").addEventListener("click", function validateDetails(event) {
    event.preventDefault(); // Prevent form submission until validation passes

    var studentFirstName = document.getElementById("studentfirstName").value;
    var studentLastName = document.getElementById("studentlastName").value;
    var studentID = document.getElementById("studentID").value;
    var studentEmail = document.getElementById("studentemail").value;
    var studentPassword = document.getElementById("studentpassword").value;
    var confirmPassword = document.getElementById("confirmpassword").value;
    var showpopUp = document.getElementById("showpopUp");

    // Step 1: Validate the email format
    if (!validateEmail(studentEmail)) {
        displaySuccess('red', 'Please enter a valid email address.');
        return; // Stop further execution if email is invalid
    }

    // Step 2: Check if the email already exists
    checkEmailExists(studentEmail, function(emailExists) {
        if (emailExists) {
            displaySuccess('red', 'This email is already registered.');
            return; // Stop further execution and show the "email already exists" message
        } else {
            // Step 3: Validate password length and match
            if (studentPassword.length < 8) {
                displaySuccess('red', 'Password must be at least 8 characters');
                return; // Stop further execution if password is too short
            }

            if (studentPassword !== confirmPassword) {
                displaySuccess('red', 'Passwords do not match.');
                return; // Stop further execution if passwords do not match
            }

            var isValidPassword = validatePassword(studentPassword);
            if (!isValidPassword) {
                displaySuccess('red', 'Password should contain a capital letter, special characters, and numbers.');
                return; // Stop further execution if password is not valid
            }

            // Step 4: Submit the form if all validations pass
            displaySuccess('green', 'Signup successful');
            setTimeout(() => {
                document.getElementById("formpage").submit();  // Submit the form after a brief success message
            }, 2000); // Delay the form submission by 2 seconds
        }
    });
});

// Function to check if email already exists in the database
function checkEmailExists(email, callback) {
    fetch('../php/checkIfemailExist.php', { // Replace with actual server-side script URL
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email }) // Send email to server for validation
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            callback(true); // Email exists in the database
        } else {
            callback(false); // Email does not exist
        }
    })
    .catch(error => {
        displaySuccess('red', 'Error checking email availability.');
        console.error(error);
        callback(false); // In case of an error, assume the email is not taken
    });
}

function displaySuccess(color, message) {
    var showpopUp = document.getElementById("showpopUp");
    showpopUp.style.display = "block";
    showpopUp.style.backgroundColor = color;
    showpopUp.innerHTML = message;

    setTimeout(() => {
        showpopUp.style.display = "none";
    }, 2000); // Hide the message after 2 seconds
}
