<?php
include '../app/config/db.php';
// User(DAL) => Data Layer Service's
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
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ///////////////////////
    //  CRUD OPERATIONS  //
    ///////////////////////

    // Add a new user
    public function addUser($fullname, $address, $gender, $phone_number, $email, $password, $roles)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, address, gender, phone_number, email, password)
                VALUES (:fullname, :address, :gender, :phone_number, :email, :password, 'user')";
        $stmt = $this->pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $roles);

        return $stmt->execute();
    }

    // Update an existing user
    public function updateUser($id, $fullname, $address, $gender, $phone_number, $email, $password, $roles)
    {
        $query = "UPDATE users
            SET fullname = :fullname, address = :address, gender = :gender, phone_number = :phone_number, email = :email, password = :password, role = :role
            WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $roles);

        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser($id)
    {
        // Prepare and execute the delete statement
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
        if ($result) {
            $query = "ALTER TABLE users AUTO_INCREMENT = 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        return $result;
    }
    
}
