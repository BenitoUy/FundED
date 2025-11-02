<?php
session_start();
include('config.php');

// check login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $location = $_POST['location'];
  $bio = $_POST['bio'];
  $image_name = 'default.png';

  // handle profile image
  if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $image_name = basename($_FILES['profile_image']['name']);
    $targetFile = $targetDir . $image_name;
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile);
  }

  $stmt = $conn->prepare("INSERT INTO profiles (user_id, full_name, email, phone, location, bio, profile_image)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("issssss", $user_id, $full_name, $email, $phone, $location, $bio, $image_name);

  if ($stmt->execute()) {
    echo "<script>alert('Profile created successfully!'); window.location='my_profiles.php';</script>";
  } else {
    echo "<script>alert('Error creating profile');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
  <h2>Create New Profile</h2>
  <form method="POST" enctype="multipart/form-data" class="mt-3">
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="full_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control">
    </div>
    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control">
    </div>
    <div class="mb-3">
      <label>Bio</label>
      <textarea name="bio" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label>Profile Image</label>
      <input type="file" name="profile_image" class="form-control">
    </div>
    <button type="submit" class="btn btn-danger">Save Profile</button>
  </form>
</body>
</html>
