<?php include("header.php"); ?>
  <div class="container">
    <h2>Setting Management</h2>

    <?php
    // Create SQLite database and table if they don't exist
    $db = new SQLite3('database.db');

 

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['update'])) {
        // Update settings
        $officeHours = $_POST['office-hours'];
        $maintenanceMode = isset($_POST['maintenance-mode']) ? 1 : 0;
        $cutoffTime = $_POST['cutoff-time'];
        
        $query = "UPDATE settings SET office_hours = '$officeHours', maintenance_mode = $maintenanceMode, cutoff_time = '$cutoffTime', updated_at = CURRENT_TIMESTAMP WHERE id = 1";
        $db->exec($query);
      }
    }

    // Retrieve settings from the database
    $query = "SELECT * FROM settings WHERE id = 1";
    $result = $db->query($query);
    $settings = $result->fetchArray(SQLITE3_ASSOC);
  

    // Set default values if no settings are found
    if (!$settings) {
      $query = "INSERT INTO settings (office_hours, maintenance_mode, cutoff_time) VALUES ('', 0, '')";
      $db->exec($query);
    }

    $query = "SELECT * FROM settings WHERE id = 1";
    $result = $db->query($query);
    $settings = $result->fetchArray(SQLITE3_ASSOC);
    ?>

    <!-- Settings Form -->
    <h4>System Settings</h4>
    <form method="POST">
      <div class="form-group">
        <label for="office-hours">Office Hours:</label>
        <textarea type="text" class="form-control" id="office-hours" name="office-hours"> <?php echo $settings['office_hours']; ?></textarea>
      </div>
      <div class="form-group">
        <label for="cutoff-time">Cut Off Time:</label>
        <input type="text" class="form-control" id="cutoff-time" name="cutoff-time" value="<?php echo $settings['cutoff_time']; ?>">
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="maintenance-mode" name="maintenance-mode" <?php if ($settings['maintenance_mode']) echo 'checked'; ?>>
        <label class="form-check-label" for="maintenance-mode">Maintenance Mode</label>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>
  </div>
  <?php include("footer.php"); ?>