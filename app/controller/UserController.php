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
        $student_no = htmlspecialchars(trim($_POST['student_no']));
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $address = htmlspecialchars(trim($_POST['address']));
        $dateOfBirth = htmlspecialchars(trim($_POST['dateOfBirth']));
        $gender = htmlspecialchars(trim($_POST['gender']));
        $phone_number = htmlspecialchars(trim($_POST['phone_number']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $role = htmlspecialchars(trim($_POST['role']));

        // Validations for required fields
        if (empty($student_no) ||empty($fullname) || empty($address) || empty($email) || empty($password) || empty($role)) {
            $_SESSION['alert'] = ['type' => 'warning', 'status' => 'Warning', 'message' => 'Please fill all required fields.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Check if the email already exists
        $user = $this->userDAL->getUserByEmail($email);
        if ($user) {
            $_SESSION['alert'] = ['type' => 'warning', 'status' => 'Warning', 'message' => 'Email already exists.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Handle File Upload
        $profile_image = null; // Initialize profile image variable
        if (isset($_FILES['profile_image'])) {
            // Call the file upload handler and store the returned file name
            $profile_image = $this->handlerFileUpload();
            // Check if file upload was successful, if null set an error and return
            if ($profile_image === null) {
                $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'Failed to upload profile image.'];
                header('Location: ../admin/newuser.php');
                exit;
            }
        }

        // Call the method to add the user to the database
        $result = $this->userDAL->addUser($student_no,$profile_image,$fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role);

        if ($result) {
            $_SESSION['alert'] = ['type' => 'success', 'status' => 'Success', 'message' => 'Added new user successfully.'];
            header('Location: ../admin/newuser.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'Failed to add new user.'];
            header('Location: ../admin/newuser.php');
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
        $role = htmlspecialchars(trim($_POST['role']));

        // Validations for required fields
        if (empty($fullname) || empty($address) || empty($email) || empty($password) || empty($role)) {
            $_SESSION['alert'] = ['type' => 'warning', 'status' => 'Warning', 'message' => 'Please fill all required fields.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Check if the email already exists
        $user = $this->userDAL->getUserByEmail($email);
        if ($user && $user['id'] !== $id) {
            $_SESSION['alert'] = ['type' => 'warning', 'status' => 'Warning', 'message' => 'Email already exists.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Handle File Upload
        // Get current profile image filename from the database
        $currentProfileImage = isset($user) ? $user['profile_image'] : null;
        // Handle File Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            // Upload the new profile image
            $newProfileImage = $this->handlerFileUpload();
            if ($newProfileImage) {
                // Remove old profile image if exists
                if ($currentProfileImage && file_exists("../../../public/uploads/profiles/$currentProfileImage")) {
                    unlink("../../../public/uploads/profiles/$currentProfileImage");
                }
            } else {
                // Handle the case where the upload fails
                $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'Failed to upload the new profile image.'];
                header('Location: ../admin/newuser.php');
                exit;
            }
        } else {
            // If no new profile image is uploaded, keep the current one
            $newProfileImage = $currentProfileImage;
        }

        $result = $this->userDAL->updateUser($id, $fullname, $address, $dateOfBirth, $gender, $phone_number, $email, $password, $role, $newProfileImage);

        if ($result) {
            $_SESSION['alert'] = ['type' => 'success', 'status' => 'Success', 'message' => 'Updated user successfully.'];
            header('Location: ../admin/studentinfolists.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'Failed to update user.'];
            header('Location: ../admin/newuser.php');
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
            $_SESSION['alert'] = ['type' => 'warning', 'status' => 'Warning', 'message' => 'Do you want to delete this user?'];
            header('Location: ../admin/studentinfolists.php');
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'Failed to delete user.'];
            header('Location: ../admin/studentinfolists.php');
            exit;
        }
    }

    public function handlerFileUpload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_image'])) {
            return;
        }
        $file = $_FILES['profile_image'];
        $filename = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        $fileExt = explode('.', $filename);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = ['.jpg', '.jpeg', '.png'];

        // Check if the file is an image
        if (in_array($fileActualExt, $allowed)) {
            $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'You cannot upload files of this type.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Check for errors
        if ($fileError > 0) {
            $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'There was an error uploading your file.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Check file size
        if ($fileSize > 5000000) {
            $_SESSION['alert'] = ['type' => 'error', 'status' => 'Error', 'message' => 'Your file is too large.'];
            header('Location: ../admin/newuser.php');
            exit;
        }

        // Upload the file
        $fileNameNew = uniqid('', true) . "." . $fileActualExt;
        $fileDestination = "../../../public/uploads/{$fileNameNew}";
        // Move the uploaded file
        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            return $fileNameNew; // Return the new file name on success
        } else {
            return null; // Return null if upload failed
        }
    }
}
