<?php

// index.php - Medewerkers lijst
require 'config.php';
$medewerkers = $pdo->query("SELECT * FROM medewerkers")->fetchAll(PDO::FETCH_ASSOC);
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Medewerkers Beheer</title>
    </head>
    <body>
    <h2>Medewerkers</h2>
    <a href="form.php">Toevoegen</a>
    <table border="1">
        <tr>
            <th>ID</th><th>Naam</th><th>Team</th><th>Functie</th><th>Acties</th>
        </tr>
        <?php foreach ($medewerkers as $medewerker): ?>
            <tr>
                <td><?= $medewerker['idMedewerker'] ?></td>
                <td><?= htmlspecialchars($medewerker['voornaam'] . ' ' . $medewerker['achternaam']) ?></td>
                <td><?= htmlspecialchars($medewerker['team']) ?></td>
                <td><?= htmlspecialchars($medewerker['functie']) ?></td>
                <td>
                    <a href="form.php?id=<?= $medewerker['idMedewerker'] ?>">Bewerken</a>
                    <a href="delete.php?id=<?= $medewerker['idMedewerker'] ?>" onclick="return confirm('Weet je het zeker?');">Verwijderen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </body>
    </html>
