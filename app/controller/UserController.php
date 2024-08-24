<?php
require 'app/model/UserDAL.php';

// Handles forms
class UserController
{

    private $userDAL;
    private $currentUserID;

    public function __construct($db)
    {
        $this->userDAL = new UserDAL($db);
        $this->currentUserID = isset(($_SESSION['user_id'])) ? $_SESSION['user_id'] : null;
    }

    // public function isAdmin()
    // {
    //     return isset($_SESSION['roles']) && $_SESSION['roles'] === 'admin';
    // }

    // public function redirectIfNotAdmin()
    // {
    //     if (!$this->isAdmin()) {
    //         header("Location: ../auth/login.php");
    //         exit;
    //     }
    // }

    public function getAllUser()
    {
        return $this->userDAL->getAllUser();
    }

    public function getUserById($id)
    {
        return $this->userDAL->getUserById($id);
    }

    public function handleAddUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])) {
            $fullName = $_POST['fullName'];
            $phoneNumber = $_POST['phoneNumber'];
            $address = $_POST['address'];
            $dateOfBirth = $_POST['dateOfBirth'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $roles = $_POST['role'];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $result = $this->userDAL->addUser($fullName, $phoneNumber, $address, $dateOfBirth, $email, $hashedPassword, $roles);

            if ($result === true) {
                header("Location: ../admin/user_management.php");
                exit;
            } elseif ($result === "User with this email already exists.") {
                return "User with this email already exists.";
            } else {
                return "Failed to add user.";
            }
        }
        return null;
    }

    public function handleUpdateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
            $id = $_POST['editUserId'];
            $fullName = $_POST['editFullName'];
            $phoneNumber = $_POST['editPhoneNumber'];
            $address = $_POST['editAddress'];
            $dateOfBirth = $_POST['editDateOfBirth'];
            $email = $_POST['editEmail'];
            $password = $_POST['editPassword'];
            $roles = $_POST['editRole'];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $success = $this->userDAL->updateUser($id, $fullName, $phoneNumber, $address, $dateOfBirth, $email, $hashedPassword, $roles);

            return $success ? "User updated successfully." : "Failed to update user.";
        }
        return null;
    }

    public function handleDeleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
            $id = $_POST['deleteUserId'];

            $success = $this->userDAL->deleteUser($id);
            return $success ? "User deleted successfully." : "Failed to delete user.";
        }
        return null;
    }
}
