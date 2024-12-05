<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Grades_BasicAccess)) {
  echo "Not allowed to create grades grade lists.\n";
  die();
}

$isStudent = in_array('cn=Grades Students,ou=roles,dc=NHLStenden,dc=com', $rbac->groups);
$isTeacher = in_array('cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com', $rbac->groups);

$role = '';
if ($isStudent) {
  $role = 'Student';
}
if ($isTeacher) {
  $role = 'Teacher';
}

?>
<html lang="NL">
<head>
    <title>Hello Intranet - Cijferadministratie!</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_GRADES,'', $rbac) ?>
        <section class="welcome" aria-label="Welcome section">
            <h1>Welkom bij de cijferadministratie</h1>
            <p aria-label="Welcome text">Je kunt hier navigeren naar verschillende onderdelen van de cijfer
                administratie, afhankelijk van
                je rol. Jouw rol is <span class="role" aria-label="role"> <?= $role ?></span>.
            </p>
            <p>
                Kijk in de navigatie balk hierboven om naar de verschillende onderdelen van de applicatie te gaan.
            </p>

        </section>
    </article>

</main>
</body>
</html>
