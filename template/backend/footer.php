
  <!-- ====== MODAL ĐỔI MẬT KHẨU ====== -->
  <div class="modal-overlay" id="changePassModal">
    <div class="modal-box">
      <div class="modal-header">
        <h3><i class="fa-solid fa-shield-halved"></i> Đổi mật khẩu tài khoản</h3>
        <button class="modal-close" id="btnCloseChangePass"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form onsubmit="event.preventDefault(); submitChangePass();">
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" required placeholder="Nhập mật khẩu cũ">
          </div>
          <div class="form-group">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control" required placeholder="Tối thiểu 8 ký tự, có ký tự đặc biệt">
          </div>
          <div class="form-group">
            <label class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" required placeholder="Nhập lại mật khẩu mới">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" id="btnCancelPass">Hủy</button>
          <button type="submit" class="btn btn-primary">Xác nhận đổi</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ====== JAVASCRIPT ====== -->
  <script>
    // 1. Thu nhỏ toàn bộ Menu: Ẩn sạch tất cả chỉ để lại 1 nút mở duy nhất
    const sidebar = document.getElementById('sidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');

    sidebarToggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
    });

    // 2. Dropdown Menu Đa cấp
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function () {
        const parentItem = this.parentElement;
        const submenu = parentItem.querySelector('.submenu');
        const arrow = this.querySelector('.arrow-icon');

        if (submenu.classList.contains('open')) {
          submenu.classList.remove('open');
          if (arrow) arrow.style.transform = 'rotate(0deg)';
        } else {
          submenu.classList.add('open');
          if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
      });
    });

    // 3. User Menu Profile Dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenuDropdown = document.getElementById('userMenuDropdown');

    userMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      userMenuDropdown.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
      if (!userMenuBtn.contains(e.target)) {
        userMenuDropdown.classList.remove('show');
      }
    });

    // 4. Modal Đổi Mật Khẩu
    const changePassModal = document.getElementById('changePassModal');
    const btnOpenChangePass = document.getElementById('btnOpenChangePass');
    const btnCloseChangePass = document.getElementById('btnCloseChangePass');
    const btnCancelPass = document.getElementById('btnCancelPass');

    btnOpenChangePass.addEventListener('click', () => {
      userMenuDropdown.classList.remove('show');
      changePassModal.classList.add('active');
    });

    const closeModal = () => changePassModal.classList.remove('active');
    btnCloseChangePass.addEventListener('click', closeModal);
    btnCancelPass.addEventListener('click', closeModal);

    // 5. Loading Overlay mượt mà với hiệu ứng Dots nhảy
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingBaseText = document.getElementById('loadingBaseText');

    function simulateProcess(customMessage = 'Đang xử lý') {
      loadingBaseText.textContent = customMessage;
      loadingOverlay.classList.add('show');
      
      setTimeout(() => {
        loadingOverlay.classList.remove('show');
      }, 1000);
    }

    function triggerPageLoad(pageName) {
      document.getElementById('currentBreadcrumb').innerText = pageName;
      simulateProcess(`Đang tải dữ liệu`);
    }

    function submitChangePass() {
      closeModal();
      simulateProcess('Đang cập nhật mật khẩu');
    }

    function handleLogout() {
      userMenuDropdown.classList.remove('show');
      simulateProcess('Đang đăng xuất hệ thống');
      setTimeout(() => {
        alert('Đã kết thúc phiên làm việc!');
      }, 1100);
    }
  </script>
</body>
</html>