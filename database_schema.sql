-- Create the database 'nepalbazar' if it does not already exist
CREATE DATABASE IF NOT EXISTS nepalbazar;

-- Use the 'nepalbazar' database for the following queries
USE nepalbazar;

-- Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,      -- Unique user ID (Primary Key, auto-incrementing)
    name VARCHAR(100) NOT NULL,                 -- User's full name (required field)
    email VARCHAR(100) UNIQUE NOT NULL,         -- User's email address (must be unique and required)
    address TEXT NOT NULL,                      -- User's address (required field)
    phone_no VARCHAR(15) NOT NULL,              -- User's phone number (required field)
    password VARCHAR(255) NOT NULL,             -- User's hashed password (required field)
    role ENUM('user', 'admin') DEFAULT 'user',  -- User's role ('user' or 'admin'), defaults to 'user'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of when the account was created (auto-filled with current timestamp)
);

-- Create the 'categories' table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Unique category ID (Primary Key, auto-incrementing)
    name VARCHAR(255) NOT NULL                  -- Category name (required field)
);

-- Insert initial categories into the 'categories' table
INSERT INTO categories (name) VALUES
('Electronics'),      -- Category for electronic items
('Clothing'),         -- Category for clothing items
('Home & Kitchen'),   -- Category for home and kitchen products
('Books');            -- Category for books

-- Create the 'products' table with additional columns
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,    -- Unique product ID (Primary Key, auto-incrementing)
    category INT NOT NULL,                 -- Foreign key referencing 'categories' table (required field)
    name VARCHAR(255) NOT NULL,            -- Product name (required field)
    description TEXT NOT NULL,            -- Product description (required field)
    price DECIMAL(10, 2) NOT NULL,         -- Product price (required field, allows up to 10 digits, with 2 decimal places)
    discount DECIMAL(5,2) NOT NULL,           -- Discount percentage (e.g., 20.50 for 20.5%)
    image VARCHAR(255) NOT NULL,           -- Product image file name (required field)
    height DECIMAL(10, 2) NULL,            -- Height of the product (in any unit, e.g., cm, inches)
    width DECIMAL(10, 2) NULL,             -- Width of the product (in any unit, e.g., cm, inches)
    material VARCHAR(255) NULL,            -- Material of the product (e.g., 'Wood', 'Plastic', etc.)
    weight DECIMAL(10, 2) NULL,            -- Weight of the product (in kilograms, pounds, etc.)
    size VARCHAR(255) NULL,                -- Size of the product (e.g., 'Small', 'Medium', 'Large')

    FOREIGN KEY (category) REFERENCES categories(id) -- Foreign key constraint: links the category of the product to the 'categories' table
);

-- Create the 'offers' table
CREATE TABLE offers (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique ID for each offer
    title VARCHAR(255) NOT NULL,              -- Offer title
    description TEXT,                         -- Offer description
    discount DECIMAL(5,2) NOT NULL,           -- Discount percentage (e.g., 20.50 for 20.5%)
    start_date DATE NOT NULL,                 -- Offer start date
    end_date DATE NOT NULL,                   -- Offer end date
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',  -- Status of the offer (active/inactive)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the offer was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Timestamp when the offer was last updated
);

-- Create the 'cart' table to manage items in the user's shopping cart
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique cart ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    quantity INT NOT NULL DEFAULT 1,           -- Quantity of the product in the cart (defaults to 1)
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when the product was added to the cart
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Timestamp when the cart was last updated
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id), -- Foreign key constraint
    UNIQUE (user_id, product_id)             -- Ensure a user can't add the same product more than once
);

-- Create the 'orders' table to manage user orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique order ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    total_amount DECIMAL(10, 2) NOT NULL,     -- Total price of the order
    status ENUM('pending', 'completed', 'cancelled', 'shipped', 'delivered') NOT NULL DEFAULT 'pending',  -- Order status
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the order was placed
    shipping_address TEXT NOT NULL,           -- Shipping address for the order
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'order_items' table to store details of each product in an order
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique order item ID (Primary Key, auto-incrementing)
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    quantity INT NOT NULL,                    -- Quantity of the product ordered
    price DECIMAL(10, 2) NOT NULL,            -- Price of the product at the time of the order
    discount DECIMAL(5, 2) NOT NULL,          -- Discount applied to the product
    FOREIGN KEY (order_id) REFERENCES orders(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'payments' table to handle payment information
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique payment ID (Primary Key, auto-incrementing)
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    payment_method ENUM('credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cod') NOT NULL,  -- Payment method used
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',  -- Payment status
    amount DECIMAL(10, 2) NOT NULL,           -- Amount paid
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of when payment was made
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'shipping' table to manage the shipping status of orders
CREATE TABLE shipping (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique shipping ID (Primary Key, auto-incrementing)
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    shipping_method VARCHAR(255) NOT NULL,     -- Shipping method (e.g., 'Standard', 'Express')
    shipping_status ENUM('pending', 'shipped', 'in_transit', 'delivered', 'returned') NOT NULL DEFAULT 'pending',  -- Shipping status
    shipped_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date when the order was shipped (auto-set to current timestamp)
    delivered_date TIMESTAMP NULL,            -- Date when the order was delivered (NULL by default)
    tracking_number VARCHAR(255),             -- Tracking number for the shipment
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);


-- Create the 'reviews' table for customers to review products
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique review ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),  -- Rating given by the user (1 to 5 stars)
    review TEXT,                              -- The review text written by the user
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the review was created
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'product_images' table to allow multiple images for each product
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique image ID (Primary Key, auto-incrementing)
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    image_url VARCHAR(255) NOT NULL,           -- URL of the product image
    is_main BOOLEAN NOT NULL DEFAULT FALSE,   -- Flag to indicate the main image for the product
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'inventory' table to track product inventory
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique inventory ID (Primary Key, auto-incrementing)
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    stock_quantity INT NOT NULL,              -- Number of items available in stock
    restock_date TIMESTAMP,                   -- Date when the stock was last updated or restocked
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'wishlists' table to allow users to save favorite products
CREATE TABLE wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique wishlist ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the product was added to the wishlist
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'coupons' table for discount codes
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique coupon ID (Primary Key, auto-incrementing)
    code VARCHAR(50) UNIQUE NOT NULL,         -- Unique coupon code
    description TEXT,                         -- Description of the coupon (e.g., '10% off on electronics')
    discount DECIMAL(5, 2) NOT NULL,          -- Discount percentage (e.g., 10.50 for 10.5%)
    start_date DATE NOT NULL,                 -- Coupon start date
    end_date DATE NOT NULL,                   -- Coupon expiration date
    minimum_order_amount DECIMAL(10, 2),      -- Minimum order amount to use the coupon
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'  -- Coupon status (active/inactive)
);

-- Create the 'coupon_usage' table to track coupon usage
CREATE TABLE coupon_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique usage ID (Primary Key, auto-incrementing)
    coupon_id INT NOT NULL,                   -- Foreign key referencing the 'coupons' table
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    usage_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date when the coupon was used
    FOREIGN KEY (coupon_id) REFERENCES coupons(id),  -- Foreign key constraint
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'returns' table to manage product returns
CREATE TABLE returns (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique return ID (Primary Key, auto-incrementing)
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    return_reason TEXT NOT NULL,              -- Reason for the return
    return_status ENUM('requested', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'requested', -- Return status
    requested_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the return was requested
    approved_date TIMESTAMP NULL,             -- Timestamp when the return was approved (NULL by default)
    completed_date TIMESTAMP NULL,            -- Timestamp when the return was completed (NULL by default)
    FOREIGN KEY (order_id) REFERENCES orders(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'customer_support' table for managing support tickets
CREATE TABLE customer_support (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique ticket ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    subject VARCHAR(255) NOT NULL,             -- Subject of the support ticket
    message TEXT NOT NULL,                    -- Message describing the issue
    status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open', -- Ticket status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the ticket was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Timestamp when the ticket was last updated
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'product_variants' table to handle variants of products (e.g., different colors, sizes, etc.)
CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique variant ID (Primary Key, auto-incrementing)
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    sku VARCHAR(100) UNIQUE NOT NULL,         -- Stock keeping unit (unique identifier for the variant)
    variant_name VARCHAR(255) NOT NULL,       -- Name of the variant (e.g., 'Color: Red', 'Size: Medium')
    price DECIMAL(10, 2) NOT NULL,            -- Price of the variant
    stock_quantity INT NOT NULL,              -- Quantity available for this specific variant
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'product_tags' table for tagging products with keywords
CREATE TABLE product_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique tag ID (Primary Key, auto-incrementing)
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    tag VARCHAR(255) NOT NULL,                 -- Tag or keyword (e.g., 'Eco-friendly', 'Best-seller')
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'marketing_campaigns' table to manage advertising campaigns
CREATE TABLE marketing_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique campaign ID (Primary Key, auto-incrementing)
    title VARCHAR(255) NOT NULL,               -- Campaign title
    description TEXT,                          -- Description of the campaign
    start_date DATE NOT NULL,                  -- Campaign start date
    end_date DATE NOT NULL,                    -- Campaign end date
    budget DECIMAL(10, 2) NOT NULL,            -- Campaign budget
    status ENUM('active', 'inactive', 'completed') NOT NULL DEFAULT 'active', -- Campaign status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Timestamp when the campaign was created
);

-- Create the 'campaign_product_association' table to link products to marketing campaigns
CREATE TABLE campaign_product_association (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique association ID (Primary Key, auto-incrementing)
    campaign_id INT NOT NULL,                  -- Foreign key referencing the 'marketing_campaigns' table
    product_id INT NOT NULL,                   -- Foreign key referencing the 'products' table
    FOREIGN KEY (campaign_id) REFERENCES marketing_campaigns(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'affiliate_program' table for managing affiliate marketing
CREATE TABLE affiliate_program (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique affiliate ID (Primary Key, auto-incrementing)
    affiliate_name VARCHAR(255) NOT NULL,      -- Name of the affiliate
    affiliate_email VARCHAR(100) UNIQUE NOT NULL, -- Affiliate's email (unique)
    commission_rate DECIMAL(5, 2) NOT NULL,   -- Commission rate for the affiliate (e.g., 5% commission)
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active', -- Affiliate status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp when the affiliate was added
);

-- Create the 'affiliate_sales' table to track sales from affiliates
CREATE TABLE affiliate_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique sale ID (Primary Key, auto-incrementing)
    affiliate_id INT NOT NULL,                 -- Foreign key referencing the 'affiliate_program' table
    order_id INT NOT NULL,                     -- Foreign key referencing the 'orders' table
    commission_earned DECIMAL(10, 2) NOT NULL, -- Commission earned from the sale
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date when the sale occurred
    FOREIGN KEY (affiliate_id) REFERENCES affiliate_program(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'gift_cards' table for selling gift cards
CREATE TABLE gift_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique gift card ID (Primary Key, auto-incrementing)
    code VARCHAR(50) UNIQUE NOT NULL,         -- Unique gift card code
    amount DECIMAL(10, 2) NOT NULL,           -- Amount of the gift card (e.g., $50)
    status ENUM('active', 'redeemed', 'expired') NOT NULL DEFAULT 'active', -- Gift card status
    expiration_date DATE NOT NULL,            -- Expiration date of the gift card
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp when the gift card was created
);

-- Create the 'gift_card_usage' table to track usage of gift cards
CREATE TABLE gift_card_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique usage ID (Primary Key, auto-incrementing)
    gift_card_id INT NOT NULL,                 -- Foreign key referencing the 'gift_cards' table
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    amount_used DECIMAL(10, 2) NOT NULL,       -- Amount used from the gift card
    usage_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date when the gift card was used
    FOREIGN KEY (gift_card_id) REFERENCES gift_cards(id),  -- Foreign key constraint
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'subscriptions' table to manage subscription-based products or services
CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique subscription ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    subscription_type VARCHAR(255) NOT NULL,   -- Type of subscription (e.g., 'Monthly', 'Annual')
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Start date of the subscription
    end_date TIMESTAMP NOT NULL,              -- End date of the subscription
    status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active', -- Subscription status
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'product_recommendations' table to store recommended products for users
CREATE TABLE product_recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique recommendation ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    reason TEXT,                              -- Reason for the recommendation
    recommended_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of when the product was recommended
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'currencies' table to manage multi-currency support
CREATE TABLE currencies (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique currency ID (Primary Key, auto-incrementing)
    code VARCHAR(10) UNIQUE NOT NULL,         -- Currency code (e.g., USD, EUR, INR)
    name VARCHAR(255) NOT NULL,               -- Currency name (e.g., 'United States Dollar')
    symbol VARCHAR(10) NOT NULL               -- Currency symbol (e.g., '$', '€')
);

-- Create the 'currency_exchange_rates' table to store exchange rates for different currencies
CREATE TABLE currency_exchange_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique exchange rate ID (Primary Key, auto-incrementing)
    from_currency_id INT NOT NULL,             -- Foreign key referencing the 'currencies' table
    to_currency_id INT NOT NULL,               -- Foreign key referencing the 'currencies' table
    exchange_rate DECIMAL(10, 4) NOT NULL,     -- Exchange rate from one currency to another
    effective_date DATE NOT NULL,              -- The date when this exchange rate is valid
    FOREIGN KEY (from_currency_id) REFERENCES currencies(id), -- Foreign key constraint
    FOREIGN KEY (to_currency_id) REFERENCES currencies(id)  -- Foreign key constraint
);

-- Create the 'fraud_detection' table to track suspicious or fraudulent activity
CREATE TABLE fraud_detection (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique fraud ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    issue_description TEXT,                   -- Description of the suspicious or fraudulent activity
    status ENUM('investigating', 'resolved', 'dismissed') NOT NULL DEFAULT 'investigating', -- Status of the case
    flagged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the activity was flagged
    resolved_at TIMESTAMP,                    -- Timestamp when the case was resolved
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'delivery_reviews' table for users to rate the delivery experience
CREATE TABLE delivery_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique review ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),  -- Rating for the delivery (1 to 5)
    review TEXT,                              -- Review text for the delivery experience
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the review was submitted
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'user_activity' table to track user actions and behavior on the platform
CREATE TABLE user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique activity ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    action VARCHAR(255) NOT NULL,              -- Action performed by the user (e.g., 'Viewed Product', 'Added to Cart')
    action_details TEXT,                      -- Details about the action (e.g., product ID, page URL)
    action_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the action occurred
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'abandoned_carts' table to track users who add items to cart but don't complete checkout
CREATE TABLE abandoned_carts (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique cart ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    cart_data TEXT NOT NULL,                   -- JSON or serialized data representing the cart contents
    abandonment_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the cart was abandoned
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'product_views' table to track product views
CREATE TABLE product_views (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique product view ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    view_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the product was viewed
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'product_complaints' table for customers to submit product-related complaints
CREATE TABLE product_complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique complaint ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    complaint TEXT NOT NULL,                  -- Description of the complaint
    status ENUM('open', 'resolved', 'closed') NOT NULL DEFAULT 'open', -- Complaint status
    complaint_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the complaint was made
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'user_notifications' table for sending notifications to users
CREATE TABLE user_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique notification ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    title VARCHAR(255) NOT NULL,               -- Notification title
    message TEXT NOT NULL,                    -- Notification message
    notification_type ENUM('email', 'sms', 'push') NOT NULL,  -- Type of notification (email, SMS, or push)
    status ENUM('sent', 'read', 'unread') NOT NULL DEFAULT 'unread', -- Status of the notification
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the notification was created
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'subscriptions' table to manage subscription-based products or services
CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique subscription ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    subscription_type VARCHAR(255) NOT NULL,   -- Type of subscription (e.g., 'Monthly', 'Annual')
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Start date of the subscription
    end_date TIMESTAMP NOT NULL,              -- End date of the subscription
    status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active', -- Subscription status
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'product_recommendations' table to store recommended products for users
CREATE TABLE product_recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique recommendation ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    reason TEXT,                              -- Reason for the recommendation
    recommended_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of when the product was recommended
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'currencies' table to manage multi-currency support
CREATE TABLE currencies (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique currency ID (Primary Key, auto-incrementing)
    code VARCHAR(10) UNIQUE NOT NULL,         -- Currency code (e.g., USD, EUR, INR)
    name VARCHAR(255) NOT NULL,               -- Currency name (e.g., 'United States Dollar')
    symbol VARCHAR(10) NOT NULL               -- Currency symbol (e.g., '$', '€')
);

-- Create the 'currency_exchange_rates' table to store exchange rates for different currencies
CREATE TABLE currency_exchange_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique exchange rate ID (Primary Key, auto-incrementing)
    from_currency_id INT NOT NULL,             -- Foreign key referencing the 'currencies' table
    to_currency_id INT NOT NULL,               -- Foreign key referencing the 'currencies' table
    exchange_rate DECIMAL(10, 4) NOT NULL,     -- Exchange rate from one currency to another
    effective_date DATE NOT NULL,              -- The date when this exchange rate is valid
    FOREIGN KEY (from_currency_id) REFERENCES currencies(id), -- Foreign key constraint
    FOREIGN KEY (to_currency_id) REFERENCES currencies(id)  -- Foreign key constraint
);

-- Create the 'fraud_detection' table to track suspicious or fraudulent activity
CREATE TABLE fraud_detection (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique fraud ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    issue_description TEXT,                   -- Description of the suspicious or fraudulent activity
    status ENUM('investigating', 'resolved', 'dismissed') NOT NULL DEFAULT 'investigating', -- Status of the case
    flagged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the activity was flagged
    resolved_at TIMESTAMP,                    -- Timestamp when the case was resolved
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'delivery_reviews' table for users to rate the delivery experience
CREATE TABLE delivery_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique review ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    order_id INT NOT NULL,                    -- Foreign key referencing the 'orders' table
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),  -- Rating for the delivery (1 to 5)
    review TEXT,                              -- Review text for the delivery experience
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the review was submitted
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (order_id) REFERENCES orders(id)  -- Foreign key constraint
);

-- Create the 'user_activity' table to track user actions and behavior on the platform
CREATE TABLE user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique activity ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    action VARCHAR(255) NOT NULL,              -- Action performed by the user (e.g., 'Viewed Product', 'Added to Cart')
    action_details TEXT,                      -- Details about the action (e.g., product ID, page URL)
    action_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the action occurred
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'abandoned_carts' table to track users who add items to cart but don't complete checkout
CREATE TABLE abandoned_carts (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique cart ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    cart_data TEXT NOT NULL,                   -- JSON or serialized data representing the cart contents
    abandonment_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the cart was abandoned
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- Create the 'product_views' table to track product views
CREATE TABLE product_views (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique product view ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    view_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the product was viewed
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'product_complaints' table for customers to submit product-related complaints
CREATE TABLE product_complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique complaint ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    product_id INT NOT NULL,                  -- Foreign key referencing the 'products' table
    complaint TEXT NOT NULL,                  -- Description of the complaint
    status ENUM('open', 'resolved', 'closed') NOT NULL DEFAULT 'open', -- Complaint status
    complaint_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the complaint was made
    FOREIGN KEY (user_id) REFERENCES users(id),  -- Foreign key constraint
    FOREIGN KEY (product_id) REFERENCES products(id)  -- Foreign key constraint
);

-- Create the 'user_notifications' table for sending notifications to users
CREATE TABLE user_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,        -- Unique notification ID (Primary Key, auto-incrementing)
    user_id INT NOT NULL,                     -- Foreign key referencing the 'users' table
    title VARCHAR(255) NOT NULL,               -- Notification title
    message TEXT NOT NULL,                    -- Notification message
    notification_type ENUM('email', 'sms', 'push') NOT NULL,  -- Type of notification (email, SMS, or push)
    status ENUM('sent', 'read', 'unread') NOT NULL DEFAULT 'unread', -- Status of the notification
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the notification was created
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Foreign key constraint
);

-- This table stores system actions and logs for monitoring and security purposes.
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(255) NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    details TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- This table stores available payment methods for orders.
CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    method_name VARCHAR(255) NOT NULL  -- e.g., 'Credit Card', 'PayPal', etc.
);

-- This table tracks payment status and method for each order.
CREATE TABLE order_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method_id INT NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id)
);
