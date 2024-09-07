<?php
namespace test\auth;

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\controller\AuthController;
use app\model\AuthDAL;
use app\Helpers\Cookies;

class AuthControllerTest extends TestCase
{
    private $authDAL;
    private $cookies;
    private $authController;

    protected function setUp(): void
    {
        // Mock the AuthDAL and Cookies classes
        $this->authDAL = $this->createMock(AuthDAL::class);
        $this->cookies = $this->createMock(Cookies::class);

        // Mock cookie initialization
        $this->cookies->method('initializeSession')->willReturn(true);

        // Pass the mock to the controller
        $this->authController = new AuthController($this->authDAL);
    }

    public function testLoginSuccess()
    {
        // Mock user data returned from AuthDAL
        $user = [
            'id' => 1,
            'role' => 'admin',
            'fullname' => 'John Doe',
            'email' => 'johndoe@example.com'
        ];

        // Set up POST request data
        $_POST['email'] = 'johndoe@example.com';
        $_POST['password'] = 'password123';
        $_POST['login'] = true;  // Ensure login is set

        // Simulate a POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Mock the authentication process in AuthDAL
        $this->authDAL->method('authenticateUser')->willReturn($user);

        // Capture the output
        ob_start();
        $this->authController->login();
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Assertions
        $this->assertEquals(200, http_response_code());
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Login successful.', $response['message']);
        $this->assertArrayHasKey('userId', $response);
        $this->assertEquals(1, $response['userId']);
        $this->assertArrayHasKey('token', $response);
    }

    public function testLoginInvalidEmailFormat()
    {
        // Set up POST request data with an invalid email
        $_POST['email'] = 'invalid-email';
        $_POST['password'] = 'password123';
        $_POST['login'] = true;

        // Simulate a POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Capture the output
        ob_start();
        $this->authController->login();
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Assertions
        $this->assertEquals(400, http_response_code());
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Invalid email format.', $response['error']);
    }

    public function testRegisterSuccess()
    {
        // Set up POST request data for registration
        $_POST['fullname'] = 'John Doe';
        $_POST['address'] = '123 Main St';
        $_POST['dateOfBirth'] = '1990-01-01';
        $_POST['gender'] = 'male';
        $_POST['phone_number'] = '1234567890';
        $_POST['email'] = 'johndoe@example.com';
        $_POST['password'] = 'password123';
        $_POST['role'] = 'user';

        // Simulate a POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Mock emailExists to return false
        $this->authDAL->method('emailExists')->willReturn(false);

        // Mock registerUser to return true
        $this->authDAL->method('registerUser')->willReturn(true);

        // Capture the output
        ob_start();
        $this->authController->register();
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Assertions
        $this->assertEquals(201, http_response_code());
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('Registration successful. Redirecting to login...', $response['message']);
    }

    public function testRegisterUserAlreadyExists()
    {
        // Set up POST request data for registration
        $_POST['fullname'] = 'John Doe';
        $_POST['address'] = '123 Main St';
        $_POST['dateOfBirth'] = '1990-01-01';
        $_POST['gender'] = 'male';
        $_POST['phone_number'] = '1234567890';
        $_POST['email'] = 'johndoe@example.com';
        $_POST['password'] = 'password123';
        $_POST['role'] = 'user';

        // Simulate a POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Mock emailExists to return true
        $this->authDAL->method('emailExists')->willReturn(true);

        // Capture the output
        ob_start();
        $this->authController->register();
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Assertions
        $this->assertEquals(409, http_response_code());
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('User with this email already exists.', $response['error']);
    }
}

