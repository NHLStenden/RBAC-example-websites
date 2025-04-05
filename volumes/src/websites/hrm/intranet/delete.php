<?php
// delete.php - Verwijderen
require 'config.php';
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM medewerkers WHERE idMedewerker = ?");
    $stmt->execute([$id]);
}
header('Location: index.php');
exit;
