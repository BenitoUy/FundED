<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profiles = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
  <h2>My Profiles</h2>
  <a href="create_profile.php" class="btn btn-success mb-3">+ Add Profile</a>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Photo</th>
        <th>Name</th>
        <th>Email</th>
        <th>Location</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($p = $profiles->fetch_assoc()): ?>
        <tr>
          <td><img src="uploads/<?php echo htmlspecialchars($p['profile_image']); ?>" width="60" class="rounded-circle"></td>
          <td><?php echo htmlspecialchars($p['full_name']); ?></td>
          <td><?php echo htmlspecialchars($p['email']); ?></td>
          <td><?php echo htmlspecialchars($p['location']); ?></td>
          <td>
            <a href="edit_profile.php?id=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
            <a href="delete_profile.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this profile?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
