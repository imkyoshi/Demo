<?php
namespace app\controller;

use app\Helpers\Cookies;
use app\model\AuthDAL;
use Random\RandomException;

// use app\api\apiAuth; // Api Auth

class AuthController
{
    private AuthDAL $authDAL;
    protected Cookies $cookies;

    public function __construct(AuthDAL $authDAL, Cookies $cookies)
    {
        $this->authDAL = $authDAL;
        $this->cookies = $cookies;
        $this->cookies->initializeSession();
    }

    /**
     * @throws RandomException
     */
    public function login(): void
    {
        // Start the session if it hasn't been started yet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login'])) {
            return;
        }
        
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        // Validate input values
        if (empty($email) || empty($password)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required.'];
            header("Location: ../auth/login.php");
            exit;
        }
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format.'];
            header("Location: ../auth/login.php");
            exit;
        }
        // Authenticate user
        $user = $this->authDAL->authenticateUser($email, $password);
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Login successful.'];

            // Cookies
            $token = bin2hex(random_bytes(32));
            $encryptedToken = $this->cookies->encrypt($token);
            session_regenerate_id(true);
            setcookie('auth_token', $encryptedToken, time() + 86400 * 30, "/", "", true, true);
            // Remember Cookies
            if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                $cookieExpiration = time() + 30 * 24 * 60 * 60;
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
            header("Location: $redirectUrl");
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Wrong email or password.'];
            header("Location: ../auth/login.php");
            exit;
        }
    }

    /**
     * @throws RandomException
     */
    public function register(): void
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
            header("Location: ../auth/login.php");
            exit;
        }
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format.'];
            header("Location: ../auth/login.php");
            exit;
        }
        // Password must be at least 8 characters long
        if (strlen($password) < 8) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Password must be at least 8 characters long.'];
            header("Location: ../auth/login.php");
            exit;
        }
        // Check if the email already exists in the database
        if ($this->authDAL->emailExists($email)) {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'The user with this email already exists.'];
            header("Location: ../auth/login.php");
            exit;
        }

        // Register the user if all validations pass
        $result = $this->authDAL->registerUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);
        if ($result) {
            $resetToken = bin2hex(random_bytes(32));
            $this->authDAL->storePasswordResetToken($email, $resetToken);
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Registration successful. Please log in.'];
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to register user.'];
            header("Location: ../auth/login.php");
            exit;
        }
    }

    public function logout(): void
    {
        $this->cookies->initializeSession();
        // session_start();
        session_unset();
        session_destroy();
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Logged out successfully.'];
        header("Location: ../auth/login.php");
        exit;
    }
}
