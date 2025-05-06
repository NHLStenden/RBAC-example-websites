<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/db.php';

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

$permission1 = (int)$_POST['permission1'];
$permission2 = (int)$_POST['permission2'];
$description = htmlspecialchars($_POST['description']);

if ($permission1 == $permission2) {
  die('Mag niet twee dezelfde permissies gebruiken');
}

// table prevents double combinations by forcing perm1 < perm2. so swap if needed
if ($permission1 > $permission2) {
  $temp        = $permission1;
  $permission1 = $permission2;
  $permission2 = $temp;
}


$pdo  = ConnectDatabaseIAM();
$sql  = "INSERT INTO permission_conflicts (idPermissionA, idPermissionB, description) VALUES (:id1, :id2, :description)";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(":id1", $permission1);
$stmt->bindParam(":id2", $permission2);
$stmt->bindParam(":description", $description);

try {
  $stmt->execute();
} catch (PDOException $e) {
  if ($e->getCode() == 23000) {
    die("Deze combinatie van permissies bestaat al. ");
  }

  echo $e->getMessage();
  die();
}


// set expires header
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');

// set cache-control header
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);

// set pragma header
header('Pragma: no-cache');

http_response_code(301);
header('Location: show-sods.php');

