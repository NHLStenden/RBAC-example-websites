<?php
// config.php - Database configuratie
$host = 'localhost';
$dbname = 'jouw_database';
$username = 'jouw_gebruiker';
$password = 'jouw_wachtwoord';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
}
