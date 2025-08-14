<!-- main.php -->
<?php
$products = get_all_products(); // Placeholder function for fetching all products

// Sidebar option selection (default to '')
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'view_product') {
    // View Products Section
    echo "<h2 class='text-2xl font-semibold mb-4 text-center bg-blue-300 rounded-lg py-4'>Product List</h2>";
    echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'>";
    foreach ($products as $product) {
        echo "<div class='bg-white p-4 shadow-lg rounded-lg'>
                <img src='../assets/images/prouduct_images/" . $product['images'] . "' alt='" . $product['name'] . "' class='w-full h-32 object-cover mb-4 rounded'>
                <h3 class='font-semibold'>" . $product['name'] . "</h3>
                <p class='text-gray-600'>" . $product['description'] . "</p>
                <p class='text-blue-600'>$" . number_format($product['price'], 2) . "</p>
            </div>";
    }
    echo "</div>";
} elseif ($action === 'edit_product') {
    // Edit Product Section
    echo "<h2 class='text-2xl font-semibold mb-4'>Edit Product</h2>";
    echo "<p>Select a product to edit its details.</p>";
    echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6'>";
    foreach ($products as $product) {
        echo "<div class='bg-white p-4 shadow-lg rounded-lg'>
                <img src='../assets/images/prouduct_images/" . $product['images'] . "' alt='" . $product['name'] . "' class='w-full h-32 object-cover mb-4 rounded'>
                <h3 class='font-semibold'>" . $product['name'] . "</h3>
                <a href='../products/edit_products.php?id=" . $product['id'] . "' class='text-blue-600 hover:text-blue-800 mt-2 inline-block'>Edit</a>
            </div>";
    }
    echo "</div>";
} elseif ($action === 'manage_product') {
    // Manage Products Section (CRUD operations)
    echo "<h2 class='text-2xl font-semibold mb-4'>Manage Products</h2>";
    echo "<p>You can add, update, or delete products here.</p>";
    echo "<div class='mt-6 h-screen'>
            <a href='../products/add_products.php' class='bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700'>Add New Product</a>
            <a href='../products/delete_view.php' class='bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700'>Delete Product</a>
        </div>";
} elseif ($action === 'offer_product') {
    // Offers Product Section
    echo "<h2 class='text-2xl font-semibold mb-4'>Offer Products</h2>";
    echo "<p>You can View and Add Offers Products.</p>";
    echo "<div class='mt-6'>
            <a href='../products/offer_products.php' class='bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700'>Add New Offers</a>
        </div>";
} else {
    // Default view or fallback
    echo "<h2 class='text-2xl font-semibold mb-4'>Welcome to the Dashboard</h2>";
    echo "<p>Select an option from the sidebar to manage products.</p>";
}
?>
