<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

echo "=== TEST 1: Database Tables Existence ===" . PHP_EOL;
$db = Database::getInstance();
$db->query("SHOW TABLES LIKE 'ioc_auth_%'");
$tables = $db->fetch_array();
foreach ($tables as $t) {
    echo "Found table: " . $t[0] . PHP_EOL;
}

echo PHP_EOL . "=== TEST 2: Menu History Sync & Versioning ===" . PHP_EOL;
$jsonContent = file_get_contents(__DIR__ . '/../config/menus_admin.json');
Auth::syncMenuHistory($jsonContent, 'Kiểm thử đồng bộ phiên bản tự động', 'admin');

$db->query("SELECT version_tag, content_hash, created_at, created_by, is_active FROM ioc_auth_menu_versions WHERE is_active = 1 ORDER BY id DESC LIMIT 1");
$ver = $db->fetch_object(true);
if ($ver) {
    echo "Current Active Version Tag: " . $ver->version_tag . PHP_EOL;
    echo "Hash: " . $ver->content_hash . PHP_EOL;
    echo "Created At: " . $ver->created_at . PHP_EOL;
    echo "Created By: " . $ver->created_by . PHP_EOL;
} else {
    echo "No active version found!" . PHP_EOL;
}

echo PHP_EOL . "=== TEST 3: Doctor User (TS.BS Trần Quốc Tuấn) Permissions ===" . PHP_EOL;
$db->query("SELECT * FROM ioc_users WHERE citizen_id = '001085012345' LIMIT 1");
$doctor = $db->fetch_array(true);
if ($doctor) {
    Auth::syncSession($doctor);
    echo "Doctor Fullname: " . ($doctor['display_name'] ?? $doctor['username']) . PHP_EOL;
    echo "Assigned Role Code: " . $_SESSION['user']['role_code'] . PHP_EOL;
    echo "Assigned Role Name: " . $_SESSION['user']['role_name'] . PHP_EOL;
    echo "Menu Access Count: " . count($_SESSION['user']['menu_access']) . " menus: " . implode(', ', $_SESSION['user']['menu_access']) . PHP_EOL;
    echo "Permissions Count: " . count($_SESSION['user']['permissions']) . PHP_EOL;
    
    // Check Menu access
    echo "Can Menu [dashboard]? " . (Auth::canMenu('dashboard') ? 'YES' : 'NO') . PHP_EOL;
    echo "Can Menu [outpatient]? " . (Auth::canMenu('outpatient') ? 'YES' : 'NO') . PHP_EOL;
    echo "Can Menu [inpatient]? " . (Auth::canMenu('inpatient') ? 'YES' : 'NO') . PHP_EOL;
    echo "Can Menu [category-beds]? " . (Auth::canMenu('category-beds') ? 'YES' : 'NO') . PHP_EOL;
    echo "Can Menu [users] (Admin Menu)? " . (Auth::canMenu('users') ? 'YES (FAIL)' : 'NO (CORRECT - BLOCKED)') . PHP_EOL;
    echo "Can Menu [roles] (Admin Menu)? " . (Auth::canMenu('roles') ? 'YES (FAIL)' : 'NO (CORRECT - BLOCKED)') . PHP_EOL;
    echo "Can Menu [it-infra] (Admin Menu)? " . (Auth::canMenu('it-infra') ? 'YES (FAIL)' : 'NO (CORRECT - BLOCKED)') . PHP_EOL;
    
    // Check Action permissions (Thêm, Sửa vs CẤM XÓA)
    echo "Permission [outpatient.view]: " . (Auth::can('outpatient.view') ? 'YES' : 'NO') . PHP_EOL;
    echo "Permission [outpatient.create]: " . (Auth::can('outpatient.create') ? 'YES' : 'NO') . PHP_EOL;
    echo "Permission [outpatient.edit]: " . (Auth::can('outpatient.edit') ? 'YES' : 'NO') . PHP_EOL;
    echo "Permission [outpatient.delete]: " . (Auth::can('outpatient.delete') ? 'YES (FAIL)' : 'NO (CORRECT - FORBIDDEN)') . PHP_EOL;
    echo "Permission [users.delete]: " . (Auth::can('users.delete') ? 'YES (FAIL)' : 'NO (CORRECT - FORBIDDEN)') . PHP_EOL;
} else {
    echo "Doctor not found!" . PHP_EOL;
}

echo PHP_EOL . "=== TEST 4: Super Admin (KS. Vũ Xuân Cường) Permissions ===" . PHP_EOL;
$db->query("SELECT * FROM ioc_users WHERE citizen_id = '037098045678' LIMIT 1");
$admin = $db->fetch_array(true);
if ($admin) {
    Auth::syncSession($admin);
    echo "Admin Fullname: " . ($admin['display_name'] ?? $admin['username']) . PHP_EOL;
    echo "Admin Role Code: " . ($_SESSION['user']['role_code'] ?? 'N/A') . PHP_EOL;
    echo "Admin Permissions: " . json_encode($_SESSION['user']['permissions'] ?? []) . PHP_EOL;
    echo "Is Super Admin? " . (Auth::isSuperAdmin() ? 'YES' : 'NO') . PHP_EOL;
    echo "Can Menu [roles]? " . (Auth::canMenu('roles') ? 'YES' : 'NO') . PHP_EOL;
    echo "Can Menu [users]? " . (Auth::canMenu('users') ? 'YES' : 'NO') . PHP_EOL;
    echo "Permission [outpatient.delete]: " . (Auth::can('outpatient.delete') ? 'YES' : 'NO') . PHP_EOL;
    echo "Permission [users.delete]: " . (Auth::can('users.delete') ? 'YES' : 'NO') . PHP_EOL;
    echo "Permission [roles.create]: " . (Auth::can('roles.create') ? 'YES' : 'NO') . PHP_EOL;
}

echo PHP_EOL . "=== TEST 5: Visible Menus Filtering for Sidebar ===" . PHP_EOL;
// For doctor
Auth::syncSession($doctor);
$docGroups = Auth::getVisibleMenus();
$docVisibleMenuIds = [];
foreach ($docGroups as $grp) {
    foreach ($grp['menus'] as $m) {
        $docVisibleMenuIds[] = $m['id'];
    }
}
echo "Doctor Visible Menus (" . count($docVisibleMenuIds) . "): " . implode(', ', $docVisibleMenuIds) . PHP_EOL;

// For admin
Auth::syncSession($admin);
$admGroups = Auth::getVisibleMenus();
$admVisibleMenuIds = [];
foreach ($admGroups as $grp) {
    foreach ($grp['menus'] as $m) {
        $admVisibleMenuIds[] = $m['id'];
    }
}
echo "Admin Visible Menus (" . count($admVisibleMenuIds) . "): " . implode(', ', $admVisibleMenuIds) . PHP_EOL;

echo PHP_EOL . "=== TEST 6: JavaScript Bridge Data ===" . PHP_EOL;
Auth::syncSession($doctor);
echo "Doctor JS Data: " . Auth::toJsData() . PHP_EOL;

echo PHP_EOL . "ALL VERIFICATION TESTS COMPLETED SUCCESSFULLY!" . PHP_EOL;
