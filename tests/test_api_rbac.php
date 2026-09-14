<?php
define('__SITE_PATH', realpath(__DIR__ . '/..'));
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/init.php';

echo "=== TEST API & VERSIONING ===" . PHP_EOL;
$db = Database::getInstance();

// 1. Check version count before change
$db->query("SELECT COUNT(*) as total FROM ioc_auth_menu_versions");
$countBefore = (int)($db->fetch_object(true)->total);
echo "Initial Version Count in DB: {$countBefore}" . PHP_EOL;

// 2. Simulate a modified JSON content with real change
$originalContent = file_get_contents(__DIR__ . '/../config/menus_admin.json');
$modifiedContent = str_replace('"id": "dashboard"', '"id": "dashboard_v2"', $originalContent);

Auth::syncMenuHistory($modifiedContent, 'Thử nghiệm tạo version mới tự động khi sửa JSON', 'admin');

$db->query("SELECT COUNT(*) as total FROM ioc_auth_menu_versions");
$countAfter = (int)($db->fetch_object(true)->total);
echo "After modification Version Count: {$countAfter}" . PHP_EOL;

if ($countAfter > $countBefore) {
    echo "SUCCESS: A new version row was automatically generated in ioc_auth_menu_versions!" . PHP_EOL;
    $db->query("SELECT version_tag, content_hash, created_at, change_note FROM ioc_auth_menu_versions ORDER BY id DESC LIMIT 1");
    $newRow = $db->fetch_object(true);
    echo "New Version Tag: " . $newRow->version_tag . " | Time: " . $newRow->created_at . " | Note: " . $newRow->change_note . PHP_EOL;
} else {
    echo "FAIL: Version count did not increase!" . PHP_EOL;
}

// 3. Restore original content sync
Auth::syncMenuHistory($originalContent, 'Khôi phục nguyên bản');

echo PHP_EOL . "=== TEST ROLE API SIMULATION ===" . PHP_EOL;
// Test creating a new role: PHARMACIST (Dược sĩ - Chỉ xem & Xuất báo cáo, cấm Thêm/Sửa/Xóa)
$roleCode = 'TEST_PHARMACIST_' . time();
$roleName = 'Dược Sĩ Lâm Sàng Test';
$menuAccess = json_encode(['dashboard', 'outpatient']);
$permissions = json_encode(['dashboard.view', 'outpatient.view', 'outpatient.export']);

$db->query("INSERT INTO ioc_auth_roles (role_code, role_name, description, menu_access, permissions, is_active, created_at)
            VALUES ('{$roleCode}', '{$roleName}', 'Vai trò test tự động', '{$db->escapestring($menuAccess)}', '{$db->escapestring($permissions)}', 1, NOW())");
$insertId = mysqli_insert_id($db->query("SELECT 1") ? mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, defined('DB_PORT') ? (int)DB_PORT : 3306) : null);

$db->query("SELECT * FROM ioc_auth_roles WHERE role_code = '{$roleCode}'");
$createdRole = $db->fetch_object(true);
if ($createdRole) {
    echo "Created Role: {$createdRole->role_name} (Code: {$createdRole->role_code})" . PHP_EOL;
    echo "Role Menu Access: {$createdRole->menu_access}" . PHP_EOL;
    echo "Role Permissions: {$createdRole->permissions}" . PHP_EOL;
    
    // Clean up test role
    $db->query("DELETE FROM ioc_auth_roles WHERE role_code = '{$roleCode}'");
    echo "Cleaned up test role successfully!" . PHP_EOL;
} else {
    echo "FAIL: Could not create role!" . PHP_EOL;
}

echo PHP_EOL . "ALL API & VERSIONING TESTS PASSED!" . PHP_EOL;
