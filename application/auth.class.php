<?php
/**
 * Care IOC - Core Authentication & Dynamic RBAC Engine
 * Quản lý phiên làm việc, phân quyền động dựa trên file JSON và Database Backup
 */

class Auth {
    private static $catalogs = [];

    /**
     * Kiểm tra trạng thái đăng nhập
     */
    public static function check(): bool {
        return isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Lấy thông tin tài khoản đang đăng nhập
     */
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Lấy UUID của cán bộ hiện tại
     */
    public static function id(): ?string {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Kiểm tra tài khoản có phải Quản trị viên tối cao (Toàn quyền)
     */
    public static function isSuperAdmin(): bool {
        if (!self::check()) return false;
        $perms = $_SESSION['user']['permissions'] ?? [];
        $roleCode = $_SESSION['user']['role_code'] ?? '';
        return in_array('*', $perms) || $roleCode === 'SUPER_ADMIN';
    }

    /**
     * Lấy đường dẫn file cấu hình theo phân hệ (scope)
     */
    public static function getJsonPath(string $scope = 'admin'): string {
        $scope = strtolower(trim($scope));
        if ($scope === 'backend') {
            return __SITE_PATH . '/config/menus_backend.json';
        } elseif ($scope === 'dashboard') {
            return __SITE_PATH . '/config/menus_dashboard.json';
        } else {
            return __SITE_PATH . '/config/menus_admin.json';
        }
    }

    /**
     * Nạp cây Menu và Actions từ file JSON theo từng phân hệ (admin, backend, dashboard)
     * Tự động đồng bộ và lưu lịch sử vào CSDL ioc_auth_menu_versions khi có thay đổi
     */
    public static function getMenuCatalog(string $scope = 'admin'): array {
        $scope = strtolower(trim($scope));
        if (isset(self::$catalogs[$scope])) {
            return self::$catalogs[$scope];
        }

        $filePath = self::getJsonPath($scope);
        if (file_exists($filePath)) {
            $jsonContent = file_get_contents($filePath);
            self::$catalogs[$scope] = json_decode($jsonContent, true) ?? [];

            // Tự động kiểm tra và lưu snapshot vào CSDL theo từng phân hệ
            self::syncMenuHistory($jsonContent, "Cập nhật danh mục chức năng phân hệ [{$scope}]", $scope);
        } else {
            // Nếu mất file vật lý trên server -> Tự động khôi phục từ CSDL
            self::$catalogs[$scope] = self::restoreMenuFromDatabase($scope);
        }

        return self::$catalogs[$scope] ?? [];
    }

    /**
     * Lấy danh mục tất cả các phân hệ: admin, backend, dashboard
     */
    public static function getAllCatalogs(): array {
        return [
            'admin' => [
                'title' => 'Phân hệ Quản Trị IOC Y Tế (Admin)',
                'menus' => self::getMenuCatalog('admin')
            ],
            'backend' => [
                'title' => 'Phân hệ Backend Quản Lý & Vận Hành',
                'menus' => self::getMenuCatalog('backend')
            ],
            'dashboard' => [
                'title' => 'Phân hệ Trung Tâm Điều Hành IOC (Dashboard)',
                'menus' => self::getMenuCatalog('dashboard')
            ]
        ];
    }

    /**
     * Tự động sinh hàng mới trong ioc_auth_menu_versions mỗi lần JSON có thay đổi
     */
    public static function syncMenuHistory(string $jsonContent, string $note = 'Cập nhật danh mục chức năng hệ thống', string $scope = 'admin'): void {
        global $db;
        if (!$db) return;

        $scope = strtolower(trim($scope));
        $safeScope = $db->escapestring($scope);
        $currentHash = md5(trim($jsonContent));

        // Kiểm tra hash của bản ghi active gần nhất theo đúng phân hệ (scope)
        $db->query("SELECT id, content_hash FROM ioc_auth_menu_versions WHERE is_active = 1 AND scope = '{$safeScope}' ORDER BY id DESC LIMIT 1");
        $last = $db->fetch_object(true);

        // Nếu nội dung thay đổi hoặc chưa có bản ghi nào -> INSERT 1 row mới
        if (!$last || $last->content_hash !== $currentHash) {
            // Tắt active của bản ghi cũ trong cùng scope
            $db->query("UPDATE ioc_auth_menu_versions SET is_active = 0 WHERE is_active = 1 AND scope = '{$safeScope}'");

            $safeContent = $db->escapestring($jsonContent);
            $user = isset($_SESSION['user']['display_name']) 
                ? $_SESSION['user']['display_name'] . ' (' . ($_SESSION['user']['citizen_id'] ?? '') . ')' 
                : 'SYSTEM_SYNC';
            $versionTag = "{$scope}.v1." . time();
            $safeNote = $db->escapestring($note);

            $db->query("INSERT INTO ioc_auth_menu_versions (scope, version_tag, config_content, content_hash, created_by, change_note, is_active, created_at)
                        VALUES ('{$safeScope}', '{$versionTag}', '{$safeContent}', '{$currentHash}', '{$user}', '{$safeNote}', 1, NOW())");
        }
    }

    /**
     * Khôi phục file JSON từ CSDL nếu file trên ổ cứng bị xóa nhầm
     */
    private static function restoreMenuFromDatabase(string $scope = 'admin'): array {
        global $db;
        if (!$db) return [];

        $safeScope = $db->escapestring(strtolower(trim($scope)));
        $db->query("SELECT config_content FROM ioc_auth_menu_versions WHERE is_active = 1 AND scope = '{$safeScope}' ORDER BY id DESC LIMIT 1");
        if ($db->num_row() > 0) {
            $row = $db->fetch_object(true);
            $content = $row->config_content;
            @file_put_contents(self::getJsonPath($scope), $content);
            return json_decode($content, true) ?? [];
        }
        return [];
    }

    /**
     * Trích xuất toàn bộ danh sách quyền chi tiết dạng phẳng để Admin tích chọn
     * Hỗ trợ $scope = 'all' (cả 3 phân hệ), hoặc 'admin', 'backend', 'dashboard'
     */
    public static function getAllActions(string $scope = 'all'): array {
        $actions = [];
        $scopes = ($scope === 'all') ? ['admin', 'backend', 'dashboard'] : [strtolower(trim($scope))];

        foreach ($scopes as $sc) {
            $catalog = self::getMenuCatalog($sc);
            foreach ($catalog as $group) {
                $menus = $group['menus'] ?? [];
                foreach ($menus as $menu) {
                    $menuId = $menu['id'];
                    $menuActions = $menu['actions'] ?? [];
                    foreach ($menuActions as $actionKey => $actionLabel) {
                        $actions["{$menuId}.{$actionKey}"] = [
                            'scope' => $sc,
                            'menu_id' => $menuId,
                            'menu_name' => $menu['name'],
                            'action' => $actionKey,
                            'label' => $actionLabel
                        ];
                    }
                }
            }
        }
        return $actions;
    }

    /**
     * Kiểm tra người dùng hiện tại có quyền thao tác cụ thể hay không
     * Hỗ trợ Wildcard: '*' (toàn quyền), 'users.*' (toàn quyền module users)
     */
    public static function can(string $permission): bool {
        if (self::isSuperAdmin()) {
            return true;
        }

        $userPerms = $_SESSION['user']['permissions'] ?? [];
        if (in_array('*', $userPerms)) {
            return true;
        }

        // Kiểm tra đúng quyền cụ thể
        if (in_array($permission, $userPerms)) {
            return true;
        }

        // Kiểm tra quyền wildcard theo module (vd: 'users.*' thoả mãn 'users.create')
        $parts = explode('.', $permission);
        if (count($parts) === 2) {
            $moduleWildcard = $parts[0] . '.*';
            if (in_array($moduleWildcard, $userPerms)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra người dùng có ít nhất một trong các quyền trong mảng
     */
    public static function canAny(array $permissions): bool {
        if (self::isSuperAdmin()) return true;
        foreach ($permissions as $p) {
            if (self::can($p)) return true;
        }
        return false;
    }

    /**
     * Kiểm tra người dùng có quyền nhìn thấy và truy cập vào menu cụ thể hay không
     */
    public static function canMenu(string $menuId): bool {
        if (self::isSuperAdmin()) {
            return true;
        }

        $allowedMenus = $_SESSION['user']['menu_access'] ?? [];
        return in_array($menuId, $allowedMenus);
    }

    /**
     * Dành riêng cho Sidebar: Lọc cây menu từ JSON, chỉ trả về các menu được phép xem
     */
    /**
     * Dành riêng cho Sidebar: Lọc cây menu từ JSON, chỉ trả về các menu được phép xem theo phân hệ
     */
    public static function getVisibleMenus(string $scope = 'admin'): array {
        $catalog = self::getMenuCatalog($scope);
        $visibleGroups = [];

        foreach ($catalog as $group) {
            $menus = $group['menus'] ?? [];
            $visibleMenus = [];

            foreach ($menus as $menu) {
                if (self::canMenu($menu['id'])) {
                    $visibleMenus[] = $menu;
                }
            }

            // Chỉ hiển thị nhóm nếu có ít nhất 1 menu con được phép xem
            if (!empty($visibleMenus)) {
                $copyGroup = $group;
                $copyGroup['menus'] = $visibleMenus;
                $visibleGroups[] = $copyGroup;
            }
        }

        return $visibleGroups;
    }

    /**
     * Chặn truy cập nếu chưa đăng nhập
     */
    public static function requireLogin(): void {
        if (!self::check()) {
            $isApi = (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], '/api/')) ||
                     (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) ||
                     (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
            if ($isApi) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(401);
                echo json_encode([
                    'status' => 401,
                    'message' => 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại!',
                    'redirect' => XC_URL . '/admin/login'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            header("Location: " . XC_URL . "/admin/login");
            exit;
        }
    }

    /**
     * Chặn cứng 403 nếu không đủ quyền thao tác (dùng bảo vệ API hoặc hàm xử lý)
     */
    public static function requirePermission(string $permission): void {
        self::requireLogin();

        if (!self::can($permission)) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(403);
            echo json_encode([
                'status' => 403,
                'message' => "Từ chối truy cập: Bạn không có quyền thực hiện thao tác [{$permission}]!"
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Nạp toàn bộ vai trò, menu_access và permissions của user từ CSDL vào Session
     */
    public static function syncSession(object|array $user): void {
        global $db;
        $userId = is_object($user) ? $user->id : $user['id'];

        // Truy vấn vai trò của user trong ioc_auth_user_roles và ioc_auth_roles (lấy vai trò mới nhất)
        $db->query("SELECT r.* FROM ioc_auth_roles r 
                    JOIN ioc_auth_user_roles ur ON ur.role_id = r.id 
                    WHERE ur.user_id = '{$userId}' AND r.is_active = 1 
                    ORDER BY ur.id DESC 
                    LIMIT 1");

        $role = $db->num_row() > 0 ? $db->fetch_object(true) : null;

        $roleCode = $role ? $role->role_code : 'GUEST';
        $roleName = $role ? $role->role_name : 'Cán bộ chưa phân quyền';
        $menuAccess = ($role && !empty($role->menu_access)) ? json_decode($role->menu_access, true) : [];
        $permissions = ($role && !empty($role->permissions)) ? json_decode($role->permissions, true) : [];

        // Nếu là SUPER_ADMIN -> Cấp toàn quyền trên toàn bộ các phân hệ
        if ($roleCode === 'SUPER_ADMIN') {
            $permissions = ['*'];
            // Toàn bộ menu từ cả 3 phân hệ admin, backend, dashboard
            $menuAccess = [];
            foreach (['admin', 'backend', 'dashboard'] as $sc) {
                $catalog = self::getMenuCatalog($sc);
                foreach ($catalog as $g) {
                    foreach ($g['menus'] ?? [] as $m) {
                        $menuAccess[] = $m['id'];
                    }
                }
            }
        }

        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['user']['id'] = $userId;
        }
        $_SESSION['user']['role_code'] = $roleCode;
        $_SESSION['user']['role_name'] = $roleName;
        $_SESSION['user']['menu_access'] = is_array($menuAccess) ? $menuAccess : [];
        $_SESSION['user']['permissions'] = is_array($permissions) ? $permissions : [];
    }

    /**
     * Xuất dữ liệu quyền an toàn để nhúng vào JavaScript phía Client
     */
    public static function toJsData(): string {
        $data = [
            'user_id' => $_SESSION['user']['id'] ?? '',
            'isSuperAdmin' => self::isSuperAdmin(),
            'role_code' => $_SESSION['user']['role_code'] ?? 'GUEST',
            'role_name' => $_SESSION['user']['role_name'] ?? 'Cán bộ chưa phân quyền',
            'menu_access' => $_SESSION['user']['menu_access'] ?? [],
            'permissions' => $_SESSION['user']['permissions'] ?? []
        ];
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
