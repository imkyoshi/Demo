<?php

namespace app\controller;

use app\model\AuthDAL;
use app\Helpers\Cookies;
// use app\api\apiAuth; // Api Auth

class AuthControlle1r
{
    private $authDAL;
    private $cookies;

    public function __construct(AuthDAL $authDAL)
    {
        $this->authDAL = $authDAL;
        $this->cookies = new Cookies();
        $this->cookies->initializeSession();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
            return;
        }
        
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        
        // Validate input values
        if (empty($email) || empty($password)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required.'];
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format.'];
            return;
        }

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Authenticate user
        $user = $this->authDAL->authenticateUser($email, $password);
        if (!$user) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Wrong email or password.'];
            return;
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time(); // For session timeout
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Login successful.'];

         // Generate a secure token
        $token = bin2hex(random_bytes(32));
        $encryptedToken = $this->cookies->encrypt($token);
         // Store the token in a secure HTTP-only cookie
         setcookie('auth_token', $encryptedToken, time() + (86400 * 30), "/", "", true, true);

        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            $cookieExpiration = time() + 30 * 24 * 60 * 60; // 30 days`
            $encryptedEmail = $this->cookies->encrypt($email);
            setcookie('user_email', $encryptedEmail, $cookieExpiration, '/', '', true, true);
        } else {
            setcookie('user_email', '', time() - 3600, '/', '', true, true);
        }

        // Redirect based on role
        $redirectMap = [
            'admin' => '../admin/dashboard.php',
            'officer' => '../officer/dashboard.php',
            'user' => '../user/dashboard.php',
        ];
        $redirectUrl = $redirectMap[$user['role']] ?? '../user/dashboard.php';
        // $encryptedRedirectUrl = $this->cookies->encrypt($redirectUrl);
        
        header("Location: $redirectUrl,");
        exit;
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register'])) {
            return;
        }
        
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : 'user';

        // Validate input values
        if (empty($fullname) || empty($address) || empty($dateOfBirth) || empty($phone_number) || empty($email) || empty($password)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required.'];
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format.'];
            return;
        }

        // Password must be at least 8 characters long
        if (strlen($password) < 8) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Password must be at least 8 characters long.'];
            return;
        }

        // Check if the email already exists in the database
        if ($this->authDAL->emailExists($email)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'User with this email already exists.'];
            return;
        }

        // Register the user if all validations pass
        $result = $this->authDAL->registerUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);
        if ($result) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Registration successful. Please log in.'];
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to register user.'];
        }
    }

    public function getDecryptedEmailFromCookie()
    {
        if (isset($_COOKIE['user_email'])) {
            $decryptedEmail = $this->cookies->decrypt($_COOKIE['user_email']);
            return $decryptedEmail;
        }
        return null;
    }

    public function logout()
    {
        $this->cookies->initializeSession();
        session_start();
        session_unset();
        session_destroy();
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Logged out successfully.'];
        header('Location: login.php');
        exit;
    }
}