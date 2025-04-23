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
    echo "Add permission to role (AJAX): Missing permissions\n";
    die();
}

if (!is_numeric($_POST["idRole"]) || !is_numeric($_POST["idPermission"])) {
    http_response_code(406);
    die('not acceptable');
}

$idRole = (int)$_POST["idRole"];
$idPermission = (int)$_POST["idPermission"];

echo "$idRole | $idPermission\n";

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

$sql = "INSERT INTO role_permissions (fk_idPermission, fk_idRole) VALUES(:idPermission, :idRole)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idPermission', $idPermission, PDO::PARAM_INT);
$stmt->bindValue(':idRole', $idRole, PDO::PARAM_INT);
$stmt->execute();

// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');

http_response_code(301);
header('Location: edit-role.php?id=' . $idRole);