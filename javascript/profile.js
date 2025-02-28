 // Example data that would be fetched from the form
    document.addEventListener('DOMContentLoaded', function() {
      const profilePic = document.getElementById('profilePicDisplay');
      const dobDisplay = document.getElementById('dobDisplay');
      const firstDateDisplay = document.getElementById('firstDateDisplay');
      const hobbyDisplay = document.getElementById('hobbyDisplay');
      const personalityDisplay = document.getElementById('personalityDisplay');
      const petsDisplay = document.getElementById('petsDisplay');
      const smokingDisplay = document.getElementById('smokingDisplay');
      const travelDisplay = document.getElementById('travelDisplay');
      const musicDisplay = document.getElementById('musicDisplay');
      const favoriteFoodDisplay = document.getElementById('favoriteFoodDisplay');
      const favoriteColorDisplay = document.getElementById('favoriteColorDisplay');
      const occupationDisplay = document.getElementById('occupationDisplay');
      const locationDisplay = document.getElementById('locationDisplay');
      const bioDisplay = document.getElementById('bioDisplay');
      
      // Populate profile information (this would normally come from a backend or session)
      dobDisplay.textContent = "03/15/1995";
      firstDateDisplay.textContent = "A fun movie night!";
      hobbyDisplay.textContent = "Hiking";
      personalityDisplay.textContent = "Extrovert";
      petsDisplay.textContent = "Cats";
      smokingDisplay.textContent = "No";
      travelDisplay.textContent = "Yes";
      musicDisplay.textContent = "Indie";
      favoriteFoodDisplay.textContent = "Mexican";
      favoriteColorDisplay.textContent = "Blue";
      occupationDisplay.textContent = "Software Engineer";
      locationDisplay.textContent = "New York, USA";
      bioDisplay.textContent = "I am passionate about technology and love traveling the world!";
      
      // Simulating profile picture
      profilePic.src = "../images/backgroundimage.jpg";  // Set the actual path to the profile picture

      // Simulate multiple image uploads in the gallery
      const galleryContainer = document.getElementById('galleryContainer');
      const imageFiles = [
        "image1.jpg", "image2.jpg", "image3.jpg", "image4.jpg", "image5.jpg"
      ]; // This would be dynamic, based on uploaded files
      
      imageFiles.forEach((imageSrc) => {
        const imgElement = document.createElement('div');
        imgElement.classList.add('gallery-item');
        imgElement.innerHTML = `<img src="${imageSrc}" alt="Uploaded Image">`;
        galleryContainer.appendChild(imgElement);
      });
    });