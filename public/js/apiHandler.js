// public\js\apiHandler.js
document.addEventListener('DOMContentLoaded', function() {
    function handleFormSubmit(formId) {
        const form = document.getElementById(formId);

        // Only proceed if the form exists on the page
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
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
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        }
    }

    // Initialize handler only for the form that exists on the page
    if (document.getElementById('loginForm')) {
        handleFormSubmit('loginForm');
    }
    if (document.getElementById('registerForm')) {
        handleFormSubmit('registerForm');
    }
});
