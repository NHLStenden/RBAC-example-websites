<?php


include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once 'lib/attestation-functions.inc.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
  echo "Not allowed to open the Admin panel\n";
  die();
}
// LDAP server details

function createAttestationTable()
{
  $pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  [$header, $report] = getRolePermissionCrossTable($pdo);

  $headerHTML = implode('', array_map(function($x) {return "<th>$x</th>";}, $header));

// Output the report as a table
  echo "<table><thead><tr>$headerHTML</tr></thead>";

  foreach ($report as $user_info) {
    echo "<tr>";
    echo implode("", array_map(function($x) {
        return "<td>$x</td>";
        }, $user_info));
    echo "</tr>";
  }
  echo "</table>";

}

?>
<html lang="NL">
<head>
    <title>Attestation - Rollen</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/attestation.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, 'attestation_roles.php', $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Welkom bij het Admin panel van NHL Stenden.</h1>
            <p>
                Kijk in de navigatie balk hierboven om naar de verschillende applicaties te gaan.
            </p>

        </section>
        <section class="report roles">
            <button><a href="download_attestation_roles.php">Download</a></button>
          <?php createAttestationTable(); ?>
        </section>

    </article>

</main>
</body>
</html>
