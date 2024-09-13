<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new URLSearchParams(new FormData(form));

        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString() // Send form data as a URL-encoded string
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
});


</script> -->
