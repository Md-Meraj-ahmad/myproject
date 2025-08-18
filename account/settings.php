<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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


<form action="profile_update.php" method="POST" enctype="multipart/form-data" class="mt-10 space-y-4">
    <input type="hidden" name="user_id" value="<?= $userId ?>">

    <div>
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="input-field">
    </div>

    <div>
        <label>Phone Number</label>
        <input type="text" name="phone_no" value="<?= htmlspecialchars($user['phone_no']) ?>" class="input-field">
    </div>

    <div>
        <label>Address</label>
        <textarea name="address" class="input-field"><?= htmlspecialchars($user['address']) ?></textarea>
    </div>

    <div>
        <label>Bio</label>
        <textarea name="bio" class="input-field"><?= htmlspecialchars($user['bio']) ?></textarea>
    </div>

    <div>
        <label>Avatar</label>
        <input type="file" name="avatar" class="input-field">
    </div>

    <div>
        <label>Theme</label>
        <select name="theme" class="input-field">
            <option value="light" <?= ($prefs['theme'] ?? '') === 'light' ? 'selected' : '' ?>>Light</option>
            <option value="dark" <?= ($prefs['theme'] ?? '') === 'dark' ? 'selected' : '' ?>>Dark</option>
        </select>
    </div>

    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        Save Changes
    </button>
</form>