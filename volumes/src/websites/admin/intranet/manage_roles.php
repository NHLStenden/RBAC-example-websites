<?php

/** @file intranet/logging.php
 * Index for the intranet. Users need to login using BasicAuth
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2024
 *
 * Show the user's DN and all group memberships + permissions
 */

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

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");
$sql = "SELECT * FROM `roles` ORDER BY `title` ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$roles = $stmt->fetchAll();

?>

<html lang="NL">
<head>
    <title>Admin Panel | Rollen</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
        <?= showheader(Websites::WEBSITE_ADMIN, '', $rbac) ?>
    </article>
    <article class="roles">
        <table>
        <?php
          foreach ($roles as $role):
        ?>
          <tr>
              <td><?= $role['title'] ?></td>
              <td><a href="edit-role.php?id=<?= $role['idRole'] ?>">Edit</a></td>
          </tr>

          <?php endforeach; ?>
        </table>
    </article>
</main>
</body>
</html>