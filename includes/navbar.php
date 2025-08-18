<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // Database connection

// Active link highlighter
function activeLink($path) {
    return strpos($_SERVER['REQUEST_URI'], $path) === 0 ? 'text-yellow-300' : 'hover:text-yellow-300';
}

// Cart count from session
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Wishlist count from DB
$user_id = $_SESSION['user_id'] ?? null;
$wishlist_count = 0;

if ($user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlists WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($wishlist_count);
    $stmt->fetch();
    $stmt->close();
}
?>

<!-- Header -->
<header class="bg-indigo-600 fixed w-full top-0 left-1/2 transform -translate-x-1/2 shadow-md z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
        <!-- Hamburger (Mobile) -->
        <div class="md:hidden">
            <button onclick="toggleMenu()" class="text-white text-xl hover:scale-110 transition">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Logo -->
        <a href="/" class="text-3xl font-bold text-white tracking-wide flex items-center space-x-1">
            <span class="text-yellow-300">Nepal</span><span class="text-white">Bazar</span>
        </a>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center space-x-8 text-white">
            <nav class="flex space-x-4">
                <a href="/" class="<?php echo activeLink('/'); ?> transition duration-300">Home</a>
                <a href="/products" class="<?php echo activeLink('/products'); ?> transition duration-300">Products</a>
                <a href="/blogs" class="<?php echo activeLink('/blogs'); ?> transition duration-300">Blogs</a>
                <a href="/contact" class="<?php echo activeLink('/contact'); ?> transition duration-300">Contact</a>
                <a href="/policy" class="<?php echo activeLink('/policy'); ?> transition duration-300">Policy</a>
            </nav>

            <!-- Cart -->
            <a href="/cart/cart.php" class="relative flex items-center bg-white text-indigo-700 px-3 py-2 rounded-lg hover:bg-indigo-100 transition">
                <i class="fas fa-shopping-cart mr-1"></i>
                <span>Cart</span>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                    <?php echo $cart_count; ?>
                </span>
            </a>

            <!-- Wishlist -->
            <a href="/cart/wishlist.php" class="relative flex items-center bg-white text-indigo-700 px-3 py-2 rounded-lg hover:bg-indigo-100 transition">
                <i class="fas fa-heart mr-1"></i>
                <span>Wishlist</span>
                <?php if ($wishlist_count > 0): ?>
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                        <?php echo $wishlist_count; ?>
                    </span>
                <?php endif; ?>
            </a>

            <!-- User Icon / Dropdown -->
            <div>
                <?php if ($user_id): ?>
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 focus:outline-none">
                            <img src="/assets/images/user-avatar.png" alt="User" class="w-8 h-8 rounded-full border border-white">
                        </button>
                        <div id="userDropdown" class="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded shadow-md hidden z-50">
                            <a href="/account/profile.php" class="block px-4 py-2 hover:bg-indigo-100">Profile</a>
                            <a href="/account/orders.php" class="block px-4 py-2 hover:bg-indigo-100">Orders</a>
                            <a href="/account/logout.php" class="block px-4 py-2 text-red-500 hover:bg-red-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/account/reg_login.php" class="flex items-center bg-white text-indigo-600 px-3 py-2 rounded-lg hover:bg-indigo-100 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden flex-col bg-indigo-700 px-4 py-3 space-y-2 text-white">
        <a href="/" class="block <?php echo activeLink('/'); ?> hover:text-yellow-300">Home</a>
        <a href="/products" class="block <?php echo activeLink('/products'); ?> hover:text-yellow-300">Products</a>
        <a href="/blogs" class="block <?php echo activeLink('/blogs'); ?> hover:text-yellow-300">Blogs</a>
        <a href="/contact" class="block <?php echo activeLink('/contact'); ?> hover:text-yellow-300">Contact</a>
        <a href="/policy" class="block <?php echo activeLink('/policy'); ?> hover:text-yellow-300">Policy</a>
        <a href="/cart/cart.php" class="block hover:text-yellow-300">Cart (<?php echo $cart_count; ?>)</a>
        <a href="/cart/wishlist.php" class="block hover:text-yellow-300">Wishlist (<?php echo $wishlist_count; ?>)</a>
        <?php if ($user_id): ?>
            <a href="/account/logout.php" class="block text-red-300 hover:text-red-500">Logout</a>
        <?php else: ?>
            <a href="/account/reg_login.php" class="block hover:text-yellow-300">Sign In</a>
        <?php endif; ?>
    </div>
</header>

<!-- Search Bar -->
<div class="mt-16 w-full bg-white shadow-md">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center space-x-4">
        <a href="#" class="hidden md:flex items-center text-blue-600 hover:text-blue-800 transition">
            <i class="fas fa-map-marker-alt mr-2"></i> Update Location
        </a>
        <form action="/search.php" method="GET" class="flex-grow relative">
            <input type="text" name="q" placeholder="Search products, brands, etc."
            class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 pl-10">
            <i class="fas fa-search absolute top-1/2 left-3 transform -translate-y-1/2 text-gray-400"></i>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    function toggleMenu() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    }

    function toggleDropdown() {
        document.getElementById('userDropdown').classList.toggle('hidden');
    }
</script>
