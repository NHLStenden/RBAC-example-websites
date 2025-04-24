<?php

function GenerateSectionForMyLdapInfoFromRBAC(RBACSupport $rbac): string|null
{
   return    GenerateSectionForMyLdapInfo($rbac->userInfoLDAP);
}

function GenerateSectionForMyLdapInfo(array $userInfoLDAP): string|null
{

  $items = [
    "Distinguised Name" => $userInfoLDAP['dn'],
    "Volledige naam" => $userInfoLDAP['cn'],
    "Voornaam" => $userInfoLDAP['givenname'],
    "Achternaam" => $userInfoLDAP['sn'],
    "Username" => $userInfoLDAP['uid'],
    "Medewerkernummer" => $userInfoLDAP['employeenumber'],
    "Type medewerker" => $userInfoLDAP['employeetype'],
    "Organisatie" => $userInfoLDAP['o'],
    "Postcode" => $userInfoLDAP['postalcode'],
    "Kamernummer" => $userInfoLDAP['roomnumber'],
  ];

  if (isset($userInfoLDAP['jpegphoto'])) {
      $jpegPhoto = base64_encode($userInfoLDAP['jpegphoto']);
  }
  else {
      $jpegPhoto = null;
  }
  $result = '<section class="my-info"><table>';
  foreach ($items as $key => $item) {
    $result .= "<tr><td>$key:</td><td>$item</td></tr>";
  }
  $result .= "</table>";
  $result .= "<div><img src='data:image/jpeg;base64,$jpegPhoto' /></div>";
  $result .= "</section>";

  return $result;
}


function GenerateSectionForMyLdapRoles(RBACSupport $rbac): string|null
{
  $groups = implode("\n", array_map(function ($group) {
    $groupParts = explode(",", $group);
    $rolename   = explode("=", $groupParts[0])[1];
    return "<li>$rolename</li>";
  }, $rbac->groups));

  return <<< SECTION_MY_LDAP_ROLES
    <section class="ldap-groups">
    <header><h3>Rollen</h3></header>
        
        <ul>$groups</ul>
   </section>

SECTION_MY_LDAP_ROLES;
}


function GenerateSectionForMyLdapPermissions(RBACSupport $rbac): string|null
{
  $permissions = implode("\n", array_map(function ($permission) {
    $role = $permission['role'];
    $permissionName = $permission['permission'];
    $application = $permission['application'];

    return "<tr></tr><td>$role</td><td>$application</td><td>$permissionName</td></tr>\n";

  }, $rbac->permissions));

  return <<< SECTION_MY_LDAP_ROLES
    <section class="ldap-permissions">
        <h3>Permissies</h3>
        <table>
        <thead>
          <th>Rol</th>
          <th>Applicatie</th>
          <th>Permissie</th>
      </thead>
      <tbody>$permissions</tbody>      
      </table>
   </section>

SECTION_MY_LDAP_ROLES;
}
