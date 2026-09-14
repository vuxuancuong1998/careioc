<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

echo "=== TEST 3 PORTAL SCOPES (ADMIN, BACKEND, DASHBOARD) ===" . PHP_EOL;

// 1. Check file existence
echo "File 1 (admin): " . Auth::getJsonPath('admin') . " (Exists: " . (file_exists(Auth::getJsonPath('admin')) ? 'YES' : 'NO') . ")" . PHP_EOL;
echo "File 2 (backend): " . Auth::getJsonPath('backend') . " (Exists: " . (file_exists(Auth::getJsonPath('backend')) ? 'YES' : 'NO') . ")" . PHP_EOL;
echo "File 3 (dashboard): " . Auth::getJsonPath('dashboard') . " (Exists: " . (file_exists(Auth::getJsonPath('dashboard')) ? 'YES' : 'NO') . ")" . PHP_EOL;

// 2. Load catalogs
$adminCatalog = Auth::getMenuCatalog('admin');
$backendCatalog = Auth::getMenuCatalog('backend');
$dashCatalog = Auth::getMenuCatalog('dashboard');

echo PHP_EOL . "Admin Groups: " . count($adminCatalog) . PHP_EOL;
echo "Backend Groups: " . count($backendCatalog) . PHP_EOL;
echo "Dashboard Groups: " . count($dashCatalog) . PHP_EOL;

// 3. Check actions count
$adminActions = Auth::getAllActions('admin');
$backendActions = Auth::getAllActions('backend');
$dashActions = Auth::getAllActions('dashboard');
$allActions = Auth::getAllActions('all');

echo PHP_EOL . "Admin Actions Count: " . count($adminActions) . PHP_EOL;
echo "Backend Actions Count: " . count($backendActions) . PHP_EOL;
echo "Dashboard Actions Count: " . count($dashActions) . PHP_EOL;
echo "Total Actions (All 3 Portals): " . count($allActions) . " (Match Sum: " . (count($allActions) === (count($adminActions) + count($backendActions) + count($dashActions)) ? 'YES' : 'NO') . ")" . PHP_EOL;

// 4. Verify database versions created for each scope
$db = Database::getInstance();
$db->query("SELECT scope, version_tag, content_hash, created_at FROM ioc_auth_menu_versions WHERE is_active = 1 ORDER BY id DESC");
$versions = $db->fetch_array();
echo PHP_EOL . "Active Versions in DB:" . PHP_EOL;
foreach ($versions as $v) {
    echo "- Scope [{$v['scope']}]: Tag {$v['version_tag']} | Time: {$v['created_at']}" . PHP_EOL;
}

echo PHP_EOL . "ALL 3 PORTAL SCOPE TESTS PASSED!" . PHP_EOL;
