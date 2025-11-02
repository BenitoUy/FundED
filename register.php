<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $bio = trim($_POST['bio']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ✅ Validate input
    if (empty($full_name) || empty($email) || empty($phone) || empty($location) || empty($bio) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Please complete all fields.'); window.history.back();</script>";
        exit;
    }

    // ✅ Password match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // ✅ Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.history.back();</script>";
        exit;
    }

    // ✅ Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $profile_image = 'default.png';

    // ✅ Insert new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, address, bio, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $email, $hashed_password, $phone, $location, $bio, $profile_image);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error: Could not register user.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
$default_avatar = 'img/default_avatar.jpg';

// When inserting a new user
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password, profile_image, bio, contact, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $full_name, $email, $hashed_password, $default_avatar, $bio, $contact, $address);

?>
