<?php
session_start();

require '../../../vendor/autoload.php'; // Correct autoload

use app\config\Connection;
use app\controller\AuthController;
use app\model\AuthDAL;

$connection = new Connection();
$pdo = $connection->connect(); // Establish the database connection

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->login();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>DEMO | Login Page</title>
    <link href="../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                    <div class="my-5 text-center">
                        <img src="../../../public/img/st 3.png" alt="logo" width="150">
                    </div>
                    <div class="shadow-lg card">
                        <div class="p-5 card-body">
                            <h1 class="mb-4 fs-4 card-title fw-bold">Login</h1>

                            <!-- Display session alert messages using SweetAlert2 -->
                            <?php if (isset($_SESSION['alert'])): ?>
                                <script>
                                    Swal.fire({
                                        icon: "<?php echo $_SESSION['alert']['type']; ?>",
                                        title: "<?php echo $_SESSION['alert']['message']; ?>",
                                    });
                                </script>
                                <?php unset($_SESSION['alert']); ?>
                            <?php endif; ?>

                            <form method="POST" action="" class="needs-validation" novalidate="" autocomplete="on">
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" placeholder="Enter your email." required autofocus>
                                    <div class="invalid-feedback">
                                        Email is invalid
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="mb-2 w-100">
                                        <label class="text-muted" for="password">Password</label>
                                        <a href="forgotpassword.php" class="float-end">
                                            Forgot Password?
                                        </a>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Enter your password." required>
                                    <div class="invalid-feedback">
                                        Password is required
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                        <label for="remember" class="form-check-label">Remember Me</label>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-primary ms-auto">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="py-3 border-0 card-footer">
                            <div class="text-center">
                                Don't have an account? <a href="register.php" class="text-dark">Create One</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center text-muted">
                        Copyright &copy; 2024 &mdash; Your Company
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require '../../view/auth/include/script.php'; ?>
</body>

</html>
