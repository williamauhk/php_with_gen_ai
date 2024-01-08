<?php include("header.php"); ?>
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="mt-5">Currency Exchange Booking</h2>
        <?php
          // Check if the user is logged in
          // (You need to implement the login functionality separately)
          $loggedIn = true; // Change this condition as per your login logic

          if (!$loggedIn) {
            echo '<div class="alert alert-danger">Please login to continue.</div>';
          } else {
            // Establish a connection to the SQLite database
            $db = new SQLite3('database.db');

            // Create the bookings table if it doesn't exist
            $query = "CREATE TABLE IF NOT EXISTS bookings (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              currency TEXT,
              amount REAL
            )";
            $db->exec($query);

            // Check if the form is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              // Retrieve the submitted booking details
              $currency = $_POST['currency'];
              $amount = $_POST['amount'];

              // Insert the booking into the database
              $query = "INSERT INTO bookings (currency, amount) VALUES ('$currency', '$amount')";
              $db->exec($query);

              // Generate a random booking code
              $bookingCode = uniqid();

              // Display the booking details and confirmation message
              echo '<div class="alert alert-success">Booking successful!</div>';
              echo '<p><strong>Booking Details:</strong></p>';
              echo '<p>Currency: ' . $currency . '</p>';
              echo '<p>Amount: ' . $amount . '</p>';
              echo '<p>Booking Code: ' . $bookingCode . '</p>';
            }

            // Close the database connection
            $db->close();
            unset($db);
          }

          //add page 
          $db = new SQLite3('database.db');
          $query = "CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            currency TEXT,
            amount REAL
          )";
          $db->exec($query);
            
        ?>


<h2>Countdown Timer</h2>
  <div id="timer">00:00:00</div>

  <form method="POST">
    <label for="end-time">End Time:</label>
    <input type="datetime-local" id="end-time" name="end-time">
    <button type="submit">Start Timer</button>
  </form>

  <script>
    function startTimer(endTime, display) {
      var timer = setInterval(function () {
        var now = new Date().getTime();
        var timeRemaining = endTime - now;

        if (timeRemaining < 0) {
          clearInterval(timer);
          display.textContent = "00:00:00";
          return;
        }

        var hours = Math.floor((timeRemaining / (1000 * 60 * 60)) % 24);
        var minutes = Math.floor((timeRemaining / (1000 * 60)) % 60);
        var seconds = Math.floor((timeRemaining / 1000) % 60);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = hours + ":" + minutes + ":" + seconds;
      }, 1000);
    }

    window.onload = function () {
      var endTime = new Date("<?php echo $_POST['end-time'] ?? ''; ?>").getTime();
      var display = document.getElementById("timer");

      if (endTime) {
        startTimer(endTime, display);
      }
    };
  </script>

        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="form-group">
            <label for="currency">Currency</label>
            <select class="form-control" id="currency" name="currency" required>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GBP">GBP</option>
              <!-- Add more currency options as needed -->
            </select>
          </div>
          <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
          </div>
          <button type="submit" class="btn btn-primary">Book Now</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>