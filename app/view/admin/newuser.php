<?php
session_start();
require '../../../vendor/autoload.php'; // Autoload classes via Composer
require_once '../../../app/config/Connection.php';

use app\controller\UserController;
use app\model\UserDAL;
use app\config\Connection;

//Establish Database Connection
$connection = new Connection();
$pdo = $connection->connect();
//Fetch the Controller,Model of User
$userDAL = new UserDAL($pdo);
$userController = new UserController($userDAL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Please login first To access the page.'];
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->handlerAddUser();
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
                        <h4>User Management</h4>
                        <h6>Add/Update User</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" id="fullname" name="fullname">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" id="address" name="address">
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input type="text" id="dateOfBirth" name="dateOfBirth" class="datetimepicker" value="21-09-2024">
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="select" id="gender" name="gender">
                                        <option value=""> Select your gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Others</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" id="phone_number" name="phone_number">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Student No</label>
                                    <input type="text" id="student_no" name="student_no">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="pass-input">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="select" id="role" name="role">
                                        <option value=""> Select your role</option>
                                        <option value="admin">Admin</option>
                                        <option value="registar">Registar</option>
                                        <option value="cashier">cashier</option>
                                        <option value="student">Student</option>
                                        <option value="student">User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label> Profile Picture</label>
                                    <div class="custom-file-container" data-upload-id="myFirstImage">
                                        <label class="text-black">Clear Image <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file" class="custom-file-container__custom-file__custom-file-input" name="profile_image" accept="image/*">
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" id= "addUser" name="addUser" class="btn btn-submit me-2">Add User</button>
                                <a href="../admin/studentinfolists.php" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <?php require '../admin/layout/footer.php';?>