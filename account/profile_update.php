<?php
include "../config/db.php";

$userId = $_POST['user_id'];
$name = $_POST['name'];
$phone = $_POST['phone_no'];
$address = $_POST['address'];
$bio = $_POST['bio'];
$theme = $_POST['theme'];
$avatarPath = null;

if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $filename = time() . '_' . $_FILES['avatar']['name'];
    move_uploaded_file($_FILES['avatar']['tmp_name'], "../uploads/$filename");
    $avatarPath = "uploads/$filename";
}

$prefs = json_encode(['theme' => $theme]);

$conn->begin_transaction();

try {
    // Update users table
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone_no = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $phone, $address, $userId);
    $stmt->execute();

    // Check if profile exists
    $check = $conn->prepare("SELECT id FROM profiles WHERE user_id = ?");
    $check->bind_param("i", $userId);
    $check->execute();
    $hasProfile = $check->get_result()->num_rows > 0;

    if ($hasProfile) {
        $query = "UPDATE profiles SET bio = ?, preferences = ?";
        if ($avatarPath) $query .= ", avatar = '$avatarPath'";
        $query .= " WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $bio, $prefs, $userId);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO profiles (user_id, bio, preferences, avatar) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $bio, $prefs, $avatarPath);
        $stmt->execute();
    }

    $conn->commit();
    header("Location: profile.php?success=1");
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>
