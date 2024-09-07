<?php
namespace app\controller;

require '../app/config/db.php';
require '../app/model/UserDAL.php';

// Handles forms
class UserController
{
    private $userDAL;
    private $currentUserID;

    public function __construct($userDAL)
    {
        $this->userDAL = $userDAL;
        $this->currentUserID = isset(($_SESSION['user_id'])) ? $_SESSION['user_id'] : null;
    }
    // Check if the user is admin
    public function isAdmin()
    {
        return isset($_SESSION['roles']) && $_SESSION['roles'] === 'admin';
    }
    public function redirectIfNotAdmin()
    {
        if (!$this->isAdmin()) {
            header("Location: ../auth/login.php");
            exit;
        }
    }
    // Fetch User
    public function getAllUser()
    {
        return $this->userDAL->getAllUser();
    }
    public function getUserById($id)
    {
        return $this->userDAL->getUserById($id);
    }
    ///////////////////////
    //  CRUD OPERATIONS  //
    ///////////////////////
    
    // Add a new user
    public function handleAddUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])) {
            $fullName = htmlspecialchars(trim($_POST['fullName']));
            $phone_number = htmlspecialchars(trim($_POST['phoneNumber']));
            $address = htmlspecialchars(trim($_POST['address']));
            $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $roles = htmlspecialchars(trim($_POST['role']));
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            //Validation
            if (empty($fullname) || empty($address) || empty($dateOfBirth) || empty($phone_number) || empty($email) || empty($password)) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required.'];
            } else {
                $result = $this->userDAL->addUser($fullName, $phone_number, $address, $dateOfBirth, $email, $hashedPassword, $roles);
                if ($result) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Add New User Succesfull'];
                    header('Location: ../admin/user_management.php');
                    exit;
                } else {
                    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to add new User'];
                }
            }
            return null;
        }
    }
    // Update an existing user
    public function handleUpdateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
            $id = $_POST['editUserId'];
            $fullName = htmlspecialchars(trim($_POST['editFullName']));
            $phone_number = htmlspecialchars(trim($_POST['editPhoneNumber']));
            $address = htmlspecialchars(trim($_POST['editAddress']));
            $dateOfBirth = htmlspecialchars(trim($_POST['editDateOfBirth']));
            $email = filter_var(trim($_POST['editEmail']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['editPassword']);
            $roles = htmlspecialchars(trim($_POST['editRole']));
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            //Validation
            if (empty($fullname) || empty($address) || empty($dateOfBirth) || empty($phone_number) || empty($email) || empty($password)) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required.'];
            } else {
                $result = $this->userDAL->updateUser($id, $fullName, $phone_number, $address, $dateOfBirth, $email, $hashedPassword, $roles);
                if ($result) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Update New User Succesfull'];
                    header('Location: ../admin/user_management.php');
                    exit;
                } else {
                    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to Update new User'];
                }
            }
            return null;
        }
    }
    // Delete a new user
    public function handleDeleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
            $id = htmlspecialchars(trim($_POST['deleteUserId']));
            $result = $this->userDAL->deleteUser($id);
            if ($result) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Delete Successful'];
                header('Location: ../admin/user_management.php');
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to delete User'];
            }
        }
        return null;
    }
}
