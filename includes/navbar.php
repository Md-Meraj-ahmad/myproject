<header class="hidden md:flex justify-between items-center p-2 bg-indigo-500 shadow-sm fixed w-full top-0 left-1/2 transform -translate-x-1/2 mx-auto rounded-b-xl z-50">
    <!-- Hamburger Icon for Mobile -->
    <div class="md:hidden flex items-center">
        <button onclick="toggleMenu()" class="text-white focus:outline-none mr-2 transition-transform duration-300 hover:scale-110">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Brand Name -->
    <nav class="flex w-full justify-center items-center">
        <div class="text-2xl font-extrabold text-white flex-grow text-center md:text-left">
            <span class="text-blue-800">Nepal</span><span class="text-white">Bazar</span>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex space-x-6 text-white justify-center flex-grow">
            <a href="/" class="py-1 px-1 hover:bg-blue-800 rounded-lg transition duration-300 transform hover:scale-105">Home</a>
            <a href="/" class="py-1 px-1 hover:bg-blue-800 rounded-lg transition duration-300 transform hover:scale-105">Products</a>
            <a href="/" class="py-1 px-1 hover:bg-blue-800 rounded-lg transition duration-300 transform hover:scale-105">Blogs</a>
            <a href="/" class="py-1 px-1 hover:bg-blue-800 rounded-lg transition duration-300 transform hover:scale-105">Contact</a>
            <a href="/" class="py-1 px-1 hover:bg-blue-800 rounded-lg transition duration-300 transform hover:scale-105">Policy</a>
        </div>

        <!-- Cart and User Links -->
        <div class="hidden md:flex items-center space-x-6 mr-4">
            <a href="/cart" class="px-2 py-2 text-blue-600 bg-white rounded-lg hover:bg-blue-100 flex items-center space-x-2 transition duration-300 transform hover:scale-105">
                <i class="fas fa-shopping-cart"></i>
                <span>Cart (0)</span>
            </a>

            <!-- User Actions -->
            <div class="flex space-x-6">
                <?php session_start(); ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../account/logout.php" id="logout-link" class="bg-red-500 text-white text-lg font-semibold px-2 py-2 rounded-lg hover:bg-red-600 transition duration-300 transform hover:scale-105">Logout</a>
                <?php else: ?>
                <a href="../account/reg_login.php" id="signin-link" class="text-white text-lg font-bold px-2 py-2 rounded-lg hover:bg-blue-200 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<!-- Search Bar Fixed to Bottom of Navbar (Optional) -->
<div class="w-full bg-white p-2 shadow-lg flex justify-between items-center rounded-t-xl md:mt-16">
    <div class="hidden md:flex space-x-6">
        <a href="#" class="text-blue-300 hover:text-blue-500 transition duration-200">Update Location</a>
    </div>
    
    <div class="flex justify-center w-full mr-12 flex-grow">
        <input type="text" id="search-bar" class="p-3 rounded-lg border border-gray-300 w-64 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Search products, brands, etc.">
    </div>
</div>

<script src="../assets/js/navScript.js" async></script>
