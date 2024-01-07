<?php include("header.php"); ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="mt-5">Signup</h2>
        <?php
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // Establish a connection to the SQLite database
          $db = new SQLite3('database.db');
          $db->busyTimeout(5000);
          // Retrieve the submitted username and password
          $username = $_POST['username'];
          $password = $_POST['password'];

          // Check if the username already exists in the database
          $query = "SELECT * FROM users WHERE username = '$username'";
          $result = $db->query($query);

          if ($result->fetchArray()) {
            // Username already exists
            echo '<div class="alert alert-danger">Username already exists. Please choose a different username.</div>';
          } else {
            // Insert the new user into the database
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            $db->exec($query);

            // User registration successful
            echo '<div class="alert alert-success">Registration successful!</div>';
          }

          // Close the database connection
          $db->close();
          unset($db);
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Signup</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>