<?php
///////////////////////////////////////////
// HANDLING FORMS & VALIDATION FUNCTIONS //
///////////////////////////////////////////
require '../app/config/db.php';
require '../app/model/AuthDAL.php';

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

class AuthController
{
    private $authDAL;

    public function __construct($authDAL)
    {
        $this->authDAL = $authDAL;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);

            // Validations
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format.'];
                return;
            }

            $user = $this->authDAL->authenticateUser($email, $password); // Verify email and password
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['roles'] = $user['role'];
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Login successful.'];

                // Set cookies for 'Remember Me' functionality
                if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                    $cookieExpiration = time() + 30 * 24 * 60 * 60; // 30 days Cookies Expriration time
                    setcookie('user_email', $email, $cookieExpiration, '/', '', true, true);
                } else {
                    setcookie('user_email', '', time() - 3600, '/', '', true, true);
                }
                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: ../admin/dashboard.php');
                        break;
                    case 'officer':
                        header('Location: ../officer/dashboard.php');
                        break;
                    case 'user':
                    default:
                        header('Location: ../user/dashboard.php');
                        break;
                }
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Wrong email or password.'];
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
            $fullname = htmlspecialchars(trim($_POST['fullname']));
            $address = htmlspecialchars(trim($_POST['address']));
            $gender = htmlspecialchars(trim($_POST['gender']));
            $phone_number = htmlspecialchars(trim($_POST['phone_number']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : 'user';

            // Validation
            if (empty($fullname) || empty($address) || empty($phone_number) || empty($email) || empty($password)) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required.'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format.'];
            } elseif (strlen($password) < 8) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Password must be at least 8 characters long.'];
            } else {

                // Check if email already exists
                $existingUser = $this->authDAL->authenticateUser($email, $password);
                if ($existingUser) {
                    $_SESSION['alert'] = ['type' => 'error', 'message' => 'User with this email already exists.'];
                } else {

                    $result = $this->authDAL->registerUser($fullname, $address, $gender, $phone_number, $email, $password, $role);
                    if ($result) {
                        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Registration successful. Please log in.'];
                        header('Location: login.php');
                        exit;
                    } else {
                        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to register user.'];
                    }
                }
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Logged out successfully.'];
        header('Location: login.php');
        exit;
    }
}