<?php include('includes/header.php'); ?>

<!-- Hero Banner Section -->
<section class="relative bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white py-16 rounded-b-xl shadow-lg">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">🔥 Limited Time Offers Just for You!</h1>
        <p class="text-lg md:text-xl mb-8">Discover exclusive discounts, curated just for our awesome customers.</p>
        <a href="#featured" class="bg-white text-indigo-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition">Explore Products</a>
    </div>
</section>

<!-- Promotional Offers Slider -->
<section class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">💎 Our Hottest Deals</h2>

        <div class="relative">
            <div class="flex space-x-6 overflow-x-auto scroll-smooth pb-4" id="banner-slider">
                <?php
                $banners = [
                    [
                        'title' => '20% Off Everything',
                        'description' => 'Shop now and enjoy 20% off sitewide. Limited time only!',
                        'image' => 'https://via.placeholder.com/400x250?text=20%25+OFF',
                        'link' => '/order'
                    ],
                    [
                        'title' => 'BOGO Free!',
                        'description' => 'Buy one, get one free on selected collections.',
                        'image' => 'https://via.placeholder.com/400x250?text=BOGO',
                        'link' => '/order'
                    ],
                    [
                        'title' => 'Free Shipping Weekend',
                        'description' => 'Enjoy free shipping on all orders over $50.',
                        'image' => 'https://via.placeholder.com/400x250?text=Free+Shipping',
                        'link' => '/order'
                    ]
                ];

                foreach ($banners as $banner) {
                    echo '
                    <div class="min-w-[20rem] bg-white rounded-lg shadow hover:shadow-xl transition duration-300 overflow-hidden">
                        <img src="' . $banner['image'] . '" alt="' . $banner['title'] . '" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">' . $banner['title'] . '</h3>
                            <p class="text-gray-600 mb-4">' . $banner['description'] . '</p>
                            <a href="' . $banner['link'] . '" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Shop Now</a>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <!-- Arrows -->
            <button onclick="scrollSlider('banner', 'left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-indigo-600 text-white p-2 rounded-full shadow hover:bg-indigo-700">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollSlider('banner', 'right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-indigo-600 text-white p-2 rounded-full shadow hover:bg-indigo-700">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Enhanced Product Section with Categories -->
<section id="featured" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-6">🛒 Shop by Category</h2>
        <p class="text-center text-gray-600 mb-8">Find exactly what you need across all our categories</p>

        <!-- Category Tabs -->
        <div class="flex justify-center flex-wrap gap-4 mb-10">
            <?php
            // Sample categories with icons
            $categories = [
                ['name' => 'All', 'icon' => '🛍️'],
                ['name' => 'Electronics', 'icon' => '🎧'],
                ['name' => 'Fashion', 'icon' => '👗'],
                ['name' => 'Home', 'icon' => '🏠'],
                ['name' => 'Beauty', 'icon' => '💄'],
                ['name' => 'Toys', 'icon' => '🧸']
            ];

            foreach ($categories as $category) {
                $catName = $category['name'];
                $catIcon = $category['icon'];
                echo '<button data-category="' . strtolower($catName) . '" class="category-tab bg-gray-200 text-gray-700 hover:bg-indigo-100 px-4 py-2 rounded-full font-medium transition">
                        ' . $catIcon . ' ' . $catName . '</button>';
            }
            ?>
        </div>

        <!-- Product Cards Container -->
        <div class="relative">
            <div class="flex flex-wrap justify-center gap-6" id="product-container">
                <?php
                include('config/db.php');
                $query = "SELECT * FROM products";
                $result = mysqli_query($conn, $query);
                $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($products as $product) {
                    // Assume each product has a category field in DB
                    $category = strtolower($product['category']);
                    echo '
                    <div class="product-card bg-white border rounded-lg shadow hover:shadow-md transition duration-300 w-72" data-category="' . $category . '">
                        <img src="assets/images/product_images/' . $product['image'] . '" alt="' . $product['name'] . '" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">' . $product['name'] . '</h3>
                            <p class="text-gray-600 mb-2">₹' . number_format($product['price'], 2) . '</p>
                            <a href="/order?product_id=' . $product['id'] . '" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Buy Now</a>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<script>
    const categoryTabs = document.querySelectorAll('.category-tab');
    const productCards = document.querySelectorAll('.product-card');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const selected = tab.getAttribute('data-category');
            
            categoryTabs.forEach(t => t.classList.remove('bg-indigo-500', 'text-white'));
            tab.classList.add('bg-indigo-500', 'text-white');

            productCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selected === 'all' || selected === cardCategory) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
</script>


<!-- Featured Products Section -->
<section id="featured" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-10">🛒 Featured Products</h2>
        <p class="text-center text-gray-600 mb-12">Hand-picked items our customers love the most!</p>

        <div class="relative">
            <div class="flex space-x-6 overflow-x-auto scroll-smooth" id="product-slider">
                <?php
                include('config/db.php');
                $limit = 10;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                $query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
                $result = mysqli_query($conn, $query);
                $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($products as $product) {
                    echo '
                    <div class="min-w-[20rem] bg-white border rounded-lg shadow hover:shadow-lg transition duration-300">
                        <img src="assets/images/product_images/' . $product['image'] . '" alt="' . $product['name'] . '" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">' . $product['name'] . '</h3>
                            <p class="text-gray-600 mb-3">₹' . number_format($product['price'], 2) . '</p>
                            <a href="/order?product_id=' . $product['id'] . '" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Buy Now</a>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <!-- Arrows -->
            <button onclick="scrollSlider('product', 'left')" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-green-600 text-white p-2 rounded-full hover:bg-green-700">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="scrollSlider('product', 'right')" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-green-600 text-white p-2 rounded-full hover:bg-green-700">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Pagination -->
        <div class="text-center mt-8">
            <?php
            $totalQuery = "SELECT COUNT(*) FROM products";
            $totalResult = mysqli_query($conn, $totalQuery);
            $totalCount = mysqli_fetch_row($totalResult)[0];
            $totalPages = ceil($totalCount / $limit);

            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i == $page ? 'font-bold text-indigo-700' : 'text-gray-600';
                echo '<a href="?page=' . $i . '" class="mx-2 ' . $active . ' hover:text-indigo-900">' . $i . '</a>';
            }
            ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- JavaScript for Slider -->
<script>
    let startTouch = 0;
    const bannerSlider = document.getElementById('banner-slider');
    const productSlider = document.getElementById('product-slider');
    let isScrolling = false;

    function scrollSlider(type, direction) {
        if (isScrolling) return;
        isScrolling = true;

        const scrollAmount = direction === 'left' ? -300 : 300;
        const slider = type === 'banner' ? bannerSlider : productSlider;

        slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });

        setTimeout(() => { isScrolling = false; }, 400);
    }

    function addTouchEvents(slider) {
        slider.addEventListener('touchstart', (e) => startTouch = e.touches[0].clientX);
        slider.addEventListener('touchmove', (e) => {
            if (!startTouch) return;
            const diff = startTouch - e.touches[0].clientX;
            if (Math.abs(diff) > 30) {
                scrollSlider(slider.id === 'banner-slider' ? 'banner' : 'product', diff > 0 ? 'right' : 'left');
                startTouch = 0;
            }
        });
        slider.addEventListener('touchend', () => startTouch = 0);
    }

    addTouchEvents(bannerSlider);
    addTouchEvents(productSlider);
</script>
