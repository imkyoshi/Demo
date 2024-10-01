<?php
namespace app\model;
use PDO;
use Exception;

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
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bindParam(1, $id, PDO::PARAM_INT); // Bind the positional parameter
        $stmt->execute();
        
        // Return the actual user data or false if no user is found
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function storePasswordResetToken($email, $resetToken)
    {
        $expiresAt = new \DateTime('+1 hour');
        $expiresAtStr = $expiresAt->format('Y-m-d H:i:s'); // Store formatted date in a variable
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':token', $resetToken, PDO::PARAM_STR);
        $stmt->bindParam(':expires_at', $expiresAtStr, PDO::PARAM_STR); // Use the variable here
        return $stmt->execute();
    }
    ///////////////////////
    //  CRUD OPERATIONS  //
    ///////////////////////

    // Add a new user
    public function addUser($student_no, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role, $profile_image)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Correct SQL query with all placeholders
        $stmt = $this->pdo->prepare("INSERT INTO users (student_no, fullname, address, dateOfBirth, gender, phone_number, email, password, role, profile_image)
                VALUES (:student_no, :fullname, :address, :dateOfBirth, :gender, :phone_number, :email, :password, :role, :profile_image)");
        
        // Bind parameters
        $stmt->bindParam(':student_no', $student_no, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':profile_image', $profile_image, PDO::PARAM_STR);

        return $stmt->execute();
    }


    // Update an existing user
    public function updateUser($id, $student_no, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role, $profile_image)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users
            SET student_no = :student_no, fullname = :fullname, address = :address, dateOfBirth = :dateOfBirth, gender = :gender, phone_number = :phone_number, email = :email, password = :password, role = :role, profile_image = :profile_image
            WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':student_no', $student_no, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':profile_image', $profile_image, PDO::PARAM_STR);

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
