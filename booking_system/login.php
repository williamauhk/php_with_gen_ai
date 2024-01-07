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

          // Create the users table if it doesn't exist
          $query = "CREATE TABLE IF NOT EXISTS users (
                      id INTEGER PRIMARY KEY AUTOINCREMENT,
                      username TEXT NOT NULL,
                      password TEXT NOT NULL
                    )";
          $db->exec($query);

    

          // Retrieve the submitted username and password
          $username = $_POST['username'];
          $password = $_POST['password'];

          // Query the database for the user with the given credentials
          $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
          $result = $db->query($query);

          // Check if the user exists in the database
          if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            // User authentication successful
            echo '<div class="alert alert-success">Login successful!</div>';
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
            <label for="username">Username</label>
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
</body>
</html>