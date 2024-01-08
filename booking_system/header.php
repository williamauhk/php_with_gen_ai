
<?php
    session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu</title><script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Currency Exchange</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
  <?php
    if (!isset($_SESSION['role'])) {
        // If the user is not logged in, show Login and Sign Up
        echo '
        <li class="nav-item active">
            <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="signup.php">Sign Up</a>
        </li>';
    } else{
      if ($_SESSION['role'] != 'staff') {
        // If the user is not staff, show Order
        echo '
        <li class="nav-item">
            <a class="nav-link" href="order.php">Order</a>
        </li>';
      } else {
        // If the user is staff, show other links
        echo '
        <li class="nav-item">
            <a class="nav-link" href="booking_admin.php">Booking</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="member.php">Member</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="rate.php">Rate</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="setting.php">Setting</a>
        </li>';
      }
      echo '
      <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
      </li>';
      
    }

    ?>
</ul>
    </div>
  </nav>
