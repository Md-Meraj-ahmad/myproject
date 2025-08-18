<?php
include('../config/db.php');

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9;
$page = max(1, $page);
$offset = ($page - 1) * $limit;

// Prepare search SQL condition
$search_sql = '';
$params = [];
$param_types = '';

if ($search !== '') {
    $search_sql = " WHERE p.name LIKE ? ";
    $params[] = '%' . $search . '%';
    $param_types .= 's';
}

// Count total products for pagination
$count_sql = "SELECT COUNT(*) FROM products p $search_sql";
$stmt = mysqli_prepare($conn, $count_sql);
if ($search !== '') {
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
}
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $total_products);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$total_pages = ceil($total_products / $limit);

// Prepare product fetch query with direct limit injection (safe after casting)
$offset = (int)$offset;
$limit = (int)$limit;

$sql = "SELECT p.id, p.name, p.price, pi.image_url 
        FROM products p
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
        $search_sql
        ORDER BY p.id DESC
        LIMIT $offset, $limit";

$stmt = mysqli_prepare($conn, $sql);
if ($search !== '') {
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Products List</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8">

    <h1 class="text-4xl font-bold mb-8 text-center text-indigo-700">Products</h1>

    <!-- Search Form -->
    <form method="GET" action="" class="mb-6 max-w-md mx-auto flex">
        <input
            type="text"
            name="q"
            value="<?php echo htmlspecialchars($search); ?>"
            placeholder="Search products..."
            class="flex-grow border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        />
        <button type="submit" class="bg-indigo-600 text-white px-6 rounded-r hover:bg-indigo-700 transition">Search</button>
    </form>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <?php if (mysqli_num_rows($result) === 0): ?>
            <p class="col-span-full text-center text-gray-500">No products found.</p>
        <?php else: ?>
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <a href="view_product.php?id=<?php echo $product['id']; ?>" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition flex flex-col items-center">
                    <?php if ($product['image_url']): ?>
                        <img
                            src="../assets/images/product_images/<?php echo htmlspecialchars($product['image_url']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                            class="w-48 h-48 object-contain mb-4"
                        />
                    <?php else: ?>
                        <div class="w-48 h-48 bg-gray-200 flex items-center justify-center text-gray-400 mb-4">No Image</div>
                    <?php endif; ?>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2 text-center"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p class="text-indigo-600 font-bold">$<?php echo number_format($product['price'], 2); ?></p>
                </a>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="mt-8 flex justify-center space-x-2">
            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                <a
                    href="?<?php
                        $query_params = $_GET;
                        $query_params['page'] = $p;
                        echo http_build_query($query_params);
                    ?>"
                    class="px-3 py-1 rounded <?php echo $p == $page ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-indigo-100'; ?>"
                >
                    <?php echo $p; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
