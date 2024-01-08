<?php include("header.php"); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Member Management</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h2>Member Management</h2>

    <?php
    // Create SQLite database and table if they don't exist
    $db = new SQLite3('database.db');
    $query = "CREATE TABLE IF NOT EXISTS members (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                email TEXT,
                phone TEXT
              )";
    $db->exec($query);

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['create'])) {
        // Create new member
        $name = $_POST['member-name'];
        $email = $_POST['member-email'];
        $phone = $_POST['member-phone'];
        $query = "INSERT INTO members (name, email, phone) VALUES ('$name', '$email', '$phone')";
        $db->exec($query);
      } elseif (isset($_POST['update'])) {
        // Update existing member
        $id = $_POST['member-id'];
        $name = $_POST['member-name'];
        $email = $_POST['member-email'];
        $phone = $_POST['member-phone'];
        $query = "UPDATE members SET name = '$name', email = '$email', phone = '$phone' WHERE id = $id";
        $db->exec($query);
      } elseif (isset($_POST['delete'])) {
        // Delete member
        $id = $_POST['member-id'];
        $query = "DELETE FROM members WHERE id = $id";
        $db->exec($query);
      }
    }

    // Retrieve members from the database
    $query = "SELECT * FROM members";
    $result = $db->query($query);
    $members = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $members[] = $row;
    }
    ?>

    <!-- Create Member Form -->
    <h4>Create Member</h4>
    <form method="POST">
      <div class="form-group">
        <label for="member-name">Name:</label>
        <input type="text" class="form-control" id="member-name" name="member-name" required>
      </div>
      <div class="form-group">
        <label for="member-email">Email:</label>
        <input type="email" class="form-control" id="member-email" name="member-email" required>
      </div>
      <div class="form-group">
        <label for="member-phone">Phone:</label>
        <input type="text" class="form-control" id="member-phone" name="member-phone" required>
      </div>
      <button type="submit" name="create" class="btn btn-primary">Create</button>
    </form>

    <!-- Member List -->
    <h4>Member List</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Member ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($members as $member) { ?>
          <tr>
            <td><?php echo $member['id']; ?></td>
            <td><?php echo $member['name']; ?></td>
            <td><?php echo $member['email']; ?></td>
            <td><?php echo $member['phone']; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="member-id" value="<?php echo $member['id']; ?>">
                <div class="form-group">
                  <input type="text" name="member-name" value="<?php echo $member['name']; ?>" required>
                </div>
                <div class="form-group">
                  <input type="email" name="member-email" value="<?php echo $member['email']; ?>" required>
                </div>
                <div class="form-group">
                  <input type="text" name="member-phone" value="<?php echo $member['phone']; ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
              </form>
           </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>