<?php
namespace app\model;
use PDO;
use Exception;

class AuthDAL1
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    // Authenticate the user before loggin in
    public function authenticateUser($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT id, password, role FROM tbl_studentaccount WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    // Check if the email already exists
    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM tbl_studentaccount WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function studentNoExists($student_no): bool
    {
        // Check in both tbl_studentaccount and tbl_studentinfo
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM tbl_studentaccount 
            WHERE student_no = :student_no
            UNION ALL
            SELECT COUNT(*) 
            FROM tbl_studentinfo 
            WHERE student_no = :student_no
        ");
        
        $stmt->bindParam(':student_no', $student_no, PDO::PARAM_STR);
        $stmt->execute();
    
        // Fetch the count from both tables
        $count = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // Return true if the count in either table is greater than 0
        return $count[0] > 0 || $count[1] > 0;
    }

    // Register the Users
    public function registerUser($student_no,$fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role = 'user')
    {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Start a transaction
            $this->pdo->beginTransaction();

            // Insert into tbl_studentaccount
            $stmtAccount = $this->pdo->prepare("INSERT INTO tbl_studentaccount (student_no, fullname, email, password, role) VALUES (:student_no, :fullname, :email, :password, :role)");
            $stmtAccount->bindParam(':student_no', $student_no, PDO::PARAM_STR);
            $stmtAccount->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmtAccount->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtAccount->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmtAccount->bindParam(':role', $role, PDO::PARAM_STR);
            $stmtAccount->execute();

            // Insert into tbl_studentinfo
            $stmtInfo = $this->pdo->prepare("INSERT INTO tbl_studentinfo (student_no, fullname, address, dateOfBirth, gender, phone_number, email) VALUES (:student_no, :fullname, :address, :dateOfBirth, :gender, :phone_number, :email)");
            $stmtInfo->bindParam(':student_no', $student_no, PDO::PARAM_STR);
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
    // Forgot password
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

    // Validating password reset token
    public function validateResetToken($token): mixed
    {
        $stmt = $this->pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Reset Password
    public function resetPassword($email, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        return $stmt->execute();
    }
    // Deleteting Expire Token
    public function deleteExpiredTokens()
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE expires_at < NOW()");
        return $stmt->execute();
    }
}
