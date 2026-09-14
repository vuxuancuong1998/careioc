<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

global $db;
$db->query("SELECT * FROM ioc_auth_roles WHERE role_code = 'KE_TOAN_VAN_HANH'");
if ($db->num_row() > 0) {
    while ($r = $db->fetch_object(true)) {
        echo "SUCCESS! ID: " . $r->id . " | Name: " . $r->role_name . " | Scope: " . $r->scope . PHP_EOL;
    }
} else {
    echo "NOT_FOUND" . PHP_EOL;
}
