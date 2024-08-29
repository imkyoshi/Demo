<?php
require '../config/db.php';
require '../model/AuthDAL.php';
require '../Helpers/Cookies.php';

use Helpers\Cookies;

header("Content-Type: application/json");
$requestMethod = $_SERVER['REQUEST_METHOD'];

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

switch ($requestMethod) {
    case 'POST':
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'login':
                $authController->login();
                break;
            case 'register':
                $authController->register();
                break;
            case 'logout':
                $authController->logout();
                break;
            default:
                echo json_encode(['error' => 'Invalid action']);
                http_response_code(400);
                break;
        }
        break;
    default:
        echo json_encode(['error' => 'Unsupported request method']);
        http_response_code(405);
        break;
}

class AuthController
{
    private $authDAL;
    private $cookies;

    public function __construct($authDAL)
    {
        $this->authDAL = $authDAL;
        $this->cookies = new Cookies();
        $this->cookies->initializeSession();
    }

    public function login()
    {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            echo json_encode(['error' => 'All fields are required.']);
            http_response_code(400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Invalid email format.']);
            http_response_code(400);
            return;
        }

        $user = $this->authDAL->authenticateUser($email, $password);
        if (!$user) {
            echo json_encode(['error' => 'Wrong email or password.']);
            http_response_code(401);
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $response = ['message' => 'Login successful.', 'redirect' => $this->getRedirectUrl($user['role'])];
        
        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            $cookieExpiration = time() + 30 * 24 * 60 * 60; // 30 days
            $encryptedEmail = $this->cookies->encrypt($email);
            setcookie('user_email', $encryptedEmail, $cookieExpiration, '/', '', true, true);
        } else {
            setcookie('user_email', '', time() - 3600, '/', '', true, true);
        }

        echo json_encode($response);
        http_response_code(200);
    }

    public function register()
    {
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
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Invalid email format.']);
            http_response_code(400);
            return;
        }

        if (strlen($password) < 8) {
            echo json_encode(['error' => 'Password must be at least 8 characters long.']);
            http_response_code(400);
            return;
        }

        if ($this->authDAL->emailExists($email)) {
            echo json_encode(['error' => 'User with this email already exists.']);
            http_response_code(409);
            return;
        }

        $result = $this->authDAL->registerUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);
        if ($result) {
            echo json_encode(['message' => 'Registration successful. Please log in.']);
            http_response_code(201);
        } else {
            echo json_encode(['error' => 'Failed to register user.']);
            http_response_code(500);
        }
    }

    public function logout()
    {
        $this->cookies->initializeSession();
        session_start();
        session_unset();
        session_destroy();
        echo json_encode(['message' => 'Logged out successfully.']);
        http_response_code(200);
    }

    private function getRedirectUrl($role)
    {
        $redirectMap = [
            'admin' => '../admin/dashboard.php',
            'officer' => '../officer/dashboard.php',
            'user' => '../user/dashboard.php',
        ];
        return $redirectMap[$role] ?? '../user/dashboard.php';
    }
}
