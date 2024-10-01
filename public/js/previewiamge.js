document.getElementById('profile_image').addEventListener('change', function(event) {
    const input = event.target;
    const previewContainer = document.getElementById('image_preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.createElement('div');
            imagePreview.className = 'image-preview';
            imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Image" class="preview-img">';
            previewContainer.innerHTML = ''; // Clear previous content
            previewContainer.appendChild(imagePreview);

            // Show the preview image in fullscreen when clicked
            const previewImg = imagePreview.querySelector('img');
            previewImg.addEventListener('click', function() {
                const fullscreenModal = document.getElementById('fullscreenModal');
                const fullscreenImg = document.getElementById('fullscreenImg');
                fullscreenImg.src = e.target.result;
                fullscreenModal.style.display = 'flex'; // Show modal
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
});

// Close the modal
document.getElementById('closeModal').addEventListener('click', function() {
    const fullscreenModal = document.getElementById('fullscreenModal');
    fullscreenModal.style.display = 'none'; // Hide modal
});

// Optional: Close the modal when clicking outside of the image
document.getElementById('fullscreenModal').addEventListener('click', function(event) {
    if (event.target === this) {
        this.style.display = 'none';
    }
});
