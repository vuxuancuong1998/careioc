<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';
global $db;
$db->query("DESCRIBE ioc_auth_roles");
while ($r = $db->fetch_object(true)) {
    echo $r->Field . ' (' . $r->Type . ')' . PHP_EOL;
}
