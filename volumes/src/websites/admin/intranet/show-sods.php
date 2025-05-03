<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  http_response_code(500);
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_AdminPanel_Manage_RolePermissions)) {
  http_response_code(406);
  echo "manage-sod: missing correct permission\n";
  die();
}


$pdo  = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");
$sql  = "SELECT * FROM application ORDER BY title";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$apps = $stmt->fetchAll();

$sql  = "SELECT * FROM vw_SOD";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$sods = $stmt->fetchAll();

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/manage-sod.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <title>Beheer Functiescheiding</title>
    <script src="js/manage-sod.js" type="module"></script>
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
    </article>
    <article class="tables">
        <section>
            <fieldset>
                <legend>Bestaande functiescheidingen</legend>
                <table>
                    <thead>
                    <tr>
                        <th>Applicatie</th>
                        <th>Permissie 1</th>
                        <th>Permissie 2</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($sods as $sod):
                      ?>
                        <tr>
                            <td><?= $sod['applicationTitle'] ?></td>
                            <td><?= $sod['permission1_title'] ?></td>
                            <td><?= $sod['permission2_title'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </fieldset>
        </section>
        <a href="add-sod.php"><button>Nieuw...</button></a>
    </article>
</main>
</body>
</html>
