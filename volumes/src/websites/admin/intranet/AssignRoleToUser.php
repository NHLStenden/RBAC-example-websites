<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/ldap_support.inc.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
  echo "Not allowed to open the Admin panel\n";
  die();
}

$lnk     = ConnectAndCheckLDAP();
$user_dn = $_POST['user'];
$role_dn = $_POST['role'];

$result = AssignUserToRole($lnk, $role_dn, $user_dn);

if ($result) {
  header('location: /intranet', 301);
  die();
}

$error = ldap_error($lnk);
?>

<!doctype html>
<html lang="NL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cannot authorise</title>
</head>
<body>
<p>Kan de autorisatie niet toewijzen!</p>
<p><?= $error ?></p>
</body>
</html>
