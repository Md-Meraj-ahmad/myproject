<?php
// Include database connection
include('db.php');

// Function to handle the login
function handleLogin($email, $password) {
    global $conn;

    // Sanitize email and password
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and store user details
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_address'] = $user['address'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Check role if admin
            if ($user['role'] == 'admin') {
                header('Location: ../admin/dashboard.php');
            }else{
                header('Location: ../index.php');
            }
        }else{
            echo "Invalid password";
        }
    }else{
        echo "User not found";
    }
}

// Function to handle user registration
function handleRegister($name, $email, $address, $password, $password_confirmation) {
    global $conn;

    // Validate inputs
    if (empty($name) || empty($email) || empty($address) || empty($password) || empty($password_confirmation)) {
        return "All fields are required.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // Check if passwords match
    if ($password !== $password_confirmation) {
        return "Passwords do not match.";
    }

    // Validate password strength (at least 8 characters, one number, one letter)
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }
    if (!preg_match("/[A-Za-z]/", $password)) {
        return "Password must include at least one letter.";
    }
    if (!preg_match("/[0-9]/", $password)) {
        return "Password must include at least one number.";
    }

    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $address = mysqli_real_escape_string($conn, $address);
    $password = mysqli_real_escape_string($conn, $password);
    $password_confirmation = mysqli_real_escape_string($conn, $password_confirmation);

    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return "An account with this email already exists.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Role 
    $role = "user";

    // Insert new user into the database
    $insert_query = "INSERT INTO users (name, email, address, password, role) VALUES ('$name', '$email', '$address', '$hashed_password', '$role')";
    
    if (mysqli_query($conn, $insert_query)) {
        return "Registration successful. You can now log in.";
    } else {
        return "Error registering user. Please try again.";
    }
}

// Handling form submissions for login and register
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Handle Login
        $email = $_POST['email'];
        $password = $_POST['password'];

        $login_message = handleLogin($email, $password);
    }

    if (isset($_POST['signup-name']) && isset($_POST['signup-email']) && isset($_POST['signup-password']) && isset($_POST['signup-password_confirmation'])) {
        // Handle Registration
        $name = $_POST['signup-name'];
        $email = $_POST['signup-email'];
        $address = $_POST['signup-address'];
        $password = $_POST['signup-password'];
        $password_confirmation = $_POST['signup-password_confirmation'];

        $registration_message = handleRegister($name, $email, $address, $password, $password_confirmation);
    }
}

// function to get the current user's details
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'address' => $_SESSION['user_address'],
            'role' => $_SESSION['user_role']
        ];
    }
    return null;
}

// Function to get all Products
function get_all_products() {
    // Include ../config/db.php
    include '../config/db.php';
    // Query to get all products
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    $products = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    return $products;
}

?>
