<?php
ini_set('session.name', 'RBACSESSID');
ini_set('session.cookie_domain', '.rbac.docker');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_secure', '0');   // lokaal
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

session_start();

/**
 * @param string $permission
 * @return RBACSupport
 */
function checkLoginOrFail(string $permission): RBACSupport {
    if (!isset($_SESSION['valid'])) {
        header(403);
        die();
    }
    $userDN = $_SESSION['dn'];
    $rbac = new RBACSupport($userDN);
    if (!$rbac->process()) {
        die('Could not connect to RBAC server.');
    }
    if (!$rbac->has($permission)) {
        echo "Not allowed to open this page\n";
        die();
    }
    return $rbac;
}

