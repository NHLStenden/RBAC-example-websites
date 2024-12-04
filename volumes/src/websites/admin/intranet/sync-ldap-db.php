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

// LDAP server configuratie
$ldap_dn = "ou=roles,dc=NHLStenden,dc=com";

try {
  // Verbinding maken met LDAP
  $ldap_conn = ConnectAndCheckLDAP();

  // Verbinding maken met MySQL via PDO
  $pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

  // LDAP zoekopdracht uitvoeren
  $search = ldap_search($ldap_conn, $ldap_dn, "(objectClass=groupOfUniqueNames)");
  $entries = ldap_get_entries($ldap_conn, $search);

  foreach ($entries as $entry) {
    if (isset($entry['dn']) && isset($entry['cn'][0])) {
      $dn = $entry['dn'];
      $title = $entry['cn'][0];

      // Controleer of de rol al in de database bestaat
      $stmt = $pdo->prepare("SELECT idRole FROM roles WHERE distinghuishedName = :dn");
      $stmt->execute([':dn' => $dn]);

      if ($stmt->rowCount() == 0) {
        // Voeg de rol toe aan de database
        $description = "Description for $title"; // Pas dit aan naar wens
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

  echo "Synchronisatie voltooid.";
} catch (Exception $e) {
  echo "Fout: " . $e->getMessage();
}
?>
