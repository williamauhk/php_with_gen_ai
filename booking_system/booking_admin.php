<?php include("header.php"); ?>
<div class="container">
    <h2>Booking Management</h2>
    <?php
$bookingsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $bookingsPerPage;
?>
<?php
    // Create SQLite database and table if they don't exist
    $db = new SQLite3('database.db');


    function createBooking($db, $currencyFrom, $currencyTo, $amountFrom, $amountTo) {
      $stmt = $db->prepare("INSERT INTO bookings (currency_from, currency_to, amount_from, amount_to) VALUES (?, ?, ?, ?)");
      $stmt->bindValue(1, $currencyFrom);
      $stmt->bindValue(2, $currencyTo);
      $stmt->bindValue(3, $amountFrom);
      $stmt->bindValue(4, $amountTo);
      $stmt->execute();
  }
  
  function updateBooking($db, $id, $currencyFrom, $currencyTo, $amountFrom, $amountTo) {
      $stmt = $db->prepare("UPDATE bookings SET currency_from = ?, currency_to = ?, amount_from = ?, amount_to = ? WHERE id = ?");
      $stmt->bindValue(1, $currencyFrom);
      $stmt->bindValue(2, $currencyTo);
      $stmt->bindValue(3, $amountFrom);
      $stmt->bindValue(4, $amountTo);
      $stmt->bindValue(5, $id);
      $stmt->execute();
  }
  
  function deleteBooking($db, $id) {
      $stmt = $db->prepare("DELETE FROM bookings WHERE id = ?");
      $stmt->bindValue(1, $id);
      $stmt->execute();
  }

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['create'])) {
          createBooking($db, $_POST['currency-from'], $_POST['currency-to'], $_POST['amount-from'], $_POST['amount-to']);
      } elseif (isset($_POST['update'])) {
          updateBooking($db, $_POST['booking-id'], $_POST['currency-from'], $_POST['currency-to'], $_POST['amount-from'], $_POST['amount-to']);
      } elseif (isset($_POST['delete'])) {
          deleteBooking($db, $_POST['booking-id']);
      }
  }

    // Retrieve bookings from the database
    $query = "SELECT * FROM bookings order by id desc LIMIT $bookingsPerPage OFFSET $offset ";
    $result = $db->query($query);
    $bookings = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $bookings[] = $row;
    }

    // Retrieve exchange rates from the database
      $query = "SELECT DISTINCT currency_from FROM exchange_rates;";
      $result = $db->query($query);
      $exchangeRates = [];
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $exchangeRates_from[] = $row;
      }

      $query = "SELECT DISTINCT currency_to FROM exchange_rates;";
      $result = $db->query($query);
      $exchangeRates = [];
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $exchangeRates_to[] = $row;
      }
    ?>

<?php
$totalBookingsQuery = "SELECT COUNT(*) as count FROM bookings";
$totalBookingsResult = $db->query($totalBookingsQuery);
$totalBookings = $totalBookingsResult->fetchArray(SQLITE3_ASSOC)['count'];
$totalPages = ceil($totalBookings / $bookingsPerPage);
?>
   <!-- Create Booking Modal -->
   <div class="modal fade" id="createBookingModal" tabindex="-1" role="dialog" aria-labelledby="createBookingModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createBookingModalLabel">Create Booking</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form method="POST">
    <div class="form-group">
  <label for="currency-from">Currency From:</label>
  <select class="form-control" id="currency-from" name="currency-from" required>
    <?php foreach ($exchangeRates_from as $exchangeRate) { ?>
      <option value="<?php echo $exchangeRate['currency_from']; ?>"><?php echo $exchangeRate['currency_from']; ?></option>
    <?php } ?>
  </select>
</div>
<div class="form-group">
  <label for="currency-from">Currency To:</label>
  <select class="form-control" id="currency-to" name="currency-to" required>
    <?php foreach ($exchangeRates_to as $exchangeRate) { ?>
      <option value="<?php echo $exchangeRate['currency_to']; ?>" ><?php echo $exchangeRate['currency_to']; ?></option>
    <?php } ?>
  </select>
</div>
      <div class="form-group">
        <label for="amount-from">Amount From:</label>
        <input type="number" class="form-control" id="amount-from" name="amount-from" required>
      </div>
      <div class="form-group">
        <label for="amount-to">Amount To:</label>
        <input type="number" class="form-control" id="amount-to" name="amount-to" required>
      </div>
      <button type="submit" name="create" class="btn btn-primary">Create</button>
    </form>
          </div>
        </div>
      </div>
    </div>

<!-- Update Booking Modal -->
<div class="modal fade" id="updateBookingModal" tabindex="-1" role="dialog" aria-labelledby="updateBookingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateBookingModalLabel">Update Booking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
          <input type="hidden" name="booking-id" id="booking-id">
          <div class="form-group">
            <label for="currency-from">Currency From:</label>
            <select class="form-control" id="currency-from" name="currency-from" required>
              <?php foreach ($exchangeRates_from as $exchangeRate) { ?>
                <option value="<?php echo $exchangeRate['currency_from']; ?>"><?php echo $exchangeRate['currency_from']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="currency-to">Currency To:</label>
            <select class="form-control" id="currency-to" name="currency-to" required>
              <?php foreach ($exchangeRates_to as $exchangeRate) { ?>
                <option value="<?php echo $exchangeRate['currency_to']; ?>"><?php echo $exchangeRate['currency_to']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="amount-from">Amount From:</label>
            <input type="number" class="form-control" id="amount-from" name="amount-from" required>
          </div>
          <div class="form-group">
            <label for="amount-to">Amount To:</label>
            <input type="number" class="form-control" id="amount-to" name="amount-to" required>
          </div>
          <div class="form-group">
            <input type="hidden" name="order-id">
            <button type="submit" name="update" class="btn btn-primary">Update</button>
         
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Create Booking Form -->
<div class="row mt-3 mb-3">
  <div class="col-md-6">
    <h4>Create Booking</h4>
  </div>
  <div class="col-md-6 text-right">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createBookingModal">
      Create Booking
    </button>
  </div>
</div>
   
    <!-- Order List -->
   
    <table class="table">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Booking Code</th>
          <th>Currency From</th>
          <th>Currency To</th>
          <th>Amount From</th>
          <th>Amount To</th>
          <th>User</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $booking) { ?>
          <tr>
            <td><?php echo $booking['id']; ?></td>
            <td><?php echo $booking['booking_code']; ?></td>
            <td><?php echo $booking['currency_from']; ?></td>
            <td><?php echo $booking['currency_to']; ?></td>
            <td><?php echo $booking['amount_from']; ?></td>
            <td><?php echo $booking['amount_to']; ?></td>
            <td><?php echo $booking['username']; ?></td>
            <td>
      
        <!-- Delete Booking Form -->
<form method="POST"> <button type="button" class="btn btn-primary update-button" data-toggle="modal" data-target="#updateBookingModal" data-booking='<?php echo json_encode($booking); ?>'>Update</button>
  <input type="hidden" name="booking-id" value="<?php echo $booking['id']; ?>">
  <button type="submit" name="delete" class="btn btn-danger">Delete</button>
</form>    </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    
    <nav aria-label="Page navigation">
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
      <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php } ?>
  </ul>
</nav>
    <script>
$(document).ready(function() {
  $('.update-button').on('click', function() {
    var booking = $(this).data('booking');
    $('#updateBookingModal #booking-id').val(booking.id);
    $('#updateBookingModal #currency-from').val(booking.currency_from);
    $('#updateBookingModal #currency-to').val(booking.currency_to);
    $('#updateBookingModal #amount-from').val(booking.amount_from);
    $('#updateBookingModal #amount-to').val(booking.amount_to);
  });
});
</script>
  </div>
  <?php include("footer.php"); ?>