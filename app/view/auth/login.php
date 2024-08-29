<?php
session_start();
require '../../../app/config/db.php';
require '../../../app/controller/AuthController.php';

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->login();
}
?>
<?php require '../auth/layout/header.php'; ?>

    <!-- Left Cover Image with Lorem Ipsum -->
    <div class="cover w-full md:w-1/2 bg-cover bg-center hidden md:flex items-center justify-center" style="background-image: url('../../../public/img/schol2.png');">
        <div class="text-white text-center px-6">
            <!-- <h1 class="text-4xl font-bold mb-4">Welcome to Our Service</h1>
            <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque euismod nisl nec urna luctus, et dapibus nulla aliquet. Sed ac commodo mi. Mauris sit amet justo quam.</p> -->
        </div>
    </div>

    <!-- Right Login Form -->
    <div class="login-form-container flex items-center justify-center w-full md:w-1/2 bg-white-100 p-6 md:p-12">
        <div class="login-form w-full max-w-lg max-h-md">
            <div class="text-center mb-10">
                <!-- Brand Logo with Responsive Size -->
                <img src="../../../public/img/st 3.png" alt="Brand Logo" class="mx-auto mb-4 w-24 md:w-36">
                <h2 class="text-2xl font-bold mb-6">Login</h2><hr class="drop-shadow-md">
            </div>
            <form id="loginForm" action="" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-md font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors" required>
                    <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-md font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors" required>
                    <div id="passwordFeedback" class="invalid-feedback">Password is required.</div>
                </div>
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 text-md text-gray-700">Remember Me</label>
                </div>
                <button type="submit" name="login" class="w-full bg-green-400 text-white py-2 px-4 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Login</button>
                <div class="text-center mt-4">
                    <a href="forgotpassword.php" class="text-blue-500 hover:text-blue-600 text-md">Forgot Password?</a>
                    <p class="mt-2 text-md">Don't have an account? <a href="register.php" class="text-blue-500 hover:text-blue-600">Register</a></p>
                </div>
            </form>
        </div>
    </div>
<?php require '../auth/layout/footer.php'; ?>
