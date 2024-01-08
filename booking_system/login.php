<?php include("header.php"); ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="mt-5">Login</h2>
        <?php
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // Establish a connection to the SQLite database
       
          $db = new SQLite3('database.db');
          $db->busyTimeout(5000);
    

          // Retrieve the submitted username and password
          $username = $_POST['username'];
          $password = $_POST['password'];

          // Query the database for the user with the given credentials
          $query = "SELECT * FROM users WHERE username = :username AND password = :password";
          $stmt = $db->prepare($query);
          
          $stmt->bindValue(':username', $username, SQLITE3_TEXT);
          $stmt->bindValue(':password', $password, SQLITE3_TEXT);
          
          $result = $stmt->execute();
          
          // Check if the user exists in the database
          if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
              // User authentication successful
              echo '<div class="alert alert-success">Login successful!</div>';
          
              // Start the session
            
              // Add username and role to session
              $_SESSION['username'] = $row['username'];
              $_SESSION['role'] = $row['role'];
              // Redirect to different pages based on role
              if ($_SESSION['role'] == 'staff') {
                  echo '<script type="text/javascript">window.location.href = "booking_admin.php";</script>';
              } else {
                  echo '<script type="text/javascript">window.location.href = "order.php";</script>';
              }
          } else {
            // User authentication failed
            echo '<div class="alert alert-danger">Invalid username or password.</div>';
          }

          // Close the database connection
          $db->close();
          unset($db);
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="form-group">
            <label for="username">Phone no</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
    </div>
  </div>
  <?php include("footer.php"); ?>