  <!-- ==================== POPUP MODAL ĐỔI MẬT KHẨU ==================== -->
  <div id="change-password-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-blue-100 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
        <div class="flex items-center gap-2 text-brand-700">
          <i class="ph-bold ph-key text-xl"></i>
          <h3 class="font-bold text-base text-slate-800">Đổi Mật Khẩu Quản Trị</h3>
        </div>
        <button onclick="closeChangePasswordModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
          <i class="ph ph-x text-lg"></i>
        </button>
      </div>
      <form onsubmit="handlePasswordSubmit(event)" class="p-6 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Mật khẩu hiện tại</label>
          <input type="password" required class="w-full px-3.5 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500" placeholder="••••••••">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Mật khẩu mới</label>
          <input type="password" required minlength="8" class="w-full px-3.5 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500" placeholder="Tối thiểu 8 ký tự">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Xác nhận mật khẩu mới</label>
          <input type="password" required minlength="8" class="w-full px-3.5 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500" placeholder="••••••••">
        </div>
        <div class="pt-2 flex items-center justify-end gap-2.5">
          <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Hủy</button>
          <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-500/20">Lưu Mật Khẩu</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== JAVASCRIPT HỆ THỐNG ==================== -->
  <script>
    let isSidebarCollapsed = false;

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('mobile-overlay');
      const isMobile = window.innerWidth < 1024;

      if (isMobile) {
        if (sidebar.classList.contains('-translate-x-full')) {
          sidebar.classList.remove('-translate-x-full');
          overlay.classList.remove('hidden');
        } else {
          sidebar.classList.add('-translate-x-full');
          overlay.classList.add('hidden');
        }
      } else {
        isSidebarCollapsed = !isSidebarCollapsed;
        const textElements = document.querySelectorAll('.sidebar-text');
        if (isSidebarCollapsed) {
          sidebar.classList.remove('w-64');
          sidebar.classList.add('w-20');
          textElements.forEach(el => el.classList.add('hidden'));
        } else {
          sidebar.classList.remove('w-20');
          sidebar.classList.add('w-64');
          textElements.forEach(el => el.classList.remove('hidden'));
        }
      }
    }

    function initSidebarState() {
      const sidebar = document.getElementById('sidebar');
      if (window.innerWidth < 1024) {
        sidebar.classList.add('-translate-x-full');
      } else {
        sidebar.classList.remove('-translate-x-full');
      }
    }
    window.addEventListener('resize', initSidebarState);
    window.addEventListener('DOMContentLoaded', initSidebarState);

    // Xổ / Thu submenu danh mục
    function toggleSubmenu(menuId, button) {
      const menu = document.getElementById(menuId);
      const arrow = button.querySelector('.submenu-arrow');
      menu.classList.toggle('hidden');
      if (menu.classList.contains('hidden')) {
        arrow.classList.remove('rotate-180');
      } else {
        arrow.classList.add('rotate-180');
      }
    }
  </script>

  <!-- ==================== RBAC JAVASCRIPT ENGINE ==================== -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    window.__CARE_AUTH__ = <?php echo Auth::toJsData(); ?>;

    const CareAuth = {
      data: window.__CARE_AUTH__ || { isSuperAdmin: false, permissions: [], menu_access: [] },
      can: function(permission) {
        if (this.data.isSuperAdmin) return true;
        const perms = this.data.permissions || [];
        if (perms.includes('*')) return true;
        if (perms.includes(permission)) return true;
        const parts = permission.split('.');
        if (parts.length === 2 && perms.includes(parts[0] + '.*')) return true;
        return false;
      },
      canMenu: function(menuId) {
        if (this.data.isSuperAdmin) return true;
        const menus = this.data.menu_access || [];
        return menus.includes(menuId);
      },
      applyDOM: function() {
        document.querySelectorAll('[data-permission]').forEach(el => {
          const req = el.getAttribute('data-permission');
          if (!CareAuth.can(req)) {
            el.remove(); // Gỡ bỏ hoàn toàn khỏi DOM để chống F12 bấm lén
          }
        });
      }
    };

    // Chuyển tab nội dung có kiểm soát phân quyền chặt chẽ
    function switchTab(tabId, element) {
      if (tabId === 'category-staff') tabId = 'users';

      // Kiểm tra quyền truy cập menu
      if (!CareAuth.canMenu(tabId)) {
        Swal.fire({
          icon: 'error',
          title: 'Truy cập bị từ chối (403)',
          text: 'Vai trò của bạn (' + (CareAuth.data.role_name || 'Cán bộ') + ') không được cấp quyền truy cập chức năng này!',
          confirmButtonColor: '#2563eb',
          confirmButtonText: 'Đã hiểu'
        });
        return;
      }

      document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
      const selectedTab = document.getElementById(`tab-${tabId}`);
      if (selectedTab) {
        selectedTab.classList.remove('hidden');
        CareAuth.applyDOM(); // Áp dụng lọc quyền lại cho nội dung tab
      }
      try { 
        localStorage.setItem('careioc_active_tab', tabId); 
        if (CareAuth.data.user_id) {
          localStorage.setItem('careioc_active_tab_' + CareAuth.data.user_id, tabId);
        }
      } catch(e) {}

      document.querySelectorAll('.nav-item').forEach(btn => {
        btn.classList.remove('bg-brand-50', 'text-brand-700', 'font-semibold');
        btn.classList.add('text-slate-600');
        const icon = btn.querySelector('i');
        if (icon) icon.classList.remove('text-brand-600');
      });

      document.querySelectorAll('.subnav-item').forEach(btn => {
        btn.classList.remove('text-brand-700', 'font-bold', 'bg-blue-50/80');
        btn.classList.add('text-slate-600');
      });

      if (element) {
        if (element.classList.contains('subnav-item')) {
          element.classList.add('text-brand-700', 'font-bold', 'bg-blue-50/80');
          element.classList.remove('text-slate-600');
        } else {
          element.classList.add('bg-brand-50', 'text-brand-700', 'font-semibold');
          element.classList.remove('text-slate-600');
          const icon = element.querySelector('i');
          if (icon) icon.classList.add('text-brand-600');
        }
      }

      const pageTitle = document.getElementById('page-title');
      if (pageTitle) {
        if (tabId === 'users') {
          pageTitle.innerText = 'Quản Lý Người Dùng & Hồ Sơ Cá Nhân';
        } else if (tabId === 'roles') {
          pageTitle.innerText = 'Phân Quyền Vai Trò & Truy Cập';
        } else if (tabId === 'category-departments') {
          pageTitle.innerText = 'Quản Lý Khoa Phòng';
        } else if (tabId === 'category-beds') {
          pageTitle.innerText = 'Quản Lý Giường Bệnh';
        } else if (tabId === 'outpatient') {
          pageTitle.innerText = 'Khám Bệnh & Tiếp Đón Ngoại Trú';
        } else if (tabId === 'inpatient') {
          pageTitle.innerText = 'Nội Trú & Quản Lý Bệnh Nhân';
        } else if (tabId === 'it-infra') {
          pageTitle.innerText = 'Hạ Tầng Máy Chủ & Mạng';
        } else if (tabId === 'security') {
          pageTitle.innerText = 'An Toàn Thông Tin & Tuân Thủ';
        } else {
          const textSpan = element ? element.querySelector('span.sidebar-text') : null;
          pageTitle.innerText = textSpan ? textSpan.innerText : 'Tổng Quan IOC';
        }
      }

      if (window.innerWidth < 1024) toggleSidebar();
    }

    // Mở tab âm thầm khi tải trang (không bắn alert 403 nếu khởi tạo)
    function openTabSilently(tabId, element) {
      if (tabId === 'category-staff') tabId = 'users';
      if (!CareAuth.canMenu(tabId)) return;

      document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
      const selectedTab = document.getElementById(`tab-${tabId}`);
      if (selectedTab) {
        selectedTab.classList.remove('hidden');
        CareAuth.applyDOM();
      }

      document.querySelectorAll('.nav-item').forEach(btn => {
        btn.classList.remove('bg-brand-50', 'text-brand-700', 'font-semibold');
        btn.classList.add('text-slate-600');
        const icon = btn.querySelector('i');
        if (icon) icon.classList.remove('text-brand-600');
      });

      document.querySelectorAll('.subnav-item').forEach(btn => {
        btn.classList.remove('text-brand-700', 'font-bold', 'bg-blue-50/80');
        btn.classList.add('text-slate-600');
      });

      if (element) {
        if (element.classList.contains('subnav-item')) {
          element.classList.add('text-brand-700', 'font-bold', 'bg-blue-50/80');
          element.classList.remove('text-slate-600');
        } else {
          element.classList.add('bg-brand-50', 'text-brand-700', 'font-semibold');
          element.classList.remove('text-slate-600');
          const icon = element.querySelector('i');
          if (icon) icon.classList.add('text-brand-600');
        }
      }

      const pageTitle = document.getElementById('page-title');
      if (pageTitle) {
        const titles = {
          'users': 'Quản Lý Người Dùng & Hồ Sơ Cá Nhân',
          'roles': 'Phân Quyền Vai Trò & Truy Cập',
          'category-departments': 'Quản Lý Khoa Phòng',
          'category-beds': 'Quản Lý Giường Bệnh',
          'outpatient': 'Khám Bệnh & Tiếp Đón Ngoại Trú',
          'inpatient': 'Nội Trú & Quản Lý Bệnh Nhân',
          'it-infra': 'Hạ Tầng Máy Chủ & Mạng',
          'security': 'An Toàn Thông Tin & Tuân Thủ',
          'dashboard': 'Tổng Quan Điều Hành IOC'
        };
        pageTitle.innerText = titles[tabId] || (element?.querySelector('span.sidebar-text')?.innerText || 'Tổng Quan IOC');
      }
    }

    // Dropdown User
    function toggleUserDropdown(event) {
      event.stopPropagation();
      document.getElementById('user-dropdown').classList.toggle('hidden');
    }
    window.addEventListener('click', () => {
      const dropdown = document.getElementById('user-dropdown');
      if (dropdown && !dropdown.classList.contains('hidden')) dropdown.classList.add('hidden');
    });

    // Modal Đổi Mật Khẩu
    function openChangePasswordModal() {
      document.getElementById('user-dropdown').classList.add('hidden');
      document.getElementById('change-password-modal').classList.remove('hidden');
    }
    function closeChangePasswordModal() {
      document.getElementById('change-password-modal').classList.add('hidden');
    }
    function handlePasswordSubmit(event) {
      event.preventDefault();
      alert('Đổi mật khẩu tài khoản quản trị thành công!');
      closeChangePasswordModal();
    }

    // Đăng xuất và xóa sạch cache tab lưu trữ trên trình duyệt
    function logoutAction() {
      if (confirm('Bạn có chắc chắn muốn đăng xuất khỏi Trung tâm điều hành IOC?')) {
        try {
          localStorage.removeItem('careioc_active_tab');
          if (CareAuth && CareAuth.data && CareAuth.data.user_id) {
            localStorage.removeItem('careioc_active_tab_' + CareAuth.data.user_id);
          }
        } catch(e) {}
        window.location.href = '<?php echo XC_URL; ?>/admin/logout';
      }
    }

    // Tự động quét phân quyền và khôi phục tab đang thao tác khi tải trang
    document.addEventListener('DOMContentLoaded', () => {
      CareAuth.applyDOM();

      const urlParams = new URLSearchParams(window.location.search);
      const urlRoute = urlParams.get('route');
      const userKey = CareAuth.data.user_id ? ('careioc_active_tab_' + CareAuth.data.user_id) : 'careioc_active_tab';
      let savedTab = localStorage.getItem(userKey);
      if (!savedTab && CareAuth.data.user_id) {
        savedTab = localStorage.getItem('careioc_active_tab');
      }
      let targetTab = urlRoute || savedTab;

      // Kiểm tra tính hợp lệ của tab mục tiêu với quyền hạn của user hiện tại
      if (!targetTab || !CareAuth.canMenu(targetTab)) {
        // Tự động chuyển hướng vào menu hợp lệ đầu tiên mà user được cấp phép
        if (CareAuth.data.menu_access && CareAuth.data.menu_access.length > 0) {
          targetTab = CareAuth.data.menu_access[0];
        } else {
          targetTab = null;
        }
      }

      // Chỉ mở tab hợp lệ, tuyệt đối không gọi mở tab bị cấm khi trang vừa tải
      if (targetTab) {
        const navBtn = document.querySelector(`.nav-item[onclick*="'${targetTab}'"]`) ||
                       document.querySelector(`.subnav-item[onclick*="'${targetTab}'"]`);
        openTabSilently(targetTab, navBtn);
      }
    });
  </script>
</body>
</html>
