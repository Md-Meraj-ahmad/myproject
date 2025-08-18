<?php
include('includes/header.php'); // If using your header file
include('config/db.php'); // DB connection

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

?>

<section class="py-16 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            if ($search_query !== '') {
                $search_query = mysqli_real_escape_string($conn, $search_query);
                $query = "SELECT * FROM products WHERE name LIKE '%$search_query%' OR category LIKE '%$search_query%'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($product = mysqli_fetch_assoc($result)) {
                        echo '
                        <div class="bg-white rounded-lg shadow p-4">
                            <img src="assets/images/product_images/' . $product['image'] . '" alt="' . $product['name'] . '" class="w-full h-48 object-cover rounded">
                            <h3 class="text-xl font-semibold mt-4">' . $product['name'] . '</h3>
                            <p class="text-gray-600">₹' . number_format($product['price'], 2) . '</p>
                            <a href="/order?product_id=' . $product['id'] . '" class="inline-block mt-3 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Buy Now</a>
                        </div>';
                    }
                } else {
                    echo '<p class="text-gray-500 col-span-full">No products found matching your search.</p>';
                }
            } else {
                echo '<p class="text-red-500 col-span-full">Please enter a search term.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>
