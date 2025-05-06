<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';

// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');


$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_AdminPanel_Manage_RolePermissions)) {
  echo "Not allowed to manage roles/permissions\n";
  die();
}

if (!is_numeric($_GET["id"])) {
  http_response_code(406);
  die('not acceptable');
}


$idConflict = (int)$_GET['id'];

$pdo = ConnectDatabaseIAM();

$sql  = "DELETE FROM permission_conflicts WHERE idConflict = :idConflict";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idConflict', $idConflict, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() !== 1) {
  http_response_code(404);
  die('not found');
}

http_response_code(301);
header('Location: show-sods.php');