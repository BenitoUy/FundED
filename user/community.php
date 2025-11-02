<?php include('config.php'); 
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

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
  <title>Community - Student Campaigns</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="css/style.css"/>
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
      <li><a href="community.php" class="active"><i class="fa fa-users"></i> Community</a></li>
      <li><a href="create.php"><i class="fa fa-bullhorn"></i> Create Campaign</a></li>
      <li><a href="profile.php"><i class="fa fa-user"></i> Profile</a></li>
      <li><a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');"><i class="fa fa-sign-out-alt"></i> Sign Out</a></li>
    </ul>
  </div>

  <!-- Header -->
  <header class="create-header">
    <div class="header-content">
      <h1>Community</h1>
      <p>Support students in need by donating to their scholarship campaigns.</p>
    </div>
  </header>

  <!-- Campaign List -->
  <div class="content community container py-5" style="margin-left: 250px;">
    <div class="row g-4" style="margin-left:170px";>
      <?php
      $current_user_id = $_SESSION['user_id']; // logged in user
      $result = $conn->query("SELECT * FROM campaigns ORDER BY created_at DESC");
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $img = !empty($row['image']) ? 'uploads/' . $row['image'] : 'img/default_campaign.jpg';

              $title = htmlspecialchars($row['title']);
              $desc = htmlspecialchars(substr($row['description'], 0, 100)) . '...';
              $goal = number_format($row['goal_amount'], 2);
              $raised = number_format($row['raised_amount'], 2);

              // Prevent self-donation
              $can_donate = ($row['user_id'] != $current_user_id);

              echo "
              <div class='col-md-4'>
                <div class='card student-card h-100 shadow-sm border-0'>
                  <img src='$img' class='card-img-top' alt='Campaign Image' style='height:220px; object-fit:cover;'>
                  <div class='card-body'>
                    <h5 class='card-title fw-bold text-danger'>$title</h5>
                    <p class='card-text text-muted small mb-3'>$desc</p>
                    <p><strong>Goal:</strong> ₱$goal</p>
                    <p><strong>Raised:</strong> ₱$raised</p>";

              if ($can_donate) {
                echo "<a href='donate.php?id={$row['id']}' class='btn btn-danger w-100'><i class='fa fa-heart me-2'></i>Donate</a>";
              } else {
                echo "<button class='btn btn-secondary w-100' disabled>Your Campaign</button>";
              }

              echo "
                  </div>
                </div>
              </div>
              ";
          }
      } else {
          echo "<p class='text-center text-muted'>No campaigns yet. Be the first to create one!</p>";
      }
      $conn->close();
      ?>
    </div>
  </div>
</body>
</html>
