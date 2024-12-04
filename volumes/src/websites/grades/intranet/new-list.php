<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);

if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Grades_Create_Gradelists)) {
  echo "You do not have permission to create a new list.";
  die();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blackboard - Nieuwe cijferlijst</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/new-list.css" rel="stylesheet">

</head>
<body>
<article>
  <?php echo showheader(Websites::WEBSITE_GRADES,'new-list.php', $rbac) ?>

</article>
</body>
</html>
