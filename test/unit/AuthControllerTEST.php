<?php
use PHPUnit\Framework\TestCase;
use YourNamespace\AuthController; // Adjust the namespace as needed
use YourNamespace\AuthDAL; // Adjust the namespace as needed

class AuthControllerTest extends TestCase
{
    private $authDAL;
    private $authController;
    private $pdo;

    protected function setUp(): void
    {
        // Set up an in-memory SQLite database for testing
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                fullname TEXT,
                address TEXT,
                gender TEXT,
                phone_number TEXT,
                email TEXT UNIQUE,
                password TEXT,
                role TEXT
            )
        ");
        $this->authDAL = new AuthDAL($this->pdo);
        $this->authController = new AuthController($this->authDAL);
    }

    public function testLoginSuccess()
    {
        $this->authDAL->registerUser(
            'Alice Smith', '789 Oak St', 'Female', '1231231234', 'alice@example.com', 'password123', 'user'
        );
        
        $_POST['login'] = true;
        $_POST['email'] = 'alice@example.com';
        $_POST['password'] = 'password123';

        $this->authController->login();

        $this->assertArrayHasKey('user_id', $_SESSION);
        $this->assertEquals('user', $_SESSION['roles']);
    }

    public function testLoginFailure()
    {
        $_POST['login'] = true;
        $_POST['email'] = 'nonexistent@example.com';
        $_POST['password'] = 'wrongpassword';

        $this->authController->login();

        $this->assertArrayHasKey('alert', $_SESSION);
        $this->assertEquals('error', $_SESSION['alert']['type']);
    }

    public function testRegister()
    {
        $_POST['register'] = true;
        $_POST['fullname'] = 'Bob Johnson';
        $_POST['address'] = '101 Pine St';
        $_POST['gender'] = 'Male';
        $_POST['phone_number'] = '5555555555';
        $_POST['email'] = 'bob@example.com';
        $_POST['password'] = 'securepassword';

        $this->authController->register();

        $user = $this->authDAL->authenticateUser('bob@example.com', 'securepassword');
        $this->assertNotFalse($user);
        $this->assertEquals('Bob Johnson', $user['fullname']);
    }
}
