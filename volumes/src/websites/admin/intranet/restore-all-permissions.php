<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_AdminPanel_Manage_RolePermissions)) {
  echo "Restore permissions:Missing permissions\n";
  die();
}

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

$sql  = "CALL ResetAllRolesAndPermissions();";
$stmt = $pdo->prepare($sql);
$stmt->execute();


// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');

http_response_code(301);
header('Location: manage_roles.php');