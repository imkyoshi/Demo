<?php
use PHPUnit\Framework\TestCase;
use PDO;
use YourNamespace\AuthDAL; // Adjust the namespace as needed

class AuthDALTest extends TestCase
{
    private $pdo;
    private $authDAL;

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
    }

    public function testRegisterUser()
    {
        $result = $this->authDAL->registerUser(
            'John Doe', '123 Main St', 'Male', '1234567890', 'john@example.com', 'password123', 'user'
        );
        $this->assertTrue($result);
    }

    public function testAuthenticateUserSuccess()
    {
        $this->authDAL->registerUser(
            'Jane Doe', '456 Elm St', 'Female', '0987654321', 'jane@example.com', 'password123', 'user'
        );
        $user = $this->authDAL->authenticateUser('jane@example.com', 'password123');
        $this->assertNotFalse($user);
        $this->assertEquals('Jane Doe', $user['fullname']);
    }

    public function testAuthenticateUserFailure()
    {
        $user = $this->authDAL->authenticateUser('nonexistent@example.com', 'wrongpassword');
        $this->assertFalse($user);
    }
}
