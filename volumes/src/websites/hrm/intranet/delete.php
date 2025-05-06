<?php
// delete.php - Verwijderen
include_once '../../shared/lib/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
  $pdo  = ConnectDatabaseHRM();
  $stmt = $pdo->prepare("DELETE FROM medewerkers WHERE idMedewerker = ?");
  $stmt->execute([$id]);
}
header('Location: index.php');
exit;
