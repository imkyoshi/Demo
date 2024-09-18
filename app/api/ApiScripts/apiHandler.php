<script>
document.addEventListener('DOMContentLoaded', function() {
    function handleFormSubmit(formId) {
        const form = document.getElementById(formId);

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(form);
            fetch('', {
                method: 'POST',
                body: formData,
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
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    }

    // Initialize handlers for both forms
    handleFormSubmit('loginForm');
    handleFormSubmit('registerForm');
});
</script>
