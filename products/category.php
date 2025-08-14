<?php include('../includes/header.php'); ?>
<div class="py-12 bg-gray-500 mt-10" id="#cloth">
    <h1 class="text-7x1 font-bold text-xl mt-8 ml-2 text-bold mb-4 text-white">
        Clothes
    </h1>
    <div class="max-w-7x1 mx-auto px-6 lg:px-8 flex ">
        <?php 
            include('../config/db.php');
            $query = "SELECT * FROM products WHERE category=2";
            $result = mysqli_query($conn, $query);
            $clothes = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // loop through each clothes and display it
            foreach ($clothes as $cloth){
                echo '
                <div class="bg-gray-300 shadow-lg rounded-lg overflow-hidden w-80">
                    <img src="/../assets/images/prouduct_images/' . $cloth['image'] . '" alt="' . $cloth['name'] . '" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-500">'. $cloth['name'] .'</h3>
                        <p class="text-gray-600 mt-2">'. $cloth['price'] .'</p>
                        <a href="/order" class="mt-4 inside-block text-blue-600 font-semibold hovere:text0blue-600 w-full align-center">Order Now</a>
                    </div>
                </div>';
            }
        ?>
    </div>
</div>
<div class="py-8 bg-gray-500" id="#electronics">
    <h1 class="text-2x1 font-bold ml-2 mb-2 mt-2 text-white text-xl">Electronics</h1>
    <div class="max-w-7x1 mx-auto px-6 lg:px-8 flex space-x-4">
        <?php
            $query = "SELECT * FROM products WHERE category=1";
            $result = mysqli_query($conn, $query);
            $electronics = mysqli_fetch_all($result, MYSQLI_ASSOC);
            // Loop through ecah electronic and display it
            foreach ($electronics as $electronic) {
                echo '
                <div class="bg-gray-300 shadow-lg rounded-lg overflow-hidden w-80">
                    <img src="/../assets/images/prouduct_images/' . $electronic['image'] . '" alt="' . $electronic['name'] . '" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-500">'. $electronic['name'] .'</h3>
                        <p class="text-gray-600 mt-2">'. $electronic['price'] .'</p>
                        <a href="/order" class="mt-4 inside-block text-blue-600 font-semibold hovere:text0blue-600 w-full align-center">Order Now</a>
                    </div>
                </div>';
            }
        ?>
    </div>
</div>