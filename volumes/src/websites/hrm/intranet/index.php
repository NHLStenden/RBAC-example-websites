<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
    die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_HRM_Manage_Employees)) {
    echo "Not allowed to open the manage employees\n";
    die();
}

// index.php - Medewerkers lijst
require 'config.php';
$medewerkers = $pdo->query("SELECT * FROM medewerkers ORDER BY achternaam, voornaam")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medewerkers Beheer</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

</head>
<body>
<main class="container-fluid">
    <article>
        <?= showheader(Websites::WEBSITE_HRM, basename(__FILE__), $rbac) ?>
    </article>
    <section>
        <h2>Medewerkers</h2>
        <p>
            <a class="button" href="form.php">Nieuwe medewerker Toevoegen</a>
        </p>
        <table class="list">
            <thead>
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Organisatie</th>
                <th>Telefoon</th>
                <th>Kamernummer</th>
                <th>Postcode</th>
                <th>Team</th>
                <th>Functie</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($medewerkers as $medewerker): ?>
                <tr>
                    <td><?= $medewerker['personeelsnummer'] ?></td>
                    <td><?= htmlspecialchars($medewerker['voornaam'] . ' ' . $medewerker['achternaam']) ?></td>
                    <td><?= htmlspecialchars($medewerker['team']) ?></td>
                    <td><?= htmlspecialchars($medewerker['telefoonnummer']) ?></td>
                    <td><?= htmlspecialchars($medewerker['kamernummer']) ?></td>
                    <td><?= htmlspecialchars($medewerker['postcode']) ?></td>
                    <td><?= htmlspecialchars($medewerker['functie']) ?></td>
                    <td>
                        <a class="button" href="form.php?id=<?= $medewerker['idMedewerker'] ?>">Bewerken</a>
                        <a class="button" href="delete.php?id=<?= $medewerker['idMedewerker'] ?>"
                           onclick="return confirm('Weet je het zeker?');">Verwijderen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
