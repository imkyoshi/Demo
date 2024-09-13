<?php
session_start();

require '../../../vendor/autoload.php'; // Autoload classes via Composer
require_once '../../../app/config/Connection.php';

use app\controller\AuthController;
use app\model\AuthDAL;
use app\config\Connection;
use app\Helpers\Cookies; // Make sure to include the Cookies class

$connection = new Connection();
$pdo = $connection->connect();
$authDAL = new AuthDAL($pdo);
$cookies = new Cookies(); 
$authController = new AuthController($authDAL, $cookies);

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->register();
    exit;
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
            <form id="registerForm" action="register.php" method="POST">
                <div class="grid grid-cols-1 gap-4 mb-2 md:grid-cols-2">
                    <div class="mb-2">
                        <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="fullname" name="fullname"
                            class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"
                            required>
                    </div>
                    <div class="mb-2">
                        <label for="phone_numbe" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone_numbe" name="phone_number"
                            class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"
                            required>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="address" name="address"
                        class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"
                        required>
                </div>
                <div class="grid grid-cols-1 gap-4 mb-3 md:grid-cols-2">
                    <div class="mb-3">
                        <label for="dateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth"
                            class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"
                            required>
                            <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender"
                            class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"
                            required>
                            <option value="" disabled selected>Select your gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email"class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"required>
                    <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="block w-full px-2 py-1 mt-1 transition-colors border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500"
                        required>
                        <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <!-- Terms and Agreement Modal Trigger -->
                <div class="mb-6">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="text-gray-700 text-md">I agree to the
                        <a href="#" class="text-blue-500 hover:text-blue-600" data-modal-target="#termsModal">Terms and
                            Agreement</a>
                    </label>
                </div>

                <button type="submit" name="register"
                    class="w-full px-4 py-2 text-white bg-green-400 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Register</button>
                <div class="mt-4 text-center">
                    <p class="mt-2 text-lg">Already have an account? <a href="login.php"
                            class="text-blue-500 hover:text-blue-600">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <?php require '../../api/ApiScripts/apiRegister.php'; ?>
    <?php require '../auth/layout/modal.php'; ?>
<?php require '../auth/layout/footer.php'; ?>
