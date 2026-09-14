<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

global $db;
$db->query("SELECT u.*, r.role_code, r.permissions 
            FROM ioc_users u 
            LEFT JOIN ioc_auth_user_roles ur ON ur.user_id = u.id 
            LEFT JOIN ioc_auth_roles r ON r.id = ur.role_id 
            WHERE u.citizen_id = '037098045678'");
while ($r = $db->fetch_object(true)) {
    echo "ID: " . $r->id . PHP_EOL;
    echo "User: " . $r->display_name . PHP_EOL;
    echo "Role Code: " . $r->role_code . PHP_EOL;
    echo "Perms: " . $r->permissions . PHP_EOL;
}
