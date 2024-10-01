<?php
session_start();
require '../../../vendor/autoload.php'; // Autoload classes via Composer
require_once '../../../app/config/Connection.php';

use app\config\Connection;
use app\controller\UserController;
use app\model\UserDAL;

// Establish Database Connection
$connection = new Connection();
$pdo = $connection->connect();
// Fetch the Controller, Model of User
$userDAL = new UserDAL($pdo);
$userController = new UserController($userDAL);

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Please login first to access the page.'];
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->handlerUpdateUser();
}

// Get the user ID from the URL
$user_id = $_GET['edituser'] ?? null;
if (!$user_id) {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Invalid user ID.'];
    header("Location: studentinfolists.php");
    exit;
}

// Fetch the user data
$user = $userController->getUserById($user_id);
if (!$user) {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'User not found.'];
    header("Location: studentinfolists.php");
    exit;
}

?>

<?php require '../admin/layout/header.php';?>

<div class="main-wrapper">
<?php require '../admin/layout/top-nav.php';?>
<?php require '../admin/layout/side-bar.php';?>

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Student Information</h4>
                    <h6>Edit/Update User</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input type="text" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($user['dateOfBirth']); ?>" class="datetimepicker" value="21-09-2024">
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="select" name="gender">
                                        <option value=""> Select your gender</option>
                                        <option value="Male" <?php echo htmlspecialchars($user['gender'] == 'Male' ? 'selected' : ''); ?>>Male</option>
                                        <option value="Female" <?php echo htmlspecialchars($user['gender'] == 'Female' ? 'selected' : ''); ?>>Female</option>
                                        <option value="Other" <?php echo htmlspecialchars($user['gender'] == 'Other' ? 'selected' : ''); ?>>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Mobile No</label>
                                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" class="">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Student No</label>
                                    <input type="text" id="student_no" name="student_no" value="<?php echo htmlspecialchars($user['student_no']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="pass-group">
                                        <input type="password" id="password" name="password"  class="pass-inputs" value="<?php echo htmlspecialchars($user['password']); ?>">
                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="select" name="role">
                                        <option value=""> Select your role</option>
                                        <option value="admin" <?php echo htmlspecialchars($user['role'] == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                        <option value="registar" <?php echo htmlspecialchars($user['role'] == 'registar' ? 'selected' : ''); ?>>Registar</option>
                                        <option value="cashier" <?php echo htmlspecialchars($user['role'] == 'cashier' ? 'selected' : ''); ?>>Cashier</option>
                                        <option value="student" <?php echo htmlspecialchars($user['role'] == 'student' ? 'selected' : ''); ?>>Student</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <div class="label-wrapper">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <div>
                                            <p>Current file: <?php echo htmlspecialchars($user['profile_image']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <input class="form-control" type="file" id="profile_image" name="profile_image">
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="image_preview" class="form-label">Profile Image Preview:</label>
                                    <div id="image_preview" style="margin-top: 10px;">
                                        <?php if (!empty($user['profile_image'])): ?>
                                            <img src="../../../public/uploads/profiles/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" id="currentProfileImage" name="currentProfileImage" style="max-width: 100%; height: auto; border-radius: 5px; border: 1px solid #ddd;">
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" id= "updateUser" name="updateUser" class="btn btn-submit me-2">Add User</button>
                                <a href="../admin/studentinfolists.php" class="btn btn-cancel">Cancel</a>
                            </div>
                            <!-- Fullscreen Modal -->
                            <div id="fullscreenModal" class="modal">
                                <button class="close-btn" id="closeModal">&times;</button>
                                <img id="fullscreenImg" src="" alt="Fullscreen Preview">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require '../admin/layout/footer.php';?>
