<?php
require '../../app/model/AuthDAL.php';
require '../../app/controller/AuthController.php';

use PHPUnit\Framework\TestCase;
use app\model\AuthDAL;
use app\controller\AuthController;


class AuthControllerTest extends TestCase
{
    private $authDAL;
    private $authController;

    protected function setUp(): void
    {
        $this->authDAL = $this->createMock(AuthDAL::class);
        $this->authController = new AuthController($this->authDAL);
    }

    public function testLoginSuccess()
    {
        $_POST['login'] = true;
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';
        $_POST['remember'] = 'on';

        $user = ['id' => 1, 'role' => 'admin'];
        $this->authDAL->method('authenticateUser')
            ->willReturn($user);
        $this->authDAL->method('emailExists')
            ->willReturn(false);

        ob_start();
        $this->authController->login();
        $response = ob_get_clean();

        $this->assertStringContainsString('Login successful.', $response);
    }

    public function testLoginFailure()
    {
        $_POST['login'] = true;
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'wrongpassword';

        $this->authDAL->method('authenticateUser')
            ->willReturn(false);

        ob_start();
        $this->authController->login();
        $response = ob_get_clean();

        $this->assertStringContainsString('Wrong email or password.', $response);
    }

    public function testRegisterSuccess()
    {
        $_POST['register'] = true;
        $_POST['fullname'] = 'John Doe';
        $_POST['address'] = '123 Main St';
        $_POST['dateOfBirth'] = '1990-01-01';
        $_POST['gender'] = 'male';
        $_POST['phone_number'] = '1234567890';
        $_POST['email'] = 'john@example.com';
        $_POST['password'] = 'password123';

        $this->authDAL->method('emailExists')
            ->willReturn(false);
        $this->authDAL->method('registerUser')
            ->willReturn(true);

        ob_start();
        $this->authController->register();
        $response = ob_get_clean();

        $this->assertStringContainsString('Registration successful. Please log in.', $response);
    }

    public function testRegisterFailure()
    {
        $_POST['register'] = true;
        $_POST['fullname'] = 'John Doe';
        $_POST['address'] = '123 Main St';
        $_POST['dateOfBirth'] = '1990-01-01';
        $_POST['gender'] = 'male';
        $_POST['phone_number'] = '1234567890';
        $_POST['email'] = 'john@example.com';
        $_POST['password'] = 'short';

        ob_start();
        $this->authController->register();
        $response = ob_get_clean();

        $this->assertStringContainsString('Password must be at least 8 characters long.', $response);
    }

    public function testLogout()
    {
        $_POST['logout'] = true;

        ob_start();
        $this->authController->logout();
        $response = ob_get_clean();

        $this->assertStringContainsString('Logged out successfully.', $response);
    }
}
