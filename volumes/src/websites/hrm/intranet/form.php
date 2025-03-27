<?php
// form.php - Toevoegen/bewerken
require 'config.php';
$id = $_GET['id'] ?? null;
$medewerker = ['voornaam' => '', 'achternaam' => '', 'team' => '', 'functie' => ''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM medewerkers WHERE idMedewerker = ?");
    $stmt->execute([$id]);
    $medewerker = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [$_POST['voornaam'], $_POST['achternaam'], $_POST['team'], $_POST['functie']];
    if ($id) {
        $stmt = $pdo->prepare("UPDATE medewerkers SET voornaam=?, achternaam=?, team=?, functie=? WHERE idMedewerker=?");
        $stmt->execute([...$data, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO medewerkers (voornaam, achternaam, team, functie) VALUES (?, ?, ?, ?)");
        $stmt->execute($data);
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medewerker <?= $id ? 'Bewerken' : 'Toevoegen' ?></title>
</head>
<body>
<h2>Medewerker <?= $id ? 'Bewerken' : 'Toevoegen' ?></h2>
<form method="post">
    <label>Voornaam: <input type="text" name="voornaam" value="<?= htmlspecialchars($medewerker['voornaam']) ?>"
                            required></label><br>
    <label>Achternaam: <input type="text" name="achternaam" value="<?= htmlspecialchars($medewerker['achternaam']) ?>"
                              required></label><br>
    <label>Team: <input type="text" name="team" value="<?= htmlspecialchars($medewerker['team']) ?>"
                        required></label><br>
    <label>Functie: <input type="text" name="functie" value="<?= htmlspecialchars($medewerker['functie']) ?>" required></label><br>
    <button type="submit">Opslaan</button>
</form>
<a href="index.php">Terug</a>
</body>
</html>