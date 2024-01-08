<?php
// Include the header
include 'header.php';

// Connect to your database
$db = new SQLite3('database.db');
// Get username from the session
$username = $_SESSION['username'];

// Query to fetch the booking record of the user
$query = "SELECT * FROM bookings WHERE username = :username";
$stmt = $db->prepare($query);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();// Rest of your PHP code


// Query to fetch unique currency_from and currency_to
$query_from = "SELECT DISTINCT currency_from FROM exchange_rates";
$query_to = "SELECT DISTINCT currency_to FROM exchange_rates";
$result_from = $db->query($query_from);
$result_to = $db->query($query_to);

// Check if cancel request is submitted
if (isset($_POST['cancel'])) {
    // Query to delete the booking record of the user
    $query = "DELETE FROM bookings WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->execute();
}

// Check if form is submitted
if (isset($_POST['insert'])) {
    // Get form data
    $currency_from = $_POST['currency_from'];
    $currency_to = $_POST['currency_to'];
    $amount_from = $_POST['amount'];
    $amount_to = $_POST['hidden_total'];

    // Generate a 6-digit booking code
    $booking_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Query to insert booking into the database
    $query = "INSERT INTO bookings (username, booking_code, currency_from, currency_to, amount_from, amount_to) VALUES (:username, :booking_code, :currency_from, :currency_to, :amount_from, :amount_to)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':booking_code', $booking_code, SQLITE3_TEXT);
    $stmt->bindValue(':currency_from', $currency_from, SQLITE3_TEXT);
    $stmt->bindValue(':currency_to', $currency_to, SQLITE3_TEXT);
    $stmt->bindValue(':amount_from', $amount_from, SQLITE3_FLOAT);
    $stmt->bindValue(':amount_to', $amount_to, SQLITE3_FLOAT);
    $stmt->execute();
}


// Fetch the booking record
$booking = $result->fetchArray(SQLITE3_ASSOC);
?>

<div class="container">
    <h1 class="mt-4 mb-3">Order</h1>
    <div class="row"> 
         <?php if ($booking): ?>       
            <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Booking Details
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Booking Code: <br><h2><?php echo $booking['booking_code']; ?></h2></li>
                    <li class="list-group-item">Currency From: <?php echo $booking['currency_from']; ?><br>Amount: <?php echo $booking['amount_from']; ?></li>
                    <li class="list-group-item">Currency To: <?php echo $booking['currency_to']; ?><br>Total: <?php echo $booking['amount_to']; ?></li>

                </ul>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="cancel" value="1">
                        <button type="submit" class="btn btn-danger">Cancel Booking</button>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="col-lg-12">
            <form method="post">
                <div class="form-group">
                    <label for="currency_from">Currency From</label>
                    <select name="currency_from" class="form-control" id="currency_from" onchange="getExchangeRate()">
                        <?php
                        // Fetch each row as an associative array and display its data
                        while ($row = $result_from->fetchArray(SQLITE3_ASSOC)) {
                            echo '<option>' . htmlspecialchars($row['currency_from']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="currency_to">Currency To</label>
                    <select  name="currency_to" class="form-control" id="currency_to" onchange="getExchangeRate()">
                        <?php
                        // Fetch each row as an associative array and display its data
                        while ($row = $result_to->fetchArray(SQLITE3_ASSOC)) {
                            echo '<option>' . htmlspecialchars($row['currency_to']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rate">Rate</label>
                    <input type="number" class="form-control" id="rate" readonly>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" onchange="updateTotal()">
                </div>
                <div class="form-group">
                    <label for="total">Total</label>
                    <input type="number" class="form-control" id="total" readonly>
                </div>
                <input type="hidden" id="hidden_total" name="hidden_total">
                <!-- Add more form fields as needed -->
    <input type="hidden" name="insert" value="1">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form> 
              <?php endif; ?>
        </div>
    </div>
</div>
<script>
// Rest of your JavaScript code

// Countdown timer
let countdown = <?php echo $countdown; ?>;
const countdownDisplay = document.getElementById('countdown');

setInterval(function() {
    countdown--;
    countdownDisplay.textContent = countdown;
    if (countdown <= 0) {
        countdown = 0;
    }
}, 1000);
</script>
<script>
let exchangeRate = 1;

async function getExchangeRate() {
    const currency_from = document.getElementById('currency_from').value;
    const currency_to = document.getElementById('currency_to').value;
    const response = await fetch(`get_rate.php?from=${currency_from}&to=${currency_to}`);
    const data = await response.json();
    exchangeRate = data.rate;
    document.getElementById('rate').value = exchangeRate;
    updateTotal();
}

function updateTotal() {
    const amount = document.getElementById('amount').value;
    if (amount !== '') {
        const total = amount * exchangeRate;
        document.getElementById('total').value = total;
        document.getElementById('hidden_total').value = total;
    }
}
</script>

<?php
// Include the footer
include 'footer.php';
?>