<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);

if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_SharePoint_TeacherTools)) {
  echo "You do not have permission to access this page to show the teachers portal.";
  die();
}

$studentActions = [
  ['title' => 'Lesroosters beheren', 'icon' => '📅'],
  ['title' => 'Toetsen maken', 'icon' => '✏️'],
  ['title' => 'Studenten beoordelen', 'icon' => '✅'],
  ['title' => 'Cijferadministratie', 'icon' => '📊'],
  ['title' => 'Communicatie met studenten', 'icon' => '💬'],
  ['title' => 'Beoordelen van opdrachten', 'icon' => '📄'],
  ['title' => 'Studievoortgang volgen', 'icon' => '📝'],
  ['title' => 'Vergaderingen plannen', 'icon' => '🗓️'],
  ['title' => 'Handleidingen uploaden', 'icon' => '📚'],
  ['title' => 'Lessen online geven', 'icon' => '🎥'],

];


?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intranet | Docentenportaal</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/globals.css" rel="stylesheet">
  <link href="css/header.css" rel="stylesheet">
  <link href="css/teachers.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../favicon.png">
  <style>
  </style>
</head>
<body>
<article>
  <section>
    <?php
    echo showheader(Websites::WEBSITE_SHAREPOINT, basename(__FILE__), $rbac);
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