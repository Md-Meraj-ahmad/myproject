<?php session_start(); ?>

<header class="bg-indigo-600 fixed w-full top-0 left-1/2 transform -translate-x-1/2 shadow-md z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
        <!-- Left: Hamburger Icon (Mobile) -->
        <div class="md:hidden">
            <button onclick="toggleMenu()" class="text-white text-xl hover:scale-110 transition">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Center: Brand Name -->
        <a href="/" class="text-3xl font-bold text-white tracking-wide flex items-center space-x-1">
            <span class="text-yellow-300">Nepal</span><span class="text-white">Bazar</span>
        </a>

        <!-- Right: Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8 text-white">
            <nav class="flex space-x-4">
                <a href="/" class="hover:text-yellow-300 transition duration-300">Home</a>
                <a href="/products" class="hover:text-yellow-300 transition duration-300">Products</a>
                <a href="/blogs" class="hover:text-yellow-300 transition duration-300">Blogs</a>
                <a href="/contact" class="hover:text-yellow-300 transition duration-300">Contact</a>
                <a href="/policy" class="hover:text-yellow-300 transition duration-300">Policy</a>
            </nav>

            <!-- Cart Button -->
            <a href="/cart" class="relative flex items-center bg-white text-indigo-700 px-3 py-2 rounded-lg hover:bg-indigo-100 transition">
                <i class="fas fa-shopping-cart mr-1"></i>
                <span>Cart</span>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">0</span>
            </a>

            <!-- User Icon / Auth -->
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="flex items-center space-x-2">
                        <img src="/assets/images/user-avatar.png" alt="User" class="w-8 h-8 rounded-full border border-white">
                        <a href="/account/logout.php" class="text-sm font-semibold hover:underline">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="/account/reg_login.php" class="flex items-center bg-white text-indigo-600 px-3 py-2 rounded-lg hover:bg-indigo-100 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Dropdown -->
    <div id="mobile-menu" class="md:hidden hidden flex-col bg-indigo-700 px-4 py-3 space-y-2 text-white">
        <a href="/" class="block hover:text-yellow-300">Home</a>
        <a href="/products" class="block hover:text-yellow-300">Products</a>
        <a href="/blogs" class="block hover:text-yellow-300">Blogs</a>
        <a href="/contact" class="block hover:text-yellow-300">Contact</a>
        <a href="/policy" class="block hover:text-yellow-300">Policy</a>
        <a href="/cart" class="block hover:text-yellow-300">Cart</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/account/logout.php" class="block text-red-300 hover:text-red-500">Logout</a>
        <?php else: ?>
            <a href="/account/reg_login.php" class="block hover:text-yellow-300">Sign In</a>
        <?php endif; ?>
    </div>
</header>

<!-- Search Bar Below Navbar -->
<div class="mt-16 w-full bg-white shadow-md">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center space-x-4">
        <!-- Location -->
        <a href="#" class="hidden md:flex items-center text-blue-600 hover:text-blue-800 transition">
            <i class="fas fa-map-marker-alt mr-2"></i>
            Update Location
        </a>

        <!-- Search Input -->
        <div class="flex-grow relative">
            <input type="text" id="search-bar" placeholder="Search products, brands, etc." class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 pl-10">
            <i class="fas fa-search absolute top-1/2 left-3 transform -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>
</div>
<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }

    // Example: Update cart count dynamically
    function updateCartCount(count) {
        document.getElementById('cart-count').textContent = count;
    }

    // Simulate cart count update (for demonstration purposes)
    updateCartCount(3); // Replace with actual cart count logic
</script>