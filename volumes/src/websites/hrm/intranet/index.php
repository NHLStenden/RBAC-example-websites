<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

function formatDate($date) {
  // Zet de datum om naar een DateTime-object
  $dateTime = new DateTime($date);
  $now = new DateTime();

  // Vandaag
  if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
    return $dateTime->format('H:i');  // Alleen tijd
  }

  // Maximaal een week oud
  $interval = $now->diff($dateTime);
  if ($interval->days <= 7) {
    return $dateTime->format('l H:i');  // Naam van de dag en tijd
  }

  // Zelfde jaar
  if ($dateTime->format('Y') === $now->format('Y')) {
    return $dateTime->format('m-d H:i');  // Maand, dag en tijd
  }

  // Anders volledige datum in Nederlands formaat
  return $dateTime->format('l j F Y H:i');  // Volledige datum met dagnaam volledig uitgeschreven
}


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
                <th>Voornaam</th>
                <th>Organisatie</th>
                <th>Telefoon</th>
                <th>Kamernummer</th>
                <th>Postcode</th>
                <th>Functie</th>
                <th>Update</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($medewerkers as $medewerker): ?>
                <tr>
                    <td><?= $medewerker['personeelsnummer'] ?></td>
                    <td><?= htmlspecialchars($medewerker['achternaam']) ?></td>
                    <td><?= htmlspecialchars($medewerker['voornaam']) ?></td>
                    <td><?= htmlspecialchars($medewerker['team']) ?></td>
                    <td><?= htmlspecialchars($medewerker['telefoonnummer']) ?></td>
                    <td><?= htmlspecialchars($medewerker['kamernummer']) ?></td>
                    <td><?= htmlspecialchars($medewerker['postcode']) ?></td>
                    <td><?= htmlspecialchars($medewerker['functie']) ?></td>
                    <td><?= htmlspecialchars(formatDate($medewerker['last_sync'])) ?></td>
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
