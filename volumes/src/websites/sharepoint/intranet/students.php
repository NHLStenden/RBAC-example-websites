<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);

if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_SharePoint_StudentTools)) {
  echo "You do not have permission to access this page to show the student portal.";
  die();
}

$studentActions = [

  ['title' => 'Studentensport', 'icon' => '⚽'],
  ['title' => 'Relaxen', 'icon' => '🛋️'],
  ['title' => 'Groepswerk', 'icon' => '👥'],
  ['title' => 'Presentatie voorbereiden', 'icon' => '🎤'],
  ['title' => 'Labwerk doen', 'icon' => '🔬'],
  ['title' => 'Roosters bekijken', 'icon' => '📅'],
  ['title' => 'Elektronische Leeromgeving', 'icon' => '💻'],
  ['title' => 'Studievoortgang bekijken', 'icon' => '📝'],
  ['title' => 'E-mail controleren', 'icon' => '📧'],
  ['title' => 'Online lessen volgen', 'icon' => '🎥'],
  ['title' => 'Marktplaats', 'icon' => '💬'],
  ['title' => 'Hulp vragen via chat', 'icon' => '💡'],
];


?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet | Student Portal</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/students.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <style>
    </style>
</head>
<body>
<article>
    <section>
      <?php
      echo showheader(Websites::WEBSITE_SHAREPOINT, 'hrm.php', $rbac);
      ?>
    </section>
    <section class="tiles">
        <div class="container">
      <?php foreach ($studentActions as $tool): ?>
          <div class="tile">
              <div class="icon"><?= htmlspecialchars($tool['icon'], ENT_QUOTES) ?></div>
              <div class="tile-title"><?= htmlspecialchars($tool['title'], ENT_QUOTES) ?></div>
          </div>
      <?php endforeach; ?>
        </div>
    </section>
</article>
</body>
</html>