let uploadedImages = [];

    function previewImages() {
      const fileInput = document.getElementById('fileInput');
      const files = fileInput.files;

      if (files.length + uploadedImages.length > 5) {
        alert("You can upload a maximum of 5 pictures.");
        return;
      }

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
          // Add image to the uploaded array
          uploadedImages.push(e.target.result);

          // Create image element for the gallery
          const galleryItem = document.createElement('div');
          galleryItem.classList.add('gallery-item');
          
          const img = document.createElement('img');
          img.src = e.target.result;
          img.alt = `Uploaded Image ${uploadedImages.length}`;
          galleryItem.appendChild(img);

          document.getElementById('gallery').appendChild(galleryItem);

          // Update upload count display
          document.getElementById('uploadCount').innerText = `Uploaded: ${uploadedImages.length} / 5 pictures`;
        };

        reader.readAsDataURL(file);
      }
    }

    function submitImages() {
      if (uploadedImages.length === 0) {
        alert("Please upload at least one picture before submitting.");
        return;
      }

      // You can add logic here to send the uploaded images to a server
      console.log("Submitted images:", uploadedImages);
      alert("Images submitted successfully!");

      // Clear the gallery and reset the upload count
      uploadedImages = [];
      document.getElementById('gallery').innerHTML = '';
      document.getElementById('uploadCount').innerText = `Uploaded: 0 / 5 pictures`;
    }