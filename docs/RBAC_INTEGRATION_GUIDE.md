# HƯỚNG DẪN TOÀN DIỆN: TRIỂN KHAI, CHUYỂN GIAO & TÙY BIẾN HỆ THỐNG PHÂN QUYỀN ĐỘNG (DYNAMIC RBAC)

> **Tài liệu bàn giao & Hướng dẫn kỹ thuật chuẩn hóa**  
> Áp dụng cho: Hệ thống phân quyền động đa phân hệ dựa trên cấu hình JSON và Database Versioning.  
> Phiên bản: `2.0 - Multi-Portal Edition`

---

## MỤC LỤC

1. [Tổng Quan Kiến Trúc & Cơ Chế Vận Hành](#phần-1-tổng-quan-kiến-trúc--cơ-chế-vận-hành)
2. [Quy Trình 5 Bước Triển Khai Sang Dự Án Mới](#phần-2-quy-trình-5-bước-triển-khai-sang-dự-án-mới)
3. [Danh Mục Chi Tiết Các Dòng Code Cần Sửa Khi Khác Biệt Tên File / CSDL](#phần-3-danh-mục-chi-tiết-các-dòng-code-cần-sửa-khi-khác-biệt)
4. [Bảng Tra Cứu Nhanh 7 Điểm Chạm (Cheatsheet)](#phần-4-bảng-tra-cứu-nhanh-7-điểm-chạm-cheatsheet)
5. [Checklist Kiểm Thử & Nghiệm Thu (Acceptance Test)](#phần-5-checklist-kiểm-thử--nghiệm-thu)

---

## PHẦN 1: TỔNG QUAN KIẾN TRÚC & CƠ CHẾ VẬN HÀNH

Hệ thống được thiết kế theo triết lý **"Zero-Code Permission Management"** (Quản lý phân quyền không cần viết lại code): Toàn bộ danh mục chức năng được định nghĩa tập trung qua JSON, tự động lưu vết lịch sử vào CSDL, và được bảo vệ nghiêm ngặt bằng cơ chế **2 Tầng (Server Guard & Client DOM Cleanup)**.

```
                  ┌───────────────────────────────────────────────────────────┐
                  │                CẤU HÌNH DANH MỤC JSON                     │
                  │   config/menus_admin.json | menus_backend.json | ...      │
                  └─────────────────────────────┬─────────────────────────────┘
                                                │ (Auto-sync khi file đổi)
                                                ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                       CƠ SỞ DỮ LIỆU                                         │
│  • ioc_auth_menu_versions: Lưu lịch sử sao lưu (scope, content_hash, version_tag, created_at)│
│  • ioc_auth_roles: Lưu vai trò, menu_access (JSON array) & permissions (JSON array)         │
│  • ioc_auth_user_roles: Bảng liên kết gán vai trò cho cán bộ/người dùng                     │
└───────────────────────────────────────────────┬─────────────────────────────────────────────┘
                                                │
                                    Auth::syncSession($user)
                                                │
                                                ▼
                                    ┌───────────────────────┐
                                    │   $_SESSION['user']   │
                                    │ • role_code           │
                                    │ • menu_access []      │
                                    │ • permissions []      │
                                    └───────────┬───────────┘
                                                │
                 ┌──────────────────────────────┴──────────────────────────────┐
                 ▼                                                             ▼
  【 TẦNG 1: SERVER-SIDE GUARD 】                               【 TẦNG 2: CLIENT-SIDE CLEANUP 】
  • Sidebar: Auth::getVisibleMenus($scope)                      • Nhúng window.__CARE_AUTH__
  • View con: if (Auth::canMenu($id)): include ...              • Chặn tab: CareAuth.canMenu(tabId)
  • API action: Auth::requirePermission('x.delete')             • Tự xóa nút khỏi DOM: data-permission
```

### 3 Đặc tính then chốt:
1. **Tự động sao lưu & sinh phiên bản (Auto Versioning)**: Mỗi lần lập trình viên hoặc admin chỉnh sửa bất kỳ file JSON nào, hàm `Auth::syncMenuHistory()` tự động phát hiện sai khác MD5 hash và **INSERT 1 bản ghi mới** vào `ioc_auth_menu_versions` kèm thời gian sửa. Nếu file JSON trên ổ cứng bị xóa nhầm, hệ thống tự động khôi phục từ CSDL.
2. **Quy tắc "Chỉ Thêm & Sửa, CẤM XÓA" (No-Delete Rule)**:
   - Trên UI quản lý vai trò có nút chọn nhanh `Thêm & Sửa (Cấm Xóa)` giúp tích chọn toàn bộ quyền `view`, `create`, `edit` nhưng bỏ qua toàn bộ quyền `delete`.
   - Phía Client: Các nút có `data-permission="*.delete"` tự động bị xóa sổ khỏi DOM (`el.remove()`).
   - Phía Server: API xóa gọi `Auth::requirePermission('*.delete')` chặn cứng trả mã `HTTP 403 Forbidden` nếu có kẻ cố tình inspect DOM hoặc gửi cURL.
3. **Ẩn triệt để View con (View Suppression)**: View của từng tab được bọc Server-side `if (Auth::canMenu('outpatient')): ... endif;`. Người dùng không có quyền sẽ hoàn toàn không nhận được một dòng mã HTML nào từ máy chủ.

---

## PHẦN 2: QUY TRÌNH 5 BƯỚC TRIỂN KHAI SANG DỰ ÁN MỚI

Khi bạn bắt đầu một dự án mới (bán hàng, kho bãi, bệnh viện, CRM...), hãy thực hiện lần lượt 5 bước sau:

### BƯỚC 1: Tạo 3 Bảng CSDL Mới
Chạy đoạn SQL sau trên MySQL / MariaDB của dự án mới:

```sql
-- 1. Bảng lưu trữ & lịch sử phiên bản JSON
CREATE TABLE IF NOT EXISTS `ioc_auth_menu_versions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `scope` VARCHAR(50) NOT NULL DEFAULT 'admin' COMMENT 'Phân hệ: admin, backend, dashboard...',
  `version_tag` VARCHAR(50) NOT NULL,
  `config_content` LONGTEXT NOT NULL,
  `content_hash` VARCHAR(64) NOT NULL,
  `created_by` VARCHAR(100) DEFAULT 'SYSTEM',
  `change_note` VARCHAR(255) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_scope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng quản lý vai trò người dùng
CREATE TABLE IF NOT EXISTS `ioc_auth_roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_code` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã vai trò viết hoa: SUPER_ADMIN, MANAGER...',
  `role_name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `menu_access` LONGTEXT NULL COMMENT 'JSON mảng menu: ["dashboard", "orders"...]',
  `permissions` LONGTEXT NULL COMMENT 'JSON mảng actions: ["orders.create", "orders.edit"...]',
  `is_system` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng gán vai trò cho tài khoản
CREATE TABLE IF NOT EXISTS `ioc_auth_user_roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` VARCHAR(50) NOT NULL COMMENT 'ID của user trong bảng users của dự án',
  `role_id` INT NOT NULL,
  `assigned_by` VARCHAR(100) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_user_role` (`user_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Khởi tạo vai trò Quản Trị Tối Cao mặc định (Toàn quyền "*")
INSERT INTO `ioc_auth_roles` (`role_code`, `role_name`, `description`, `menu_access`, `permissions`, `is_system`, `is_active`)
VALUES ('SUPER_ADMIN', 'Quản Trị Viên Tối Cao', 'Toàn quyền điều hành và phân quyền hệ thống', '["*"]', '["*"]', 1, 1);
```

---

### BƯỚC 2: Sao Chép Các File Cốt Lõi
1. Copy `application/auth.class.php` vào thư mục thư viện/core của dự án mới.
2. Copy các file `config/menus_admin.json` (hoặc `menus_backend.json` tùy phân hệ) vào thư mục `config/`.
3. Copy thư mục giao diện quản lý vai trò `template/admin/roles/` vào dự án.
4. Copy 4 hàm API (`saveRole`, `deleteRole`, `assignUserRole`, `getMenuVersions`) vào API Controller của dự án mới.

---

### BƯỚC 3: Đồng Bộ Quyền Vào Session Khi Đăng Nhập
Tại Controller xử lý đăng nhập của dự án mới, ngay sau khi kiểm tra mật khẩu thành công:

```php
// 1. Lưu thông tin user cơ bản vào Session
$_SESSION['user'] = [
    'id'           => $user['id'],
    'display_name' => $user['fullname'],
    'citizen_id'   => $user['username']
];

// 2. GỌI HÀM NÀY: Nạp toàn bộ role_code, menu_access và permissions vào $_SESSION['user']
Auth::syncSession($user);
```

---

### BƯỚC 4: Nhúng Bảo Vệ 2 Tầng Vào Giao Diện & API

#### 4.1. Sidebar Động (`sidebar.php`):
```php
<?php $visibleGroups = Auth::getVisibleMenus('admin'); ?>
<?php foreach ($visibleGroups as $group): ?>
    <div class="menu-heading"><?= htmlspecialchars($group['group_name']) ?></div>
    <?php foreach ($group['menus'] as $menu): ?>
        <a href="javascript:void(0)" onclick="switchTab('<?= $menu['id'] ?>')" class="nav-link">
            <i class="<?= $menu['icon'] ?>"></i>
            <span><?= htmlspecialchars($menu['name']) ?></span>
        </a>
    <?php endforeach; ?>
<?php endforeach; ?>
```

#### 4.2. Trang Chủ View Con (`index.php`):
```php
<?php if (Auth::canMenu('products')): ?>
    <div id="tab-products" class="tab-pane hidden">
        <?php include __DIR__ . '/products/index.php'; ?>
    </div>
<?php endif; ?>

<?php if (Auth::canMenu('roles')): ?>
    <div id="tab-roles" class="tab-pane hidden">
        <?php include __DIR__ . '/roles/index.php'; ?>
    </div>
<?php endif; ?>
```

#### 4.3. Layout Chung (`footer.php`):
Nhúng dữ liệu quyền và cài đặt client engine:
```html
<script>
  window.__CARE_AUTH__ = <?php echo Auth::toJsData(); ?>;

  const CareAuth = {
    data: window.__CARE_AUTH__ || {},
    can: function(perm) {
      if (this.data.isSuperAdmin) return true;
      const perms = this.data.permissions || [];
      if (perms.includes('*') || perms.includes(perm)) return true;
      const parts = perm.split('.');
      if (parts.length === 2 && perms.includes(parts[0] + '.*')) return true;
      return false;
    },
    canMenu: function(menuId) {
      if (this.data.isSuperAdmin) return true;
      return (this.data.menu_access || []).includes(menuId);
    },
    applyDOM: function() {
      document.querySelectorAll('[data-permission]').forEach(el => {
        if (!this.can(el.getAttribute('data-permission'))) {
          el.remove(); // Xóa hẳn khỏi DOM
        }
      });
    }
  };

  document.addEventListener('DOMContentLoaded', () => CareAuth.applyDOM());

  function switchTab(tabId) {
    if (!CareAuth.canMenu(tabId)) {
      Swal.fire({ icon: 'error', title: '403', text: 'Bạn không có quyền truy cập chức năng này!' });
      return;
    }
    // Ẩn tab cũ, hiện tab mới...
    CareAuth.applyDOM();
  }
</script>
```

#### 4.4. Gắn Thuộc Tính `data-permission` Lên Button:
```html
<!-- Nút Thêm -->
<button data-permission="products.create" onclick="openAddModal()">Thêm Hàng Hóa</button>

<!-- Nút Sửa -->
<button data-permission="products.edit" onclick="openEditModal(...)">Sửa</button>

<!-- Nút Xóa (Tự động biến mất nếu vai trò Cấm Xóa) -->
<button data-permission="products.delete" class="btn-delete" onclick="deleteItem(...)">Xóa</button>
```

#### 4.5. Bảo Vệ API Controller (Server-side):
```php
public function deleteProduct() {
    Auth::requirePermission('products.delete'); // Chặn cURL/Postman trái phép
    // Thực thi xóa...
}
```

---

### BƯỚC 5: Định Nghĩa Chức Năng Theo Nghiệp Vụ Mới
Mở file `config/menus_admin.json` và cấu hình theo nghiệp vụ dự án mới (đặt `id` khớp với `switchTab('id')` và `tab-id`):
```json
[
  {
    "group_id": "warehouse_group",
    "group_name": "QUẢN LÝ KHO HÀNG",
    "order": 1,
    "menus": [
      {
        "id": "products",
        "name": "Danh Mục Hàng Hóa",
        "route": "products",
        "icon": "ph ph-box",
        "actions": {
          "view": "Xem danh sách hàng hóa",
          "create": "Thêm hàng hóa mới",
          "edit": "Chỉnh sửa thông tin hàng",
          "delete": "Xóa mã hàng hóa",
          "export": "Xuất báo cáo tồn kho"
        }
      }
    ]
  }
]
```

---

## PHẦN 3: DANH MỤC CHÍNH XÁC TỪNG FILE VÀ TỪNG DÒNG CỤ THỂ CẦN SỬA

Dưới đây là danh mục chi tiết từng dòng code trong mã nguồn, đi kèm đoạn mã gốc và đoạn mã cần sửa tương ứng khi dự án mới có sự khác biệt về tên file JSON, cấu trúc CSDL hoặc Session:

---

### 1. FILE: `application/auth.class.php`

#### 1.1. Dòng 13, 21, 28, 36 - 37: Khóa Session định danh người dùng
* **Mục đích**: Kiểm tra trạng thái đăng nhập, lấy User ID và kiểm tra vai trò `SUPER_ADMIN`.
* **Mã gốc tại Care IOC**:
  ```php
  // Dòng 13
  public static function check(): bool {
      return isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']);
  }

  // Dòng 21
  public static function user(): ?array {
      return $_SESSION['user'] ?? null;
  }

  // Dòng 28
  public static function id(): ?string {
      return $_SESSION['user']['id'] ?? null;
  }

  // Dòng 36 - 37
  $perms = $_SESSION['user']['permissions'] ?? [];
  $roleCode = $_SESSION['user']['role_code'] ?? '';
  ```
* **Sửa khi qua dự án mới** (Ví dụ dự án mới dùng `$_SESSION['auth_user']['user_id']`):
  ```php
  public static function check(): bool {
      return isset($_SESSION['auth_user']['user_id']) && !empty($_SESSION['auth_user']['user_id']);
  }

  public static function user(): ?array {
      return $_SESSION['auth_user'] ?? null;
  }

  public static function id(): ?string {
      return $_SESSION['auth_user']['user_id'] ?? null;
  }

  $perms = $_SESSION['auth_user']['permissions'] ?? [];
  $roleCode = $_SESSION['auth_user']['role_code'] ?? '';
  ```

---

#### 1.2. Dòng 44 - 52: Tên file và đường dẫn file cấu hình JSON
* **Mục đích**: Chỉ định đường dẫn tới các file JSON định nghĩa menu cho từng phân hệ.
* **Mã gốc tại Care IOC**:
  ```php
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
  ```
* **Sửa khi qua dự án mới**:
  - *Nếu dự án mới chỉ có 1 file JSON duy nhất*:
    ```php
    public static function getJsonPath(string $scope = 'admin'): string {
        return __DIR__ . '/../config/menus.json'; // Đổi đường dẫn file JSON của bạn
    }
    ```
  - *Nếu hằng số thư mục gốc không phải `__SITE_PATH`*: Đổi thành `ROOT_PATH`, `__DIR__ . '/..'` hoặc đường dẫn tương ứng của framework dự án mới.

---

#### 1.3. Dòng 112, 118, 127: Tên bảng CSDL lưu Lịch Sử Phiên Bản (Versioning)
* **Mục đích**: Tự động so sánh hash MD5 và INSERT bản ghi mới vào bảng history mỗi lần file JSON đổi.
* **Mã gốc tại Care IOC**:
  ```php
  // Dòng 112
  $db->query("SELECT id, content_hash FROM ioc_auth_menu_versions WHERE is_active = 1 AND scope = '{$safeScope}' ORDER BY id DESC LIMIT 1");

### 1. FILE: LÕI PHÂN QUYỀN (`application/auth.class.php`)

| STT | Dòng cụ thể | Nội dung / Chức năng | Mã gốc tại Care IOC | Tùy biến khi sang dự án khác |
|:---:|:---|:---|:---|:---|
| 1 | **Dòng 14, 21, 28** | Tên mảng Session người dùng | `$_SESSION['user']['id']` | Nếu dự án mới dùng `$_SESSION['account']` hoặc `$_SESSION['auth']`, thay đổi `['user']` thành tên tương ứng |
| 2 | **Dòng 44 – 53** | Tên & đường dẫn 3 file JSON | `menus_admin.json`, `menus_backend.json`, `menus_dashboard.json` | Thay đổi tên file, thư mục hoặc số lượng phân hệ tùy dự án |
| 3 | **Dòng 112, 118, 127** | Bảng CSDL lưu Snapshot & Lịch sử Menu | `ioc_auth_menu_versions` | Đổi thành tên bảng của bạn (ví dụ `sys_menu_versions`, `tbl_menu_history`) |
| 4 | **Dòng 121 – 123** | Người tạo phiên bản trong lịch sử | `$_SESSION['user']['display_name']` & `citizen_id` | Đổi sang cột tên người dùng tương ứng (ví dụ `fullname`, `username`) |
| 5 | **Dòng 140** | Bảng CSDL để tự động hồi phục file JSON | `ioc_auth_menu_versions` | Đổi thành tên bảng snapshot CSDL của dự án mới |
| 6 | **Dòng 189, 230** | Mảng quyền và menu trong Session | `$_SESSION['user']['permissions']`, `$_SESSION['user']['menu_access']` | Đổi `['user']` nếu dùng session khác |
| 7 | **Dòng 270** | URL Redirect khi chưa đăng nhập | `header("Location: " . XC_URL . "/admin/login");` | Đổi sang hằng số URL và route đăng nhập của dự án mới (ví dụ `BASE_URL . "/login"`) |
| 8 | **Dòng 297** | Lấy User ID trong `syncSession()` | `$userId = is_object($user) ? $user->id : $user['id'];` | Đổi `id` sang tên khóa chính user (ví dụ `user_id`, `uid`) |
| 9 | **Dòng 300 – 303** | Truy vấn lấy vai trò & bảng CSDL | `FROM ioc_auth_roles r JOIN ioc_auth_user_roles ur ON ur.role_id = r.id WHERE ur.user_id = '{$userId}'` | • Đổi tên 2 bảng `ioc_auth_roles`, `ioc_auth_user_roles`<br>• **BỎ DẤU NHÁY ĐƠN** quanh `{$userId}` nếu `user_id` là kiểu số nguyên (`INT`) |
| 10 | **Dòng 327 – 333** | Lưu quyền vào Session sau khi nạp | `$_SESSION['user']['role_code'] = ...` | Đổi tên session tương ứng với dự án mới |
| 11 | **Dòng 340 – 347** | Hàm xuất JSON nhúng sang Client JS | `toJsData()` | Đổi tên các trường session nếu có thay đổi |

---

### 2. FILE: CONTROLLER XỬ LÝ ĐĂNG NHẬP & API RBAC (`controller/apiController.php`)

| STT | Dòng cụ thể | Hàm / Nghiệp vụ | Mã gốc tại Care IOC | Tùy biến khi sang dự án khác |
|:---:|:---|:---|:---|:---|
| 1 | **Dòng 2846 – 2848** | Hàm `login()` (Điểm kích hoạt RBAC) | `Auth::syncSession($user);` | Đặt ngay sau khi verify mật khẩu thành công và trước khi chuyển trang |
| 2 | **Dòng 2896 – 2901** | Hàm `getRoles()` (Danh sách vai trò) | `SELECT r.*, COUNT(ur.id) as user_count FROM ioc_auth_roles r LEFT JOIN ioc_auth_user_roles ur ...` | Đổi tên bảng `ioc_auth_roles` và `ioc_auth_user_roles` sang tên bảng dự án mới |
| 3 | **Dòng 2931 – 2945** | Hàm `saveRole()` (Thêm / Sửa vai trò) | `UPDATE ioc_auth_roles ...` và `INSERT INTO ioc_auth_roles ...` | Đổi tên bảng roles của bạn |
| 4 | **Dòng 2961 – 2970** | Hàm `deleteRole()` (Xóa vai trò) | `DELETE FROM ioc_auth_user_roles WHERE role_id = {$id}; DELETE FROM ioc_auth_roles WHERE id = {$id};` | Đổi tên bảng roles và user_roles của bạn |
| 5 | **Dòng 2990 – 2996** | Hàm `assignUserRole()` (Gán quyền) | `INSERT INTO ioc_auth_user_roles (user_id, role_id, ...) VALUES ('{$safe_uid}', {$role_id}, ...)` | Đổi tên bảng; bỏ dấu nháy đơn nếu `user_id` là kiểu số nguyên |
| 6 | **Dòng 3000 – 3013** | Hàm `getMenuCatalogApi()` (Lấy cây menu) | `Auth::getAllCatalogs()`, `Auth::getMenuCatalog($scope)` | Giữ nguyên hoặc thêm scope nếu dự án có phân hệ mới |
| 7 | **Dòng 3025 – 3028** | Hàm `getMenuVersions()` (Lịch sử menu) | `FROM ioc_auth_menu_versions WHERE scope = ...` | Đổi tên bảng lịch sử snapshot |

---

### 3. FILE: GIAO DIỆN VIEW & LAYOUT

| STT | File | Dòng cụ thể | Mã gốc tại Care IOC | Tùy biến khi sang dự án khác |
|:---:|:---|:---|:---|:---|
| 1 | `template/admin/layouts/sidebar.php` | **Dòng 23** | `$visibleGroups = Auth::getVisibleMenus('admin');` | Truyền đúng phân hệ: `'admin'`, `'backend'`, hoặc `'dashboard'` |
| 2 | `template/admin/layouts/footer.php` | **Dòng 92** | `window.__CARE_AUTH__ = <?php echo Auth::toJsData(); ?>;` | Nhúng vào chân trang master layout để client JS có quyền |
| 3 | `template/admin/roles/index.php` | Toàn bộ file | Quản lý vai trò (Tabs 3 phân hệ, Modal Version History) | Copy sang dự án mới, đổi đường dẫn CSS/JS và endpoint API |
| 4 | `3 File cấu hình JSON` | `config/menus_*.json` | Danh mục menu, route, icons, actions cụ thể của dự án | Thay bằng danh mục chức năng và actions thực tế của dự án mới |
              VALUES ('{$safe_name}', '{$safe_code}', '{$safe_desc}', '{$safe_menu}', '{$safe_perms}', 0, 1)");
  ```
* **Sửa khi qua dự án mới**: Đổi `ioc_auth_roles` thành tên bảng vai trò của bạn (ví dụ `tbl_roles`).

---

#### 2.2. Dòng 2961 - 2969: Trong hàm `deleteRole()`
* **Mục đích**: Xóa vai trò và các bản ghi gán vai trò của cán bộ.
* **Mã gốc tại Care IOC**:
  ```php
  $db->query("SELECT is_system FROM ioc_auth_roles WHERE id = {$id} LIMIT 1");
  ...
  $db->query("DELETE FROM ioc_auth_user_roles WHERE role_id = {$id}");
  $db->query("DELETE FROM ioc_auth_roles WHERE id = {$id}");
  ```
* **Sửa khi qua dự án mới**: Đổi `ioc_auth_roles` và `ioc_auth_user_roles` khớp với CSDL mới.

---

#### 2.3. Dòng 2991 - 2995: Trong hàm `assignUserRole()`
* **Mục đích**: Gán vai trò cho tài khoản người dùng và lưu vết người phân quyền.
* **Mã gốc tại Care IOC**:
  ```php
  // Dòng 2991: Lấy tên người đang thao tác phân quyền từ session
  $currentUserName = $_SESSION['user']['display_name'] ?? 'ADMIN';

  // Dòng 2993 - 2995: Lưu liên kết User - Role
  $db->query("INSERT INTO ioc_auth_user_roles (user_id, role_id, assigned_by) 
              VALUES ('{$safe_uid}', {$role_id}, '{$currentUserName}') 
              ON DUPLICATE KEY UPDATE role_id = {$role_id}, assigned_by = '{$currentUserName}', created_at = NOW()");
  ```
* **Sửa khi qua dự án mới**:
  - Đổi `$_SESSION['user']['display_name']` theo trường tên người dùng của bạn (ví dụ `$_SESSION['auth_user']['fullname']`).
  - Đổi tên bảng `ioc_auth_user_roles` thành tên bảng liên kết user-role của bạn.
  - Nếu `user_id` là `INT`: Bỏ dấu nháy đơn `'{$safe_uid}'` thành `{$safe_uid}`.

---

#### 2.4. Dòng 3018: Trong hàm `getMenuVersions()`
* **Mã gốc tại Care IOC**:
  ```php
  $db->query("SELECT id, scope, version_tag, content_hash, created_by, change_note, is_active, created_at 
              FROM ioc_auth_menu_versions 
              {$where}
              ORDER BY id DESC LIMIT 30");
  ```
* **Sửa khi qua dự án mới**: Đổi `ioc_auth_menu_versions` thành tên bảng history của bạn.

---

### 3. FILE: GIAO DIỆN QUẢN LÝ VAI TRÒ (`template/admin/roles/index.php`)

#### 3.1. Dòng 7 - 11: Nạp danh sách vai trò kèm số lượng user
* **Mã gốc tại Care IOC**:
  ```php
  $db->query("SELECT r.*, COUNT(ur.id) as user_count 
              FROM ioc_auth_roles r 
              LEFT JOIN ioc_auth_user_roles ur ON ur.role_id = r.id 
              GROUP BY r.id 
              ORDER BY r.is_system DESC, r.id ASC");
  ```
* **Sửa khi qua dự án mới**: Đổi `ioc_auth_roles` và `ioc_auth_user_roles` theo bảng mới.

---

#### 3.2. Dòng 389, 431, 494: Đường dẫn gọi API trong JavaScript
* **Mã gốc tại Care IOC**:
  ```javascript
  // Dòng 389: Lưu vai trò
  fetch('<?php echo XC_URL; ?>/api/saveRole', { ... })

  // Dòng 431: Xóa vai trò
  fetch('<?php echo XC_URL; ?>/api/deleteRole', { ... })

  // Dòng 494: Lấy lịch sử phiên bản JSON
  const url = '<?php echo XC_URL; ?>/api/getMenuVersions' + ...;
  ```
* **Sửa khi qua dự án mới**: Đổi hằng số URL `XC_URL` thành hằng số URL của dự án mới (ví dụ `BASE_URL` hoặc URL tương đối `/api/...`).

---

### 4. FILE: FORM GÁN VAI TRÒ CÁN BỘ (Ví dụ: `users/modals.php`)

* **Vị trí nạp danh sách vai trò cho thẻ `<select>`**:
  ```php
  // Đổi tên bảng ioc_auth_roles nếu dự án mới có tên khác
  $db->query("SELECT id, role_name, role_code FROM tbl_roles WHERE is_active = 1");
  $allRoles = $db->fetch_object();
  ```
* **Vị trí Submit AJAX (Dòng 321)**:
  ```javascript
  // Đổi hằng số URL khi gọi API gán vai trò
  fetch('<?= BASE_URL ?>/api/assignUserRole', {
      method: 'POST',
      body: new URLSearchParams({ user_id: id, role_id: roleId })
  });
  ```

---

## PHẦN 4: BẢNG TRA CỨU NHANH 7 ĐIỂM CHẠM (CHEATSHEET)

Dán bảng này vào đầu file README của dự án mới để rà soát khi cài đặt:

| STT | Điểm chạm (Touchpoint) | Mã tại Care IOC | Cần thay thế ở dự án mới | File chứa |
| :---: | :--- | :--- | :--- | :--- |
| **1** | **Tên file JSON** | `menus_admin.json` | Đường dẫn file JSON của bạn | `application/auth.class.php:44` |
| **2** | **Bảng Lịch sử JSON** | `ioc_auth_menu_versions` | Tên bảng history của bạn | `auth.class.php`, `apiController.php` |
| **3** | **Bảng Danh mục Role**| `ioc_auth_roles` | Tên bảng roles của bạn | `auth.class.php`, `apiController.php`, `roles/index.php` |
| **4** | **Bảng Gán Role-User**| `ioc_auth_user_roles` | Tên bảng gán quyền của bạn | `auth.class.php`, `apiController.php`, `roles/index.php` |
| **5** | **Khóa Session User** | `$_SESSION['user']` | Khóa session user của bạn | `auth.class.php`, `apiController.php` |
| **6** | **Hằng số Domain URL**| `XC_URL` | `BASE_URL` hoặc URL gốc | `auth.class.php`, `roles/index.php` |
| **7** | **Đường dẫn thư mục** | `__SITE_PATH` | `__DIR__` hoặc `ROOT_PATH` | `auth.class.php` |

---

## PHẦN 5: CHECKLIST KIỂM THỬ & NGHIỆM THU

Sau khi hoàn tất cấu hình qua dự án mới, hãy chạy checklist 6 bước sau để đảm bảo hệ thống an toàn 100%:

- [ ] **1. CSDL**: Bảng lưu lịch sử tự động có bản ghi đầu tiên ngay sau khi nạp trang lần đầu.
- [ ] **2. File JSON**: Sửa thử 1 chữ trong file JSON, reload trang và xác nhận CSDL tự sinh 1 hàng mới với MD5 hash khác.
- [ ] **3. Super Admin**: Đăng nhập tài khoản có role `SUPER_ADMIN` hoặc quyền `*`, xác nhận thấy toàn bộ menu và có thể thực hiện mọi thao tác.
- [ ] **4. Vai trò giới hạn**: Tạo một vai trò chỉ xem + thêm/sửa, **bỏ tích quyền xóa** và gán cho một tài khoản thường.
- [ ] **5. Client DOM Cleanup**: Đăng nhập tài khoản thường, xác nhận nút "Xóa" trên giao diện hoàn toàn biến mất khỏi DOM.
- [ ] **6. Server 403 Guard**: Dùng Postman hoặc cURL gửi request gọi API xóa với tài khoản thường, xác nhận máy chủ trả về đúng mã lỗi `HTTP 403 Forbidden`.
