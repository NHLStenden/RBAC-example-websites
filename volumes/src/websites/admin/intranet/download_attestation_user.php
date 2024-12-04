<?php

include_once 'lib/attestation-functions.inc.php';

[$header, $report] = collectAllUsersAndGroupMemberships();

$fp = fopen('php://temp', 'r+');
fputcsv($fp, $header);

// Add data rows
foreach ($report as $user_info) {
  fputcsv($fp, $user_info);
}

// Offer file as download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attestation.csv"');

rewind($fp);
fpassthru($fp);
fclose($fp);


echo $csvString;
