<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_AdminPanel_Manage_RolePermissions)) {
  echo "Manage roles: missing permissions\n";
  die();
}

$pdo  = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");
$sql  = "SELECT * FROM `roles` ORDER BY `title` ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$roles = $stmt->fetchAll();

?>

<html lang="NL">
<head>
    <title>Admin Panel | Rollen</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/manage_roles.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN,basename(__FILE__), $rbac) ?>

        <section class="roles">
            <p>
                <button><a href="restore-all-permissions.php"> Restore all permissions</a></button>
                <button><a href="sync-ldap-db.php"> Synchroniseer Rollen</a></button>
            </p>
            <table>
              <?php
              foreach ($roles as $role):
                ?>
                  <tr>
                      <td><?= $role['title'] ?></td>
                      <td><?= $role['title'] ?></td>
                      <td>
                          <button><a href="edit-role.php?id=<?= $role['idRole'] ?>">Edit</a></button>
                      </td>
                  </tr>

              <?php endforeach; ?>
            </table>
        </section>
    </article>
</main>
</body>
</html>