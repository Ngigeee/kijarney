let currentQuestion = 1; 
const totalQuestions = 10;
const progressBar = document.getElementById('progressBar');

// Function to move to the next question and update progress
function nextQuestion(questionNumber) {
  // Hide the current question
  document.getElementById('question' + questionNumber).classList.remove('active');
  
  // Show the next question
  currentQuestion++;
  if (currentQuestion <= totalQuestions) {
    document.getElementById('question' + currentQuestion).classList.add('active');
  }

  // Update the progress bar
  const progress = (currentQuestion - 1) / totalQuestions * 100;
  progressBar.style.width = progress + '%';
}

// Function to go back to the previous question
function previousQuestion(questionNumber) {
  // Hide the current question
  document.getElementById('question' + questionNumber).classList.remove('active');
  
  // Show the previous question
  currentQuestion--;
  if (currentQuestion >= 1) {
    document.getElementById('question' + currentQuestion).classList.add('active');
  }

  // Update the progress bar
  const progress = (currentQuestion - 1) / totalQuestions * 100;
  progressBar.style.width = progress + '%';
}

// Function to submit the form
function submitForm() {
  let allFieldsFilled = true;

  // Loop through each question card to check if required fields are filled
  for (let i = 1; i <= totalQuestions; i++) {
    const question = document.getElementById('question' + i);
    const inputs = question.querySelectorAll('input[required], textarea[required], select[required]'); // Check all required fields
    
    inputs.forEach(input => {
      // Debugging: Log the field being checked
      console.log(`Checking field: ${input.name} - Value: "${input.value}"`);

      if (input.type === 'file' && !input.files.length) {
        // If the field is a file input and no file is selected, mark it as empty
        console.log(`File input empty: ${input.name}`);
        allFieldsFilled = false;
        highlightField(input);
      } else if (input.type === 'radio' && !input.checked) {
        // If it's a radio button, check if one option is selected
        console.log(`Radio button not checked: ${input.name}`);
        allFieldsFilled = false;
        highlightField(input);
      } else if (!input.value.trim()) {
        // If any field is empty (including text areas and select elements)
        console.log(`Text input empty: ${input.name}`);
        allFieldsFilled = false;
        highlightField(input);
      }
    });
  }

  if (!allFieldsFilled) {
    // If any field is not filled, alert the user and focus on the first empty field
    alert("Please fill in all the required fields before submitting.");
  } else {
    // If all fields are filled, proceed with form submission
    // Show loading button
    document.querySelector('.loading').classList.add('active');
    document.querySelector('.btn').style.display = 'none'; // Hide the submit button while loading

    // Create a new FormData object to handle the form data, including files
    const formData = new FormData(document.querySelector('form'));

    // Send the form data using AJAX (fetch API or XMLHttpRequest)
    fetch('../php/firsttimeDetails.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json()) // Assuming the server returns a JSON response
    .then(data => {
      // Handle the response here (e.g., success message, redirect, etc.)
      if (data.success) {
        // Redirect to the next page or show a success message
        setTimeout(() => {
          window.location.href = 'pictureUpload.html'; // Redirect after successful submission
        }, 2000);
      } else {
        // Handle error response (show error message, etc.)
        alert('Error submitting form: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred. Please try again.');
    });
  }
}

// Function to highlight empty fields that need to be filled
function highlightField(input) {
  const parent = input.closest('.question-container');
  parent.style.border = '2px solid red'; // Highlight with red border
  
  // Optional: Focus on the first empty field
  if (!document.querySelector('.highlighted')) {
    input.focus();
    input.classList.add('highlighted');
  }
}

// Function to preview the profile image
function previewProfileImage() {
  const fileInput = document.getElementById('profilePic');
  const file = fileInput.files[0];
  const previewDiv = document.getElementById('filePreview');
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      previewDiv.innerHTML = ''; // Clear previous preview
      previewDiv.appendChild(img);
    };
    reader.readAsDataURL(file);
  }
}

// Function to preview multiple images
function previewMultipleImages() {
  const files = document.getElementById('multiplePics').files;
  const previewDiv = document.getElementById('multiPreview');
  previewDiv.innerHTML = ''; // Clear previous preview

  Array.from(files).forEach(file => {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      previewDiv.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
}
  const urlParams = new URLSearchParams(window.location.search);
        const encryptedData = urlParams.get('data');

        // If there's no 'data' parameter, show an error and redirect
        if (!encryptedData) {
            alert("Error occurred: No data found in URL. Redirecting to the registration page.");
            window.location.href = "http://localhost/kijarney/html/register.html";  // Redirect to your registration page
        } else {
            // If the 'data' parameter exists, proceed with decryption

            // Replace URL-safe base64 characters with standard base64
            const base64Data = encryptedData.replace(/-/g, '+').replace(/_/g, '/');

            // Decode the base64 to get the encrypted text and IV
            const decodedData = atob(base64Data);
            const [encryptedText, ivBase64] = decodedData.split('::');

            // Convert the IV from base64
            const iv = CryptoJS.enc.Base64.parse(ivBase64);

            // Your encryption key (should be the same as used server-side)
            const key = CryptoJS.enc.Utf8.parse("mysecretkey12345"); // Replace with your actual secret key

            // Decrypt the data using AES-256-CBC
            const decrypted = CryptoJS.AES.decrypt(encryptedText, key, {
                iv: iv
            });

            // Convert the decrypted data to a UTF-8 string
            const decryptedData = decrypted.toString(CryptoJS.enc.Utf8);

            if (decryptedData) {
                // Split the decrypted data back into the parameters
                const [firstName, email, id] = decryptedData.split('::');

                // Display the decrypted information
                document.getElementById('confirmation').innerHTML = `
                    <p>Welcome, ${firstName}!</p>
                    <p>Email: ${email}</p>
                    <p>ID: ${id}</p>
                `;
            } else {
                document.getElementById('confirmation').innerHTML = "<p>Error decrypting data.</p>";
            }
        }