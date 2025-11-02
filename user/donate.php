<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$campaign_id = $_GET['id'] ?? 0;

// Get campaign info
$stmt = $conn->prepare("SELECT * FROM campaigns WHERE id = ?");
$stmt->bind_param("i", $campaign_id);
$stmt->execute();
$result = $stmt->get_result();
$campaign = $result->fetch_assoc();

if (!$campaign) {
    die("Invalid campaign.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);

    if ($amount > 0) {
        // Insert donation record
        $insert = $conn->prepare("INSERT INTO donations (campaign_id, user_id, amount, donated_at) VALUES (?, ?, ?, NOW())");
        $insert->bind_param("iid", $campaign_id, $user_id, $amount);
        $insert->execute();

        // Update campaign raised amount
        $update = $conn->prepare("UPDATE campaigns SET raised_amount = raised_amount + ? WHERE id = ?");
        $update->bind_param("di", $amount, $campaign_id);
        $update->execute();

        echo "<script>alert('Thank you for your donation!'); window.location='community.php';</script>";
        exit();
    } else {
        echo "<script>alert('Please enter a valid amount.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Donate - FundED</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="card mx-auto" style="max-width: 500px;">
    <div class="card-body">
      <h3 class="card-title text-danger text-center mb-3">Donate to <?= htmlspecialchars($campaign['title']) ?></h3>
      <p class="text-muted text-center"><?= htmlspecialchars($campaign['description']) ?></p>
      <p class="text-center"><strong>Goal:</strong> ₱<?= number_format($campaign['goal_amount'], 2) ?><br>
      <strong>Raised:</strong> ₱<?= number_format($campaign['raised_amount'], 2) ?></p>
      
      <form method="POST">
        <div class="mb-3">
          <label for="amount" class="form-label">Donation Amount (₱)</label>
          <input type="number" name="amount" id="amount" class="form-control" required min="1" step="0.01">
        </div>
        <button type="submit" class="btn btn-danger w-100">Donate Now</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
