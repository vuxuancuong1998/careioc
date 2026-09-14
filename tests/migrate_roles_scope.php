<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

global $db;
$cols = [];
$db->query("DESCRIBE ioc_auth_roles");
while ($r = $db->fetch_object(true)) {
    $cols[] = $r->Field;
}

if (!in_array('scope', $cols)) {
    $db->query("ALTER TABLE ioc_auth_roles ADD COLUMN scope VARCHAR(50) DEFAULT 'admin' AFTER role_name, ADD INDEX idx_role_scope (scope)");
    echo "Added column 'scope' successfully!" . PHP_EOL;
} else {
    echo "Column 'scope' already exists!" . PHP_EOL;
}

// Cập nhật scope mẫu cho các vai trò hiện có
$db->query("UPDATE ioc_auth_roles SET scope = 'admin' WHERE scope IS NULL OR scope = ''");
echo "Updated default scopes!" . PHP_EOL;
