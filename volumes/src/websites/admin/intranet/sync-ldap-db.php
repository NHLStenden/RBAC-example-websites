<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
  echo "Not allowed to open the Admin panel\n";
  die();
}

function DoSync()
{
// LDAP server configuratie
  $ldap_dn = "ou=roles,dc=NHLStenden,dc=com";

  try {
    // Verbinding maken met LDAP
    $ldap_conn = ConnectAndCheckLDAP();

    // Verbinding maken met MySQL via PDO
    $pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

    // LDAP zoekopdracht uitvoeren
    $search  = ldap_search($ldap_conn, $ldap_dn, "(objectClass=groupOfUniqueNames)");
    $entries = ldap_get_entries($ldap_conn, $search);

    $nrOfNewRoles = 0;
    foreach ($entries as $entry) {
      if (isset($entry['dn']) && isset($entry['cn'][0])) {
        $dn    = $entry['dn'];
        $title = $entry['cn'][0];

        echo "Controleer rol: $title\n";

        // Controleer of de rol al in de database bestaat
        $stmt = $pdo->prepare("SELECT idRole FROM roles WHERE distinghuishedName = :dn");
        $stmt->bindValue(':dn', $dn, PDO::PARAM_STR);
        $stmt->execute([':dn' => $dn]);

        if ($stmt->rowCount() == 0) {

          echo "- Nieuwe rol gedetecteerd.\n";

          $nrOfNewRoles++;

          // Voeg de rol toe aan de database
          $description = "Rol voor $title";
          $stmt_insert = $pdo->prepare("INSERT INTO roles (title, description, distinghuishedName) VALUES (:title, :description, :dn)");
          $stmt_insert->execute([
            ':title' => $title,
            ':description' => $description,
            ':dn' => $dn
          ]);
        }
      }
    }

    // Sluit de verbindingen
    ldap_unbind($ldap_conn);
    $pdo = null;

    echo "\n\nSynchronisatie voltooid. Er zijn $nrOfNewRoles nieuwe rollen aangemaakt";
  } catch (Exception $e) {
    echo "Fout: " . $e->getMessage();
  }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/manage_roles.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">

    <title>Synchroniseer rollen</title>
</head>
<body>

<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, '', $rbac) ?>
        <section class="results">
          <pre>
          <?php
          DoSync();
          ?>
            </pre>
        </section>
    </article>
</main>
</body>
</html>
