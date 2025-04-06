<?php

include_once '../../shared/lib/RBACSupport.php';
include_once '../../shared/partials/header.php';
include_once '../../shared/lib/ldap_support.inc.php';

$rbac = new RBACSupport($_SERVER["AUTHENTICATE_UID"]);
if (!$rbac->process()) {
  die('Could not connect to RBAC server.');
}
if (!$rbac->has(Permission_Admin_Panel)) {
  echo "Not allowed to open the Admin panel\n";
  die();
}

$lnk                        = ConnectAndCheckLDAP();
$users_staff['HRM']         = GetAllUsersInDN($lnk, 'ou=HRM,ou=Staff,dc=NHLStenden,dc=com');
$users_staff['Marketing']   = GetAllUsersInDN($lnk, 'ou=Marketing,ou=Staff,dc=NHLStenden,dc=com');
$users_staff['ICT Support'] = GetAllUsersInDN($lnk, 'ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com');
$users_staff['Docenten']    = GetAllUsersInDN($lnk, 'ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com');
$roles                      = GetAllRolesInDN($lnk, "ou=roles,dc=NHLStenden,dc=com");

?>
<html lang="NL">
<head>
    <title>Admin Panel | Attestation - Rollen</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/AssignUserToRoleForm.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link href="css/attestation.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <script src="js/attestation.js" type="module"></script>
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
    </article>
    <article>
        <fieldset>
            <legend>Nieuwe autorisatie aanvraag verwerken</legend>
            <form method="post" action="AssignRoleToUser.php">
                <label for="role">Rol:</label>
                <select name="role" id="role">
                  <?php foreach ($roles as $role) : ?>
                      <option value="<?= $role['dn'] ?>"><?= $role['cn'] ?></option>
                  <?php endforeach; ?>

                </select>
                <br>
                <label for="user">Gebruiker:</label>
                <select name="user" id="user">
                  <?php foreach ($users_staff as $key => $department): ?>
                      <optgroup label="<?= $key ?>">
                        <?php foreach ($department as $user) : ?>
                            <option value="<?= $user['dn'] ?>"><?= $user['sn'] . "," . $user['givenName'] ?></option>
                        <?php endforeach; ?>
                      </optgroup>
                  <?php endforeach; ?>
                </select>
                <br>
                <button type="submit">Autoriseer</button>
            </form>
        </fieldset>
    </article>

</main>
</body>
</html>
