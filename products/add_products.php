<?php
// Include database connection
include('../config/db.php');

// Initialize variables for error/success messages
$success_message = '';
$error_message = '';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $category = $_POST['category'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $height = $_POST['height'];
    $width = $_POST['width'];
    $materials = $_POST['materials'];
    $weight = $_POST['weight'];
    $size = $_POST['size'];

    // Check if all fields are filled out
    if (empty($category) || empty($name) || empty($description) || empty($price) || empty($discount) || empty($height) || empty($width) || empty($materials) || empty($weight) || empty($size)) {
        $error_message = 'Please fill out all fields.';
    } else {
        // Check if at least one image is uploaded
        if (empty($_FILES['images']['name'][0])) {
            $error_message = 'Please upload at least one image.';
        } else {
            $image_names = [];
            $target_dir = __DIR__ . '/../assets/images/product_images/'; // Absolute path

            // Check if the directory exists, if not, create it
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);  // Create the directory if it doesn't exist
            }

            $uploadOk = 1;

            // Loop through the images array
            foreach ($_FILES['images']['name'] as $key => $image) {
                $target_file = $target_dir . basename($_FILES['images']['name'][$key]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if image file is a valid image
                if (getimagesize($_FILES['images']['tmp_name'][$key]) === false) {
                    $error_message = "File is not an image.";
                    $uploadOk = 0;
                    break;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    $error_message = "Sorry, file already exists.";
                    $uploadOk = 0;
                    break;
                }

                // Check file size (limit to 5MB)
                if ($_FILES['images']['size'][$key] > 5000000) {
                    $error_message = "Sorry, your file is too large.";
                    $uploadOk = 0;
                    break;
                }

                // Allow certain file formats (JPG, JPEG, PNG, GIF)
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                    break;
                }

                // Check if upload is ok
                if ($uploadOk === 0) {
                    $error_message = "Sorry, one or more files were not uploaded.";
                    break;
                } else {
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                        // Save the file name in the array
                        $image_names[] = basename($_FILES['images']['name'][$key]);
                    } else {
                        $error_message = "Sorry, there was an error uploading your file.";
                        break;
                    }
                }
            }

            // If images are uploaded successfully, insert product and images into the database
            if (empty($error_message)) {
                // Insert product WITHOUT the 'image' field since images are stored in product_images table
                $query = "INSERT INTO products (category, name, description, price, discount, height, width, material, weight, size) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'issdddddss', $category, $name, $description, $price, $discount, $height, $width, $materials, $weight, $size);

                if (mysqli_stmt_execute($stmt)) {
                    // Get inserted product id
                    $product_id = mysqli_insert_id($conn);

                    // Insert each image into product_images table with product_id
                    foreach ($image_names as $index => $img_name) {
                        $is_main = ($index === 0) ? 1 : 0; // First image is main image

                        $img_query = "INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, ?)";
                        $img_stmt = mysqli_prepare($conn, $img_query);
                        mysqli_stmt_bind_param($img_stmt, 'isi', $product_id, $img_name, $is_main);
                        mysqli_stmt_execute($img_stmt);
                        mysqli_stmt_close($img_stmt);
                    }

                    $success_message = 'Product and images added successfully!';
                } else {
                    $error_message = 'Error inserting product: ' . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            }

        }
    }
}

// Fetch categories from the database for the dropdown
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);
?>

<div class="container mx-auto py-8 max-w-4xl">
    <h1 class="text-3xl font-semibold text-center bg-gradient-to-r from-purple-400 via-green-400 to-blue-400 rounded-lg py-6 mb-6">
        Add New Product
    </h1>

    <!-- Display error or success message -->
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


    <!-- Product Add Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-4xl mx-auto">
        <!-- Category Selection -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                <option value="" selected disabled>Select Category</option>
                <?php while ($row = mysqli_fetch_assoc($category_result)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Product Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" name="name" id="name" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
        </div>

        <!-- Product Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Product Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
        </div>

        <!-- Product Price -->
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Product Price</label>
            <input type="number" name="price" id="price" class="mt-1 p-2 w-full border border-gray-300 rounded-md" step="0.01">
        </div>

        <!-- Product Discount -->
        <div>
            <label for="discount" class="block text-sm font-medium text-gray-700">Product Discount</label>
            <input type="number" name="discount" id="discount" class="mt-1 p-2 w-full border border-gray-300 rounded-md" step="0.01">
        </div>
                    
        <!-- Product Images (Multiple) -->
        <div>
            <label for="images" class="block text-sm font-medium text-gray-700">Product Images</label>
            <input type="file" name="images[]" id="images" class="mt-1 p-2 w-full border border-gray-300 rounded-md" multiple>
        </div>

        <!-- Product Height -->
        <div>
            <label for="height" class="block text-sm font-medium text-gray-700">Product Height</label>
            <input type="float" name="height" id="height" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
        </div>
        
        <!-- Product Width -->
        <div>
            <label for="width" class="block text-sm font-medium text-gray-700">Product Width</label>
            <input type="float" name="width" id="width" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
        </div>
        
        <!-- Product Materials -->
        <div>
            <label for="Materials" class="block text-sm font-medium text-gray-700">Product Materials</label>
            <input type="text" name="materials" id="materials" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
        </div>
        
        <!-- Product Weight -->
        <div>
            <label for="weight" class="block text-sm font-medium text-gray-700">Product Weight</label>
            <input type="float" name="weight" id="weight" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
        </div>
        
        <!-- Product Size -->
        <div>
            <label for="size" class="block text-sm font-medium text-gray-700">Product Size</label>
            <input type="float" name="size" id="size" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition duration-300">
                Add Product
            </button>
        </div>
    </form>
</div>
