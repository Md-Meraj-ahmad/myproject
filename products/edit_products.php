<div class="md:hidden">
    <!-- Include the header (no PHP code) -->
    <?php include('../includes/header.php'); ?>
</div>

<!-- Edit Products -->
<div class="bg-gradient-to-l from-pink-400 to-red-300 p-8">
    <div class="container mx-auto px-4 py-8 bg-white rounded-lg">
        <h1 class="text-2xl font-bold mb-6 bg-blue-300 p-2 rounded border border-blue-900 text-center">Update Product</h1>
                
        <!-- Product Update Form -->
        <form action="../config/function.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Category Selection -->
            <div>
                <label for="category" class="block text-sm font-medium">Category</label>
                <select name="category" id="category" class="mt-1 p-2 w-full border border-gray-300 rounded" required>
                    <option value="" disabled>Select Category</option>
                    <!-- Static category options (replace these with actual categories later) -->
                    <option value="1">Category 1</option>
                    <option value="2">Category 2</option>
                    <option value="3">Category 3</option>
                </select>
            </div>
                
            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-medium">Product Name</label>
                <input type="text" name="name" id="name" class="mt-1 p-2 w-full border border-gray-300 rounded" value="Sample Product Name" required>
            </div>
            
            <!-- Product Description -->
            <div>
                <label for="description" class="block text-sm font-medium">Product Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded" required>Sample product description</textarea>
            </div>
            
            <!-- Product Price -->
            <div>
                <label for="price" class="block text-sm font-medium">Product Price</label>
                <input type="number" name="price" id="price" class="mt-1 p-2 w-full border border-gray-300 rounded" value="99.99" step="0.01" required>
            </div>
            
            <!-- Product Images (Multiple) -->
            <div>
                <label for="image" class="block text-sm font-medium">Product Images</label>
                <input type="file" name="image[]" id="image" class="mt-1 p-2 w-full border border-gray-300 rounded" multiple>
                
                <!-- Display current images (Static for now) -->
                <div class="mt-2 flex flex-grow">
                    <img src="../assets/images/product_images/sample1.jpg" alt="Product Image" class="w-24 h-24 object-cover mr-2">
                    <img src="../assets/images/product_images/sample2.jpg" alt="Product Image" class="w-24 h-24 object-cover mr-2">
                </div>
            </div>
            
            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-green-600 transition duration-300">Update Product</button>
            </div>
        </form>
    </div>
</div>
