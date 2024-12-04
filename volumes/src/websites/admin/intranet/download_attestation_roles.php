<?php
include_once 'lib/attestation-functions.inc.php';

$pdo = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");
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
