<?php include("header.php"); ?>


  <?php
  // SQLite database file
  $dbFile = 'currency_exchange.db';

  // Create the SQLite database if it doesn't exist
  if (!file_exists($dbFile)) {
    $db = new SQLite3($dbFile);
    $db->exec("CREATE TABLE exchange_rates (id INTEGER PRIMARY KEY AUTOINCREMENT, currency_from TEXT, currency_to TEXT, exchange_rate REAL)");
  } else {
    $db = new SQLite3($dbFile);
  }

  // Function to fetch all exchange rates from the database
  function getExchangeRates($db) {
    $query = "SELECT * FROM exchange_rates";
    $result = $db->query($query);
    $exchangeRates = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $exchangeRates[] = $row;
    }
    return $exchangeRates;
  }

  // Function to add an exchange rate to the database
  function addExchangeRate($db, $currencyFrom, $currencyTo, $exchangeRate) {
    $query = "INSERT INTO exchange_rates (currency_from, currency_to, exchange_rate) VALUES (:currencyFrom, :currencyTo, :exchangeRate)";
    $statement = $db->prepare($query);
    $statement->bindValue(':currencyFrom', $currencyFrom);
    $statement->bindValue(':currencyTo', $currencyTo);
    $statement->bindValue(':exchangeRate', $exchangeRate);
    $statement->execute();
  }
 // Function to update an exchange rate in the database
 function updateExchangeRate($db, $id, $currencyFrom, $currencyTo, $exchangeRate) {
    $query = "UPDATE exchange_rates SET currency_from = :currencyFrom, currency_to = :currencyTo, exchange_rate = :exchangeRate WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':currencyFrom', $currencyFrom);
    $statement->bindValue(':currencyTo', $currencyTo);
    $statement->bindValue(':exchangeRate', $exchangeRate);
    $statement->bindValue(':id', $id);
    $statement->execute();
  }

  // Function to delete an exchange rate from the database
  function deleteExchangeRate($db, $id) {
    $query = "DELETE FROM exchange_rates WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
  }

  // Handle form submissions
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
      $currencyFrom = $_POST['currency-from'];
      $currencyTo = $_POST['currency-to'];
      $exchangeRate = $_POST['exchange-rate'];
      addExchangeRate($db, $currencyFrom, $currencyTo, $exchangeRate);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['exchange-rate-id'];
        $currencyFrom = $_POST['currency-from'];
        $currencyTo = $_POST['currency-to'];
        $exchangeRate = $_POST['exchange-rate'];
        updateExchangeRate($db, $id, $currencyFrom, $currencyTo, $exchangeRate);
    } elseif (isset($_POST['delete'])) {
      $id = $_POST['exchange-rate-id'];
      deleteExchangeRate($db, $id);
    }
  }

  // Fetch all exchange rates from the database
  $exchangeRates = getExchangeRates($db);
  ?>

  <div class="container">
    <h1>Currency Exchange Management</h1>

    <form method="POST" class="mb-3">
      <div class="form-row">
        <div class="col">
          <input type="text" class="form-control" placeholder="Currency From" name="currency-from" required>
        </div>
        <div class="col">
          <input type="text" class="form-control" placeholder="Currency To" name="currency-to" required>
        </div>
        <div class="col">
          <input type="number" step="0.01" class="form-control" placeholder="Exchange Rate" name="exchange-rate" required>
        </div>
        <div class="col">
          <button type="submit" name="add" class="btn btn-primary">Add</button>
        </div>
      </div>
    </form>

    <table class="table">
      <thead>
        <tr>
          <th>Currency From</th>
          <th>Currency To</th>
          <th>Exchange Rate</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($exchangeRates as $exchangeRate): ?>
          <tr>
            <td><?php echo $exchangeRate['currency_from']; ?></td>
            <td><?php echo $exchangeRate['currency_to']; ?></td>
            <td><?php echo $exchangeRate['exchange_rate']; ?></td>
            <td>
              <form method="POST" class="d-inline-block">
                <input type="hidden" name="exchange-rate-id" value="<?php echo $exchangeRate['id']; ?>">
                <div class="form-row">
                  <div class="col">
                    <input type="text" class="form-control" name="currency-from" value="<?php echo $exchangeRate['currency_from']; ?>" required>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control" name="currency-to" value="<?php echo $exchangeRate['currency_to']; ?>" required>
                  </div>
                  <div class="col">
                    <input type="number" step="0.01" class="form-control" name="exchange-rate" value="<?php echo $exchangeRate['exchange_rate']; ?>" required>
                  </div>
                  <div class="col">
                    <button type="submit" name="update" class="btn btn-primary btn-sm">Update</button>
                  </div>
                </div>
              </form>              <form method="POST" class="d-inline-block">
                <input type="hidden" name="exchange-rate-id" value="<?php echo $exchangeRate['id']; ?>">
                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>