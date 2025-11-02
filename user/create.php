<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$query->bind_param("i", $user_id); $query->execute(); $result =
$query->get_result(); $user = $result->fetch_assoc(); $full_name =
htmlspecialchars($user['full_name']); $email = htmlspecialchars($user['email']);




// Prevent browser from loading this page from cache after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Campaign | StudentAid</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background-color: #f6f9fc;
        color: #333;
        line-height: 1.6;
      }
    </style>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Sidebar Start -->
    <div class="sidebar">
      <div class="sidebar-logo">
        <img src="img/logo.png" alt="FundED Logo" />
      </div>
      <ul class="sidebar-nav">
        <li>
          <a href="dashboard.php"
            ><i class="fa fa-tachometer-alt"></i> Dashboard</a
          >
        </li>
        <li>
          <a href="discover.html"><i class="fa fa-search"></i> Discover</a>
        </li>
        <li>
          <a href="community.php"><i class="fa fa-users"></i> Community</a>
        </li>
        <li>
          <a href="create.php" class="active"
            ><i class="fa fa-bullhorn"></i> Create Campaign</a
          >
        </li>
        <li>
          <a href="profile.php"><i class="fa fa-user"></i> Profile</a>
        </li>
        <li>
          <a
            href="logout.php"
            onclick="return confirm('Are you sure you want to sign out?');"
            ><i class="fa fa-sign-out-alt"></i> Sign Out</a
          >
        </li>
      </ul>
    </div>
    <!-- Sidebar End -->
    <header class="create-header">
      <div class="header-content">
        <h1>
          <i class="fa-solid fa-hand-holding-heart"></i> Create a Student
          Campaign
        </h1>
        <p>
          Are you a student facing financial or personal challenges? Launch your
          campaign today and let your community lend a helping hand.
        </p>
      </div>
    </header>

    <main class="form-section" style="padding-left: 150px">
      <div class="form-wrapper">
        <h2><i class="fa-solid fa-bullhorn"></i> Campaign Information</h2>
        <p class="intro-text">
          Fill in the details below to start your campaign. Make it heartfelt,
          clear, and honest to inspire support from others.
        </p>

        <form
          class="campaign-form"
          action="create_campaign.php"
          method="post"
          enctype="multipart/form-data"
        >
          <!-- Personal Information -->
          <fieldset>
            <legend>
              <i class="fa-solid fa-user"></i> Personal Information
            </legend>

         <div class="form-group">
  <label for="name">Full Name <span>*</span></label>
  <input
    type="text"
    id="name"
    name="name"
    value="<?php echo $full_name; ?>"
    readonly
    required
  />
</div>
            <div class="form-group">
              <label for="email">Email Address <span>*</span></label>
              <input
                type="email"
                id="email"
                name="email"
                value="<?php echo $email; ?>"
                readonly
                required
              />
            </div>

            <div class="form-group">
              <label for="studentId">Student ID (Optional)</label>
              <input
                type="text"
                id="studentId"
                name="studentId"
                placeholder="Enter your student ID"
              />
            </div>
          </fieldset>

          <!-- Campaign Details -->
          <fieldset>
            <legend>
              <i class="fa-solid fa-file-lines"></i> Campaign Details
            </legend>

            <div class="form-group">
              <label for="title">Campaign Title <span>*</span></label>
              <input
                type="text"
                id="title"
                name="title"
                placeholder="e.g. Help Me Graduate – Thesis Fund"
                required
              />
            </div>

            <div class="form-group">
              <label for="goal">Goal Amount (₱) <span>*</span></label>
              <input
                type="number"
                id="goal"
                name="goal"
                placeholder="e.g. 10000"
                min="0"
                required
              />
            </div>

            <div class="form-group">
              <label for="category">Category <span>*</span></label>
              <select id="category" name="category" required>
                <option value="" disabled selected>Select a category</option>
                <option value="education">Education</option>
                <option value="health">Health</option>
                <option value="projects">School Projects</option>
                <option value="emergency">Emergency</option>
              </select>
            </div>

            <div class="form-group">
              <label for="description"
                >Campaign Description <span>*</span></label
              >
              <textarea
                id="description"
                name="description"
                rows="6"
                placeholder="Describe your situation and why you need support..."
                required
              ></textarea>
            </div>

            <div class="form-group">
              <label for="image">Upload a Cover Image (Optional)</label>
              <input type="file" id="image" name="image" accept="image/*" />
              <small
                ><i class="fa-solid fa-info-circle"></i> Recommended: clear,
                high-quality photo (JPG/PNG, max 5MB)</small
              >
            </div>
          </fieldset>

          <!-- Consent -->
          <div class="form-group checkbox">
            <label>
              <input type="checkbox" name="agree" required />
              I confirm that all information provided is accurate and that I
              consent to have my campaign displayed publicly.
            </label>
          </div>

          <!-- Submit -->
          <button type="submit" class="submit-btn">
            <i class="fa-solid fa-paper-plane"></i> Submit My Campaign
          </button>
        </form>
      </div>
    </main>

    <footer class="create-footer">
      <p>© 2025 StudentAid | Empowering Students, Building Futures</p>
    </footer>
  </body>
</html>
