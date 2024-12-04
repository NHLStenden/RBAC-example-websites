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

$idRole = (int) $_GET["id"];

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");
$sql = "SELECT * FROM `roles` WHERE idRole = :idRole";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idRole', $idRole, PDO::PARAM_INT);
$stmt->execute();
$roles = $stmt->fetchAll();

?>

<html lang="NL">
<head>
    <title>Admin Panel | Edit Rol</title>
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
    <?= $roles[0]['title'] ?>
</main>
</body>
</html>