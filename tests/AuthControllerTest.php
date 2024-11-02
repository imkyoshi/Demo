<?php
namespace tests;

use app\controller\AuthController;
use app\Helpers\Cookies;
use app\model\AuthDAL;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    private $authDAL;
    private $cookies;
    private $authController;

    protected function setUp(): void
    {
        // Mock AuthDAL
        $this->authDAL = $this->createMock(AuthDAL::class);

        // Mock Cookies
        $this->cookies = $this->createMock(Cookies::class);

        // Define return values for the encrypt method
        $this->cookies->method('encrypt')
            ->willReturnCallback(function ($data) {
                return $data === 'some_token' ? 'encrypted_token' : 'default_encrypted_value';
            });

        // Instantiate AuthController with mocks
        $this->authController = new AuthController($this->authDAL, $this->cookies);

        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function tearDown(): void
    {
        // Clear session and cookies
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        $_COOKIE = [];
    }

    // LOGIN TEST CASES

    public function testLoginSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['login'] = true;
        $_POST['email'] = 'test@gmail.com';
        $_POST['password'] = 'password';
        $_POST['remember'] = 'on';

        $user = ['id' => 3, 'role' => 'student', 'password' => password_hash('password', PASSWORD_DEFAULT)];

        $this->authDAL->expects($this->once())
            ->method('authenticateUser')
            ->with('test@gmail.com', 'password')
            ->willReturn($user);

        // Mock session start to ensure session variables are available
        $_SESSION = [];

        ob_start();
        $this->authController->login();
        $output = ob_get_clean();

        // Check if the auth_token cookie is set
        $this->assertArrayHasKey('auth_token', $_COOKIE);

        // Check session variables
        $this->assertArrayHasKey('role', $_SESSION);
        $this->assertEquals('admin', $_SESSION['role']);

        // Check redirection URL
        $this->assertStringContainsString('Location: ../admin/dashboard.php', $output);
    }

    public function testLoginFailed()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['login'] = true;
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'wrongpassword';

        $this->authDAL->expects($this->once())
            ->method('authenticateUser')
            ->with('test@example.com', 'wrongpassword')
            ->willReturn(false);

        // Mock session start to ensure session variables are available
        $_SESSION = [];

        ob_start();
        $this->authController->login();
        $output = ob_get_clean();

        // Check session alert key
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('Wrong email or password.', $_SESSION['alert']['message'], $output);
    }

    public function testLoginInvalidEmail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['login'] = true;
        $_POST['email'] = 'invalid-email';
        $_POST['password'] = 'password123';

        // Mock session start to ensure session variables are available
        $_SESSION = [];

        ob_start();
        $this->authController->login();
        $output = ob_get_clean();

        // Check session alert key
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('Invalid email format.', $_SESSION['alert']['message'], $output);
    }

    public function testLoginEmptyFields()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['login'] = true;
        $_POST['email'] = '';
        $_POST['password'] = '';

        // Mock session start to ensure session variables are available
        $_SESSION = [];

        ob_start();
        $this->authController->login();
        $output = ob_get_clean();

        // Check session alert key
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('All fields are required.', $_SESSION['alert']['message'], $output);
    }

    public function testLogout()
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = 'admin';
        $_COOKIE['auth_token'] = 'encrypted_token';

        ob_start();
        $this->authController->logout();
        $output = ob_get_clean();

        // Check that session and cookies are cleared
        $this->assertEmpty($_SESSION['logged_in']);
        $this->assertEmpty($_SESSION['role']);
        $this->assertEmpty($_COOKIE['auth_token']);
        $this->assertStringContainsString('Location: login.php', $output);
    }

    // REGISTER TEST CASES

    public function testRegisterSuccess()
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Reset the session array
        $_SESSION = [];

        // Simulate POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'register' => true,
            'fullname' => 'John Doe',
            'address' => '123 Elm Street',
            'dateOfBirth' => '1990-01-01',
            'gender' => 'male',
            'phone_number' => '555-5555',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'user'
        ];

        // Mocking dependencies on `$authDAL`
        $this->authDAL->expects($this->once())
            ->method('emailExists')
            ->with('john@example.com')
            ->willReturn(false);

        $this->authDAL->expects($this->once())
            ->method('registerUser')
            ->with('John Doe', '123 Elm Street', '1990-01-01', 'male', '555-5555', 'john@example.com', 'password123', 'user')
            ->willReturn(true);

        // Mock output and call register method
        ob_start();
        $this->authController->register();
        $output = ob_get_clean();

        // Check that session alert is set correctly
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertSame('Registration successful. Please log in.', $_SESSION['alert']['message']);

        // Check that a redirect happened
        $this->assertStringContainsString('Location: login.php', $output);

        // Ensure session alert type is 'success'
        $this->assertSame('success', $_SESSION['alert']['type']);
    }


    public function testRegisterFailureEmailExists()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['register'] = true;
        $_POST['fullname'] = 'John Doe';
        $_POST['address'] = '123 Elm Street';
        $_POST['dateOfBirth'] = '1990-01-01';
        $_POST['gender'] = 'male';
        $_POST['phone_number'] = '555-5555';
        $_POST['email'] = 'existing@example.com';
        $_POST['password'] = 'password123';
        $_POST['role'] = 'user';

        $this->authDAL->expects($this->once())
            ->method('emailExists')
            ->with('existing@example.com')
            ->willReturn(true);

        ob_start();
        $this->authController->register();
        $output = ob_get_clean();

        // Ensure $_SESSION['alert']['message'] is set
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('All fields are required.', $_SESSION['alert']['message'], $output);
    }

    public function testRegisterFailureEmptyFields()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['register'] = true;
        $_POST['fullname'] = '';
        $_POST['address'] = '';
        $_POST['dateOfBirth'] = '';
        $_POST['gender'] = '';
        $_POST['phone_number'] = '';
        $_POST['email'] = '';
        $_POST['password'] = '';
        $_POST['role'] = '';

        ob_start();
        $this->authController->register();
        $output = ob_get_clean();

        // Ensure $_SESSION['alert']['message'] is set
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('All fields are required.', $_SESSION['alert']['message'], $output);
    }

    // FORGOT PASSWORD TEST CASES

    public function testForgotPasswordSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['forgot_password'] = true;
        $_POST['email'] = 'test@example.com';

        $this->authDAL->expects($this->once())
            ->method('emailExists')
            ->with('test@example.com')
            ->willReturn(true);

        ob_start();
        $this->authController->forgotPassword();
        $output = ob_get_clean();

        // Ensure $_SESSION['alert']['message'] is set
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('Password reset email has been sent.', $_SESSION['alert']['message'], $output);
    }

    public function testForgotPasswordFailureEmailNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['forgot_password'] = true;
        $_POST['email'] = 'nonexistent@example.com';

        $this->authDAL->expects($this->once())
            ->method('emailExists')
            ->with('nonexistent@example.com')
            ->willReturn(false);

        ob_start();
        $this->authController->forgotPassword();
        $output = ob_get_clean();

        // Ensure $_SESSION['alert']['message'] is set
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('Email address not found.', $_SESSION['alert']['message'], $output);
    }

    public function testForgotPasswordEmptyEmail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate POST request
        $_POST['forgot_password'] = true;
        $_POST['email'] = '';

        ob_start();
        $this->authController->forgotPassword();
        $output = ob_get_clean();

        // Ensure $_SESSION['alert']['message'] is set
        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertArrayHasKey('message', $_SESSION['alert']);
        $this->assertStringContainsString('Email is required.', $_SESSION['alert']['message'], $output);
    }
}
