<?php require '../auth/layout/header.php'; ?>

<body class="flex flex-col md:flex-row min-h-screen">

    <!-- Left Cover Image with Lorem Ipsum -->
    <div class="cover w-full md:w-1/2 bg-cover bg-center hidden md:flex items-center justify-center"
        style="background-image: url('../../../public/img/schol2.png');">
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
                <img src="../../../public/img/st 3.png" alt="Brand Logo" class="mx-auto mb-4 w-24 md:w-36">
                <h2 class="text-2xl font-bold mb-6">Forgot Password</h2><hr class="drop-shadow-md">
            </div>
            <form id="forgotPasswordForm" action="forgotpassword.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-lg font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email"  class="mt-1 block w-full px-6 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-green-300 hover:border-green-500 transition-colors"required>
                    <div id="emailFeedback" class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <button type="submit"
                    class="w-full bg-green-400 text-white py-3 px-6 rounded-md hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300">Send
                    Reset Link</button>
                <div class="text-center mt-4">
                    <p class="mt-2 text-lg">Remembered your password? <a href="login.php"
                            class="text-blue-500 hover:text-blue-600">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <?php require '../auth/layout/footer.php'; ?>