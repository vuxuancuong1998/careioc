<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

$db = Database::getInstance();
$db->query("SHOW COLUMNS FROM ioc_auth_menu_versions");
$cols = $db->fetch_array();
echo "COLUMNS OF ioc_auth_menu_versions:" . PHP_EOL;
$hasScope = false;
foreach ($cols as $c) {
    echo "- " . $c['Field'] . " (" . $c['Type'] . ")" . PHP_EOL;
    if ($c['Field'] === 'scope') {
        $hasScope = true;
    }
}

if (!$hasScope) {
    echo "Adding 'scope' column to ioc_auth_menu_versions..." . PHP_EOL;
    $db->query("ALTER TABLE ioc_auth_menu_versions ADD COLUMN `scope` VARCHAR(50) NOT NULL DEFAULT 'admin' AFTER `id`, ADD INDEX `idx_scope` (`scope`)");
    echo "Column 'scope' added successfully!" . PHP_EOL;
} else {
    echo "Column 'scope' already exists!" . PHP_EOL;
}
