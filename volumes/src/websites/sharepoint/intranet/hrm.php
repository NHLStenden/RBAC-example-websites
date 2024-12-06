<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);

if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_SharePoint_HRM)) {
  echo "You do not have permission to access this page to show your student info.";
  die();
}


?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Resource Management</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/hrm.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <style>
    </style>
</head>
<body>
<article>
    <section>
      <?php
      echo showheader(Websites::WEBSITE_SHAREPOINT,basename(__FILE__), $rbac);
      ?>
    </section>
    <section>
        <header>
            <h3>Welkom bij Human Resource Management</h3>
        </header>
    </section>
    <section class="tiles">
        <div class="container">
            <div class="tile">
                <div class="icon">💼</div>
                <div class="tile-title">Declareren</div>
            </div>
            <div class="tile">
                <div class="icon">📄</div>
                <div class="tile-title">Salarisstroken</div>
            </div>
            <div class="tile">
                <div class="icon">💪</div>
                <div class="tile-title">Vitaliteit</div>
            </div>
            <div class="tile">
                <div class="icon">🗓️</div>
                <div class="tile-title">Verlof aanvragen</div>
            </div>
            <div class="tile">
                <div class="icon">📚</div>
                <div class="tile-title">Trainingen</div>
            </div>
            <div class="tile">
                <div class="icon">📝</div>
                <div class="tile-title">Feedback geven</div>
            </div>
            <div class="tile">
                <div class="icon">👤</div>
                <div class="tile-title">Persoonlijke gegevens</div>
            </div>
            <div class="tile">
                <div class="icon">👥</div>
                <div class="tile-title">Teamoverzicht</div>
            </div>
            <div class="tile">
                <div class="icon">📊</div>
                <div class="tile-title">Projecten</div>
            </div>
            <div class="tile">
                <div class="icon">📁</div>
                <div class="tile-title">Documenten</div>
            </div>
            <div class="tile">
                <div class="icon">🚀</div>
                <div class="tile-title">Onboarding</div>
            </div>
            <div class="tile">
                <div class="icon">🏁</div>
                <div class="tile-title">Offboarding</div>
            </div>
            <div class="tile">
                <div class="icon">🏢</div>
                <div class="tile-title">Organigram</div>
            </div>
            <div class="tile">
                <div class="icon">📰</div>
                <div class="tile-title">Nieuws</div>
            </div>
            <div class="tile">
                <div class="icon">🎉</div>
                <div class="tile-title">Evenementen</div>
            </div>
            <div class="tile">
                <div class="icon">📖</div>
                <div class="tile-title">Medewerkers-gids</div>
            </div>
            <div class="tile">
                <div class="icon">📜</div>
                <div class="tile-title">HR Beleid</div>
            </div>
            <div class="tile">
                <div class="icon">🔒</div>
                <div class="tile-title">Veiligheid</div>
            </div>
            <div class="tile">
                <div class="icon">💻</div>
                <div class="tile-title">IT Support</div>
            </div>
            <div class="tile">
                <div class="icon">📞</div>
                <div class="tile-title">Contact HR</div>
            </div>
        </div>
    </section>
</article>
</body>
</html>
