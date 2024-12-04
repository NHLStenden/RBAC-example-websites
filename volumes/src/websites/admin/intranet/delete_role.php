<?php

$idRolePermission = (int)$_GET['id'];

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

$sql  = "SELECT * FROM `vw_Role_Permissions` WHERE idRolePermission = :idRole";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idRole', $idRolePermission, PDO::PARAM_INT);
$stmt->execute();
$role = $stmt->fetchAll()[0];

$idRole = $role['idRole'];

$sql = "DELETE FROM role_permissions WHERE idRolePermission = :idRole";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':idRole', $idRolePermission, PDO::PARAM_INT);
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