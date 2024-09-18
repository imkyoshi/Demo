<?php
namespace app\controller;

use app\model\AuthDAL;
use app\Helpers\Cookies;

class AuthController1
{
    private AuthDAL $authDAL;
    protected Cookies $cookies;

    public function __construct(AuthDAL $authDAL, Cookies $cookies)
    {
        $this->authDAL = $authDAL;
        $this->cookies = $cookies;
        $this->cookies->initializeSession();
    }

    public function login()
    {
        // Ensure the request method is POST and the 'login' field exists
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($_POST['login'])) {
            $this->jsonResponse(['error' => 'Invalid request.'], 405);
            return;
        }
    
        // Sanitize email input
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
    
        // Check if email or password is empty
        if (empty($email) || empty($password)) {
            $this->jsonResponse(data: ['error' => 'All fields are required.'], statusCode: 400);
            return;
        }
    
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['error' => 'Invalid email format.'], 400);
            return;
        }
    
        // Authenticate the user
        $user = $this->authDAL->authenticateUser($email, $password);
        if ($user) {
            // Set session variables upon successful authentication
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time(); // Track login time for session management

            // Cookies
            $token = bin2hex(random_bytes(32));
            $encryptedToken = $this->cookies->encrypt($token);
            session_regenerate_id(true);
            setcookie('auth_token', $encryptedToken, time() + 86400 * 30, "/", "", true, true);

            // Remember Cookies
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
                'redirect' => $redirectUrl,
                'token' => $token
            ];
            $this->jsonResponse($response, 200);
        } else {
            $this->jsonResponse(['error' => 'Wrong email or password.'], 401);
            return;
        }
        exit;
    }
    

    public function register()
    {
        // Sanitize inputs
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : 'user';
        
        // Validations
        if (empty($fullname) || empty($address) || empty($dateOfBirth) || empty($phone_number) || empty($email) || empty($password)) {
            $this->jsonResponse(data: ['error' => 'All fields are required.'], statusCode: 400);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['error' => 'Invalid email format.'], 400);
            return;
        }
        if (strlen($password) < 8) {
            $this->jsonResponse(['error' => 'Password must be at least 8 characters long.'], 400);
            return;
        }
        if ($this->authDAL->emailExists($email)) {
            $this->jsonResponse(['error' => 'User with this email already exists.'], 409);
            return;
        }
    
        // Register the user
        $result = $this->authDAL->registerUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);
        if ($result) {
            // Generate a token for password reset purposes
            $resetToken = bin2hex(random_bytes(32));
            $this->authDAL->storePasswordResetToken($email, $resetToken);
            // header('Content-Type: text/html');
            // Create a response
            $response = [
                'message' => 'Registration successful. Redirecting to login...',
                'redirect' => 'login.php',
                'resetToken' => $resetToken,
            ];
            $this->jsonResponse($response, 201);
            
        } else {
            $this->jsonResponse(['error' => 'Failed to register user.'], 500);
            return;
        }
        exit;
    }

    public function logout()
    {
        // Regenerate session and unset all data
        $this->cookies->initializeSession();
        session_start();
        session_unset();
        session_destroy();
        $this->jsonResponse(['error' => 'Logged out successfully.'], 200);
        exit;
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
