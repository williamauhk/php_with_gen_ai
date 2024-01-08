<?php
// Include the header
include 'header.php';

// Connect to your database
$db = new SQLite3('database.db');

// Query to fetch all exchange rates
$query = "SELECT * FROM exchange_rates";
$result = $db->query($query);
?>

<div class="container">
    <h1 class="mt-4 mb-3">Exchange Rates</h1>
    <div class="row">
        <div class="col-lg-12">
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
</div>

<?php
// Include the footer
include 'footer.php';
?>