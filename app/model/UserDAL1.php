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
        // Fetch All tbl_studentinfo
        $sql = "SELECT * FROM tbl_studentinfo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        // Fetch User by ID
        $stmt = $this->pdo->prepare("SELECT * FROM tbl_studentinfo WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tbl_studentinfo WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ///////////////////////
    //  CRUD OPERATIONS  //
    ///////////////////////

    // Add a new user
    public function addUser($student_no, $profile_image, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Start a transaction
            $this->pdo->beginTransaction();

            // Insert into tbl_studentaccount
            $stmtAccount = $this->pdo->prepare("INSERT INTO tbl_studentaccount (student_no, fullname, email, password, role) 
                                            VALUES (:student_no, :fullname, :email, :password, :role)");
            $stmtAccount->bindParam(':student_no', $student_no, PDO::PARAM_STR);
            $stmtAccount->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmtAccount->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtAccount->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmtAccount->bindParam(':role', $role, PDO::PARAM_STR);
            $stmtAccount->execute();

            // Insert into tbl_studentinfo
            $stmtInfo = $this->pdo->prepare("INSERT INTO tbl_studentinfo (student_no, profile_image, fullname, address, dateOfBirth, gender, phone_number, email) 
                                            VALUES (:student_no, :profile_image, :fullname, :address, :dateOfBirth, :gender, :phone_number, :email)");
            $stmtInfo->bindParam(':student_no', $student_no, PDO::PARAM_STR);
            $stmtInfo->bindParam(':profile_image', $profile_image, PDO::PARAM_STR);
            $stmtInfo->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmtInfo->bindParam(':address', $address, PDO::PARAM_STR);
            $stmtInfo->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
            $stmtInfo->bindParam(':gender', $gender, PDO::PARAM_STR);
            $stmtInfo->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
            $stmtInfo->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtInfo->execute();

            // Commit the transaction
            $this->pdo->commit();
            return true; // Success
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $this->pdo->rollBack();
            error_log("Registration error: " . $e->getMessage()); // Log the error for debugging
            return false; // Indicate failure
        }
    }

    // Update an existing user
    public function updateUser($id, $student_no, $profile_image, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email)
    {
        $stmt = $this->pdo->prepare( "UPDATE tbl_studentinfo
            SET student_no = :student_no, profile_image = :profile_image, fullname = :fullname, address = :address, gender = :gender, phone_number = :phone_number, email = :email
            WHERE id = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':student_no', $student_no, PDO::PARAM_STR);
        $stmt->bindParam(':profile_image', $profile_image, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser($id)
    {
        // Prepare and execute the delete statement
        $stmt = $this->pdo->prepare("DELETE FROM tbl_studentinfo WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        
        if ($result) {
            // Optionally reset auto-increment
            $query = "ALTER TABLE tbl_studentinfo AUTO_INCREMENT = 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
    
        return $result;
    }
}
