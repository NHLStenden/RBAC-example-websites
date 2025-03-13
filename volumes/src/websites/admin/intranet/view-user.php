<?php
include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/partials/my-ldap-info.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);

if (!$rbac->process()) {
    die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
    echo "You do not have permission to access this page to show other user's info.";
    die();
}

$hasOtherUserData = isset($_POST['userid']);
$found = false;

if ($hasOtherUserData) {
// FIXME: prevent hacking; do some sanitation!
    $user = $_POST['userid'];

    $rbac_other_user = new RBACSupport($user);
    $found = $rbac_other_user->process();

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Zoek gebruiker</title>
    <link href="css/view-user.css" rel="stylesheet">
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/other-user-data.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
<article>

    <?php
    echo showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac);
    ?>
    <fieldset>
        <legend>Zoeken</legend>
        <form action="view-user.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" maxlength="24" name="userid">
            <button type="submit">Zoek!</button>
        </form>
    </fieldset>
</article>
<article>
    <?php
    if ($hasOtherUserData && $found) {
        echo GenerateSectionForMyLdapInfoFromRBAC($rbac_other_user);
        echo GenerateSectionForMyLdapRoles($rbac_other_user);
        echo GenerateSectionForMyLdapPermissions($rbac_other_user);
    } elseif ($hasOtherUserData && !$found) {
        echo "<p class='error'>Niet gevonden</p>";
    }
    ?>
</article>
</body>
</html>
