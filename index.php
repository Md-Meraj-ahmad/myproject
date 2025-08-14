<?php include('includes/header.php'); ?>

<!-- Top Banner Slider Section -->
<section class="relative bg-gradient-to-l from-blue-300 via-green-300 to-pink-300 text-white py-12 rounded-t-xl">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-4">
        <!-- Heading and paragraph section -->
        <h2 class="text-3xl font-semibold text-center mb-6 text-black">Special Offers Just for You!</h2>
        <p class="text-center mb-6 text-black">Browse our exclusive offers and grab the best deals before they are gone.</p>

        <!-- Banner Slider -->
        <div class="relative overflow-hidden">
            <div class="flex space-x-6 justify-center" id="banner-slider">
                <?php
                // Define an array for the banner items
                $banners = [
                    [
                        'title' => 'Exclusive Offer 1',
                        'description' => 'Get 20% off on all products for a limited time.',
                        'image' => 'https://via.placeholder.com/400x250',
                        'link' => '/order'
                    ],
                    [
                        'title' => 'Exclusive Offer 2',
                        'description' => 'Buy 1, Get 1 Free on select items.',
                        'image' => 'https://via.placeholder.com/400x250',
                        'link' => '/order'
                    ],
                    [
                        'title' => 'Exclusive Offer 3',
                        'description' => 'Free shipping on all orders this weekend.',
                        'image' => 'https://via.placeholder.com/400x250',
                        'link' => '/order'
                    ]
                ];

                // Loop through the banners array and display each banner item
                foreach ($banners as $banner) {
                    echo '
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-80">
                        <img src="' . $banner['image'] . '" alt="' . $banner['title'] . '" class="w-full h-48 object-cover" loading="lazy">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800">' . $banner['title'] . '</h3>
                            <p class="text-gray-600 mt-2">' . $banner['description'] . '</p>
                            <a href="' . $banner['link'] . '" class="mt-4 inline-block text-blue-600 font-semibold hover:text-blue-800">Order Now</a>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <!-- Slider Navigation Arrows -->
            <div class="absolute top-1/2 left-0 transform -translate-y-1/2 p-2">
                <button class="bg-gray-800 text-white rounded-full p-2 focus:outline-none hover:bg-gray-600" onclick="scrollSlider('banner', 'left')">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="absolute top-1/2 right-0 transform -translate-y-1/2 p-2">
                <button class="bg-gray-800 text-white rounded-full p-2 focus:outline-none hover:bg-gray-600" onclick="scrollSlider('banner', 'right')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Main content section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Heading and paragraph section -->
        <h2 class="text-3xl font-semibold text-center text-gray-900 mb-12">Featured Products</h2>
        <p class="text-center text-gray-600 mb-6">Check out our amazing products. Scroll through our collection and order now!</p>

        <!-- Product Slider -->
        <div class="relative">
            <div class="overflow-hidden">
                <!-- Slider Container -->
                <div class="flex space-x-6 justify-center" id="product-slider">
                    <?php
                    // config/db.php
                    include('config/db.php');

                    // Pagination setup
                    $limit = 10; // Adjust as needed
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    $query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $query);
                    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    // Loop through each product and display it
                    foreach ($products as $product) {
                        echo '
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden w-80">
                            <img src="assets/images/product_images/' . $product['image'] . '" alt="' . $product['name'] . '" class="w-full h-48 object-cover" loading="lazy">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800">' . $product['name'] . '</h3>
                                <p class="text-gray-600 mt-2">' . $product['price'] . '</p>
                                <a href="/order" class="mt-4 inline-block text-blue-600 font-semibold hover:text-blue-800">Order Now</a>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Slider Navigation Arrows -->
            <div class="absolute top-1/2 left-0 transform -translate-y-1/2 p-2">
                <button class="bg-gray-800 text-white rounded-full p-2 focus:outline-none hover:bg-gray-600" onclick="scrollSlider('product', 'left')">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="absolute top-1/2 right-0 transform -translate-y-1/2 p-2">
                <button class="bg-gray-800 text-white rounded-full p-2 focus:outline-none hover:bg-gray-600" onclick="scrollSlider('product', 'right')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="text-center mt-6">
            <?php
            $totalQuery = "SELECT COUNT(*) FROM products";
            $totalResult = mysqli_query($conn, $totalQuery);
            $totalCount = mysqli_fetch_row($totalResult)[0];
            $totalPages = ceil($totalCount / $limit);

            echo '<div class="pagination">';
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '" class="mx-2 text-blue-600 hover:text-blue-800">' . $i . '</a>';
            }
            echo '</div>';
            ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- Add JavaScript for slider -->
<script>
    let startTouch = 0;
    const bannerSlider = document.getElementById('banner-slider');
    const productSlider = document.getElementById('product-slider');
    let isScrolling = false; // Prevent multiple scroll actions at once

    // Function to scroll the slider
    function scrollSlider(type, direction) {
        if (isScrolling) return; // Avoid multiple scroll triggers at the same time
        isScrolling = true; // Lock scrolling
        
        const scrollAmount = direction === 'left' ? -300 : 300; // Adjust scroll amount
        const slider = type === 'banner' ? bannerSlider : productSlider;
        
        slider.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });

        setTimeout(() => {
            isScrolling = false; // Unlock scrolling after 500ms
        }, 500); // Allow 500ms before another scroll
    }

    // Touch events for swiping
    function addTouchEvents(slider) {
        slider.addEventListener('touchstart', (e) => {
            startTouch = e.touches[0].clientX;
        });

        slider.addEventListener('touchmove', (e) => {
            if (!startTouch) return;
            const touchMove = e.touches[0].clientX;
            const diff = startTouch - touchMove;
            if (Math.abs(diff) > 30) { // Minimum distance to trigger scroll
                if (diff > 0) {
                    scrollSlider(slider.id === 'banner-slider' ? 'banner' : 'product', 'right');
                } else {
                    scrollSlider(slider.id === 'banner-slider' ? 'banner' : 'product', 'left');
                }
                startTouch = 0; // Reset touch position after scroll
            }
        });

        slider.addEventListener('touchend', () => {
            startTouch = 0;
        });
    }

    // Add touch events to both sliders
    addTouchEvents(bannerSlider);
    addTouchEvents(productSlider);
</script>

<!-- Add Tailwind CSS classes for animation -->
<style>
    /* Remove the infinite animation, instead rely on JS scroll */
    @keyframes slide-right {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    .animate-slide-right {
        display: flex;
    }
</style>
