
<?php 
session_start();
$loggedin_id =13;
// $_SESSION['myId'];
$connection = new mysqli('localhost', 'root', '', 'test'); //
// Check for connection errors 
if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
} else {
    $stmt = $connection->prepare("SELECT firstName, image_path,ID FROM registration WHERE ID !=? ORDER BY ID ASC");
    $stmt->bind_param("i", $loggedin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store usernames and image paths in arrays
    $usernames = array();
    $imagePaths = array();
    $ID = array();
    while ($row = $result->fetch_assoc()) {
        $usernames[] = $row['firstName'];
        $imagePaths[] = $row['image_path'];
        $ID[] = $row['ID'];
    }

}


// Include database connection (assuming it's in a separate file)
$conn = new mysqli('localhost', 'root', '', 'test');

// Check for connection errors
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Function to retrieve image path based on username


// Session handling



// Function to retrieve user data based on user ID
function get_user_data($user_id, $conn)
{
    $sql = "SELECT * FROM registration WHERE ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close(); // Close statement before returning
        $result->close(); // Close result before returning
        return $row;
    } else {
        $stmt->close(); // Close statement before returning
        $result->close(); // Close result before returning
        return null; // Indicate no user data found
    }
}

// Session handling
if (isset($_GET['id'])) {
    if (isset($conn)) {
        $recipientData = get_user_data($recipient_id, $conn); // Fetch profile information of the recipient

        if ($recipientData) {
            // User data found, extract thextract recipient's profile information
            $recipient_username = $recipientData['firstName'];

            $recipient_email = $recipientData['email'];
            $recipient_workPlace = $recipientData['work'];
            $recipient_Hobbies = $recipientData['hobbies'];
            $recipient_Bio = $recipientData['bio'];
            $recipient_PhoneNumber = $recipientData['phone'];
            $recipient_image_path = $recipientData['image_path'];
            // Extract other fields as needed for the recipient

            // Now, you have both logged-in user's and recipient's profile information

            // Proceed with HTML content to display both profiles
            // Your HTML content goes here...
        } else {
            echo "recipient data not found.";

        }
    } else {
        echo "Database connection not available.";
    }
} else {
    // Handle the case where user ID is not available (e.g., not logged in)
    // Make sure to stop the script execution after redirection



    if (isset($_SESSION['myId'])) {


        // Check if $conn is defined
        if (isset($conn)) {
            $userData = get_user_data($loggedin_id, $conn);// Fetch profile information of the recipient

            if ($userData) {
                // User data found, extract the fields for both logged-in user and recipient
                $username = $userData['firstName'];
                $email = $userData['email'];
                $workPlace = $userData['work'];
                $Hobbies = $userData['hobbies'];
                $Bio = $userData['bio'];
                $PhoneNumber = $userData['phone'];
                $image_path = $userData['image_path'];

                // Extract recipient's profile informa
            } else {
                echo "User data not found.";
            }
        } else {
            echo "Database connection not available.";
        }
    } else {
        // Handle the case where user ID is not available (e.g., not logged in)
$loggedin_id =19;
        //header("location:error.php");
        //exit(); // Make sure to stop the script execution after redirection
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="copyHomepage.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <div class="holdNavs">
        <div class="allNavs">
            <div class="storyStatus">
                <p style="font-size: large;margin-top:55%;margin-left:35%;height: 10px;
            width: 10px;background:green;border-radius:50%;"></p>
                <p style="font-size: x-small;margin-top:45%;color:green;"> online</p>
            </div>
            <div class="addStory" id="addStory">
                <form action="uploadStories.php" method="post" enctype="multipart/form-data" id="myForm"
                    style="background:transparent;margin-top:18%;">
                    <input type="file" name="fileToUpload" id="fileToUpload" hidden>
                    <label for="fileToUpload" id="fileUploadButton"
                        style=" margin:5%;padding:12px;border-radius:7px;text-align:center;font-size:medium;font-weight:bold;border:none;color:white;background:transparent; cursor: pointer;">
                        + Upload Story
                    </label>

                    <input type="submit" name="fileToUpload" id="submitUpload" hidden>

                </form>
                <p style="font-size: x-small;margin-top:25%;color:black;">Add Story</p>
            </div>
            <p id="showMyStoryline"
                style="font-size: x-small;margin-top:75%;margin-left:15%;color:transparent;font-weight:bold;display:none;">
                my
                Story</p>



        </div>

        <div class="userNavs" id="userNavs">
            <div class="theStoryDiv">



            </div>

        </div>

        <div class="seeAllStories" id="seeAllStories">
            <p class="fa fa-envira" style="color:white;font-size: large;margin-top:25%;"> </p>
            <p style="font-size:x-small;color:darkblue;font-weight:bold;"> see all</p>
        </div>

        <div class="searchCont">
            <div class="searchUser">

                <input type="text" placeholder="search for matches" id="searchMatch" name="searchMatch"
                    class="fa fa-home">

                <p class="fa fa-bell"
                    style="outline:solid;outline-color:lightgrey;outline-width:1px;color:lightgrey;font-size: large;margin-top:1%;margin-right:2%;margin-left:2%;background:transparent;height:30px;width:30px;border-radius:50%;display:none;">
                </p>

                <button onclick="displaySettings()" class="fa fa-gear fa-spin" id="displaySettings"></button>
            </div>
        </div>
    </div>


    <div class="header">
        <img class="smallPic" src="<?php
        if (isset($_GET['id'])) {
            echo $recipient_image_path;
        } else {
            echo $image_path;
        }

        ?>" alt="<?php
        if (isset($_GET['id'])) {
            echo $recipient_username;
        } else {
            echo $username;
        }



        ?>'s Profile image ">
        <p style="margin-left:25%;margin-top:-2%;font-family:'Courier New', Courier, monospace;display:none;">You</p>

        <ul class="navigation">


            <li><a href="copyHomepage.php" class="fa fa-home" type="homePage">&nbspHome</a></li>

            <li><a href="#" class="fa fa-envelope" id="seeAllTexts">&nbspTexts</a></li>
            <li><a href="homepage.php" class="fa fa-user" id="seeMatches">&nbspmatch</a></li>
            <li><a href="upload.html" class="fa fa-calendar-o" id="contactHelp">+ Upload</a></li>
            <li><a href="profile.php" class="fa fa-calendar-o">&nbspProfile</a></li>
            <li><a href="settings.html" class="fa fa-calendar-o">&nbspSettings</a></li>
        </ul>

    </div>
    <script>
        const contactHelp = document.getElementById("contactHelp");
        contactHelp.addEventListener("click", function () {
            fetch("help&support.html")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("col-2").innerHTML = "";
                    document.getElementById("col-2").innerHTML = data;
                })
        });

    </script>

    <div class="allelements">

        <script>
            // script.js
            // script.js
            document.addEventListener("DOMContentLoaded", function () {
                // Show the loader
                document.getElementById('loader').style.display = 'block';
                document.body.style.display = 'none';

                // Function to dynamically load a script
                function loadScript(url, callback) {
                    var script = document.createElement('script');
                    script.src = url;

                    script.onload = function () {
                        callback();
                    };

                    script.onerror = function () {
                        console.error('Failed to load script:', url);
                        // Hide loader and show content even if script fails
                        document.getElementById('loader').style.display = 'none';
                        document.body.style.display = 'block';
                    };

                    document.head.appendChild(script);
                }

                // Call the function to load a specific script
                loadScript('copyHomepage.js', function () {
                    // Script loaded successfully, hide loader and show content
                    document.getElementById('loader').style.display = 'none';
                    document.body.style.display = 'block';
                });
            });

            function displaySettings() {

                document.getElementById("allMySettings").style.display = "block";
            }

            function closeSettings() {
                document.getElementById("allMySettings").style.display = "none";
            }
        </script>

        <!-- Pop-up Container -->
        <div id="popupContainer" class="popup-container">
            <div class="popup-content">
                <h3 style="text-align:center;color:white;">Are you sure you want to<br>unmatch?</h3>
                <button style="text-align:center;margin-left:35%;">ok</button>
                <button style="text-align:center;margin-left:2%;" id="closePopupBtn">Cancel</button>
                <!-- Add more content as needed -->
            </div>
        </div>
        <div style="padding:3px;background:transparent;width: 57.5%;margin-left:1%;display:inline-flex;">
            <p style="padding:3px;background:transparent;width:auto;font-weight:bold;color:white;margin-left:50%;">FIND
                MATCHES:
            </p>

        </div>

        <div class="row">

            <div class="allMatchescontent">
            <iframe src="seeEveryMatch.php" name="iframe_a" class="homepagePage"></iframe>

            </div>
            
            <p class="myarrangements">All
            </p>
            <p class="myarrangements">
                Following</p>
            <p class="myarrangements">
                Popular</p>
            <p class="myarrangements">
                suggested</p>
            <div class="col-1" id="holdingPhoto">
                <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                <div class="loader" id="loader" style="display:block;margin:50%;"></div>





            </div>

            <div class="col-2" id="col-2">
                <div class="loader" id="loader" style="display:none;"></div>

                <div class="searchForPeople" id="searchForPeople">


                </div>
                <div>

                    <div class="allMySettings" id="allMySettings">
                        <button id="closeSettings" onclick="closeSettings()">&times;close</button>
                        <br>
                        <div>Account</div>
                        <select>
                            <option>personal and account information</option>
                            <option>password and authorization</option>
                            <option>personal details</option>
                            <option>Back up</option>
                            <option>Delete account</option>
                        </select>
                        <hr>
                        <div>Privacy</div>
                        <select>
                            <option>my activities</option>
                            <option>password and authorization</option>
                            <option>personal details</option>
                        </select>
                        <hr>
                        <div>Account</div>
                        <select>
                            <option>personal and account information</option>
                            <option>password and authorization</option>
                            <option>personal details</option>
                        </select>
                        <hr>
                        <div>Permissions</div>
                        <select>
                            <option>location</option>
                            <option>apps</option>
                            <option>camera</option>
                        </select>
                        <hr>
                        <div>Language</div>
                        <hr>
                        <div>Terms of Service</div>
                        <hr>
                        <div>Privacy Policy</div>
                        <hr>
                        <div>Cookies Policy</div>
                        <hr>
                        <div>Updates</div>
                        <hr>
                        <div>Invite a Friend</div>
                    </div>
                    <div>
                        <div class="username-overlay"
                            style="color:black;display:inline-flex;background:white;width:100%;">

                            <p class="profession">
                                <a class="fa fa-user">
                                </a> people to match
                            </p>
                            <p id="seeAllMatches" style="margin-left:55%;color:darkblue;font-size:medium;">
                                see all
                            </p>
                        </div>
                        <div class="content">
                            <?php
                            // Loop through each username and image path and create a div with the image and username
                            for ($i = 0; $i < count($usernames); $i++) {


                                echo '<div class="matchContainer" id="matchContainer" data-recipient-id="' . $ID[$i] . '">';
                                echo '<div class="profileImg" id="profileImg"data-recipient-id="' . $ID[$i] . '">';
                                echo '<img src="' . $imagePaths[$i] . '" alt="' . $usernames[$i] . 'Profile Image" class="matchPic">';
                                echo '</div>';
                                echo '<button class="username">';
                                echo $usernames[$i];
                                echo '</button>';
                                echo ' <button class="matching" id="matchBtn">';
                                echo '<a class="fa fa-user" href="#">';
                                echo '</a>';
                                echo '&nbsp&nbspMatch';
                                echo '</button>';
                                echo '</div>';


                            }
                            ?><br><br>

                        </div>
                        <div class="username-overlay"
                            style="color:black;display:inline-flex;background:white;width:100%;">

                            <p class="profession">
                                <a class="fa fa-image">
                                </a>
                                Saved Photos
                            </p>
                            <p style="margin-left:55%;color:darkblue;font-size:medium;">
                                see all
                            </p>
                        </div>

                        <div id="holdSavedImg" style="height:300px;background:white;width:auto;overflow-y:hidden;">

                        </div>

                        <br>
                        <br>
                        <div class="allActivities"
                            style="color:black;background:white;width:100%;height:230px;box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.5);">
                            <div class="username-overlay"
                                style="color:black;display:inline-flex;background:white;width:100%;">

                                <p class="profession">
                                    User Activity
                                </p>
                                <p style="margin-left:55%;color:darkblue;font-size:medium;">
                                    see all
                                </p>
                            </div>

                            <table id="userInfo">

                                <tr>
                                    <td style="color:skyblue;">
                                        <a class="fa fa-star"></a>
                                    </td>
                                    <td style="color:black;">
                                        my recent activity
                                    </td>

                                </tr>
                                <tr>
                                    <td style="color:red;">
                                        <a class="fa fa-heart"></a>
                                    </td>
                                    <td style="color:black;">
                                        my last activity
                                    </td>

                                </tr>
                                <tr>
                                    <td style="color:skyblue;">
                                        <a class="fa fa-text"></a>
                                    </td>
                                    <td style="color:black;">
                                        my past activity
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </div>
</body>
<script>
    function displayMyStory() {
        fetch("displayMyStory.php")
            .then(response => response.text())
            .then(data => {
                var recentStory = document.getElementById("addStory");
                var mystoryLine = document.getElementById("showMyStoryline");
                mystoryLine.style.display = "block";
                recentStory.style.backgroundColor = "white";
                recentStory.innerHTML = data;



            })
    }


    //uploading a story
    document.getElementById("fileToUpload").addEventListener("change", function () {
        const body = document.body;
        var showImg = document.createElement("div");
        showImg.setAttribute("style", "position: fixed;top: 0;left: 0;width: 100%;height: 100%; background: white;z-index:2000;overflow:auto;");
        var submitImg = document.createElement("input");
        submitImg.setAttribute("type", "button");
        submitImg.setAttribute("name", "submit");
        submitImg.setAttribute("value", "Share Story");
        submitImg.setAttribute("style", "background: darkblue;border:none;border-radius:7px;height:10%;color:white;float:right; position: absolute; bottom: 8px;right: 16px;font-size: 18px;width:fit-content;");
        var galleryImage = document.createElement("img");
        var chosenvideo = document.createElement("video");
        chosenvideo.setAttribute("controls", "");
        var sourcevideo = document.createElement("source");
        var choseedImg = this.files[0];
        if (choseedImg.type === "video/mp4") {
            sourcevideo.src = URL.createObjectURL(this.files[0]);
            chosenvideo.style.width = "100%";
            chosenvideo.style.marginLeft = "20%";
            chosenvideo.style.marginRight = "10%";
            chosenvideo.style.height = "fit-content";
            chosenvideo.appendChild(sourcevideo);
            showImg.appendChild(chosenvideo);
            showImg.appendChild(submitImg);
        } else {
            galleryImage.src = URL.createObjectURL(this.files[0]);
            galleryImage.style.width = "100%";
            galleryImage.style.marginLeft = "2%";
            galleryImage.style.marginRight = "10%";
            galleryImage.style.height = "fit-content";
            showImg.appendChild(galleryImage);
            showImg.appendChild(submitImg);
        }
        body.appendChild(showImg);

        // Add event listener to submit button
        submitImg.addEventListener("click", function () {
            var formSubmit = document.getElementById("myForm").submit();

        });
    });


    const urlParams = new URLSearchParams(window.location.search);
    const hasParam = urlParams.has("success");
    if (hasParam) {
        displayMyStory();
    }


    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('fileUploadButton').addEventListener('click', function () {
            document.getElementById('actualFile').click();
        });

        document.getElementById('actualFile').addEventListener('change', function () {
            const fileInput = document.getElementById('actualFile');
            const formData = new FormData();
            formData.append('videoImage', fileInput.files[0]);

            fetch('uploadStories.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json()) // Assuming the server responds with JSON
                .then(data => {
                    alert('Success:', data);
                    // Handle success here
                })
                .catch((error) => {
                    alert('Error:', error);
                    // Handle error here
                });
        });
    });






    function showSearches() {

        var searchinput = document.getElementById("searchMatch");
        var getDisplay = document.getElementById("searchForPeople");

        searchinput.addEventListener("keyup", function () {
            if (searchinput.value == "") {
                getDisplay.style.display = "none";
            } else {
                getDisplay.style.display = "block";
                getDisplay.innerHTML = "";
                const searchText = searchinput.value;

                fetch('searchUser.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ searchText: searchText })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.length == 0) {
                            getDisplay.innerHTML = "No Results Found";
                        } else {
                            var results = [];

                            data.forEach(result => {
                                const profileimageSearchdiv = document.createElement('div');
                                profileimageSearchdiv.setAttribute("style", "width:100%;background:white;display:inline-flex;margin-top:7%;margin-top:7%;height:100px;flex:wrap;")
                                const searchedImage = result.image_path;
                                const fullImage = document.createElement('img');
                                fullImage.setAttribute("style", "width:60px;height:60px;border-radius:50%;background:transparent;margin-right:15%;");
                                fullImage.src = searchedImage;
                                const searchUsername = result.firstName + " " + result.lastName;
                                const fullIName = document.createElement('p');
                                fullIName.innerHTML = searchUsername;
                                const mysearchBio = result.bio;
                                const searchBio = document.createElement('p');
                                searchBio.setAttribute("style", "margin-top:10%;position:absolute;font-size:small;color:grey;margin-left:30%;font-family:courier;");
                                searchBio.innerHTML = mysearchBio;
                                // Set innerHTML instead of textContent
                                profileimageSearchdiv.appendChild(fullImage);
                                profileimageSearchdiv.appendChild(fullIName);
                                profileimageSearchdiv.appendChild(searchBio);
                                results.push(profileimageSearchdiv);
                            });

                            // Clear any previous results
                            getDisplay.innerHTML = "";
                            results.forEach((result) => {
                                getDisplay.appendChild(result);
                            });
                        }
                    });
            }
        });
    }
    showSearches();
    function displayLatestStories() {
        fetch("fetchingOtherStories.php")
            .then(response => response.json())
            .then(data => {

                if (data && data.length > 0) {
                    const userNavs = document.getElementById("userNavs");
                    // Clear existing content

                    // Iterate over each media item
                    data.forEach(item => {







                        // Add username
                        const username = document.createElement("p");
                        username.setAttribute("style", "background: rgba(0, 0, 0, 0.4);text-align:center;border-radius:7px;height:23px;width:90%;font-size: x-small;color:white;transform:translate(-105%,60px);z-index:10;");
                        username.textContent = item.firstName + " " + item.lastName;


                        const mediaContainer = document.createElement("div");
                        mediaContainer.setAttribute("class", "eachStory");


                        // Check if the media path is a video or image
                        if (item.video_path) {
                            const theProfile = document.createElement("img");
                            theProfile.setAttribute("src", item.profile_image_path);
                            theProfile.setAttribute("style", "  height:30px;width: 40px;border-radius: 50%;z-index:6;transform:translate(-600%,2px);position:relative;");
                            theProfile.setAttribute("alt", "no image");


                            const storyVideo = document.createElement("video");
                            storyVideo.setAttribute("controls", "");
                            storyVideo.setAttribute("class", "myeachvideo");
                            storyVideo.setAttribute("muted", "");
                            const source = document.createElement("source");
                            source.setAttribute("src", item.video_path);
                            source.setAttribute("type", "video/mp4");
                            storyVideo.appendChild(source);
                            mediaContainer.appendChild(storyVideo);
                            mediaContainer.appendChild(username);
                            mediaContainer.appendChild(theProfile);


                        } else if (item.gallery_image_path) {
                            const theProfile = document.createElement("img");
                            theProfile.setAttribute("src", item.profile_image_path);
                            theProfile.setAttribute("style", "  height:30px;width: 30px;border-radius: 50%;z-index:10;transform:translate(-550%,2px);position:relative;");
                            theProfile.setAttribute("alt", "no image");





                            const storyImage = document.createElement("img");
                            storyImage.setAttribute("src", item.gallery_image_path);
                            storyImage.setAttribute("style", "myeachStory");
                            storyImage.setAttribute("alt", "no image");
                            mediaContainer.appendChild(storyImage);
                            mediaContainer.appendChild(username);
                            mediaContainer.appendChild(theProfile);

                        }

                        // Append the profile div to the media div


                        // Append the media div to the media container
                        userNavs.appendChild(mediaContainer);


                    });

                    // Append the media container to the latestMedia container

                } else {
                    console.log("No latest image or video found.");
                }
            })
            .catch(error => {
                console.error("Error fetching latest image or video:", error);
            });
    }
    displayLatestStories();


    function showAllStories() {
        fetch("fetchingOtherStories.php")
            .then(response => response.json())
            .then(data => {

                if (data && data.length > 0) {
                    const myAppend = document.getElementById("col-2");
                    const showStories = document.createElement("div");
                    showStories.setAttribute("class", "displayAllStories");
                    showStories.setAttribute("id", "displayAllStories");
                    // Clear existing content
                    const closeAllStories = document.createElement("p");
                    closeAllStories.setAttribute("style", "font-size:x-large;margin-bottom:5%;width:100%;color:black;float:right;");
                    closeAllStories.setAttribute("id", "closeAllStories");
                    closeAllStories.innerHTML = "&times;";
                    // Create a container for the media items

                    showStories.appendChild(closeAllStories);
                    // Iterate over each media item
                    data.forEach(item => {




                        // Add username
                        const username = document.createElement("p");

                        username.setAttribute("style", "font-size:medium;margin-top:4%;color:black;");
                        username.textContent = item.firstName + " " + item.lastName;



                        const mediaContainer = document.createElement("div");
                        mediaContainer.setAttribute("class", "holdEachContainer");


                        // Check if the media path is a video or image
                        if (item.video_path) {

                            const storyVideo = document.createElement("video");
                            storyVideo.setAttribute("controls", "");
                            storyVideo.setAttribute("class", "myAlleachStoryvideo");
                            storyVideo.setAttribute("muted", "");
                            const source = document.createElement("source");
                            source.setAttribute("src", item.video_path);
                            source.setAttribute("type", "video/mp4");
                            storyVideo.appendChild(source);
                            mediaContainer.appendChild(storyVideo);
                            mediaContainer.appendChild(username);

                        } else if (item.gallery_image_path) {

                            const storyImage = document.createElement("img");
                            storyImage.setAttribute("src", item.gallery_image_path);
                            storyImage.setAttribute("class", "myAlleachStoryimage");
                            storyImage.setAttribute("alt", "Gallery Image");
                            mediaContainer.appendChild(storyImage);
                            mediaContainer.appendChild(username);
                        }

                        // Append the profile div to the media div


                        // Append the media div to the media container
                        showStories.appendChild(mediaContainer);
                        myAppend.appendChild(showStories);

                    });

                    document.getElementById("closeAllStories").addEventListener("click", function () {

                        document.getElementById("displayAllStories").style.display = "none";
                    });
                    // Append the media container to the latestMedia container

                } else {
                    console.log("No latest image or video found.");
                }
            })
            .catch(error => {
                console.error("Error fetching latest image or video:", error);
            });
    }
    document.getElementById("seeAllStories").addEventListener("click", showAllStories);


</script>

</html>