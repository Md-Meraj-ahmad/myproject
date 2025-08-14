<header class="items-center justify-between p-2 bg-gradient-to-r from-blue-500 to-blue-700 shadow-sm mx-auto flex fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-50 rounded-b-xl">
    <!-- Hamburger Icon for Mobile -->
    <div class="md:hidden flex items-center">
        <button onclick="toggleMenu()" class="text-white focus:outline-none mr-2 transition-transform duration-300 transform hover:scale-110">
            <i class="fas fa-bars"></i> <!-- Hamburger icon -->
        </button>
    </div>

    <!-- Brand Name -->
    <nav class="shadow-lg flex w-full justify-center items-center">
        <div class="text-2xl font-extrabold text-white flex-grow text-center md:text-left">
            <span class="text-blue-200">Nepal</span><span class="text-white">Bazar</span>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:flex space-x-8 text-white justify-center flex-grow mr-14">
            <a href="/" class="hover:bg-blue-800 px-1 py-1 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Home</a>
            <a href="/about" class="hover:bg-blue-800 px-1 py-1 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">About</a>
            <a href="/services" class="hover:bg-blue-800 px-1 py-1 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Services</a>
            <a href="/contact" class="hover:bg-blue-800 px-1 py-1 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Contact</a>
            <a href="/blog" class="hover:bg-blue-800 px-1 py-1 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">Blog</a>
        </div>

        <!-- Cart and User Links -->
        <div class="hidden md:flex items-center space-x-6 mr-4">
            <a href="/cart" class="px-2 py-2 text-blue-600 bg-white rounded-lg hover:bg-blue-100 flex items-center space-x-2 transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-shopping-cart"></i>
                <span>Cart (0)</span>
            </a>

            <!-- User Actions (Sign In with icon) -->
            <div class="flex space-x-6">
                <a href="../account/reg_login.php" id="signin-link" class="text-white text-lg px-2 py-2 rounded-lg hover:bg-blue-200 transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </a>
                <a href="../account/logout.php" id="logout-link" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition duration-300 ease-in-out transform hover:scale-105 hidden">Logout</a>
            </div>
        </div>
    </nav>
</header>

<!-- Search Bar Fixed to Bottom of Navbar -->
<div class="w-full bg-white p-2 shadow-lg fixed top-[80px] left-0 z-40 flex items-center justify-between rounded-t-xl">
    <div class="hidden md:flex space-x-6">
        <a href="#" class="text-blue-300 hover:text-blue-500 transition duration-200 ease-in-out">Update Location</a>
    </div>

    <div class="flex justify-center w-full mr-12 mt-2">
        <input type="text" id="search-bar" class="p-3 rounded-lg border border-gray-300 w-64 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Search products, brands, etc.">
    </div>
</div>

<!-- Sidebar Menu -->
<div id="mobile-sidebar" class="mt-10 fixed inset-0 bg-gray-800 bg-opacity-90 transform -translate-x-full transition-transform duration-300 md:hidden z-40 rounded-xl shadow-xl">
    <div class="flex justify-end p-6">
        <button onclick="toggleMenu()" class="text-white text-xl">
            <i class="fas fa-times"></i> <!-- Close icon -->
        </button>
    </div>
    <nav class="mx-4">
        <ul class="space-y-6 mt-6">
            <!-- Action Button with Text "Menu" -->
            <li class="flex items-center space-x-4">
                <button onclick="toggleActionMenu()" class="text-white py-3 px-6 rounded-xl shadow-lg hover:bg-gray-700 transition duration-200 transform hover:scale-105 flex items-center w-full justify-between">
                    <span class="font-semibold text-xl">Menu</span>
                </button>
            </li>

            <!-- Menu Options Below (Initially hidden) -->
            <li id="action-menu" class="space-y-2 hidden pl-6">
                <a href="/" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Home</a>
                <a href="/about" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">About</a>
                <a href="/services" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Services</a>
                <a href="/contact" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Contact</a>
                <a href="/blog" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Blog</a>
                <a href="../account/login.php" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Login</a>
                <a href="../account/register.php" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Signup</a>
                <a href="../account/logout.php" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="mx-4 mt-6">
        <ul class="space-y-6">
            <!-- Action Button with Text "Category List" -->
            <li class="flex items-center space-x-4">
                <button onclick="toggleActionCategoryList()" class="text-white py-3 px-6 rounded-xl shadow-lg hover:bg-gray-700 transition duration-200 transform hover:scale-105 flex items-center w-full justify-between">
                    <span class="font-semibold text-xl">Category List</span>
                </button>
            </li>

            <!-- Category-List Options Below (Initially hidden) -->
            <li id="category-list" class="space-y-2 hidden pl-6">
                <a href="/category/1" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Mobile</a>
                <a href="/category/2" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Laptop</a>
                <a href="/../products/category.php#electronics" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Electronics</a>
                <a href="/../products/category.php#cloth" class="block text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 transform hover:scale-105">Dress</a>
            </li>
        </ul>
    </div>
</div>

<script>
    // Function to toggle the mobile menu visibility (Sidebar)
    function toggleMenu() {
        const sidebar = document.getElementById('mobile-sidebar');
        sidebar.classList.toggle('translate-x-0');  // Toggle sidebar visibility
        sidebar.classList.toggle('-translate-x-full'); // Hide sidebar when toggled
    }

    // Toggle the action menu visibility (show/hide menu options)
    function toggleActionMenu() {
        const menu = document.getElementById('action-menu');
        menu.classList.toggle('hidden');  // This will hide/show the menu options
    }

    // Toggle the action menu visibility (show/hide menu options)
    function toggleActionCategoryList() {
        const categoryList = document.getElementById('category-list');
        categoryList.classList.toggle('hidden');  // This will hide/show the category list options
    }

    // Function to toggle the user-specific menu visibility
    function toggleUserMenu() {
        const userSidebar = document.getElementById('user-sidebar');
        userSidebar.classList.toggle('translate-x-0');  // Toggle user sidebar visibility
        userSidebar.classList.toggle('-translate-x-full'); // Hide user sidebar when toggled
    }

    // Check login status
    const isLoggedIn = false; // Replace this with the actual login check, e.g., through a session or cookie

    if (isLoggedIn) {
        document.getElementById('signin-link').classList.add('hidden');
        document.getElementById('logout-link').classList.remove('hidden');
    } else {
        document.getElementById('signin-link').classList.remove('hidden');
    }
</script>
