<?php
namespace app\model;
use PDO;

class UserDAL
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //  FETCH USER'S  //
    public function getAllUser()
    {
        // Fetch All Users
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        // Fetch User by ID
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ///////////////////////
    //  CRUD OPERATIONS  //
    ///////////////////////

    // Add a new user
    public function addUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (fullname, address, gender, phone_number, email, password)
                VALUES (:fullname, :address, :gender, :phone_number, :email, :password, 'user')");
        
        // Bind parameters
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Update an existing user
    public function updateUser($id, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare( "UPDATE users
            SET fullname = :fullname, address = :address, gender = :gender, phone_number = :phone_number, email = :email, password = :password, role = :role
            WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser($id)
    {
        // Prepare and execute the delete statement
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        
        if ($result) {
            // Optionally reset auto-increment
            $query = "ALTER TABLE users AUTO_INCREMENT = 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
    
        return $result;
    }
}
