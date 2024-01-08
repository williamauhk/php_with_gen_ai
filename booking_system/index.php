<?php
// Include the header
include 'header.php';

// Connect to your database
$db = new SQLite3('database.db');

// Query to fetch all exchange rates
$query = "SELECT * FROM exchange_rates";
$result = $db->query($query);

$query = "SELECT office_hours FROM settings WHERE id = '1'";
$office_hours_result = $db->query($query);

$office_hours = $office_hours_result->fetchArray(SQLITE3_ASSOC)['office_hours'];
?>

<div class="container">
<div class="container">
    <div class="jumbotron mt-3">
        <h1 class="display-4">Welcome to ABC Exchange Company</h1>
        <p class="lead">Welcome to our website! If you don't have an account yet and want to make a booking .</p>
        <p > <a href="signup.php" class="btn btn-primary btn-lg">Please Sign Up Now</a></p>
    </div>

    <!-- Rest of your code -->
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h4> Exchange Rates</h4>
            </div>
            <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Currency From</th>
                        <th>Currency To</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch each row as an associative array and display its data
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        echo '<tr>
                            <td>' . htmlspecialchars($row['currency_from']) . '</td>
                            <td>' . htmlspecialchars($row['currency_to']) . '</td>
                            <td>' . htmlspecialchars($row['exchange_rate']) . '</td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Opening Hours</h4>
            </div>
            <div class="card-body">
                <pre class="card-text"><?php echo htmlspecialchars($office_hours); ?></pre>
            </div>
        </div>
    </div>
</div>
</div>

<?php
// Include the footer
include 'footer.php';
?>