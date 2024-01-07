<?php include("header.php"); ?>
<div class="container">
    <h2>Order Management</h2>

    <?php
    // Create SQLite database and table if they don't exist
    $db = new SQLite3('orders.db');
    $query = "CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT
              )";
    $db->exec($query);

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['create'])) {
        // Create new order
        $name = $_POST['order-name'];
        $query = "INSERT INTO orders (name) VALUES ('$name')";
        $db->exec($query);
      } elseif (isset($_POST['update'])) {
        // Update existing order
        $id = $_POST['order-id'];
        $name = $_POST['order-name'];
        $query = "UPDATE orders SET name = '$name' WHERE id = $id";
        $db->exec($query);
      } elseif (isset($_POST['delete'])) {
        // Delete order
        $id = $_POST['order-id'];
        $query = "DELETE FROM orders WHERE id = $id";
        $db->exec($query);
      }
    }

    // Retrieve orders from the database
    $query = "SELECT * FROM orders";
    $result = $db->query($query);
    $orders = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $orders[] = $row;
    }
    ?>

    <!-- Create Order Form -->
    <h4>Create Order</h4>
    <form method="POST">
      <div class="form-group">
        <label for="order-name">Order Name:</label>
        <input type="text" class="form-control" id="order-name" name="order-name" required>
      </div>
      <button type="submit" name="create" class="btn btn-primary">Create</button>
    </form>

    <!-- Order List -->
    <h4>Order List</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Order Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) { ?>
          <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo $order['name']; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="order-id" value="<?php echo $order['id']; ?>">
                <input type="text" name="order-name" value="<?php echo $order['name']; ?>" required>
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