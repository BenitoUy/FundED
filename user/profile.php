<?php
session_start();
include('config.php');

// Check user login
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header("Location: login.html");
  exit();
}

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $full_name = $_POST['fullName'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $location = $_POST['location'];
  $bio = $_POST['bio'];
  $image_name = $user['profile_image'];

  // Handle profile image upload
  if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $image_name = basename($_FILES['profileImage']['name']);
    $targetFile = $targetDir . $image_name;
    move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile);
  }

  $update = $conn->prepare("UPDATE users SET full_name=?, email=?, phone=?, location=?, bio=?, profile_image=? WHERE id=?");
  $update->bind_param("ssssssi", $full_name, $email, $phone, $location, $bio, $image_name, $user_id);

  if ($update->execute()) {
    echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
  } else {
    echo "<script>alert('Error updating profile.');</script>";
  }
}

// Fetch user's campaigns
$campaigns_stmt = $conn->prepare("SELECT * FROM campaigns WHERE user_id = ? ORDER BY created_at DESC");
$campaigns_stmt->bind_param("i", $user_id);
$campaigns_stmt->execute();
$campaigns = $campaigns_stmt->get_result();

$conn->close();

// Prevent browser from loading this page from cache after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile | EduFund</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="img/favicon.ico" rel="icon" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-logo">
      <img src="img/logo.png" alt="FundED Logo" />
    </div>
    <ul class="sidebar-nav">
      <li><a href="dashboard.php"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="discover.html"><i class="fa fa-search"></i> Discover</a></li>
      <li><a href="community.php"><i class="fa fa-users"></i> Community</a></li>
      <li><a href="create.php"><i class="fa fa-bullhorn"></i> Create Campaign</a></li>
      <li><a href="profile.php" class="active"><i class="fa fa-user"></i> Profile</a></li>
      <li><a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');"><i class="fa fa-sign-out-alt"></i> Sign Out</a></li>
    </ul>
  </div>

  <header class="create-header">
    <div class="header-content">
      <h1>Profile</h1>
    </div>
  </header>

  <div class="content profile-page container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card p-4 shadow-sm rounded-4 mb-5">
          <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
              <img
                id="profileImagePreview"
                src="<?php echo !empty($user['profile_image']) ? 'uploads/'.$user['profile_image'] : 'img/profile.jpg'; ?>"
                alt="Profile Picture"
                class="rounded-circle border border-3 border-danger"
                style="width: 130px; height: 130px; object-fit: cover;"
              />
              <label for="uploadProfile" class="position-absolute bottom-0 end-0 bg-danger text-white rounded-circle p-2" style="cursor: pointer;">
                <i class="fa fa-camera"></i>
              </label>
            </div>
            <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($user['full_name']); ?></h4>
            <p class="text-muted">Scholarship Seeker</p>
          </div>

          <form method="POST" enctype="multipart/form-data">
            <input type="file" id="uploadProfile" name="profileImage" accept="image/*" class="d-none" />

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="fullName" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" />
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($user['location']); ?>" />
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Bio</label>
              <textarea name="bio" rows="3" class="form-control"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-danger px-5 py-2 fw-semibold rounded-3">Save Changes</button>
            </div>
          </form>
        </div>

        <!-- User Campaigns Section -->
        <div class="card p-4 shadow-sm rounded-4">
          <h4 class="mb-3"><i class="fa fa-bullhorn text-danger me-2"></i>My Campaigns</h4>
          <?php if ($campaigns->num_rows > 0): ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-danger">
                  <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Goal</th>
                    <th>Raised</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($campaign = $campaigns->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($campaign['title']); ?></td>
                      <td><span class="badge bg-<?php echo $campaign['status'] == 'Active' ? 'success' : ($campaign['status'] == 'Pending' ? 'warning' : 'secondary'); ?>">
                        <?php echo htmlspecialchars($campaign['status']); ?>
                      </span></td>
                      <td>₱<?php echo number_format($campaign['goal_amount'], 2); ?></td>
                      <td>₱<?php echo number_format($campaign['raised_amount'], 2); ?></td>
                      <td>
                        <a href="edit_campaign.php?id=<?php echo $campaign['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                        <a href="delete_campaign.php?id=<?php echo $campaign['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this campaign?')"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-muted text-center">You haven’t created any campaigns yet.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("uploadProfile").addEventListener("change", (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (evt) => {
          document.getElementById("profileImagePreview").src = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
