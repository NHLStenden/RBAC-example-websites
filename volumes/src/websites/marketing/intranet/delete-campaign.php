<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once './partials/fake-campaign-list.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Marketing_Delete_Campaign)) {
  echo "Not allowed to open the SharePoint intranet\n";
  die();
}
$campaignListButtonCaption = 'Delete';
?>
<html lang="NL">
<head>
    <title>Marketing | Verwijder campagne</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/partial.fake-campaigns.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_MARKETING, basename(__FILE__), $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Verwijder een campagne</h1>
        </section>
      <?php
      displayCampaigns("Verwijderen", $rbac, Permission_Marketing_Delete_Campaign);
      ?>

    </article>

</main>
</body>
</html>
