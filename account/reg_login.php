<div class="hidden">
    <?php include('../includes/header.php'); ?>
</div>

<!-- Go to Back Options -->
<div class="w-full bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 flex items-center justify-center">
    <button onclick="window.location.href='../index.php';" class="bg-white px-4 py-2 mt-2 rounded-lg hover:bg-blue-200 transition duration-300 flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i> <!-- Left arrow icon -->
        <span>Back</span>
    </button>
</div>

<!-- Login / Sign Up Page -->
<section class="flex justify-center py-4 items-center min-h-screen bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500">
    <div class="bg-white bg-opacity-70 backdrop-blur-lg p-6 rounded-lg shadow-lg w-full max-w-md">
        <!-- Login / Sign Up Form -->
        <div class="login-signup-form">
            <h2 class="text-3xl font-bold text-center text-gray-800">Welcome to NepalBazar</h2>
            <p class="text-center text-gray-500 mt-2 text-sm">Please log in to your account or sign up to get started.</p>

            <!-- Social Login Options -->
            <div class="flex justify-center mt-6 space-x-4">
                <!-- Google Login Button -->
                <button class="w-full py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-300 flex items-center justify-center">
                    <i class="fab fa-google mr-3"></i>Log In with Google
                </button>
            </div>
            <div class="flex justify-center mt-4 space-x-4">
                <!-- Facebook Login Button -->
                <button class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 flex items-center justify-center">
                    <i class="fab fa-facebook mr-3"></i>Log In with Facebook
                </button>
            </div>

            <!-- Login / Sign Up Tabs -->
            <div class="flex justify-center mt-6 space-x-4">
                <button class="tab-btn px-6 py-2 text-lg font-medium rounded-tl-lg rounded-tr-lg focus:outline-none transition duration-300 ease-in-out border-b-2 border-transparent hover:border-gray-500 active:border-gray-700" id="login-tab" onclick="showTab('login')">Log In</button>
                <button class="tab-btn px-6 py-2 text-lg font-medium rounded-tl-lg rounded-tr-lg focus:outline-none transition duration-300 ease-in-out border-b-2 border-transparent hover:border-gray-500 active:border-gray-700" id="signup-tab" onclick="showTab('signup')">Sign Up</button>
            </div>

            <!-- Login Form -->
            <div id="login-form" class="form-content mt-6">
                <form action="../config/function.php" method="POST" onsubmit="return validateLogin()">
                    <div class="form-group mb-4">
                        <label for="login-email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" id="login-email" name="email" placeholder="Enter your email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Please enter a valid email address.</p>
                    </div>

                    <div class="form-group mb-6">
                        <label for="login-password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Please enter a valid password.</p>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-right mb-4">
                        <a href="../config/function.php" class="text-indigo-600 hover:text-indigo-800 text-sm">Forgot your password?</a>
                    </div>

                    <button type="submit" name="login" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">Log In</button>
                </form>
            </div>

            <!-- Sign Up Form -->
            <div id="signup-form" class="form-content mt-6 hidden">
                <form action="../config/function.php" method="POST" onsubmit="return validateSignup()">
                    <div class="form-group mb-4">
                        <label for="signup-name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                        <input type="text" id="signup-name" name="signup-name" placeholder="Enter your full name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Full name is required.</p>
                    </div>

                    <div class="form-group mb-4">
                        <label for="signup-email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" id="signup-email" name="signup-email" placeholder="Enter your email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Please enter a valid email address.</p>
                    </div>

                    <div class="form-group mb-4">
                        <label for="signup-address" class="block text-gray-700 font-medium mb-2">Address</label>
                        <input type="text" id="signup-address" name="signup-address" placeholder="Enter your address" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Address is required.</p>
                    </div>

                    <div class="form-group mb-4">
                        <label for="signup-password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" id="signup-password" name="signup-password" placeholder="Create a password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Password is required.</p>
                    </div>

                    <div class="form-group mb-6">
                        <label for="signup-confirm-password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                        <input type="password" id="signup-confirm-password" name="signup-password_confirmation" placeholder="Confirm your password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="error-message text-red-500 text-sm hidden">Passwords do not match.</p>
                    </div>

                    <button type="submit" name="registration" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300">Sign Up</button>
                </form>
            </div>

            <!-- Error or Success Message -->
            <p class="message text-center mt-4 text-red-500" id="message"></p>
        </div>
    </div>
</section>

<script src="../assets/js/registerScript.js" async></script>

<div class="#">
    <?php include('../includes/footer.php');?>
</div>