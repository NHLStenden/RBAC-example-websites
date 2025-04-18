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
    <title>Admin Panel | Autorisatie aanvraag verwerken</title>
    <link href="css/globals.css" rel="stylesheet">
    <link href="css/AssignUserToRoleForm.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon.png">
    <script src="js/AssignRoleToUserForm.js" type="module"></script>
</head>
<body>
<main class="container-fluid">

    <article>
      <?= showheader(Websites::WEBSITE_ADMIN, basename(__FILE__), $rbac) ?>
    </article>
    <article class="form">
        <fieldset>
            <legend>Nieuwe autorisatie aanvraag verwerken</legend>
            <form method="post" action="AssignRoleToUser.php">
                <div class="form-row">
                    <label for="role">Rol:</label>
                    <select name="role" id="role" size="10">
                        <option value="-">-Kies een rol-</option>
                      <?php foreach ($roles as $role) : ?>
                          <option value="<?= $role['dn'] ?>"><?= $role['cn'] ?></option>
                      <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <label for="user">Gebruiker:</label>
                    <select name="user" id="user" size="10">
                      <?php foreach ($users_staff as $key => $department): ?>
                          <optgroup label="<?= $key ?>">
                            <?php foreach ($department as $user) : ?>
                                <option value="<?= $user['dn'] ?>"><?= $user['sn'] . "," . $user['givenName'] ?></option>
                            <?php endforeach; ?>
                          </optgroup>
                      <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Autoriseer</button>
            </form>
        </fieldset>
        <div id="current-user-list">
            <table>
                <caption>Gebruikers in deze rol</caption>
                <thead>
                <tr>
                    <th>Achternaam</th>
                    <th>Voornaam</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
    </article>

</main>
</body>
</html>
