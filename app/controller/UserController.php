<?php
namespace app\controller;

use app\model\UserDAL;

// Handles forms
class UserController
{
    private UserDAL $userDAL;

    public function __construct(UserDAL $userDAL)
    {
        $this->userDAL = $userDAL;
        
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

    public function getUserByEmail($email)
    {
        return $this->userDAL->getUserByEmail($email);
    }

    ///////////////////////
    //  CRUD OPERATIONS  //
    ///////////////////////
    
    // Add a new user
    public function handlerAddUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['addUser'])) {
            return;
        }
        // Sanitize inputs
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : 'user';

        $result = $this->userDAL->addUser($fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);

        if ($result) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Added new user successfully.'];
            header('Location: ../admin/login.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to add new user.'];
            header('Location: ../admin/login.php');
            exit;
        }
    }
    // Update an existing user
    public function handlerUpdateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['updateUser'])) {
            return;
        }
        // Sanitize inputs
        $id = htmlspecialchars(trim($_POST['id']));
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : 'user';

        $result = $this->userDAL->updateUser($id, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);

        if ($result) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Updated user successfully.'];
            header('Location: ../admin/login.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update user.'];
            header('Location: ../admin/login.php');
            exit;
        }
    }
    // Delete a new user
    public function handlerDeleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['deleteUser'])) {
            return;
        }
        // Sanitize inputs
        $id = htmlspecialchars(trim($_POST['id']));
        $result = $this->userDAL->deleteUser($id);

        if ($result) {
            $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Do you want to delete this user?'];
            header('Location: ../admin/login.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to delete user.'];
            header('Location: ../admin/login.php');
            exit;
        }
    }
}
