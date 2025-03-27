<?php
// config.php - Database configuratie
$host = 'iam-example-hrm-server';
$dbname = 'HRM';
$username = 'admin';
$password = 'Test1234!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
}
