<?php

function GenerateSectionForMyLdapInfo(RBACSupport $rbac): string|null
{

  $items = [
    "Distinguised Name" => $rbac->userInfoLDAP['dn'],
    "Volledige naam" => $rbac->userInfoLDAP['cn'],
    "Voornaam" => $rbac->userInfoLDAP['givenname'],
    "Achternaam" => $rbac->userInfoLDAP['sn'],
    "Username" => $rbac->userInfoLDAP['uid'],
    "Medewerkernummer" => $rbac->userInfoLDAP['employeenumber'],
    "Type medewerker" => $rbac->userInfoLDAP['employeetype'],
    "Organisatie" => $rbac->userInfoLDAP['o'],
    "Postcode" => $rbac->userInfoLDAP['postalcode'],
    "Kamernummer" => $rbac->userInfoLDAP['roomnumber'],
  ];
  $jpegPhoto = base64_encode($rbac->userInfoLDAP['jpegphoto']);

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
    <section class="ldap-permissions">
        <h3>Rollen</h3>
        <ul>$groups</ul>
   </section>

SECTION_MY_LDAP_ROLES;
}


function GenerateSectionForMyLdapPermissions(RBACSupport $rbac): string|null
{
  $permissions = implode("\n", array_map(function ($group) {
    return "<li>$group</li>";
  }, $rbac->permissions));

  return <<< SECTION_MY_LDAP_ROLES
    <section class="ldap-groups">
        <h3>Permissies:</h3>
        <ul>$permissions</ul>
   </section>

SECTION_MY_LDAP_ROLES;
}
