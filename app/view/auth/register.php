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

<?php require '../../view/auth/layout/header.php'; ?>

    <!-- Left Cover Image with Lorem Ipsum -->
    <div class="cover w-full md:w-1/2 bg-cover bg-center hidden md:flex items-center justify-center"
        style="background-image: url('../../../public/img/schol2.png');">
        <div class="text-white text-center px-6">
            <!-- <h1 class="text-4xl font-bold mb-4">Welcome to Our Service</h1>
            <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque euismod nisl nec urna luctus, et dapibus nulla aliquet. Sed ac commodo mi. Mauris sit amet justo quam.</p> -->
        </div>
    </div>

    <!-- Right Login Form -->
    <div class="login-form-container flex items-center justify-center w-full md:w-1/2 bg-white-100 p-6 md:p-12">
        <div class="login-form w-full max-w-lg">
            <div class="text-center mb-10">
                <!-- Brand Logo with Responsive Size -->
                <img src="../../../public/img/st 3.png" alt="Brand Logo" class="mx-auto mb-4 w-24 md:w-36">
                <h2 class="text-2xl font-bold mb-6">Register</h2>
                <hr class="drop-shadow-md">
            </div>
            <form id="registerForm" action="register.php" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                    <div class="mb-2">
                        <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="fullname" name="fullname"
                            class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
                            required>
                    </div>
                    <div class="mb-2">
                        <label for="phone_numbe" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone_numbe" name="phone_number"
                            class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
                            required>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="address" name="address"
                        class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
                        required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div class="mb-3">
                        <label for="dateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth"
                            class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender"
                            class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
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
                    <input type="email" id="email" name="email"
                        class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"
                        required>
                </div>

                <!-- Terms and Agreement Modal Trigger -->
                <div class="mb-6">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="text-md text-gray-700">I agree to the
                        <a href="#" class="text-blue-500 hover:text-blue-600" data-modal-target="#termsModal">Terms and
                            Agreement</a>
                    </label>
                </div>

                <button type="submit" name="register"
                    class="w-full bg-green-400 text-white py-2 px-4 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Register</button>
                <div class="text-center mt-4">
                    <p class="mt-2 text-lg">Already have an account? <a href="login.php"
                            class="text-blue-500 hover:text-blue-600">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <?php require '../../view/auth/layout/modal.php'; ?>
    <?php require '../../view/auth/layout/footer.php'; ?>