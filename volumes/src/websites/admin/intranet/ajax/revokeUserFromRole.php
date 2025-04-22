<?php
include_once '../../../shared/lib/RBACSupport.php';
include_once '../../../shared/lib/ldap_support.inc.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
  echo "Not allowed to open the Admin panel\n";
  die();
}

$lnk = ConnectAndCheckLDAP();

$userDN = $_POST["user"];
$roleDN = $_POST["role"];
try {
  RevokeUserFromRole($lnk, $roleDN, $userDN);
  $users = GetAllGroupMembersOfRole($lnk, $roleDN);

  usort($users, function ($a, $b) {
    $sn = strcmp(strtolower($a["sn"]), strtolower($b["sn"]));
    if ($sn == 0) {
      return strcmp(strtolower($a["givenName"]), strtolower($b["givenName"]));
    }
    return $sn;
  });

  echo json_encode($users);
}
catch (Exception $e) {
  header($e->getMessage(), true, 409);
}