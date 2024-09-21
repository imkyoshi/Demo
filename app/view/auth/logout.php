<?php

// session_start();
session_unset();
session_destroy();

// Clear cookies
setcookie('auth_token', '', time() - 3600, '/', '', true, true);
setcookie('user_email', '', time() - 3600, '/', '', true, true);


// Optionally, you can also delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// // Redirect to the login page with a success message
// $_SESSION['alert'] = ['type' => 'success', 'message' => 'Logged out successfully.'];
header('Location: login.php');
exit;
