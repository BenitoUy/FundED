<!DOCTYPE html>
<?php
session_start();
include('../config.php');

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;

    
}

// Fetch user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$full_name = $user['full_name'];
$profile_image = !empty($user['profile_image']) ? 'uploads/' . $user['profile_image'] : 'img/profile.jpg';



// Prevent browser from loading this page from cache after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>




<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | FundED</title>

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
      rel="stylesheet"
    />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-logo">
        <img src="img/logo.png" alt="FundED Logo" />
      </div>
      <ul class="sidebar-nav">
        <li>
          <a href="dashboard.php" class="active">
            <i class="fa fa-tachometer-alt"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="discover.html"><i class="fa fa-search"></i> Discover</a>
        </li>
        <li>
          <a href="community.php"><i class="fa fa-users"></i> Community</a>
        </li>
        <li>
          <a href="create.php"
            ><i class="fa fa-bullhorn"></i> Create Campaign</a
          >
        </li>
        <li>
          <a href="profile.php"><i class="fa fa-user"></i> Profile</a>
        </li>
      <li><a href="logout.php" onclick="return confirm('Are you sure you want to sign out?');"><i class="fa fa-sign-out-alt"></i> Sign Out</a></li>

      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content dashboard">
      <!-- Dashboard Header -->
      <section class="dashboard-header text-dark">
        <div
          class="container-fluid px-4 py-4 d-flex align-items-center justify-content-between"
        >
          <div class="d-flex align-items-center gap-3">
            <img
              src="<?php echo htmlspecialchars($profile_image); ?>"
              alt="User Profile"
              class="rounded-circle shadow-sm"
              width="70"
              height="70"
            />
            <div>
              <h2 class="fw-bold mb-1">
                Welcome Back,
                <?php echo htmlspecialchars($full_name); ?>
                👋
              </h2>
              <p class="text-muted mb-0">
                Here’s an overview of your progress today.
              </p>
            </div>
          </div>
        </div>
      </section>

      <div class="container-fluid px-4 pb-5">
        <!-- Metrics -->
        <div class="row g-4 mb-5" style="padding-top: 30px">
          <div class="col-md-3">
            <div class="metric-card text-center">
              <i class="fa fa-donate fa-2x mb-2"></i>
              <h3 class="count" data-target="120000">0</h3>
              <p>Total Donations (₱)</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="metric-card text-center">
              <i class="fa fa-hand-holding-heart fa-2x mb-2"></i>
              <h3 class="count" data-target="15">0</h3>
              <p>Active Campaigns</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="metric-card text-center">
              <i class="fa fa-users fa-2x mb-2"></i>
              <h3 class="count" data-target="87">0</h3>
              <p>Total Donors</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="metric-card text-center">
              <i class="fa fa-hourglass-half fa-2x mb-2"></i>
              <h3 class="count" data-target="3">0</h3>
              <p>Pending Requests</p>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-5">
          <div class="col-md-6">
            <div class="card chart-card shadow-sm">
              <div class="card-body">
                <h4>Donation Distribution</h4>
                <canvas id="donationPie"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card chart-card shadow-sm">
              <div class="card-body">
                <h4>Monthly Donations</h4>
                <canvas id="donationBar"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Transaction History -->
        <h3 class="fw-bold mb-3">Recent Donation Transactions</h3>
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead class="table-danger">
                  <tr>
                    <th>Date</th>
                    <th>Donor</th>
                    <th>Campaign</th>
                    <th>Amount (₱)</th>
                    <th>Method</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2025-10-20</td>
                    <td>Maria Santos</td>
                    <td>STEM Scholars</td>
                    <td>5,000</td>
                    <td>GCash</td>
                    <td><span class="badge bg-success">Completed</span></td>
                  </tr>
                  <tr>
                    <td>2025-10-19</td>
                    <td>John Dela Cruz</td>
                    <td>Engineering Grant</td>
                    <td>3,500</td>
                    <td>Bank</td>
                    <td><span class="badge bg-success">Completed</span></td>
                  </tr>
                  <tr>
                    <td>2025-10-18</td>
                    <td>Angela Reyes</td>
                    <td>Medical Grant</td>
                    <td>2,000</td>
                    <td>PayPal</td>
                    <td><span class="badge bg-success">Completed</span></td>
                  </tr>
                  <tr>
                    <td>2025-10-17</td>
                    <td>Jose Ramirez</td>
                    <td>Art Scholars</td>
                    <td>1,500</td>
                    <td>GCash</td>
                    <td>
                      <span class="badge bg-warning text-dark">Pending</span>
                    </td>
                  </tr>
                  <tr>
                    <td>2025-10-15</td>
                    <td>Bea Lim</td>
                    <td>Women in Tech</td>
                    <td>10,000</td>
                    <td>Bank</td>
                    <td><span class="badge bg-success">Completed</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-5 text-muted">
          <p>© 2025 FundED | Empowering Students Through Scholarships</p>
        </footer>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Count Animation & Charts -->
    <script>
      // Counter animation
      const counters = document.querySelectorAll(".count");
      counters.forEach((counter) => {
        counter.innerText = "0";
        const updateCount = () => {
          const target = +counter.getAttribute("data-target");
          const c = +counter.innerText;
          const increment = target / 150;
          if (c < target) {
            counter.innerText = `${Math.ceil(c + increment)}`;
            setTimeout(updateCount, 10);
          } else {
            counter.innerText = target.toLocaleString();
          }
        };
        updateCount();
      });

      // Charts
      const pieCtx = document.getElementById("donationPie").getContext("2d");
      new Chart(pieCtx, {
        type: "pie",
        data: {
          labels: ["STEM", "Medical", "Engineering", "Arts", "Tech"],
          datasets: [
            {
              data: [35, 25, 15, 10, 15],
              backgroundColor: [
                "#dc3545",
                "#f87171",
                "#fb923c",
                "#fde68a",
                "#93c5fd",
              ],
            },
          ],
        },
      });

      const barCtx = document.getElementById("donationBar").getContext("2d");
      new Chart(barCtx, {
        type: "bar",
        data: {
          labels: ["May", "Jun", "Jul", "Aug", "Sep", "Oct"],
          datasets: [
            {
              label: "₱ Donations",
              data: [15000, 22000, 18000, 24000, 30000, 27000],
              backgroundColor: "#dc3545",
            },
          ],
        },
        options: { scales: { y: { beginAtZero: true } } },
      });
    </script>
  </body>
</html>
