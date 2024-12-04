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

$idRole = (int)$_GET["id"];

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

$sql  = "SELECT * FROM `roles` WHERE idRole = :idRole";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idRole', $idRole, PDO::PARAM_INT);
$stmt->execute();
$role = $stmt->fetchAll();

if (count($role) != 1) {
  $permissionsHTML = "Role not found";
  $title           = '';
} else {
  $title = $role[0]['title'];

  $sql  = "SELECT * FROM `vw_Role_Permissions` WHERE idRole = :idRole";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':idRole', $idRole, PDO::PARAM_INT);
  $stmt->execute();
  $nrOfPermissions = $stmt->rowCount();

  $permissions = $stmt->fetchAll();


  $permissionsHTML = implode("\n", array_map(function ($p) {
    $idRolePermission = $p['idRolePermission'];
    $permissionName   = $p['permission'];

    return "<tr><td>$permissionName</td>
                <td><button><a href='delete_role.php?id=$idRolePermission'>Delete</button></td>
            </tr>";
  }, $permissions));
}
// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');
?>

<html lang="NL">
<head>
    <title>Admin Panel | Edit Rol</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/edit-role.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, '', $rbac) ?>
        <section class="permissions">
            <h3>Rol: <?= $title ?></h3>
          <?php if ($nrOfPermissions != 0) { ?>
              <table>
                  <caption>Permissies</caption>
                <?= $permissionsHTML ?>
              </table>
          <?php } else {
            echo <<< NO_DATA
<p>Er zijn nog geen permissies bij deze rol gedefinieerd</p>
NO_DATA;
          }
          ?>
        </section>
    </article>
</main>
</body>
</html>