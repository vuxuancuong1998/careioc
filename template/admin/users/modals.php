<?php
global $db;
$allRoles = [];
if (isset($db)) {
    $db->query("SELECT id, role_name, role_code FROM ioc_auth_roles WHERE is_active = 1 ORDER BY id ASC");
    $allRoles = $db->fetch_object() ?: [];
}
?>
  <!-- ==================== POPUP MODAL THÊM / SỬA NGƯỜI DÙNG ==================== -->
  <div id="user-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-blue-100 overflow-hidden flex flex-col max-h-[90vh]">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 shrink-0">
        <div class="flex items-center gap-2.5 text-brand-700">
          <i class="ph-bold ph-user-gear text-2xl"></i>
          <div>
            <h3 id="user-modal-title" class="font-bold text-base text-slate-800">Thêm Người Dùng Mới</h3>
            <p class="text-[11px] text-slate-500">Quản lý định danh cá nhân & phân quyền hệ thống</p>
          </div>
        </div>
        <button onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-white transition">
          <i class="ph ph-x text-lg"></i>
        </button>
      </div>

      <form onsubmit="handleUserSubmit(event)" class="p-6 overflow-y-auto space-y-4 flex-1 no-scrollbar">
        <input type="hidden" id="user-form-id" value="">

        <!-- Phần 1: Thông tin cá nhân cơ bản -->
        <div class="border-b border-slate-100 pb-3">
          <h4 class="text-xs font-bold text-brand-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="ph-bold ph-identification-badge"></i> 1. Thông Tin Cá Nhân
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Họ và Tên <span class="text-rose-500">*</span></label>
              <input type="text" id="user-form-name" required placeholder="VD: TS.BS Trần Quốc Tuấn" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Số CCCD (12 số) <span class="text-rose-500">*</span></label>
              <div class="relative">
                <input type="text" id="user-form-cccd" required maxlength="12" pattern="[0-9]{12}" placeholder="001085012345" class="w-full pl-8 pr-3 py-2 text-xs font-mono border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                <i class="ph-bold ph-identification-card absolute left-2.5 top-2.5 text-slate-400"></i>
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Giới tính</label>
              <select id="user-form-gender" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Ngày sinh</label>
              <input type="date" id="user-form-birthday" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Số điện thoại <span class="text-rose-500">*</span></label>
              <input type="tel" id="user-form-phone" required placeholder="0912345678" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Địa chỉ thường trú</label>
              <input type="text" id="user-form-address" placeholder="Hà Nội / TP.HCM..." class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
            </div>
          </div>
        </div>

        <!-- Phần 2: Đơn vị công tác & Chức vụ -->
        <div class="border-b border-slate-100 pb-3">
          <h4 class="text-xs font-bold text-brand-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="ph-bold ph-buildings"></i> 2. Chức Vụ & Đơn Vị Công Tác
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Chức vụ <span class="text-rose-500">*</span></label>
              <input type="text" id="user-form-position" required placeholder="VD: Trưởng Khoa, Bác sĩ điều trị..." list="pos-suggestions" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
              <datalist id="pos-suggestions">
                <option value="Trưởng Khoa">
                <option value="Phó Trưởng Khoa">
                <option value="Bác Sĩ Điều Trị">
                <option value="Điều Dưỡng Trưởng">
                <option value="Điều Dưỡng Viên">
                <option value="Kỹ Thuật Viên">
                <option value="Quản trị hệ thống DB/IOC">
                <option value="Cán Bộ CNTT">
              </datalist>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Khoa / Phòng Ban <span class="text-rose-500">*</span></label>
              <select id="user-form-department" required class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
                <option value="Khoa Nội Tim Mạch">Khoa Nội Tim Mạch</option>
                <option value="Khoa Hồi Sức Cấp Cứu">Khoa Hồi Sức Cấp Cứu</option>
                <option value="Khoa Khám Bệnh">Khoa Khám Bệnh</option>
                <option value="Khoa Ngoại Tổng Hợp">Khoa Ngoại Tổng Hợp</option>
                <option value="Khoa Chẩn Đoán Hình Ảnh">Khoa Chẩn Đoán Hình Ảnh</option>
                <option value="Khoa Dược">Khoa Dược</option>
                <option value="Phòng CNTT & Chuyển Đổi Số">Phòng CNTT & Chuyển Đổi Số</option>
                <option value="Phòng Kế Hoạch Tổng Hợp">Phòng Kế Hoạch Tổng Hợp</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-slate-700 mb-1">Email Công Vụ <span class="text-rose-500">*</span></label>
              <input type="email" id="user-form-email" required placeholder="ten@hospital.gov.vn" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
            </div>
          </div>
        </div>

        <!-- Phần 3: Tài khoản & Phân quyền vai trò -->
        <div>
          <h4 class="text-xs font-bold text-brand-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="ph-bold ph-shield-check"></i> 3. Tài Khoản Đăng Nhập & Phân Quyền
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Tên đăng nhập <span class="text-rose-500">*</span></label>
              <input type="text" id="user-form-username" required placeholder="tuantq" class="w-full px-3.5 py-2 text-xs font-mono border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Mật khẩu khởi tạo</label>
              <input type="password" id="user-form-password" placeholder="Để trống nếu không đổi" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Trạng thái</label>
              <select id="user-form-status" class="w-full px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
                <option value="1">Đang hoạt động</option>
                <option value="0">Tạm khóa</option>
              </select>
            </div>

            <!-- CHỌN VAI TRÒ PHÂN QUYỀN HỆ THỐNG -->
            <div class="sm:col-span-3">
              <label class="block text-xs font-bold text-brand-700 mb-1 flex items-center gap-1.5">
                <i class="ph-bold ph-shield-check"></i> Vai Trò Phân Quyền Hệ Thống <span class="text-rose-500">*</span>
              </label>
              <select id="user-form-role" class="w-full px-3.5 py-2 text-xs font-semibold border border-brand-200 bg-brand-50/50 rounded-xl focus:outline-none focus:border-brand-500 focus:bg-white transition">
                <option value="">-- Chọn vai trò (Bác sĩ, Điều dưỡng, Quản trị...) --</option>
                <?php foreach ($allRoles as $role): ?>
                  <option value="<?= $role->id ?>"><?= htmlspecialchars($role->role_name) ?> [<?= htmlspecialchars($role->role_code) ?>]</option>
                <?php endforeach; ?>
              </select>
              <p class="text-[11px] text-slate-400 mt-1">Vai trò này sẽ quyết định các menu được hiển thị trên Sidebar và các quyền Thêm/Sửa/Xóa của cán bộ.</p>
            </div>
          </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-2.5 shrink-0 border-t border-slate-100">
          <button type="button" onclick="closeUserModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Hủy Bỏ</button>
          <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-1.5">
            <i class="ph-bold ph-check"></i> Lưu Thông Tin
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== POPUP MODAL XEM CHI TIẾT HỒ SƠ ==================== -->
  <div id="user-detail-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-blue-100 overflow-hidden">
      <div class="bg-gradient-to-r from-brand-700 via-blue-600 to-indigo-600 p-6 text-white relative">
        <button onclick="closeViewUserModal()" class="absolute top-4 right-4 text-white/70 hover:text-white p-1 rounded-lg">
          <i class="ph ph-x text-lg"></i>
        </button>
        <div class="flex items-center gap-4">
          <div id="view-user-avatar" class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-xl font-bold border border-white/30 shadow-lg">
            TT
          </div>
          <div>
            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white uppercase tracking-wider mb-1 border border-white/20">Hồ Sơ Cán Bộ IOC</span>
            <h3 id="view-user-name" class="text-lg font-bold">TS.BS Trần Quốc Tuấn</h3>
            <p id="view-user-pos" class="text-xs text-blue-100 font-medium">Trưởng Khoa Nội Tim Mạch</p>
          </div>
        </div>
      </div>

      <div class="p-6 space-y-4">
        <!-- Thẻ CCCD Highlight -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl">
              <i class="ph-bold ph-identification-card"></i>
            </div>
            <div>
              <p class="text-[11px] text-slate-400 font-semibold uppercase">Số Thẻ CCCD / Mã Định Danh</p>
              <p id="view-user-cccd" class="text-sm font-mono font-bold text-slate-800 tracking-wider">001085012345</p>
            </div>
          </div>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">Đã định danh</span>
        </div>

        <!-- Chi tiết cá nhân -->
        <div class="grid grid-cols-2 gap-3 text-xs">
          <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-slate-400 font-medium text-[11px]">Phòng ban / Khoa</p>
            <p id="view-user-dept" class="font-bold text-slate-700 mt-0.5">Khoa Nội Tim Mạch</p>
          </div>
          <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-slate-400 font-medium text-[11px]">Email công vụ</p>
            <p id="view-user-email" class="font-bold text-slate-700 mt-0.5 truncate">tuantq@hospital.gov.vn</p>
          </div>
          <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-slate-400 font-medium text-[11px]">Số điện thoại</p>
            <p id="view-user-phone" class="font-bold text-slate-700 mt-0.5">0912345678</p>
          </div>
          <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-slate-400 font-medium text-[11px]">Giới tính / Ngày sinh</p>
            <p id="view-user-gender-dob" class="font-bold text-slate-700 mt-0.5">Nam • 20/03/1985</p>
          </div>
          <div class="col-span-2 p-3 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-slate-400 font-medium text-[11px]">Địa chỉ liên hệ</p>
            <p id="view-user-address" class="font-bold text-slate-700 mt-0.5">Hà Nội</p>
          </div>
        </div>

        <div class="pt-2 flex items-center justify-end">
          <button type="button" onclick="closeViewUserModal()" class="px-5 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md transition">
            Đóng Hồ Sơ
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // ==================== HÀM XỬ LÝ NGƯỜI DÙNG ==================== //
    function openAddUserModal() {
      document.getElementById('user-modal-title').innerText = 'Thêm Người Dùng Mới';
      document.getElementById('user-form-id').value = '';
      document.getElementById('user-form-name').value = '';
      document.getElementById('user-form-cccd').value = '';
      document.getElementById('user-form-role').value = '';
      document.getElementById('user-form-gender').value = 'Nam';
      document.getElementById('user-form-birthday').value = '';
      document.getElementById('user-form-phone').value = '';
      document.getElementById('user-form-address').value = '';
      document.getElementById('user-form-position').value = '';
      document.getElementById('user-form-department').value = 'Khoa Nội Tim Mạch';
      document.getElementById('user-form-email').value = '';
      document.getElementById('user-form-username').value = '';
      document.getElementById('user-form-password').value = '';
      document.getElementById('user-form-status').value = '1';

      document.getElementById('user-modal').classList.remove('hidden');
    }

    function openEditUserModal(user) {
      document.getElementById('user-modal-title').innerText = 'Chỉnh Sửa Thông Tin Người Dùng';
      document.getElementById('user-form-id').value = user.id || '';
      document.getElementById('user-form-name').value = user.display_name || '';
      document.getElementById('user-form-cccd').value = user.citizen_id || '';
      document.getElementById('user-form-role').value = user.role_id || '';
      document.getElementById('user-form-gender').value = user.gender || 'Nam';
      document.getElementById('user-form-birthday').value = user.birthday || '';
      document.getElementById('user-form-phone').value = user.phone || '';
      document.getElementById('user-form-address').value = user.address || '';
      document.getElementById('user-form-position').value = user.position || '';
      document.getElementById('user-form-department').value = user.department || 'Khoa Nội Tim Mạch';
      document.getElementById('user-form-email').value = user.email || '';
      document.getElementById('user-form-username').value = user.username || '';
      document.getElementById('user-form-password').value = '';
      document.getElementById('user-form-status').value = user.is_active !== undefined ? user.is_active : 1;

      document.getElementById('user-modal').classList.remove('hidden');
    }

    function closeUserModal() {
      document.getElementById('user-modal').classList.add('hidden');
    }

    function openViewUserModal(user) {
      document.getElementById('view-user-name').innerText = user.display_name || 'Chưa có tên';
      document.getElementById('view-user-pos').innerText = (user.position || 'Cán bộ') + ' • ' + (user.department || '');
      document.getElementById('view-user-cccd').innerText = user.citizen_id || '-- Chưa cập nhật --';
      document.getElementById('view-user-dept').innerText = user.department || '--';
      document.getElementById('view-user-email').innerText = user.email || '--';
      document.getElementById('view-user-phone').innerText = user.phone || '--';
      document.getElementById('view-user-gender-dob').innerText = (user.gender || 'Nam') + ' • ' + (user.birthday || 'N/A');
      document.getElementById('view-user-address').innerText = user.address || '--';

      const words = (user.display_name || '').trim().split(' ');
      let initials = 'ND';
      if (words.length >= 2) {
        initials = words[words.length - 2][0] + words[words.length - 1][0];
      }
      document.getElementById('view-user-avatar').innerText = initials.toUpperCase();

      document.getElementById('user-detail-modal').classList.remove('hidden');
    }

    function closeViewUserModal() {
      document.getElementById('user-detail-modal').classList.add('hidden');
    }

    function openCurrentUserProfile() {
      const dropdown = document.getElementById('user-dropdown');
      if (dropdown) dropdown.classList.add('hidden');
      openViewUserModal({
        display_name: 'KS. Vũ Xuân Cường (Admin)',
        position: 'Quản trị hệ thống DB/IOC',
        department: 'Phòng CNTT & Chuyển Đổi Số',
        citizen_id: '037098045678',
        email: 'cuongvx@hospital.gov.vn',
        phone: '0978998877',
        gender: 'Nam',
        birthday: '1998-05-12',
        address: 'Hà Nội'
      });
    }

    function handleUserSubmit(event) {
      event.preventDefault();
      const id = document.getElementById('user-form-id').value;
      const name = document.getElementById('user-form-name').value;
      const cccd = document.getElementById('user-form-cccd').value;
      const roleId = document.getElementById('user-form-role').value;

      const data = new URLSearchParams();
      data.append('id', id);
      data.append('display_name', name);
      data.append('citizen_id', cccd);
      data.append('gender', document.getElementById('user-form-gender').value || 'Nam');
      data.append('birthday', document.getElementById('user-form-birthday').value || '');
      data.append('phone', document.getElementById('user-form-phone').value || '');
      data.append('address', document.getElementById('user-form-address').value || '');
      data.append('position', document.getElementById('user-form-position').value || '');
      data.append('department', document.getElementById('user-form-department').value || '');
      data.append('email', document.getElementById('user-form-email').value || '');
      data.append('username', document.getElementById('user-form-username').value || '');
      data.append('password', document.getElementById('user-form-password').value || '');
      data.append('is_active', document.getElementById('user-form-status').value || '1');
      data.append('role_id', roleId || '0');

      fetch('<?php echo XC_URL; ?>/api/saveUserApi', {
        method: 'POST',
        body: data
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 200) {
          Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: res.message || 'Đã lưu thông tin và vai trò phân quyền cho cán bộ: ' + name,
            timer: 1300,
            showConfirmButton: false
          }).then(() => location.reload());
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Lỗi lưu dữ liệu',
            text: res.message || 'Không thể lưu thông tin cán bộ!'
          });
        }
      })
      .catch(err => {
        Swal.fire({
          icon: 'error',
          title: 'Lỗi kết nối',
          text: 'Không thể kết nối đến máy chủ IOC: ' + err.message
        });
      });
      closeUserModal();
    }

    function toggleUserLock(id, name) {
      if (!CareAuth.can('users.delete')) {
        Swal.fire({
          icon: 'error',
          title: 'Từ chối thao tác (403)',
          text: 'Vai trò của bạn không có quyền Khóa hoặc Xóa tài khoản cán bộ!',
          confirmButtonColor: '#2563eb'
        });
        return;
      }

      Swal.fire({
        title: 'Xác nhận thay đổi trạng thái?',
        text: 'Bạn có chắc chắn muốn thay đổi trạng thái tài khoản cán bộ: ' + name + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({ icon: 'success', title: 'Thành công', text: 'Đã cập nhật trạng thái tài khoản!', timer: 1000, showConfirmButton: false });
        }
      });
    }

    function filterUserTable() {
      const q = (document.getElementById('user-search-input').value || '').toLowerCase().trim();
      const deptFilter = (document.getElementById('user-dept-filter').value || '').toLowerCase().trim();
      const posFilter = (document.getElementById('user-pos-filter').value || '').toLowerCase().trim();

      const rows = document.querySelectorAll('.user-row');
      let visibleCount = 0;

      rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const cccd = row.getAttribute('data-cccd') || '';
        const dept = (row.getAttribute('data-dept') || '').toLowerCase();
        const pos = (row.getAttribute('data-pos') || '').toLowerCase();
        const email = row.getAttribute('data-email') || '';

        const matchQ = !q || name.includes(q) || cccd.includes(q) || email.includes(q) || dept.includes(q) || pos.includes(q);
        const matchDept = !deptFilter || dept.includes(deptFilter);
        const matchPos = !posFilter || pos.includes(posFilter);

        if (matchQ && matchDept && matchPos) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      const label = document.getElementById('user-count-label');
      if (label) {
        label.innerText = 'Tìm thấy ' + visibleCount + ' người dùng phù hợp';
      }
    }

    function exportUserExcel() {
      alert('Đang tải xuống danh sách người dùng (Excel/CSV)...');
    }
  </script>
