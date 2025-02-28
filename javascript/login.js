    document.getElementById("submitBtn").addEventListener("click", function(e) {
                e.preventDefault();
        
                var studentemail = document.getElementById("studentemail").value;
                var studentpassword = document.getElementById("studentpassword").value;
        
                fetch('signup.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        studentemail: studentemail,
                        studentpassword: studentpassword
                    })
                })
                .then(response => response.json())
                .then(data => {
                    
                    if (data.error) {
                        displaySuccess('red', data.error);
                    } else if (studentemail === data.email) {
                        displaySuccess('green', 'Login successful');
                        setTimeout(() => {
                            window.location.href = "form.html";
                        }, 2000);
                    } else {
                        displaySuccess('red', 'Invalid credentials');
                        
                    }
                })
                .catch(error => {
                    displaySuccess('red', 'Error: ' + error.message);
                });
            });
        
            function displaySuccess(color, message) {
                var showpopUp = document.getElementById("showpopUp");
                showpopUp.style.display = "block";
                showpopUp.style.backgroundColor = color;
                showpopUp.innerHTML = message;
        
                setTimeout(() => {
                    showpopUp.style.display = "none";
                }, 2000); // Hide the message after 2 seconds
            }