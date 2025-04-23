<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
  echo "Home Page: Missing permissions\n";
  die();
}

?>
<html lang="NL">
<head>
    <title>Admin Panel</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Welkom bij het Admin panel van NHL Stenden.</h1>
            <p>
                Kijk in de navigatie balk hierboven om naar de verschillende applicaties te gaan.
            </p>
        </section>
        <section>
            <p>Met deze website kun je centraal zaken beheren die mogelijk normaal gesproken decentraal bij de
                applicatiebeheerder of functioneel beheerder van de websites geregeld zouden worden. Voor het gemak van
                deze oefening zijn deze functies dus in één website ondergebracht.
            </p>
            <ul>
                <li>Autorisaties voor websites: koppeling van rollen aan permissies per website.</li>
                <li>Autorisaties voor websites: toewijzen van rollen aan gebruikers.</li>
                <li>Attestation van rollen en permissies.</li>
                <li>Attestation van rollen en gebruikers.</li>
                <li>Inzage in logging.</li>
            </ul>

        </section>
    </article>

</main>
</body>
</html>
