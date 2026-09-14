<?php
/**
 * Care IOC - Phân Hệ Quản Lý Nhóm Quyền & Phân Quyền Hệ Thống
 * Thư mục: template/admin/roles/index.php
 */
global $db;
$db->query("SELECT r.*, COUNT(ur.id) as user_count 
            FROM ioc_auth_roles r 
            LEFT JOIN ioc_auth_user_roles ur ON ur.role_id = r.id 
            GROUP BY r.id 
            ORDER BY r.is_system DESC, r.id ASC");
$rolesList = $db->fetch_object() ?: [];
$allCatalogs = Auth::getAllCatalogs();
?>

<!-- ==================== TAB QUẢN LÝ NHÓM QUYỀN HỆ THỐNG ==================== -->
<div id="tab-roles" class="tab-content hidden space-y-6">

  <!-- Header & Toolbar -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-blue-100 shadow-sm">
    <div class="space-y-1">
      <div class="flex items-center gap-2.5">
        <span class="p-2.5 rounded-xl bg-brand-50 text-brand-600 font-bold">
          <i class="ph-bold ph-shield-check text-2xl"></i>
        </span>
        <div>
          <h2 class="text-lg font-bold text-slate-800">Quản Lý Nhóm Quyền Hệ Thống</h2>
          <p class="text-xs text-slate-500">Khai báo nhóm quyền thuộc từng phân hệ (Admin, Backend, Dashboard) và cấu hình danh sách menu chi tiết.</p>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-2.5 flex-wrap">
      <button onclick="openMenuVersionsModal()" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 hover:bg-blue-50 hover:text-brand-600 rounded-xl transition shadow-sm">
        <i class="ph-bold ph-clock-counter-clockwise text-base text-brand-500"></i>
        <span>Lịch Sử Sao Lưu JSON</span>
      </button>

      <button data-permission="roles.create" onclick="openAddRoleModal()" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-500/20 transition active:scale-95">
        <i class="ph-bold ph-plus-circle text-base"></i>
        <span>Thêm Mới Nhóm Quyền</span>
      </button>
    </div>
  </div>

  <!-- Bộ lọc Phân hệ (Filter Scope Tabs) -->
  <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
    <button type="button" onclick="filterRolesByScope('all', this)" class="role-filter-tab px-4 py-2 text-xs font-bold rounded-xl bg-brand-600 text-white shadow-sm transition">
      Tất cả nhóm quyền (<span id="count-all"><?php echo count($rolesList); ?></span>)
    </button>
    <button type="button" onclick="filterRolesByScope('admin', this)" class="role-filter-tab px-4 py-2 text-xs font-semibold rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-brand-600 transition">
      <i class="ph-bold ph-stethoscope mr-1 text-brand-600"></i> Phân Hệ Admin (Y Tế)
    </button>
    <button type="button" onclick="filterRolesByScope('backend', this)" class="role-filter-tab px-4 py-2 text-xs font-semibold rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition">
      <i class="ph-bold ph-package mr-1 text-emerald-600"></i> Phân Hệ Backend (Vận Hành)
    </button>
    <button type="button" onclick="filterRolesByScope('dashboard', this)" class="role-filter-tab px-4 py-2 text-xs font-semibold rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-purple-50 hover:text-purple-700 transition">
      <i class="ph-bold ph-chart-line-up mr-1 text-purple-600"></i> Phân Hệ Dashboard (Điều Hành)
    </button>
  </div>

  <!-- Danh sách các Nhóm Quyền (Roles Cards) -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="roles-grid-container">
    <?php foreach ($rolesList as $r): ?>
      <?php
        $menus = json_decode($r->menu_access, true) ?: [];
        $perms = json_decode($r->permissions, true) ?: [];
        $isAll = in_array('*', $perms) || $r->role_code === 'SUPER_ADMIN';
        $scope = !empty($r->scope) ? strtolower(trim($r->scope)) : 'admin';

        // Định dạng màu sắc badge phân hệ
        $scopeBadge = [
          'admin' => ['label' => 'Phân Hệ Admin', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'icon' => 'ph-stethoscope'],
          'backend' => ['label' => 'Phân Hệ Backend', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon' => 'ph-package'],
          'dashboard' => ['label' => 'Phân Hệ Dashboard', 'class' => 'bg-purple-50 text-purple-700 border-purple-200', 'icon' => 'ph-chart-line-up']
        ][$scope] ?? ['label' => 'Phân Hệ Admin', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'icon' => 'ph-stethoscope'];
      ?>
      <div class="role-card bg-white rounded-2xl border border-blue-100/80 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group" data-scope="<?php echo $scope; ?>">
        <div>
          <!-- Header card -->
          <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl <?php echo $r->is_system ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-brand-50 text-brand-600 border border-brand-100'; ?> flex items-center justify-center font-bold text-lg shrink-0">
                <i class="ph-bold <?php echo $r->is_system ? 'ph-crown' : 'ph-users-three'; ?>"></i>
              </div>
              <div>
                <h3 class="font-bold text-sm text-slate-800 group-hover:text-brand-600 transition"><?php echo htmlspecialchars($r->role_name); ?></h3>
                <span class="inline-block font-mono text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md mt-0.5"><?php echo htmlspecialchars($r->role_code); ?></span>
              </div>
            </div>

            <!-- Badge Phân Hệ -->
            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full border shrink-0 flex items-center gap-1 <?php echo $scopeBadge['class']; ?>">
              <i class="ph-bold <?php echo $scopeBadge['icon']; ?>"></i>
              <?php echo $scopeBadge['label']; ?>
            </span>
          </div>

          <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
            <?php echo htmlspecialchars($r->description ?: 'Chưa có mô tả cho nhóm quyền này.'); ?>
          </p>

          <!-- Badges tóm tắt quyền -->
          <div class="space-y-2 border-t border-slate-100 pt-3 text-xs">
            <div class="flex items-center justify-between text-slate-600">
              <span class="flex items-center gap-1.5 text-slate-500"><i class="ph ph-users text-sm"></i>Cán bộ áp dụng:</span>
              <button 
                type="button" 
                data-permission="roles.edit" 
                onclick='openAssignUsersModal(<?php echo htmlspecialchars(json_encode($r), ENT_QUOTES, "UTF-8"); ?>)' 
                class="text-brand-600 font-bold hover:underline flex items-center gap-1 text-xs"
                title="Xem & Gán cán bộ vào nhóm quyền này"
              >
                <span><?php echo (int)$r->user_count; ?> người</span>
                <i class="ph-bold ph-pencil-simple text-[11px]"></i>
              </button>
            </div>

            <div class="flex items-center justify-between text-slate-600">
              <span class="flex items-center gap-1.5 text-slate-500"><i class="ph ph-sidebar-simple text-sm"></i>Menu hiển thị:</span>
              <strong class="text-slate-800"><?php echo $isAll ? 'Toàn bộ menu' : count($menus) . ' menu'; ?></strong>
            </div>

            <div class="flex items-center justify-between text-slate-600">
              <span class="flex items-center gap-1.5 text-slate-500"><i class="ph ph-lock-key text-sm"></i>Quyền thao tác:</span>
              <strong class="<?php echo $isAll ? 'text-amber-600' : 'text-slate-800'; ?>">
                <?php echo $isAll ? 'Toàn quyền (*)' : count($perms) . ' quyền chi tiết'; ?>
              </strong>
            </div>
          </div>
        </div>

        <!-- Footer Actions: Tách riêng Sửa thông tin & Cấu hình nhóm quyền & Gán cán bộ -->
        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
          <!-- Nút Sửa thông tin cơ bản -->
          <button 
            data-permission="roles.edit"
            onclick='openEditRoleInfoModal(<?php echo htmlspecialchars(json_encode($r), ENT_QUOTES, "UTF-8"); ?>)' 
            class="p-2 text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition flex items-center gap-1"
            title="Sửa Tên & Phân hệ"
          >
            <i class="ph-bold ph-pencil-simple text-base"></i>
            <span class="hidden sm:inline text-[11px]">Sửa thông tin</span>
          </button>

          <div class="flex items-center gap-1.5">
            <!-- Nút GÁN CÁN BỘ -->
            <button 
              data-permission="roles.edit"
              onclick='openAssignUsersModal(<?php echo htmlspecialchars(json_encode($r), ENT_QUOTES, "UTF-8"); ?>)' 
              class="px-2.5 py-1.5 text-xs font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-xl transition flex items-center gap-1 border border-brand-200/60 active:scale-95"
              title="Gán cán bộ vào nhóm quyền này"
            >
              <i class="ph-bold ph-user-plus text-sm"></i>
              <span class="hidden md:inline">Gán cán bộ</span>
            </button>

            <!-- Nút CẤU HÌNH NHÓM QUYỀN (Hiện danh sách menu) -->
            <button 
              data-permission="roles.edit"
              onclick='openConfigRolePermissionsModal(<?php echo htmlspecialchars(json_encode($r), ENT_QUOTES, "UTF-8"); ?>)' 
              class="px-3 py-1.5 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition flex items-center gap-1.5 shadow-sm shadow-brand-500/20 active:scale-95"
            >
              <i class="ph-bold ph-sliders-horizontal text-sm"></i>
              <span>Cấu hình quyền</span>
            </button>

            <?php if (!$r->is_system): ?>
              <button 
                data-permission="roles.delete"
                onclick="deleteRoleAction(<?php echo (int)$r->id; ?>, '<?php echo htmlspecialchars(addslashes($r->role_name)); ?>')" 
                class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition" 
                title="Xóa nhóm quyền"
              >
                <i class="ph-bold ph-trash text-base"></i>
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<!-- ==================== MODAL BƯỚC 1: THÊM / SỬA THÔNG TIN NHÓM QUYỀN ==================== -->
<div id="role-info-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
  <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-blue-100 overflow-hidden flex flex-col">
    
    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50/70 to-slate-50">
      <div class="flex items-center gap-2.5 text-brand-700">
        <i class="ph-bold ph-users-three text-xl"></i>
        <h3 id="role-info-modal-title" class="font-bold text-base text-slate-800">Thêm Mới Nhóm Quyền</h3>
      </div>
      <button onclick="closeRoleInfoModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
        <i class="ph ph-x text-lg"></i>
      </button>
    </div>

    <!-- Modal Form -->
    <form id="form-role-info" onsubmit="handleRoleInfoSubmit(event)" class="p-6 space-y-4">
      <input type="hidden" name="id" id="role-info-id" value="0">

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Tên nhóm quyền <span class="text-rose-500">*</span></label>
        <input type="text" name="role_name" id="role-info-name" required placeholder="VD: Bác Sĩ Điều Trị Ngoại Trú" class="w-full px-3.5 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Mã nhóm quyền (Viết hoa, không dấu) <span class="text-rose-500">*</span></label>
        <input type="text" name="role_code" id="role-info-code" required placeholder="VD: BAC_SI_NGOAI_TRU" class="w-full px-3.5 py-2.5 text-xs font-mono uppercase border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
      </div>

      <!-- Chọn Phân Hệ Trực Thuộc (Scope) -->
      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nhóm quyền thuộc phân hệ <span class="text-rose-500">*</span></label>
        <div class="grid grid-cols-3 gap-2.5" id="scope-selector">
          <label class="flex flex-col items-center justify-center p-3 border-2 border-brand-500 bg-blue-50/50 rounded-2xl cursor-pointer transition select-none text-center scope-option" data-scope="admin">
            <input type="radio" name="scope" value="admin" checked class="hidden">
            <i class="ph-bold ph-stethoscope text-xl text-brand-600 mb-1"></i>
            <span class="text-xs font-bold text-brand-800">Admin</span>
            <span class="text-[10px] text-slate-500">Quản trị Y Tế</span>
          </label>

          <label class="flex flex-col items-center justify-center p-3 border-2 border-slate-200 hover:border-slate-300 rounded-2xl cursor-pointer transition select-none text-center scope-option" data-scope="backend">
            <input type="radio" name="scope" value="backend" class="hidden">
            <i class="ph-bold ph-package text-xl text-slate-500 mb-1"></i>
            <span class="text-xs font-bold text-slate-700">Backend</span>
            <span class="text-[10px] text-slate-500">Vận hành CRM</span>
          </label>

          <label class="flex flex-col items-center justify-center p-3 border-2 border-slate-200 hover:border-slate-300 rounded-2xl cursor-pointer transition select-none text-center scope-option" data-scope="dashboard">
            <input type="radio" name="scope" value="dashboard" class="hidden">
            <i class="ph-bold ph-chart-line-up text-xl text-slate-500 mb-1"></i>
            <span class="text-xs font-bold text-slate-700">Dashboard</span>
            <span class="text-[10px] text-slate-500">Màn hình lớn</span>
          </label>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Mô tả chức trách & nhiệm vụ</label>
        <textarea name="description" id="role-info-desc" rows="2" placeholder="Ghi chú trách nhiệm chuyên môn của nhóm quyền này..." class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500"></textarea>
      </div>

      <!-- Footer Buttons -->
      <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
        <button type="button" onclick="closeRoleInfoModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
          Hủy bỏ
        </button>
        <button type="submit" class="px-5 py-2.5 text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-500/20 transition active:scale-95">
          Lưu Nhóm Quyền
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ==================== MODAL BƯỚC 2: CẤU HÌNH NHÓM QUYỀN (HIỂN THỊ DANH SÁCH MENU) ==================== -->
<div id="role-permissions-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
  <div class="bg-white rounded-3xl w-full max-w-3xl shadow-2xl border border-blue-100 overflow-hidden flex flex-col max-h-[90vh]">
    
    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50/70 to-slate-50">
      <div class="flex items-center gap-3">
        <span class="p-2 bg-brand-50 text-brand-600 rounded-xl">
          <i class="ph-bold ph-sliders-horizontal text-xl"></i>
        </span>
        <div>
          <h3 id="role-perm-modal-title" class="font-bold text-base text-slate-800">Cấu Hình Danh Sách Menu & Quyền</h3>
          <p id="role-perm-modal-subtitle" class="text-xs text-slate-500">Phân hệ: <span class="font-bold text-brand-600 uppercase" id="role-perm-scope-name">Admin</span> (Nạp từ <code class="font-mono text-[10px] bg-slate-100 px-1 py-0.5 rounded" id="role-perm-json-file">menus_admin.json</code>)</p>
        </div>
      </div>
      <button onclick="closeRolePermissionsModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
        <i class="ph ph-x text-lg"></i>
      </button>
    </div>

    <!-- Body: Toolbar chọn nhanh & Cây Menu của ĐÚNG phân hệ đó -->
    <form id="form-role-permissions" onsubmit="handleRolePermissionsSubmit(event)" class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar">
      <input type="hidden" name="id" id="role-perm-id" value="0">

      <!-- Thanh công cụ chọn nhanh -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-blue-50/50 p-3 rounded-2xl border border-blue-100/80">
        <div class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
          <i class="ph-bold ph-lightning text-amber-500"></i>
          <span>Thao tác phân quyền nhanh:</span>
        </div>
        <div class="flex items-center gap-1.5 flex-wrap">
          <button type="button" onclick="quickSelectPerms('all')" class="text-[11px] px-3 py-1 rounded-lg bg-blue-100 text-brand-700 font-semibold hover:bg-blue-200 transition">
            Toàn quyền (*)
          </button>
          <button type="button" onclick="quickSelectPerms('no-delete')" class="text-[11px] px-3 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-semibold hover:bg-emerald-200 transition" title="Chỉ thêm và sửa, loại trừ toàn bộ quyền xóa">
            <i class="ph-bold ph-shield-plus mr-0.5"></i>Thêm & Sửa (Cấm Xóa)
          </button>
          <button type="button" onclick="quickSelectPerms('view-only')" class="text-[11px] px-3 py-1 rounded-lg bg-amber-100 text-amber-800 font-semibold hover:bg-amber-200 transition">
            Chỉ xem
          </button>
          <button type="button" onclick="quickSelectPerms('none')" class="text-[11px] px-3 py-1 rounded-lg bg-slate-200 text-slate-700 font-semibold hover:bg-slate-300 transition">
            Bỏ chọn
          </button>
        </div>
      </div>

      <!-- Container Danh sách Menu động (Render theo Scope) -->
      <div id="role-perm-tree-container" class="space-y-4">
        <!-- JS sẽ inject cây menu của đúng scope vào đây -->
      </div>

      <!-- Footer Buttons -->
      <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
        <div class="text-[11px] text-slate-400">
          * Các thay đổi phân quyền sẽ có hiệu lực ngay khi cán bộ đăng nhập lại.
        </div>
        <div class="flex items-center gap-2">
          <button type="button" onclick="closeRolePermissionsModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
            Hủy bỏ
          </button>
          <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-500/20 transition active:scale-95">
            Lưu Cấu Hình Nhóm Quyền
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ==================== MODAL LỊCH SỬ SAO LƯU PHIÊN BẢN JSON ==================== -->
<div id="versions-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
  <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-blue-100 overflow-hidden flex flex-col max-h-[85vh]">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
      <div class="flex items-center gap-2 text-brand-700">
        <i class="ph-bold ph-clock-counter-clockwise text-xl"></i>
        <h3 class="font-bold text-base text-slate-800">Lịch Sử Phiên Bản Cấu Hình Menu (Backup)</h3>
      </div>
      <button onclick="closeMenuVersionsModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
        <i class="ph ph-x text-lg"></i>
      </button>
    </div>

    <div class="p-6 overflow-y-auto no-scrollbar space-y-3">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <p class="text-xs text-slate-500">Lịch sử sao lưu tự động các file cấu hình <code class="font-mono text-brand-600 font-bold">menus_*.json</code> vào CSDL:</p>
        
        <!-- Filter Scope -->
        <div class="flex items-center gap-1 text-[11px]">
          <button type="button" onclick="loadVersionsData('')" class="version-filter-btn px-2.5 py-1 rounded-lg bg-brand-600 text-white font-bold transition" id="vbtn-all">Tất cả</button>
          <button type="button" onclick="loadVersionsData('admin')" class="version-filter-btn px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition" id="vbtn-admin">Admin</button>
          <button type="button" onclick="loadVersionsData('backend')" class="version-filter-btn px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition" id="vbtn-backend">Backend</button>
          <button type="button" onclick="loadVersionsData('dashboard')" class="version-filter-btn px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition" id="vbtn-dashboard">Dashboard</button>
        </div>
      </div>
      
      <div id="versions-table-container" class="border border-slate-200 rounded-2xl overflow-hidden text-xs">
        <div class="p-6 text-center text-slate-400">Đang tải lịch sử phiên bản...</div>
      </div>
    </div>

    <div class="px-6 py-3 border-t border-slate-100 bg-slate-50 flex justify-end">
      <button onclick="closeMenuVersionsModal()" class="px-4 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200 rounded-xl transition">
        Đóng
      </button>
    </div>
  </div>
</div>

<!-- ==================== JAVASCRIPT XỬ LÝ NGHIỆP VỤ PHÂN QUYỀN 2 BƯỚC ==================== -->
<script>
  // Dữ liệu danh mục menu của 3 phân hệ được nạp sẵn từ PHP
  const ALL_CATALOGS = <?php echo json_encode($allCatalogs, JSON_UNESCAPED_UNICODE); ?>;

  // Lọc danh sách nhóm quyền theo phân hệ (Admin, Backend, Dashboard)
  function filterRolesByScope(scope, btn) {
    document.querySelectorAll('.role-filter-tab').forEach(b => {
      b.className = 'role-filter-tab px-4 py-2 text-xs font-semibold rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition';
    });
    btn.className = 'role-filter-tab px-4 py-2 text-xs font-bold rounded-xl bg-brand-600 text-white shadow-sm transition';

    document.querySelectorAll('.role-card').forEach(card => {
      const cardScope = card.getAttribute('data-scope');
      if (scope === 'all' || cardScope === scope) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  }

  // Bắt sự kiện click chọn Scope trên Form Thêm/Sửa Nhóm Quyền
  document.querySelectorAll('#scope-selector .scope-option').forEach(opt => {
    opt.addEventListener('click', function() {
      document.querySelectorAll('#scope-selector .scope-option').forEach(o => {
        o.className = 'flex flex-col items-center justify-center p-3 border-2 border-slate-200 hover:border-slate-300 rounded-2xl cursor-pointer transition select-none text-center scope-option';
        const icon = o.querySelector('i');
        if (icon) icon.className = icon.className.replace('text-brand-600', 'text-slate-500');
        const title = o.querySelector('.font-bold');
        if (title) title.className = 'text-xs font-bold text-slate-700';
      });

      this.className = 'flex flex-col items-center justify-center p-3 border-2 border-brand-500 bg-blue-50/50 rounded-2xl cursor-pointer transition select-none text-center scope-option';
      const icon = this.querySelector('i');
      if (icon) icon.className = icon.className.replace('text-slate-500', 'text-brand-600');
      const title = this.querySelector('.font-bold');
      if (title) title.className = 'text-xs font-bold text-brand-800';
      this.querySelector('input[type="radio"]').checked = true;
    });
  });

  // ==================== BƯỚC 1: MODAL THÊM / SỬA THÔNG TIN NHÓM QUYỀN ====================
  function openAddRoleModal() {
    document.getElementById('role-info-modal-title').innerText = 'Thêm Mới Nhóm Quyền Hệ Thống';
    document.getElementById('role-info-id').value = '0';
    document.getElementById('role-info-name').value = '';
    document.getElementById('role-info-code').value = '';
    document.getElementById('role-info-code').readOnly = false;
    document.getElementById('role-info-desc').value = '';

    // Mặc định chọn Admin
    const adminOpt = document.querySelector('#scope-selector .scope-option[data-scope="admin"]');
    if (adminOpt) adminOpt.click();

    document.getElementById('role-info-modal').classList.remove('hidden');
  }

  function openEditRoleInfoModal(role) {
    document.getElementById('role-info-modal-title').innerText = 'Chỉnh Sửa Nhóm Quyền: ' + role.role_name;
    document.getElementById('role-info-id').value = role.id;
    document.getElementById('role-info-name').value = role.role_name;
    document.getElementById('role-info-code').value = role.role_code;
    document.getElementById('role-info-code').readOnly = (role.is_system == 1);
    document.getElementById('role-info-desc').value = role.description || '';

    // Chọn đúng Scope
    const scope = (role.scope || 'admin').toLowerCase();
    const opt = document.querySelector(`#scope-selector .scope-option[data-scope="${scope}"]`) || document.querySelector('#scope-selector .scope-option[data-scope="admin"]');
    if (opt) opt.click();

    document.getElementById('role-info-modal').classList.remove('hidden');
  }

  function closeRoleInfoModal() {
    document.getElementById('role-info-modal').classList.add('hidden');
  }

  // Submit Bước 1: Lưu thông tin nhóm quyền
  function handleRoleInfoSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('form-role-info');
    const formData = new FormData(form);

    Swal.fire({
      title: 'Đang lưu thông tin nhóm quyền...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    fetch('<?php echo XC_URL; ?>/api/saveRole', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams(formData)
    })
    .then(r => {
      return r.json().catch(() => {
        throw new Error('Máy chủ phản hồi định dạng không hợp lệ.');
      });
    })
    .then(res => {
      if (res.status === 200) {
        Swal.fire({
          icon: 'success',
          title: 'Thành công',
          text: res.message,
          timer: 1200,
          showConfirmButton: false
        }).then(() => {
          try { localStorage.setItem('careioc_active_tab', 'roles'); } catch(e) {}
          location.reload();
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Lỗi', text: res.message });
      }
    })
    .catch((err) => {
      console.error('Save Role Error:', err);
      Swal.fire({ icon: 'error', title: 'Lỗi', text: err.message || 'Không thể kết nối máy chủ API.' });
    });
  }

  // ==================== BƯỚC 2: CẤU HÌNH NHÓM QUYỀN (HIỂN THỊ DANH SÁCH MENU) ====================
  function openConfigRolePermissionsModal(role) {
    const scope = (role.scope || 'admin').toLowerCase();
    const scopeNameMap = {
      'admin': 'Admin (Quản Trị Y Tế)',
      'backend': 'Backend (Quản Lý & Vận Hành)',
      'dashboard': 'Dashboard (Trung Tâm Điều Hành IOC)'
    };

    document.getElementById('role-perm-id').value = role.id;
    document.getElementById('role-perm-modal-title').innerText = 'Cấu Hình Nhóm Quyền: ' + role.role_name;
    document.getElementById('role-perm-scope-name').innerText = scopeNameMap[scope] || scope;
    document.getElementById('role-perm-json-file').innerText = `menus_${scope}.json`;

    // Parse quyền hiện có của role
    let currentMenus = [];
    let currentPerms = [];
    try { currentMenus = JSON.parse(role.menu_access) || []; } catch(e) {}
    try { currentPerms = JSON.parse(role.permissions) || []; } catch(e) {}
    const isAll = currentPerms.includes('*') || role.role_code === 'SUPER_ADMIN';

    // Nạp ĐÚNG danh sách menu của phân hệ đó từ ALL_CATALOGS
    const catalogData = ALL_CATALOGS[scope] ? ALL_CATALOGS[scope].menus : [];
    renderMenuTreeForScope(catalogData, currentMenus, currentPerms, isAll);

    document.getElementById('role-permissions-modal').classList.remove('hidden');
  }

  function closeRolePermissionsModal() {
    document.getElementById('role-permissions-modal').classList.add('hidden');
  }

  // Render HTML danh sách menu cho phân hệ tương ứng
  function renderMenuTreeForScope(groups, currentMenus, currentPerms, isAll) {
    const container = document.getElementById('role-perm-tree-container');
    if (!groups || groups.length === 0) {
      container.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs">Chưa có danh mục menu nào cho phân hệ này trong file JSON.</div>';
      return;
    }

    let html = '';
    groups.forEach(group => {
      html += `
        <div class="space-y-2">
          <div class="text-[11px] font-bold uppercase tracking-wider text-brand-700 bg-brand-50/80 px-3 py-1.5 rounded-xl border border-brand-100/60 flex items-center justify-between">
            <span>${group.group_name}</span>
            <span class="text-[10px] text-slate-400 font-normal">Nhóm menu</span>
          </div>

          <div class="grid grid-cols-1 gap-2.5 pl-1">
      `;

      (group.menus || []).forEach(m => {
        const isMenuChecked = isAll || currentMenus.includes(m.id);

        html += `
          <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-sm space-y-2 hover:border-brand-200 transition">
            <!-- Menu Cha -->
            <div class="flex items-center justify-between">
              <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <input type="checkbox" name="menu_access[]" value="${m.id}" ${isMenuChecked ? 'checked' : ''} 
                       class="menu-checkbox w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500" 
                       onchange="toggleMenuActionCheckboxes('${m.id}', this.checked)">
                <span class="font-bold text-xs text-slate-800 flex items-center gap-2">
                  <i class="${m.icon} text-base text-brand-600"></i>
                  ${m.name}
                </span>
              </label>
              <span class="font-mono text-[10px] text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">id: ${m.id}</span>
            </div>
        `;

        // Danh sách Actions (Xem, Thêm, Sửa, Xóa...)
        if (m.actions && Object.keys(m.actions).length > 0) {
          html += `<div class="pl-6 pt-2 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-2">`;
          for (const [actKey, actLabel] of Object.entries(m.actions)) {
            const permKey = `${m.id}.${actKey}`;
            const isDelete = (actKey === 'delete');
            const isPermChecked = isAll || currentPerms.includes(permKey);

            html += `
              <label class="flex items-center gap-1.5 cursor-pointer text-[11px] select-none text-slate-600 hover:text-slate-900">
                <input type="checkbox" name="permissions[]" value="${permKey}" 
                       data-menu="${m.id}" data-action="${actKey}" ${isPermChecked ? 'checked' : ''}
                       class="perm-checkbox w-3.5 h-3.5 ${isDelete ? 'text-rose-600 focus:ring-rose-500' : 'text-brand-600 focus:ring-brand-500'} rounded border-slate-300">
                <span class="${isDelete ? 'text-rose-600 font-semibold' : ''}">
                  ${actLabel}
                </span>
              </label>
            `;
          }
          html += `</div>`;
        }

        html += `</div>`;
      });

      html += `</div></div>`;
    });

    container.innerHTML = html;
  }

  function toggleMenuActionCheckboxes(menuId, isChecked) {
    document.querySelectorAll(`.perm-checkbox[data-menu="${menuId}"]`).forEach(cb => {
      cb.checked = isChecked;
    });
  }

  // Nút chọn nhanh trong modal Cấu hình nhóm quyền
  function quickSelectPerms(mode) {
    if (mode === 'all') {
      document.querySelectorAll('#role-perm-tree-container .menu-checkbox, #role-perm-tree-container .perm-checkbox').forEach(cb => cb.checked = true);
    } else if (mode === 'none') {
      document.querySelectorAll('#role-perm-tree-container .menu-checkbox, #role-perm-tree-container .perm-checkbox').forEach(cb => cb.checked = false);
    } else if (mode === 'no-delete') {
      // Bật tất cả menu và quyền, NHƯNG BỎ TÍCH TOÀN BỘ QUYỀN DELETE
      document.querySelectorAll('#role-perm-tree-container .menu-checkbox').forEach(cb => cb.checked = true);
      document.querySelectorAll('#role-perm-tree-container .perm-checkbox').forEach(cb => {
        const action = cb.getAttribute('data-action');
        cb.checked = (action !== 'delete');
      });
    } else if (mode === 'view-only') {
      // Chỉ bật quyền view
      document.querySelectorAll('#role-perm-tree-container .menu-checkbox').forEach(cb => cb.checked = true);
      document.querySelectorAll('#role-perm-tree-container .perm-checkbox').forEach(cb => {
        const action = cb.getAttribute('data-action');
        cb.checked = (action === 'view');
      });
    }
  }

  // Submit Bước 2: Lưu cấu hình phân quyền cho nhóm
  function handleRolePermissionsSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('form-role-permissions');
    const formData = new FormData(form);

    Swal.fire({
      title: 'Đang lưu cấu hình nhóm quyền...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    fetch('<?php echo XC_URL; ?>/api/saveRolePermissions', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams(formData)
    })
    .then(r => {
      return r.json().catch(() => {
        throw new Error('Máy chủ phản hồi định dạng không hợp lệ.');
      });
    })
    .then(res => {
      if (res.status === 200) {
        Swal.fire({
          icon: 'success',
          title: 'Thành công',
          text: res.message,
          timer: 1200,
          showConfirmButton: false
        }).then(() => {
          try { localStorage.setItem('careioc_active_tab', 'roles'); } catch(e) {}
          location.reload();
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Lỗi', text: res.message });
      }
    })
    .catch((err) => {
      console.error('Save Role Permissions Error:', err);
      Swal.fire({ icon: 'error', title: 'Lỗi', text: err.message || 'Không thể kết nối máy chủ API.' });
    });
  }

  // ==================== XÓA NHÓM QUYỀN ====================
  function deleteRoleAction(id, name) {
    Swal.fire({
      title: 'Xác nhận xóa nhóm quyền?',
      text: 'Bạn có chắc muốn xóa [' + name + ']? Các cán bộ thuộc nhóm quyền này sẽ cần được phân quyền lại.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e11d48',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Xóa nhóm quyền',
      cancelButtonText: 'Hủy'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({ title: 'Đang xóa...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        fetch('<?php echo XC_URL; ?>/api/deleteRole', {
          method: 'POST',
          body: new URLSearchParams({ id: id })
        })
        .then(r => r.json())
        .then(res => {
          if (res.status === 200) {
            Swal.fire({ icon: 'success', title: 'Đã xóa', text: res.message, timer: 1200, showConfirmButton: false })
            .then(() => location.reload());
          } else {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: res.message });
          }
        });
      }
    });
  }

  // ==================== LỊCH SỬ PHIÊN BẢN JSON ====================
  function openMenuVersionsModal() {
    document.getElementById('versions-modal').classList.remove('hidden');
    loadVersionsData('');
  }

  function closeMenuVersionsModal() {
    document.getElementById('versions-modal').classList.add('hidden');
  }

  function loadVersionsData(scope) {
    const container = document.getElementById('versions-table-container');
    container.innerHTML = '<div class="p-6 text-center text-slate-400">Đang tải lịch sử phiên bản...</div>';

    document.querySelectorAll('.version-filter-btn').forEach(btn => {
      btn.className = 'version-filter-btn px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition';
    });
    const activeBtn = document.getElementById('vbtn-' + (scope || 'all'));
    if (activeBtn) activeBtn.className = 'version-filter-btn px-2.5 py-1 rounded-lg bg-brand-600 text-white font-bold transition';

    const url = '<?php echo XC_URL; ?>/api/getMenuVersions' + (scope ? '?scope=' + encodeURIComponent(scope) : '');

    fetch(url)
    .then(r => r.json())
    .then(res => {
      if (res.status === 200 && res.data.length > 0) {
        let rows = '';
        res.data.forEach(v => {
          const isActive = (v.is_active == 1);
          const sc = v.scope || 'admin';
          const scopeBadge = {
            'admin': '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Admin</span>',
            'backend': '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Backend</span>',
            'dashboard': '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Dashboard</span>'
          }[sc] || sc;

          rows += `
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
              <td class="p-3 text-center">${scopeBadge}</td>
              <td class="p-3 font-mono text-[11px] font-bold text-brand-600">${v.version_tag}</td>
              <td class="p-3 font-mono text-[11px] text-slate-400">${v.content_hash.substring(0, 10)}...</td>
              <td class="p-3 text-slate-700">${v.created_by}</td>
              <td class="p-3 text-slate-500">${v.change_note}</td>
              <td class="p-3 text-center">
                ${isActive ? '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Đang kích hoạt</span>' : '<span class="text-slate-400 text-[11px]">Lịch sử</span>'}
              </td>
              <td class="p-3 text-slate-400 text-[11px] text-right">${v.created_at}</td>
            </tr>
          `;
        });

        container.innerHTML = `
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px]">
                <th class="p-3 text-center">Phân hệ</th>
                <th class="p-3">Phiên bản</th>
                <th class="p-3">Mã Băm</th>
                <th class="p-3">Người cập nhật</th>
                <th class="p-3">Ghi chú</th>
                <th class="p-3 text-center">Trạng thái</th>
                <th class="p-3 text-right">Thời gian</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        `;
      } else {
        container.innerHTML = '<div class="p-6 text-center text-slate-400">Chưa có lịch sử phiên bản nào được ghi nhận.</div>';
      }
    })
    .catch(() => {
      container.innerHTML = '<div class="p-6 text-center text-rose-500">Lỗi khi tải danh sách phiên bản.</div>';
    });
  }
</script>

  <!-- ==================== MODAL GÁN CÁN BỘ VÀO NHÓM QUYỀN ==================== -->
  <div id="assign-users-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-blue-100 overflow-hidden flex flex-col max-h-[90vh]">
      <!-- Header -->
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50/80 to-slate-50 shrink-0">
        <div class="flex items-center gap-2.5">
          <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-lg">
            <i class="ph-bold ph-user-plus"></i>
          </div>
          <div>
            <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
              <span>Gán Cán Bộ Vào Nhóm Quyền</span>
              <span id="assign-role-badge" class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200"></span>
            </h3>
            <p id="assign-role-name" class="text-xs text-slate-500 font-medium">Chọn cán bộ để cấp quyền theo nhóm này</p>
          </div>
        </div>
        <button type="button" onclick="closeAssignUsersModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-white transition">
          <i class="ph ph-x text-lg"></i>
        </button>
      </div>

      <!-- Toolbar -->
      <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
        <div class="relative w-full sm:w-80">
          <input type="text" id="assign-user-search" onkeyup="filterAssignUserList()" placeholder="Tìm theo tên, CCCD, khoa phòng..." class="w-full pl-9 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 transition">
          <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
          <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer select-none">
            <input type="checkbox" id="assign-select-all" onchange="toggleSelectAllAssignUsers(this.checked)" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
            <span>Chọn tất cả</span>
          </label>
          <span id="assign-selected-count" class="text-[11px] font-bold text-brand-700 bg-brand-50 border border-brand-200 px-2.5 py-1 rounded-lg">Đã chọn: 0</span>
        </div>
      </div>

      <!-- User List -->
      <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar" id="assign-users-list-container">
        <div class="p-8 text-center text-slate-400">
          <i class="ph ph-spinner animate-spin text-2xl mb-2 text-brand-600"></i>
          <p class="text-xs">Đang nạp danh sách cán bộ...</p>
        </div>
      </div>

      <!-- Footer -->
      <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
        <span class="text-[11px] text-slate-400 italic">Mỗi cán bộ sẽ có 1 vai trò chính quyết định quyền hạn truy cập</span>
        <div class="flex items-center gap-2.5">
          <button type="button" onclick="closeAssignUsersModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200 rounded-xl transition">Hủy</button>
          <button type="button" id="assign-submit-btn" onclick="submitAssignRoleUsers()" class="px-5 py-2 text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-1.5">
            <i class="ph-bold ph-check"></i>
            <span>Lưu Phân Vai Trò</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentAssignRoleId = null;
    let allAssignUsersData = [];

    function openAssignUsersModal(role) {
      currentAssignRoleId = role.id;
      document.getElementById('assign-role-name').innerText = 'Nhóm quyền: ' + role.role_name + ' [' + role.role_code + ']';
      document.getElementById('assign-role-badge').innerText = (role.scope || 'admin').toUpperCase();
      document.getElementById('assign-user-search').value = '';
      document.getElementById('assign-select-all').checked = false;

      const container = document.getElementById('assign-users-list-container');
      container.innerHTML = `
        <div class="p-8 text-center text-slate-400">
          <i class="ph ph-spinner animate-spin text-2xl mb-2 text-brand-600"></i>
          <p class="text-xs">Đang nạp danh sách cán bộ...</p>
        </div>
      `;

      document.getElementById('assign-users-modal').classList.remove('hidden');

      fetch('<?php echo XC_URL; ?>/api/getRoleUsersApi?role_id=' + role.id)
        .then(r => r.json())
        .then(res => {
          if (res.status === 200) {
            allAssignUsersData = res.data || [];
            renderAssignUserList();
          } else {
            container.innerHTML = `<div class="p-6 text-center text-rose-500">${res.message || 'Lỗi tải danh sách cán bộ'}</div>`;
          }
        })
        .catch(err => {
          container.innerHTML = `<div class="p-6 text-center text-rose-500">Lỗi kết nối: ${err.message}</div>`;
        });
    }

    function closeAssignUsersModal() {
      document.getElementById('assign-users-modal').classList.add('hidden');
      currentAssignRoleId = null;
      allAssignUsersData = [];
    }

    function renderAssignUserList() {
      const container = document.getElementById('assign-users-list-container');
      const q = (document.getElementById('assign-user-search').value || '').toLowerCase().trim();

      const filtered = allAssignUsersData.filter(u => {
        const name = (u.display_name || '').toLowerCase();
        const cccd = (u.citizen_id || '').toLowerCase();
        const dept = (u.department || '').toLowerCase();
        const user = (u.username || '').toLowerCase();
        return !q || name.includes(q) || cccd.includes(q) || dept.includes(q) || user.includes(q);
      });

      if (filtered.length === 0) {
        container.innerHTML = '<div class="p-8 text-center text-slate-400 text-xs">Không tìm thấy cán bộ nào phù hợp.</div>';
        updateAssignSelectedCount();
        return;
      }

      let html = '';
      filtered.forEach(u => {
        const isChecked = u.is_assigned == 1 ? 'checked' : '';
        const roleBadge = u.current_role_name 
          ? `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">${u.current_role_name}</span>`
          : `<span class="px-2 py-0.5 rounded text-[10px] text-slate-400 bg-slate-100 italic">Chưa phân vai trò</span>`;

        html += `
          <label class="assign-user-item flex items-center justify-between p-3 rounded-2xl border ${u.is_assigned == 1 ? 'border-brand-300 bg-brand-50/40' : 'border-slate-200/80 bg-white'} hover:border-brand-400 hover:bg-blue-50/30 transition cursor-pointer">
            <div class="flex items-center gap-3">
              <input type="checkbox" value="${u.id}" ${isChecked} onchange="onAssignUserCheckboxChange('${u.id}', this.checked)" class="assign-user-checkbox w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
              <div>
                <div class="flex items-center gap-2">
                  <span class="font-bold text-xs text-slate-800">${u.display_name}</span>
                  <span class="font-mono text-[11px] text-slate-400">(@${u.username})</span>
                </div>
                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                  <span class="font-mono">${u.citizen_id || 'CCCD: --'}</span>
                  <span>•</span>
                  <span>${u.position || 'Cán bộ'}</span>
                  <span>•</span>
                  <span>${u.department || 'IOC'}</span>
                </div>
              </div>
            </div>
            <div class="shrink-0 text-right">
              ${roleBadge}
            </div>
          </label>
        `;
      });

      container.innerHTML = html;
      updateAssignSelectedCount();
    }

    function onAssignUserCheckboxChange(uid, checked) {
      const target = allAssignUsersData.find(u => u.id === uid);
      if (target) {
        target.is_assigned = checked ? 1 : 0;
      }
      renderAssignUserList();
    }

    function toggleSelectAllAssignUsers(checked) {
      allAssignUsersData.forEach(u => {
        u.is_assigned = checked ? 1 : 0;
      });
      renderAssignUserList();
    }

    function filterAssignUserList() {
      renderAssignUserList();
    }

    function updateAssignSelectedCount() {
      const selected = allAssignUsersData.filter(u => u.is_assigned == 1).length;
      document.getElementById('assign-selected-count').innerText = 'Đã chọn: ' + selected + ' cán bộ';
    }

    function submitAssignRoleUsers() {
      if (!currentAssignRoleId) return;
      const btn = document.getElementById('assign-submit-btn');
      btn.disabled = true;
      btn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Đang lưu...';

      const selectedIds = allAssignUsersData.filter(u => u.is_assigned == 1).map(u => u.id);

      const data = new URLSearchParams();
      data.append('role_id', currentAssignRoleId);
      selectedIds.forEach(id => {
        data.append('user_ids[]', id);
      });

      fetch('<?php echo XC_URL; ?>/api/assignRoleUsersApi', {
        method: 'POST',
        body: data
      })
      .then(r => r.json())
      .then(res => {
        if (res.status === 200) {
          Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: res.message || 'Đã cập nhật danh sách cán bộ cho nhóm quyền!',
            timer: 1200,
            showConfirmButton: false
          }).then(() => location.reload());
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Lỗi gán vai trò',
            text: res.message || 'Không thể lưu danh sách cán bộ!'
          });
          btn.disabled = false;
          btn.innerHTML = '<i class="ph-bold ph-check"></i> Lưu Phân Vai Trò';
        }
      })
      .catch(err => {
        Swal.fire({
          icon: 'error',
          title: 'Lỗi kết nối',
          text: 'Không thể kết nối đến máy chủ: ' + err.message
        });
        btn.disabled = false;
        btn.innerHTML = '<i class="ph-bold ph-check"></i> Lưu Phân Vai Trò';
      });
    }
  </script>
