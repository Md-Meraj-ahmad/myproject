<?php
// Include the header
include('../includes/header.php');

// Include Function
include('../config/function.php');


$products = get_all_products(); // Placeholder function for fetching all products

// Sidebar option selection (default to '')
$action = isset($_GET['action']) ? $_GET['action'] : '';
$sub_action = isset($_GET['sub_action']) ? $_GET['sub_action'] : ''; // New variable to handle sub-actions

?>

<!-- Dashboard Layout -->
<div class="flex mt-1">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>
    <!-- Main Content -->
    <div class="flex-1 px-2 mt-1 font-serif">
        <!-- Header and Hamburger Icon on the Same Line -->
        <div class="md:hidden mb-2 md:mb-0 flex bg-indigo-500 p-2 rounded-lg">
            <button onclick="toggleSidebar()" class="text-white focus:outline-none transition-transform duration-300 hover:scale-110">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="text-2xl font-semibold mb-4 text-white absolute left-1/2 transform -translate-x-1/2">Dashboard</h2>
        </div>

        <!-- Sidebar Menu -->
        <div id="mobile-sidebar" class="mt-24 w-60 fixed inset-0 bg-indigo-800 bg-opacity-90 transform -translate-x-full transition-transform duration-300 md:hidden z-40 rounded-xl shadow-xl">
            <div class="flex justify-end p-6">
                <button onclick="toggleSidebar()" class="text-white text-xl">
                    <i class="fas fa-times"></i> <!-- Close icon -->
                </button>
            </div>

            <div class="justify-center space-x-4 text-xl">
                <h2 class="text-2xl font-semibold mb-4 text-white">Dashboard</h2>
                <ul>
                    <li class="mb-6">
                        <a href="dashboard.php?action=view_product" class="text-white hover:bg-blue-700 p-3 rounded-lg block transition duration-300 ease-in-out transform hover:scale-105">View Products</a>
                    </li>
                    <li class="mb-6">
                        <a href="dashboard.php?action=product_list" class="text-white hover:bg-blue-700 p-3 rounded-lg block transition duration-300 ease-in-out transform hover:scale-105">List Product</a>
                    </li>
                    <li class="mb-6">
                        <a href="dashboard.php?action=manage_product" class="text-white hover:bg-blue-700 p-3 rounded-lg block transition duration-300 ease-in-out transform hover:scale-105">Manage Products</a>
                    </li>
                    <li class="mb-6">
                        <a href="dashboard.php?action=offer_product" class="text-white hover:bg-blue-700 p-3 rounded-lg block transition duration-300 ease-in-out transform hover:scale-105">Offer Products</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
        // Content display based on sidebar selection
        if ($action === 'view_product') {
            // View Products Section
            echo "<h2 class='text-2xl font-semibold mb-4 text-center bg-gradient-to-r from-purple-400 via-green-400 to-blue-400 rounded-lg py-4'>Product List</h2>";
            echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'>";
            foreach ($products as $product) {
                echo "<div class='bg-white p-4 shadow-lg rounded-lg'>
                        <img src='../assets/images/product_images/" . $product['image'] . "' alt='" . $product['name'] . "' class='w-full h-32 object-cover mb-4 rounded'>
                        <h3 class='font-semibold'>" . $product['name'] . "</h3>
                        <p class='text-gray-600'>" . $product['description'] . "</p>
                        <p class='text-blue-600'>$" . number_format($product['price'], 2) . "</p>
                    </div>";
            }
            echo "</div>";
        } elseif ($action === 'product_list') {
            // Product List Section
            echo "<h2 class='text-2xl font-semibold mb-4 text-center bg-gradient-to-r from-purple-400 via-green-400 to-blue-400 to-blue-300 py-4 rounded-lg shadow-md'>Product List</h2>";
            echo "<p class='text-center text-lg mb-8'>Select a product to edit, update, or delete.</p>";

            echo "<div class='container mx-auto px-4 grid sm:grid lg:grid'>";
            // Product Table
            echo "<div class='overflow-x-auto bg-white shadow-lg rounded-lg'>";
            echo "<table class='table-auto w-full text-sm text-left text-gray-700 border-collapse'>";
            echo "<thead class='bg-gray-200 text-gray-600'>
                    <tr class='border-b'>
                        <th class='px-4 py-2'>S.NO</th>
                        <th class='px-4 py-2'>Product Name</th>
                        <th class='px-4 py-2'>Description</th>
                        <th class='px-4 py-2'>Price</th>
                        <th class='px-4 py-2'>Image</th>
                        <th class='px-4 py-2'>Actions</th>
                    </tr>
                </thead>";
            echo "<tbody>";

            // Example row (loop through actual data in production)
            echo "<tr class='border-b hover:bg-gray-100'>
                    <td class='px-4 py-2'>1</td>
                    <td class='px-4 py-2'>Product Name</td>
                    <td class='px-4 py-2'>This is a sample product description</td>
                    <td class='px-4 py-2'>$99.99</td>
                    <td class='px-4 py-2'><img src='path_to_image' alt='Product Image' class='w-12 h-12 object-cover rounded-md'></td>
                    <td class='text-center px-4 py-2'>
                        <a href='#' class='bg-yellow-500 text-white p-2 rounded-lg hover:bg-yellow-400'>Edit</a><br><br>
                        <a href='#' class='bg-green-500 text-white p-2 rounded-lg hover:bg-green-400'>Update</a><br><br>
                        <a href='#' class='bg-red-500 text-white p-2 rounded-lg hover:bg-red-400'>Delete</a>
                    </td>
                    </tr>";

                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";  // End table container
                    echo "</div>";  // End main container
        } elseif ($action === 'manage_product') {
            // Manage Products Section (CRUD operations)
            echo "<h2 class='text-2xl font-semibold mb-4'>Manage Products</h2>";
            echo "<p>You can add, update, or delete products here.</p>";
            echo "<div class='mt-6'>";
                echo "<div class='flex flex-wrap md:space-x-2 gap-2 md:gap-0'>";
                    echo "<a href='dashboard.php?action=manage_product&sub_action=add_products' class='bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700'>Add New Product</a>";
                    echo "<a href='../products/delete_view.php' class='bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700'>Delecte Product</a>";
                echo "</div>";
                    if ($sub_action === 'add_products') {
                        // Include add_products.php form products
                        echo "<div class='flex'>";
                        include '../products/add_products.php';
                        echo "</div>";
                    }
            echo "</div>";
        } elseif ($action === 'offer_product') {
            // Offers Porduct Section
            echo "<h2 class='text-2xl font-semibold mb-4'>Offer Products</h2>";
            echo "<p>You can View and Add Offers Products.</p>";
            echo "<div class='mt-4'>";
                    echo "<div class='md'>";
                        echo "<a href='dashboard.php?action=offer_product&sub_action=add_offers' class='bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700'>Add New Offers</a>";
                    echo "</div>";
                    if ($sub_action === 'add_offers') {
                        // Include offer_products.php from products
                        echo "<div class='flex'>";
                        include '../products/offer_products.php';
                        echo "</div>";
                    }
            echo "</div>";
        } elseif($action === 'order_list') {
            // Order List Section
            echo "<h2 class='text-2xl font-semibold mb-4'>Order List</h2>";
            echo "<p>You can view and manage orders here.</p>";
        } else {
            // Default view or fallback
            echo "<h2 class='text-2xl font-semibold mb-4'>Welcome to the Dashboard</h2>";
            echo "<p>Select an option from the sidebar to manage products.</p>";
        }
        ?>
    </div>
</div>
<?php
// Include the footer
include('../includes/footer.php');
?>

<script>
    // Function to toggle the mobile menu visibility (Sidebar)
    function toggleSidebar() {
        const sidebar = document.getElementById('mobile-sidebar');
        sidebar.classList.toggle('translate-x-0'); // Toggle sidebar visibility
        sidebar.classList.toggle('-translate-x-full'); // Hide sidebar when toggled
    }

</script>
