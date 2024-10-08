<?php
namespace app\model;
use PDO;

class AuthDAL
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    // Authenticate the user before login in
    public function authenticateUser($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT id, password, role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    // Check if the email already exists
    public function emailExists($email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function studentNoExists($student_no): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE student_no = :student_no");
        $stmt->bindParam(':student_no', $student_no, PDO::PARAM_STR);
        $stmt->execute();
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Register the Users
    public function registerUser($student_no, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role = 'user')
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (student_no,fullname, address, dateOfBirth, gender, phone_number, email, password, role) 
                                    VALUES (:student_no, :fullname, :address, :dateOfBirth, :gender, :phone_number, :email, :password, :role)");

        $stmt->bindParam(':student_no', $student_no, PDO::PARAM_STR);
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
    // Deleting Expire Token
    public function deleteExpiredTokens()
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE expires_at < NOW()");
        return $stmt->execute();
    }
}
