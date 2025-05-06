<?php
include_once 'lib/attestation-functions.inc.php';
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/lib/db.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
    die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_AdminPanel_Attestation_Roles)) {
    echo "Download Attestation roles: Missing Permissions\n";
    die();
}
$pdo = ConnectDatabaseIAM();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

list($header, $report) = getRolePermissionCrossTable($pdo);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="role_permission_report.csv"');

$fp = fopen('php://temp', 'r+');
fputcsv($fp, $header);
foreach ($report as $row) {
  fputcsv($fp, $row);
}
rewind($fp);
fpassthru($fp);
fclose($fp);
