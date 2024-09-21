// public\js\apiHandler.js
document.addEventListener('DOMContentLoaded', function() {
    function handleFormSubmit(formId) {
        const form = document.getElementById(formId);

        // Only proceed if the form exists on the page
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(form);
                console.log('Form data being sent:', [...formData]); // Log the data being sent

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response received:', response); // Log the response
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data); // Log the parsed data
                    if (data.error) {
                        Swal.fire({
                            title: 'Error!',
                            text: data.error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else if (data.message) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    // Show a user-friendly message
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        }
    }

    // Initialize handler only for the forms that exist on the page
    if (document.getElementById('loginForm')) {
        handleFormSubmit('loginForm');
    }
    if (document.getElementById('registerForm')) {
        handleFormSubmit('registerForm');
    }
});
