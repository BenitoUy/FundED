<?php
session_start();
include('config.php');

// Verify login
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header("Location: login.html");
  exit();
}

// Get campaign ID
$campaign_id = $_GET['id'] ?? null;
if (!$campaign_id) {
  header("Location: profile.php");
  exit();
}

// Fetch campaign
$stmt = $conn->prepare("SELECT * FROM campaigns WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $campaign_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$campaign = $result->fetch_assoc();

if (!$campaign) {
  echo "<script>alert('Campaign not found.'); window.location.href='profile.php';</script>";
  exit();
}

// Update campaign
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $goal_amount = $_POST['goal_amount'];
  $status = $_POST['status'];

  $update = $conn->prepare("UPDATE campaigns SET title=?, description=?, goal_amount=?, status=? WHERE id=? AND user_id=?");
  $update->bind_param("ssdsii", $title, $description, $goal_amount, $status, $campaign_id, $user_id);

  if ($update->execute()) {
    echo "<script>alert('Campaign updated successfully!'); window.location.href='profile.php';</script>";
  } else {
    echo "<script>alert('Error updating campaign.');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Campaign | EduFund</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow-sm p-4 rounded-4 mx-auto" style="max-width: 700px;">
      <h3 class="text-center mb-4"><i class="fa fa-edit text-danger me-2"></i>Edit Campaign</h3>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">Title</label>
          <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($campaign['title']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" rows="4" class="form-control" required><?php echo htmlspecialchars($campaign['description']); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Goal Amount (₱)</label>
          <input type="number" step="0.01" name="goal_amount" class="form-control" value="<?php echo htmlspecialchars($campaign['goal_amount']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="Active" <?php if($campaign['status']=='Active') echo 'selected'; ?>>Active</option>
            <option value="Pending" <?php if($campaign['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Ended" <?php if($campaign['status']=='Ended') echo 'selected'; ?>>Ended</option>
          </select>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-danger px-5 py-2 rounded-3 fw-semibold">Save Changes</button>
          <a href="profile.php" class="btn btn-secondary px-4 py-2 rounded-3">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
