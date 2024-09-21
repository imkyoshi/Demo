function togglePasswordVisibility(event, userId) {
    var clickedButton = event.currentTarget;
    var parentRow = clickedButton.closest('tr');
    var passwordInput = parentRow.querySelector('#password_' + userId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}