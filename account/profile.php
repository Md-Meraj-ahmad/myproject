<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: reg_login.php");
    exit();
}
include "../includes/header.php";

$userId = $_SESSION['user_id'];

$query = $conn->prepare("
    SELECT u.name, u.email, u.address, u.phone_no, 
    p.avatar, p.bio, p.preferences 
    FROM users u 
    LEFT JOIN profiles p ON u.id = p.user_id 
    WHERE u.id = ?
");
$query->bind_param("i", $userId);
$query->execute();
$user = $query->get_result()->fetch_assoc();

// Decode preferences if using JSON
$prefs = json_decode($user['preferences'] ?? '{}', true);
?>

<?php if (isset($_GET['success'])): ?>
<div class="bg-green-100 text-green-800 p-3 rounded mb-4">
    Profile updated successfully!
</div>
<?php endif; ?>

<div class="max-w-xl mx-auto mt-12 bg-white p-6 rounded shadow">
    <div class="flex items-center space-x-4">
        <img src="<?= $user['avatar'] ?: 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) ?>" 
            class="w-20 h-20 rounded-full border object-cover" alt="Avatar">
        <div>
            <h2 class="text-xl font-semibold"><?= htmlspecialchars($user['name']) ?></h2>
            <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
        </div>
    </div>

    <div class="mt-6">
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone_no']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
        <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio']) ?></p>
        <p><strong>Theme:</strong> <?= $prefs['theme'] ?? 'Not Set' ?></p>
        <button type="button" class="bg-black rounded-lg courser-pointer text-white mt-4"><a href="settings.php">Change Setting</a></button>
    </div>
</div>



