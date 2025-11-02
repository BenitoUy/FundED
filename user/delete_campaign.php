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

// Delete campaign
$delete = $conn->prepare("DELETE FROM campaigns WHERE id = ? AND user_id = ?");
$delete->bind_param("ii", $campaign_id, $user_id);

if ($delete->execute()) {
  echo "<script>alert('Campaign deleted successfully.'); window.location.href='profile.php';</script>";
} else {
  echo "<script>alert('Error deleting campaign.'); window.location.href='profile.php';</script>";
}

$conn->close();
?>
