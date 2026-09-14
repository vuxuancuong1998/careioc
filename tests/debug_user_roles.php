<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

global $db;
echo "=== DANH SÁCH USER VÀ ROLE GÁN HIỆN TẠI ===" . PHP_EOL;
$db->query("SELECT u.id as uid, u.username, u.display_name, u.citizen_id, ur.role_id, r.role_name, r.role_code, r.is_active as role_active 
            FROM ioc_users u 
            LEFT JOIN ioc_auth_user_roles ur ON ur.user_id = u.id 
            LEFT JOIN ioc_auth_roles r ON r.id = ur.role_id");
while ($r = $db->fetch_object(true)) {
    echo "UID: " . $r->uid . " | CCCD: " . $r->citizen_id . " | Name: " . $r->display_name . " | Role: " . ($r->role_name ?: 'NULL') . " (" . ($r->role_code ?: 'NULL') . ")" . PHP_EOL;
}

echo PHP_EOL . "=== BẢNG IOC_AUTH_USER_ROLES ===" . PHP_EOL;
$db->query("SELECT * FROM ioc_auth_user_roles");
while ($r = $db->fetch_object(true)) {
    echo "ID: " . $r->id . " | user_id: " . $r->user_id . " | role_id: " . $r->role_id . PHP_EOL;
}
