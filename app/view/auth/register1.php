<?php
session_start();
require '../../../app/config/db.php';
require '../../../app/controller/AuthController.php';

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->register();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Muhamad Nauval Azhar" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="This is a login page template based on Bootstrap 5" />
    <title>DEMO|Register Page</title>
    <link href="../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                    <div class="text-center my-5">
                        <img src="../../../public/img/st 3.png" alt="logo"
                            width="150" />
                    </div>
                    <div class="card shadow-lg col-md">
                        <div class="card-body p-5">
                            <h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
                            <form method="POST" action="" class="needs-validation" novalidate="" autocomplete="on">
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="fullname">Full Name</label>
                                    <input id="fullname" type="text" class="form-control" name="fullname" placeholder="Enter your Full Name" required autofocus />
                                    <div class="invalid-feedback">Full Name is required</div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="address">Address</label>
                                    <input id="address" type="text" class="form-control" name="address" placeholder="Enter your Address" required />
                                    <div class="invalid-feedback">Address is required</div>
                                </div>

                                <div class="mb-3">
                                <label class="mb-2 text-muted" for="dateOfBirth">Date of Birth</label>
                                <input id="dateOfBirth" type="date" class="form-control" name="dateOfBirth" required>
                                    <div class="invalid-feedback">Date Of Birth is required</div>
                                </div>


                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="gender">Gender</label>
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option value="">Select your gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <div class="invalid-feedback">Gender is required</div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="phone_number">Phone Number</label>
                                    <input id="phone_number" type="text" class="form-control" name="phone_number" placeholder="Enter your Phone Number" required />
                                    <div class="invalid-feedback">Phone Number is required</div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" placeholder="Enter your Email" required />
                                    <div class="invalid-feedback">Email is invalid</div>
                                </div>

                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="password">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Enter your password" required />
                                    <div class="invalid-feedback">Password is required</div>
                                </div>

                                <p class="form-text text-muted mb-3">
                                    By registering you agree with our terms and condition.
                                </p>

                                <div class="align-items-center d-flex">
                                    <button type="submit" name="register" class="btn btn-primary ms-auto">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer py-3 border-0">
                            <div class="text-center">
                                Already have an account?
                                <a href="login.php" class="text-dark">Login</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5 text-muted">
                        Copyright &copy; 2017-2021 &mdash; Your Company
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require '../../view/auth/include/script.php';?>
</body>

</html>
