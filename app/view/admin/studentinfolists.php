<?php
session_start();
require '../../../vendor/autoload.php'; // Autoload classes via Composer
require_once '../../../app/config/Connection.php';

use app\controller\AuthController1;
use app\controller\UserController;
use app\model\AuthDAL;
use app\model\UserDAL;
use app\config\Connection;
use app\Helpers\Cookies;


//Establish Database Connection
$connection = new Connection();
$pdo = $connection->connect();

//Fetch the Controller,Model of Auth
$cookies = new Cookies();
$authDAL = new AuthDAL($pdo);
$authController = new AuthController1($authDAL, $cookies);

//Fetch the Controller,Model of User
$userDAL = new UserDAL($pdo);
$userController = new UserController($userDAL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Please login first To access the page.'];
    header("Location: ../auth/login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->handlerDeleteUser();
}
$users = $userController->getAllUser();
?>

<?php require '../admin/layout/header.php';?>

    <div class="main-wrapper">

    <?php require '../admin/layout/top-nav.php';?>


        <?php require '../admin/layout/side-bar.php';?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Stundent Information</h4>
                        <h6>Manage your User</h6>
                    </div>
                    <div class="page-btn">
                        <a href="../admin/newuser.php" class="btn btn-added"><img src="../../../public/img/icons/plus.svg" alt="img">Add
                            Student</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="../../../public/img/icons/filter.svg" alt="img">
                                        <span><img src="../../../public/img/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="../../../public/img/icons/search-white.svg"
                                            alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                src="../../../public/img/icons/pdf.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                src="../../../public/img/icons/excel.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                src="../../../public/img/icons/printer.svg" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="pb-0 card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter User Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" class="datetimepicker cal-icon"
                                                placeholder="Choose Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select">
                                                <option>Disable</option>
                                                <option>Enable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                        <div class="form-group">
                                            <a class="btn btn-filters ms-auto"><img
                                                    src="../../../public/img/icons/search-whites.svg" alt="img"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table datanew">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="checkboxs">
                                                <input type="checkbox" id="select-all">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </th>
                                        <th>Student No</th>
                                        <th>Profile</th>
                                        <th>Full Name </th>
                                        <th>Address</th>
                                        <th>Birthday</th>
                                        <th>gender</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>password</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <!-- <th>Created at</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <label class="checkboxs">
                                                    <input type="checkbox" class="user-checkbox" value="<?php echo $user['id']; ?>">
                                                    <span class="checkmarks"></span>
                                                </label>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['student_no']); ?></td>    
                                            <td>
                                                <img src="<?php echo htmlspecialchars('../../../public/uploads/' . $user['profile_image']); ?>" alt="Profile Image" width="40" height="40"> <!-- Display the profile image -->
                                            </td>
                                            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                                            <td><?php echo htmlspecialchars($user['address']); ?></td>
                                            <td><?php echo htmlspecialchars($user['dateOfBirth']); ?></td>
                                            <td><?php echo htmlspecialchars($user['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <input type="password"  value="<?php echo $user['password']; ?>"
                                                    readonly class="password-input" style="border-style: none; background-color: transparent;"
                                                    id="password_<?php echo $user['id']; ?>">
                                                <span class="toggle-password"
                                                    onclick="togglePasswordVisibility(event, <?php echo $user['id']; ?>)">
                                                    <i class="fa fa-eye" style="cursor: pointer;"></i>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                            <td><?php echo htmlspecialchars($user['updated_at']); ?></td>
                                            <td>
                                            <a class="me-3" href="newuseredit.php">
                                                <img src="../../../public/img/icons/edit.svg" alt="img">
                                            </a>
                                            <a class="me-3" href="userlists.php" >
                                                <img src="../../../public/img/icons/delete.svg" alt="img">
                                            </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No users found</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="showpayment" tabindex="-1" aria-labelledby="showpayment" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Show Payments</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Amount </th>
                                    <th>Paid By </th>
                                    <th>Paid By </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bor-b1">
                                    <td>2022-03-07 </td>
                                    <td>INV/SL0101</td>
                                    <td>$ 1500.00 </td>
                                    <td>Cash</td>
                                    <td>
                                        <a class="me-2" href="javascript:void(0);">
                                            <img src="../../../public/img/icons/printer.svg" alt="img">
                                        </a>
                                        <a class="me-2" href="javascript:void(0);" data-bs-target="#editpayment"
                                            data-bs-toggle="modal" data-bs-dismiss="modal">
                                            <img src="../../../public/img/icons/edit.svg" alt="img">
                                        </a>
                                        <a class="me-2 confirm-text" href="javascript:void(0);">
                                            <img src="../../../public/img/icons/delete.svg" alt="img">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createpayment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Payment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Customer</label>
                                <div class="input-group">
                                    <input type="text" value="2022-03-07" class="datetimepicker">
                                    <a class="scanner-set input-group-text">
                                        <img src="../../../public/img/icons/datepicker.svg" alt="img">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" value="INV/SL0101">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Received Amount</label>
                                <input type="text" value="1500.00">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Paying Amount</label>
                                <input type="text" value="1500.00">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Payment type</label>
                                <select class="select">
                                    <option>Cash</option>
                                    <option>Online</option>
                                    <option>Inprogress</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"   class="btn btn-submit">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editpayment" tabindex="-1" aria-labelledby="editpayment" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Customer</label>
                                <div class="input-group">
                                    <input type="text" value="2022-03-07" class="datetimepicker">
                                    <a class="scanner-set input-group-text">
                                        <img src="../../../public/img/icons/datepicker.svg" alt="img">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" value="INV/SL0101">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Received Amount</label>
                                <input type="text" value="1500.00">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Paying Amount</label>
                                <input type="text" value="1500.00">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Payment type</label>
                                <select class="select">
                                    <option>Cash</option>
                                    <option>Online</option>
                                    <option>Inprogress</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-submit">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php require '../admin/layout/footer.php';?>
