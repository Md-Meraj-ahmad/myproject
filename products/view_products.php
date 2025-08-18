<?php
include('../config/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID.');
}

$product_id = (int)$_GET['id'];

// Fetch product info
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die('Product not found.');
}

$product = mysqli_fetch_assoc($result);

// Fetch product images
$sql_images = "SELECT image_url, is_main FROM product_images WHERE product_id = ?";
$stmt_images = mysqli_prepare($conn, $sql_images);
mysqli_stmt_bind_param($stmt_images, 'i', $product_id);
mysqli_stmt_execute($stmt_images);
$result_images = mysqli_stmt_get_result($stmt_images);

$images = [];
while ($row = mysqli_fetch_assoc($result_images)) {
    $images[] = $row;
}

// If no images in product_images, fallback to `products.image` column (comma-separated)
if (empty($images) && !empty($product['image'])) {
    $img_list = explode(',', $product['image']);
    foreach ($img_list as $img) {
        $images[] = ['image_url' => trim($img), 'is_main' => 0];
    }
}

// Set main image: either from product_images or first fallback image
$main_image = null;
foreach ($images as $img) {
    if ($img['is_main']) {
        $main_image = $img['image_url'];
        break;
    }
}
if (!$main_image && !empty($images)) {
    $main_image = $images[0]['image_url'];
}

// Calculate discounted price
$discounted_price = $product['price'] - ($product['price'] * $product['discount'] / 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo htmlspecialchars($product['name']); ?> | Product Details</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- for icons -->
<style>
  /* For smooth thumbnail click effect */
  .thumbnail img {
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
  }
  .thumbnail img:hover {
    transform: scale(1.05);
  }
</style>
</head>
<body class="bg-gray-100">
<?php if (isset($_GET['added'])): ?>
  <div class="bg-green-500 text-white p-3 rounded mb-6">
    Product added to cart successfully!
  </div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="bg-red-500 text-white p-3 rounded mb-6">
    Failed to add product to cart. Please try again.
  </div>
<?php endif; ?>


<div class="container mx-auto px-4 py-10 max-w-6xl bg-white rounded shadow-md">

  <div class="flex flex-col md:flex-row md:space-x-10">

    <!-- Left: Main Image + Thumbnails -->
    <div class="md:w-1/2">
      <img
        id="main-image"
        src="../assets/images/product_images/<?php echo htmlspecialchars($main_image); ?>"
        alt="<?php echo htmlspecialchars($product['name']); ?>"
        class="w-full h-auto rounded-lg mb-4 object-contain max-h-[500px]"
      />

      <div class="flex space-x-3 overflow-x-auto">
        <?php foreach ($images as $img): ?>
          <div class="thumbnail flex-shrink-0 w-20 h-20 border rounded cursor-pointer <?php echo ($img['image_url'] === $main_image) ? 'border-indigo-600' : 'border-gray-300'; ?>" onclick="setMainImage('<?php echo htmlspecialchars($img['image_url']); ?>')">
            <img src="../assets/images/product_images/<?php echo htmlspecialchars($img['image_url']); ?>" alt="Thumbnail" class="w-full h-full object-cover rounded" />
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Right: Product Info -->
    <div class="md:w-1/2 mt-8 md:mt-0">
      <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>

      <p class="text-gray-700 mb-6"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

      <div class="mb-4">
        <span class="text-2xl font-semibold text-indigo-700">$<?php echo number_format($discounted_price, 2); ?></span>
        <?php if ($product['discount'] > 0): ?>
          <span class="line-through text-gray-500 ml-3">$<?php echo number_format($product['price'], 2); ?></span>
          <span class="ml-3 text-red-500 font-semibold"><?php echo htmlspecialchars($product['discount']); ?>% OFF</span>
        <?php endif; ?>
      </div>

      <div class="grid grid-cols-2 gap-4 text-gray-700 mb-8">
        <div><strong>Height:</strong> <?php echo htmlspecialchars($product['height']); ?> cm</div>
        <div><strong>Width:</strong> <?php echo htmlspecialchars($product['width']); ?> cm</div>
        <div><strong>Material:</strong> <?php echo htmlspecialchars($product['material']); ?></div>
        <div><strong>Weight:</strong> <?php echo htmlspecialchars($product['weight']); ?> kg</div>
        <div><strong>Size:</strong> <?php echo htmlspecialchars($product['size']); ?></div>
      </div>

      <form method="POST" action="add_to_cart.php" class="mt-6">
  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
  <label for="quantity" class="block mb-1 font-semibold text-gray-700">Quantity</label>
  <input type="number" name="quantity" id="quantity" value="1" min="1" max="100" class="w-24 p-2 border border-gray-300 rounded mb-4" />
  <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-300">
    <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
  </button>
</form>

    </div>

  </div>

</div>

<script>
  function setMainImage(imageUrl) {
    document.getElementById('main-image').src = '../assets/images/product_images/' + imageUrl;
    // Update border styles for thumbnails
    document.querySelectorAll('.thumbnail').forEach(div => {
      div.classList.remove('border-indigo-600');
      div.classList.add('border-gray-300');
      const img = div.querySelector('img');
      if (img && img.src.endsWith(imageUrl)) {
        div.classList.add('border-indigo-600');
        div.classList.remove('border-gray-300');
      }
    });
  }
</script>

</body>
</html>
