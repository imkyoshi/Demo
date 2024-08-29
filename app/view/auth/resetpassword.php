


<?php require '../view/auth/layout/header.php'; ?>

    <!-- Left Cover Image with Lorem Ipsum -->
    <div class="cover w-full md:w-1/2 bg-cover bg-center hidden md:flex items-center justify-center" style="background-image: url('schol2.png');">
        <div class="text-white text-center px-6">
            <!-- <h1 class="text-4xl font-bold mb-4">Welcome to Our Service</h1>
            <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque euismod nisl nec urna luctus, et dapibus nulla aliquet. Sed ac commodo mi. Mauris sit amet justo quam.</p> -->
        </div>
    </div>

    <!-- Right Login Form -->
    <div class="login-form-container flex items-center justify-center w-full md:w-1/2 bg-gray-100 p-6 md:p-12">
        <div class="login-form w-full max-w-lg max-h-md">
            <div class="text-center mb-10">
                <!-- Brand Logo with Responsive Size -->
                <img src="st 3.png" alt="Brand Logo" class="mx-auto mb-4 w-24 md:w-36">
                <h2 class="text-2xl font-bold mb-6">Forgot Password</h2>
            </div>
            <form id="resetPasswordForm" action="resetpassword.php" method="POST">
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors" required>
                    <div id="passwordFeedback" class="invalid-feedback">Password is required.</div>
                </div>
                <div class="mb-6">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors" required>
                    <div id="confirmPasswordFeedback" class="invalid-feedback">Passwords do not match.</div>
                </div>
                <button type="submit" class="w-full bg-green-400 text-white py-2 px-4 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Reset Password</button>
                <div class="text-center mt-4">
                    <p class="mt-2 text-sm">Remembered your password? <a href="login.html" class="text-blue-500 hover:text-blue-600">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Reset Passwords</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .invalid-feedback {
            display: none;
            color: #f87171; /* Tailwind's red-400 */
        }
        .invalid-feedback.show {
            display: block;
        }
        @media (max-width: 768px) {
            .cover {
                display: none;
            }
            .login-form-container {
                padding: 4rem 1.5rem; /* Adjust padding for mobile */
                width: 100%;
                background: white; /* Ensure background is white on mobile */
                min-height: 100vh; /* Ensure it takes up full viewport height */
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="flex flex-col md:flex-row min-h-screen">

    <!-- Left Cover Image with Lorem Ipsum -->
    <div class="cover w-full md:w-1/2 bg-cover bg-center hidden md:flex items-center justify-center" style="background-image: url('schol2.png');">
        <div class="text-white text-center px-6">
            <!-- <h1 class="text-4xl font-bold mb-4">Welcome to Our Service</h1>
            <p class="text-lg">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque euismod nisl nec urna luctus, et dapibus nulla aliquet. Sed ac commodo mi. Mauris sit amet justo quam.</p> -->
        </div>
    </div>

    <!-- Right Login Form -->
    <div class="login-form-container flex items-center justify-center w-full md:w-1/2 bg-gray-100 p-6 md:p-12">
        <div class="login-form w-full max-w-lg max-h-md">
            <div class="text-center mb-10">
                <!-- Brand Logo with Responsive Size -->
                <img src="st 3.png" alt="Brand Logo" class="mx-auto mb-4 w-24 md:w-36">
                <h2 class="text-2xl font-bold mb-6">Forgot Password</h2>
            </div>
            <form id="resetPasswordForm" action="resetpassword.php" method="POST">
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors" required>
                    <div id="passwordFeedback" class="invalid-feedback">Password is required.</div>
                </div>
                <div class="mb-6">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors" required>
                    <div id="confirmPasswordFeedback" class="invalid-feedback">Passwords do not match.</div>
                </div>
                <button type="submit" class="w-full bg-green-400 text-white py-2 px-4 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Reset Password</button>
                <div class="text-center mt-4">
                    <p class="mt-2 text-sm">Remembered your password? <a href="login.html" class="text-blue-500 hover:text-blue-600">Login</a></p>
                </div>
            </form>
        </div>
    </div>
    <?php require '../view/auth/layout/footer.php'; ?>