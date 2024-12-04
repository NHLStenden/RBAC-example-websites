<?php


/** @file intranet/logging.php
 * Index for the intranet. Users need to login using BasicAuth
 *
 * @author Martin Molema <martin.molema@nhlstenden.com>
 * @copyright 2024
 *
 * Show the user's DN and all group memberships + permissions
 */

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_SharePoint_All_Users)) {
  echo "Not allowed to open the SharePoint intranet\n";
  die();
}
$campaignListButtonCaption = 'Delete';
?>
<html lang="NL">
<head>
    <title>Marketing</title>
  <link href="css/globals.css" rel="stylesheet">
  <link href="css/index.css" rel="stylesheet">
  <link href="css/header.css" rel="stylesheet">
    <link href="css/partial.fake-campaigns.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

  <article>
    <?= showheader(Websites::WEBSITE_MARKETING,'', $rbac) ?>
    <section class="welcome" aria-label="Welcome section">
      <h1>Verwijder een campagne</h1>
    </section>
    <?php include_once './partials/fake-campaign-list.php'; ?>
  </article>

</main>
</body>
</html>
