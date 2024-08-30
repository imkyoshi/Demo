<?php
session_start();


use app\controller\AuthController;
use app\model\AuthDAL;

require '../../../app/config/db.php';
require '../../../app/controller/AuthController.php';
require '../../../vendor/autoload.php';

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->login();
}
?>
<?php require '../auth/layout/header.php'; ?>

    <!-- Left Cover Image with Lorem Ipsum -->
    <div class="items-center justify-center hidden w-full bg-center bg-cover cover md:w-1/2 md:flex" style="background-image: url('../../../public/img/schol2.png');">
        <div class="px-6 text-center text-white">
            <!-- <h1 class="mb-4 text-4xl font-bold">Welcome to Our Service</h1>
            <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque euismod nisl nec urna luctus, et dapibus nulla aliquet. Sed ac commodo mi. Mauris sit amet justo quam.</p> -->
        </div>
    </div>

    <!-- Right Login Form -->
    <div class="flex items-center justify-center w-full p-6 login-form-container md:w-1/2 bg-white-100 md:p-12">
        <div class="w-full max-w-lg login-form max-h-md">
            <div class="mb-10 text-center">
                <!-- Brand Logo with Responsive Size -->
                <img src="../../../public/img/st 3.png" alt="Brand Logo" class="w-24 mx-auto mb-4 md:w-36">
                <h2 class="mb-6 text-2xl font-bold">Login</h2><hr class="drop-shadow-md">
            </div>
            <form id="loginForm" action="" method="POST">
                <div class="mb-4">
                    <label for="email" class="block font-medium text-gray-700 text-md">Email</label>
                    <input type="email" id="email" name="email" class="block w-full px-4 py-2 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500" required>
                    <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block font-medium text-gray-700 text-md">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500" required>
                    <div id="passwordFeedback" class="invalid-feedback">Password is required.</div>
                </div>
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-gray-700 text-md">Remember Me</label>
                </div>
                <button type="submit" name="login" class="w-full px-4 py-2 text-white bg-green-400 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Login</button>
                <div class="mt-4 text-center">
                    <a href="forgotpassword.php" class="text-blue-500 hover:text-blue-600 text-md">Forgot Password?</a>
                    <p class="mt-2 text-md">Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-600">Register</a></p>
                </div>
            </form>
        </div>
    </div>
<?php require '../auth/layout/footer.php'; ?>
