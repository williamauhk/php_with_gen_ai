<?php
// Connect to your database
$db = new SQLite3('database.db');

// Get currency_to and currency_from from the URL
$currency_from = $_GET['from'];
$currency_to = $_GET['to'];

// Query to fetch the exchange rate
$query = "SELECT exchange_rate FROM exchange_rates WHERE currency_from = :currency_from AND currency_to = :currency_to";
$stmt = $db->prepare($query);
$stmt->bindValue(':currency_from', $currency_from, SQLITE3_TEXT);
$stmt->bindValue(':currency_to', $currency_to, SQLITE3_TEXT);
$result = $stmt->execute();

// Fetch the exchange rate
$row = $result->fetchArray(SQLITE3_ASSOC);
$exchange_rate = $row['exchange_rate'];

// Return the exchange rate in JSON format
header('Content-Type: application/json');
echo json_encode(['rate' => $exchange_rate]);
?>