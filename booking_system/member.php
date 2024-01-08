<?php include("header.php"); ?>

<!DOCTYPE html>
<html>
<head>
  <title>User Management</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h2>User Management</h2>

    <?php
    // Create SQLite database and table if they don't exist
    $db = new SQLite3('database.db');

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['create'])) {
        // Create new user
        $username = $_POST['user-username'];
        $password = $_POST['user-password'];
        $role = $_POST['user-role'];
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        $db->exec($query);
      } elseif (isset($_POST['update'])) {
        // Update existing user
        $id = $_POST['user-id'];
        $username = $_POST['user-username'];
        $password = $_POST['user-password'];
        $role = $_POST['user-role'];
        $query = "UPDATE users SET username = '$username', password = '$password', role = '$role' WHERE id = $id";
        $db->exec($query);
      } elseif (isset($_POST['delete'])) {
        // Delete user
        $id = $_POST['user-id'];
        $query = "DELETE FROM users WHERE id = $id";
        $db->exec($query);
      }
    }

    // Retrieve users from the database
    $query = "SELECT * FROM users";
    $result = $db->query($query);
    $users = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $users[] = $row;
    }
    ?>

    <!-- Create User Form -->
    <h4>Create User</h4>
    <form method="POST">
      <div class="form-group">
        <label for="user-username">Username:</label>
        <input type="text" class="form-control" id="user-username" name="user-username" required>
      </div>
      <div class="form-group">
        <label for="user-password">Password:</label>
        <input type="password" class="form-control" id="user-password" name="user-password" required>
      </div>
      <div class="form-group">
        <label for="user-role">Role:</label>
        <select class="form-control" id="user-role" name="user-role" required>
          <option value="member">Member</option>
          <option value="staff">Staff</option>
        </select>
      </div>
      <button type="submit" name="create" class="btn btn-primary">Create</button>
    </form>

    <!-- User List -->
    <h4>User List</h4>
    <table class="table">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Username</th>
          <th>Password</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) { ?>
          <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['password']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="user-id" value="<?php echo $user['id']; ?>">
                <div class="form-group">
                  <input type="text" name="user-username" value="<?php echo $user['username']; ?>" required>
                </div>
                <div class="form-group">
                  <input type="password" name="user-password" value="<?php echo $user['password']; ?>" required>
                </div>
                <div class="form-group">
                  <select class="form-control" name="user-role" required>
                    <option value="member" <?php echo $user['role'] === 'member' ? 'selected' : ''; ?>>Member</option>
                    <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                  </select>
                </div>
                <button type="submit" name="update"class="btn btn-primary">Update</button>
                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
              </form>
           </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <?php include("footer.php"); ?>