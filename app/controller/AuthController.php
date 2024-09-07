<?php
namespace app\controller;

use app\model\AuthDAL;
use app\Helpers\Cookies;

class AuthController
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
        header('Content-Type: application/json');
    
        // Ensure the request method is POST and the 'login' field exists
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'|| !isset($_POST['login'])) {
            echo json_encode(['error' => 'Invalid request method.']);
            http_response_code(405);
            return;
        }

        // if ($_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        //     echo json_encode(['error' => 'Invalid request method.']);
        //     http_response_code(405);
        //     return;
        // }
    
        // Sanitize email input
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
    
        // Check if email or password is empty
        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['error' => 'All fields are required.']);
            return;
        }
    
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email format.']);
            return;
        }
    
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
    
        // Authenticate the user
        $user = $this->authDAL->authenticateUser($email, $password);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Wrong email or password.']);
            return;
        }
    
        // Set session variables upon successful authentication
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time(); // Track login time for session management
    
        // Generate a secure token for cookie-based authentication
        $token = bin2hex(random_bytes(32));
        $encryptedToken = $this->cookies->encrypt($token);
        setcookie('auth_token', $encryptedToken, time() + 86400 * 30, "/", "", true, true);
    
        // Set "Remember Me" cookie if selected
        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            $cookieExpiration = time() + 30 * 24 * 60 * 60; // 30 days
            $encryptedEmail = $this->cookies->encrypt($email);
            setcookie('user_email', $encryptedEmail, $cookieExpiration, '/', '', true, true);
        } else {
            // Clear the email cookie if "Remember Me" was not selected
            setcookie('user_email', '', time() - 3600, '/', '', true, true);
        }
    
        // Redirect user based on role
        $redirectMap = [
            'admin' => '../admin/dashboard.php',
            'officer' => '../officer/dashboard.php',
            'user' => '../user/dashboard.php',
        ];
        $redirectUrl = $redirectMap[$user['role']] ?? '../user/dashboard.php';
    
        // Return success response
        $response = [
            'message' => 'Login successful.',
            'userId' => $user['id'],
            'fullname' => $user['fullname'],
            'email' => $user['email'],
            'redirect' => $redirectUrl,
            'token' => $token
        ];
        http_response_code(200);
        echo json_encode($response);
        return;
    }
    

    public function register()
    {
        header('Content-Type: application/json');

        // Sanitize inputs
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : 'user';

        if (empty($fullname) || empty($address) || empty($dateOfBirth) || empty($phone_number) || empty($email) || empty($password)) {
            echo json_encode(['error' => 'All fields are required.']);
            http_response_code(400);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Invalid email format.']);
            http_response_code(400);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['error' => 'Password must be at least 8 characters long.']);
            http_response_code(400);
            exit;
        }

        if ($this->authDAL->emailExists($email)) {
            echo json_encode(['error' => 'User with this email already exists.']);
            http_response_code(409);
            exit;
        }

        // Register the user
        $result = $this->authDAL->registerUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);
        if ($result) {
            echo json_encode(['message' => 'Registration successful. Redirecting to login...', 'redirect' => 'login.php']);
            http_response_code(201);
        } else {
            echo json_encode(['error' => 'Failed to register user.']);
            http_response_code(500);
        }
        exit;
    }

    public function logout()
    {
        header('Content-Type: application/json');

        // Regenerate session and unset all data
        $this->cookies->initializeSession();
        session_start();
        session_unset();
        session_destroy();
        echo json_encode(['message' => 'Logged out successfully.']);
        http_response_code(200);
        exit;
    }
}
