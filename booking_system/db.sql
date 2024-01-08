-- Drop tables if they exist
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS sqlite_sequence;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS exchange_rates;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS orders;

-- Create the tables
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    role TEXT DEFAULT 'member'
);

CREATE TABLE bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    currency_from TEXT,
    currency_to TEXT,
    amount_from REAL,
    amount_to REAL
);

CREATE TABLE exchange_rates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    currency_from TEXT,
    currency_to TEXT,
    exchange_rate REAL
);

CREATE TABLE settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    office_hours TEXT,
    maintenance_mode INTEGER,
    cutoff_time TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);