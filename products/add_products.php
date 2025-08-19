<?php
include('../config/db.php');

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $category   = (int) $_POST['category'];
    $name       = htmlspecialchars(trim($_POST['name']));
    $description= htmlspecialchars(trim($_POST['description']));
    $price      = (float) $_POST['price'];
    $discount   = (float) $_POST['discount'];
    $height     = (float) $_POST['height'];
    $width      = (float) $_POST['width'];
    $materials  = htmlspecialchars(trim($_POST['materials']));
    $weight     = (float) $_POST['weight'];
    $size       = htmlspecialchars(trim($_POST['size']));

    if (empty($category) || empty($name) || empty($description) || empty($price) || empty($discount) || empty($height) || empty($width) || empty($materials) || empty($weight) || empty($size)) {
        $error_message = 'Please fill out all fields.';
    } elseif (!isset($_FILES['images']) || $_FILES['images']['error'][0] === UPLOAD_ERR_NO_FILE) {
        $error_message = 'Please upload at least one image.';
    } else {
        $image_names = [];
        $target_dir = __DIR__ . '/../assets/images/product_images/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $uploadOk = 1;

        foreach ($_FILES['images']['name'] as $key => $image) {
            $tmp_name = $_FILES['images']['tmp_name'][$key];
            $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $unique_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;

            // Validate image
            if (getimagesize($tmp_name) === false) {
                $error_message = "One of the files is not a valid image.";
                $uploadOk = 0;
                break;
            }

            if ($_FILES['images']['size'][$key] > 5000000) {
                $error_message = "One of the files is too large. Max 5MB allowed.";
                $uploadOk = 0;
                break;
            }

            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                $error_message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                $uploadOk = 0;
                break;
            }

            if (!move_uploaded_file($tmp_name, $target_file)) {
                $error_message = "Error uploading one of the files.";
                $uploadOk = 0;
                break;
            }

            $image_names[] = $unique_name;
        }

        if ($uploadOk && empty($error_message)) {
            // Insert product with main image
            $main_image = $image_names[0]; // First image is main
            $query = "INSERT INTO products (category, name, description, price, discount, image, height, width, material, weight, size)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'issddssddss',
                $category, $name, $description, $price, $discount,
                $main_image, $height, $width, $materials, $weight, $size
            );

            if (mysqli_stmt_execute($stmt)) {
                $product_id = mysqli_insert_id($conn);

                // Insert all images into product_images
                foreach ($image_names as $index => $img_name) {
                    $is_main = ($index === 0) ? 1 : 0;
                    $img_query = "INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, ?)";
                    $img_stmt = mysqli_prepare($conn, $img_query);
                    mysqli_stmt_bind_param($img_stmt, 'isi', $product_id, $img_name, $is_main);
                    mysqli_stmt_execute($img_stmt);
                    mysqli_stmt_close($img_stmt);
                }

                $success_message = 'Product and images added successfully!';
            } else {
                $error_message = 'Database error: ' . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch categories for dropdown
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);
?>



<!-- Product Addition Form -->
<div class="container mx-auto py-8 max-w-4xl">
    <h1 class="text-3xl font-semibold text-center bg-gradient-to-r from-purple-400 via-green-400 to-blue-400 rounded-lg py-6 mb-6">
        Add New Product
    </h1>

    <!-- Display messages -->
    <?php if ($error_message) { ?>
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <?php echo $error_message; ?>
        </div>
    <?php } ?>

    <?php if ($success_message) { ?>
        <div class="bg-green-500 text-white p-4 rounded mb-6">
            <?php echo $success_message; ?>
        </div>
    <?php } ?>

    <!-- Product Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-4xl mx-auto">
        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                <option value="" disabled selected>Select Category</option>
                <?php while ($row = mysqli_fetch_assoc($category_result)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" name="name" id="name" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Product Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required></textarea>
        </div>

        <!-- Price -->
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" name="price" id="price" step="0.01" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Discount -->
        <div>
            <label for="discount" class="block text-sm font-medium text-gray-700">Discount (%)</label>
            <input type="number" name="discount" id="discount" step="0.01" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Images -->
        <div>
            <label for="images" class="block text-sm font-medium text-gray-700">Product Images (First will be main)</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Height -->
        <div>
            <label for="height" class="block text-sm font-medium text-gray-700">Height</label>
            <input type="number" name="height" id="height" step="0.01" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Width -->
        <div>
            <label for="width" class="block text-sm font-medium text-gray-700">Width</label>
            <input type="number" name="width" id="width" step="0.01" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Material -->
        <div>
            <label for="materials" class="block text-sm font-medium text-gray-700">Material</label>
            <input type="text" name="materials" id="materials" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Weight -->
        <div>
            <label for="weight" class="block text-sm font-medium text-gray-700">Weight</label>
            <input type="number" name="weight" id="weight" step="0.01" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Size -->
        <div>
            <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
            <input type="text" name="size" id="size" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Add Product
            </button>
        </div>
    </form>
</div>

