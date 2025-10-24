<?php
// config/db.php
// Change the values to match your DB server
$DB_HOST = 'localhost';
$DB_NAME = 'db_signup'; // your actual DB name
$DB_USER = 'root';
$DB_PASS = '';

// If you are using your online host (ezyro), just uncomment these and comment the local ones above
// $DB_HOST = 'sql103.ezyro.com';
// $DB_NAME = 'ezyro_40134507_db_signup';
// $DB_USER = 'ezyro_40134507';
// $DB_PASS = '8f0a52a0c';

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // In production don't echo errors; log them instead.
    die("Database connection failed: " . $e->getMessage());
}

// ✅ Add this part to load the CRUD class
require_once __DIR__ . '/../dao/crudDAO.php';

// ✅ Create an instance of crudDAO and pass the PDO connection
$dao = new crudDAO($pdo);
