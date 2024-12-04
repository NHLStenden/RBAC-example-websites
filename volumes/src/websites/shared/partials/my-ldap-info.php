<?php

function GenerateSectionForMyLdapInfo(RBACSupport $rbac): string|null
{
  $fullname = $rbac->userInfoLDAP['cn'];
  $dn       = $rbac->userInfoLDAP['dn'];
  $surname  = $rbac->userInfoLDAP['sn'];
  $uid      = $rbac->userInfoLDAP['uid'];

  return <<< SECTION_MY_LDAP_INFO
    <section class="info">
        <table>
            <tr>
                <td class="label">Distinguised Name</td>
                <td class="value">$dn</td>
            </tr>
            <tr>
                <td class="label">Volledige naam</td>
                <td class="value">$fullname</td>
            </tr>
            <tr>
                <td class="label">Achternaam</td>
                <td class="value">$surname</td>
            </tr>
            <tr>
                <td class="label">Username</td>
                <td class="value">$uid</td>
            </tr>
        </table>
    </section>
SECTION_MY_LDAP_INFO;

}


function GenerateSectionForMyLdapRoles(RBACSupport $rbac): string|null
{
  $groups = implode("\n", array_map(function ($group) {
    $groupParts = explode(",", $group);
    $rolename = explode("=", $groupParts[0])[1];
    return "<li>$rolename</li>";
  }, $rbac->groups));

  return <<< SECTION_MY_LDAP_ROLES
    <section class="ldap-groups">
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
