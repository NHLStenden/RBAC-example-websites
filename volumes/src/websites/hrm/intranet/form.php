<?php
// form.php - Toevoegen/bewerken
require 'config.php';
$id         = $_GET['id'] ?? null;
$medewerker = [
  'personeelsnummer' => '',
  'voornaam' => '',
  'achternaam' => '',
  'team' => '',
  'functie' => '',
  'telefoonnummer' => '',
  'kamernummer' => '',
  'postcode' => '',
];

if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM medewerkers WHERE idMedewerker = ?");
  $stmt->execute([$id]);
  $medewerker = $stmt->fetch(PDO::FETCH_ASSOC);
}

// FIXME: This is not very secure; SQL-injection possible; clean user input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = [
    $_POST['voornaam'],
    $_POST['achternaam'],
    $_POST['team'],
    $_POST['functie'],
    $_POST['telefoonnummer'],
    $_POST['kamernummer'],
    $_POST['medewerkerType'],
    $_POST['postcode'],
  ];
  if ($id) {
    $stmt = $pdo->prepare("UPDATE medewerkers 
                                    SET voornaam=?, 
                                        achternaam=?, 
                                        team=?, 
                                        functie=?,
                                        telefoonnummer=?,
                                        kamernummer=?,
                                        medewerkerType=?,
                                        postcode=?                                        
                                    WHERE idMedewerker=?");
    $stmt->execute([...$data, $id]);
  } else {
    $stmt2 = $pdo->prepare('SELECT max(personeelsnummer) + 1 as maxnr FROM medewerkers');
    $stmt2->execute();
    $record = $stmt2->fetch(PDO::FETCH_ASSOC);
    $newPersoneelsNr = $record['maxnr'];

    $stmt = $pdo->prepare("INSERT INTO medewerkers (personeelsnummer,
                                                voornaam,
                                                achternaam,
                                                team,
                                                functie,
                                                telefoonnummer,
                                                kamernummer,
                                                medewerkerType,
                                                postcode) VALUES (?, ?, ?, ?,?,?,?,?,?)");
    $stmt->execute([$newPersoneelsNr, ...$data]);
  }
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medewerker <?= $id ? 'Bewerken' : 'Toevoegen' ?></title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<h2>Medewerker <?= $id ? 'Bewerken' : 'Toevoegen' ?></h2>
<form method="post">
    <label for="voornaam">Voornaam: </label>
    <input type="text" name="voornaam" value="<?= htmlspecialchars($medewerker['voornaam']) ?>" required><br>
    <label>Achternaam: </label>
    <input type="text" name="achternaam" value="<?= htmlspecialchars($medewerker['achternaam']) ?>" required><br>
    <label>Telefoon: </label>
    <input type="text" name="telefoonnummer" value="<?= htmlspecialchars($medewerker['telefoonnummer']) ?>"
           required><br>
    <label>kamernummer: </label>
    <input type="text" name="kamernummer" value="<?= htmlspecialchars($medewerker['kamernummer']) ?>" required><br>
    <label>postcode: </label>
    <input type="text" name="postcode" value="<?= htmlspecialchars($medewerker['postcode']) ?>" required><br>


    <label>Team: </label>
    <select name="team" value="<?= htmlspecialchars($medewerker['team']) ?>" required>
        <option value="NHL Stenden">NHL Stenden</option>
    </select>
    <br>
    <label>Functie: </label>
    <select type="text" name="functie" value="<?= htmlspecialchars($medewerker['functie']) ?>" required>
        <option value="docent"></option>
        <option value="medewerker marketing">medewerker marketing</option>
        <option value="medewerker ICT">medewerker ICT</option>
        <option value="medewerker HRM">medewerker HRM</option>
    </select>
    <br>
    <button type="submit">Opslaan</button>
</form>
<a href="index.php">Terug</a>
</body>
</html>