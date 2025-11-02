<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if (!empty($email) && !empty($password)) {
    $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      // ✅ Check hashed password
      if (password_verify($password, $user['password'])) {
        // Create session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];

        // Redirect to dashboard
        header("Location: user/dashboard.php");
        exit();
      } else {
        echo "<script>alert('❌ Incorrect password.'); window.history.back();</script>";
      }
    } else {
      echo "<script>alert('❌ No account found with that email.'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('Please fill in both fields.'); window.history.back();</script>";
  }
}
?>
