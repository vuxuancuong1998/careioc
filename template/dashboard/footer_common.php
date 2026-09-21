  </div> <!-- End .ioc-main -->
</div> <!-- End .ioc-container -->

<script>
// ================= IOC CORE CONTROLLER SCRIPT =================
(function() {
  // 1. LIVE DIGITAL CLOCK
  function updateLiveClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    const d = String(now.getDate()).padStart(2, '0');
    const mo = String(now.getMonth() + 1).padStart(2, '0');
    const y = now.getFullYear();

    const timeEl = document.getElementById('clockTime');
    const dateEl = document.getElementById('clockDate');
    if (timeEl) timeEl.textContent = `${h}:${m}:${s}`;
    if (dateEl) dateEl.textContent = `${d}/${mo}/${y}`;
  }
  setInterval(updateLiveClock, 1000);
  updateLiveClock();

  // 2. SIDEBAR TOGGLE & LOCAL STORAGE PERSISTENCE
  const sidebar = document.getElementById('iocSidebar');
  const btnToggleSidebar = document.getElementById('btnToggleSidebar');
  const toggleIcon = document.getElementById('toggleIcon');

  function setSidebarState(collapsed) {
    if (!sidebar) return;
    if (collapsed) {
      sidebar.classList.add('collapsed');
      if (toggleIcon) {
        toggleIcon.classList.remove('fa-angles-left');
        toggleIcon.classList.add('fa-angles-right');
      }
      localStorage.setItem('ioc_sidebar_collapsed', '1');
    } else {
      sidebar.classList.remove('collapsed');
      if (toggleIcon) {
        toggleIcon.classList.remove('fa-angles-right');
        toggleIcon.classList.add('fa-angles-left');
      }
      localStorage.setItem('ioc_sidebar_collapsed', '0');
    }
  }

  // Restore saved state
  if (localStorage.getItem('ioc_sidebar_collapsed') === '1') {
    setSidebarState(true);
  }

  if (btnToggleSidebar) {
    btnToggleSidebar.addEventListener('click', function(e) {
      e.preventDefault();
      const isCurrentlyCollapsed = sidebar.classList.contains('collapsed');
      setSidebarState(!isCurrentlyCollapsed);
    });
  }

  // 3. FULLSCREEN TOGGLE
  const btnFullscreen = document.getElementById('btnFullscreen');
  if (btnFullscreen) {
    btnFullscreen.addEventListener('click', function() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
          console.warn("Lỗi Fullscreen:", err);
        });
        btnFullscreen.innerHTML = '<i class="fa-solid fa-compress"></i> <span>Thu nhỏ</span>';
      } else {
        document.exitFullscreen();
        btnFullscreen.innerHTML = '<i class="fa-solid fa-expand"></i> <span>Toàn màn hình</span>';
      }
    });
  }

  // 4. WALLBOARD AUTO CYCLE MODE (CHO MÀN HÌNH LỚN TRUNG TÂM IOC)
  const menuPages = [
    'dashboard/overview',
    'dashboard/outpatient',
    'dashboard/inpatient',
    'dashboard/laboratory',
    'dashboard/radiology',
    'dashboard/pharmacy',
    'dashboard/finance',
    'dashboard/emr',
    'dashboard/infrastructure'
  ];

  const btnWallboard = document.getElementById('btnWallboard');
  let wallboardActive = localStorage.getItem('ioc_wallboard_active') === '1';

  function updateWallboardButton() {
    if (!btnWallboard) return;
    if (wallboardActive) {
      btnWallboard.classList.add('active');
      btnWallboard.innerHTML = '<i class="fa-solid fa-tv"></i> <span>Wallboard (BẬT)</span>';
    } else {
      btnWallboard.classList.remove('active');
      btnWallboard.innerHTML = '<i class="fa-solid fa-tv"></i> <span>Wallboard</span>';
    }
  }
  updateWallboardButton();

  if (btnWallboard) {
    btnWallboard.addEventListener('click', function() {
      wallboardActive = !wallboardActive;
      localStorage.setItem('ioc_wallboard_active', wallboardActive ? '1' : '0');
      updateWallboardButton();
      if (wallboardActive) {
        scheduleNextPage();
      }
    });
  }

  function scheduleNextPage() {
    if (!wallboardActive) return;
    setTimeout(function() {
      if (localStorage.getItem('ioc_wallboard_active') !== '1') return;
      const currentUrl = window.location.href;
      let currentIndex = -1;
      for (let i = 0; i < menuPages.length; i++) {
        if (currentUrl.indexOf(menuPages[i]) !== -1) {
          currentIndex = i;
          break;
        }
      }
      const nextIndex = (currentIndex + 1) % menuPages.length;
      window.location.href = menuPages[nextIndex];
    }, 35000); // Tự động đổi trang mỗi 35 giây
  }

  if (wallboardActive) {
    scheduleNextPage();
  }

  // 5. REFRESH BUTTON
  const btnRefresh = document.getElementById('btnRefresh');
  if (btnRefresh) {
    btnRefresh.addEventListener('click', function() {
      btnRefresh.querySelector('i').classList.add('fa-spin');
      if (typeof window.fetchDashboardData === 'function') {
        window.fetchDashboardData().finally(() => {
          setTimeout(() => btnRefresh.querySelector('i').classList.remove('fa-spin'), 600);
        });
      } else {
        location.reload();
      }
    });
  }

  // 6. FILTER ENGINE (NĂM, KHOẢNG THÁNG, TỪ NGÀY - ĐẾN NGÀY, KHOA PHÒNG, ĐỐI TƯỢNG)
  const filterYear = document.getElementById('filterYear');
  const filterMode = document.getElementById('filterMode');
  const groupMonthFrom = document.getElementById('groupMonthFrom');
  const groupMonthTo = document.getElementById('groupMonthTo');
  const groupDateFrom = document.getElementById('groupDateFrom');
  const groupDateTo = document.getElementById('groupDateTo');
  const filterMonthFrom = document.getElementById('filterMonthFrom');
  const filterMonthTo = document.getElementById('filterMonthTo');
  const filterDateFrom = document.getElementById('filterDateFrom');
  const filterDateTo = document.getElementById('filterDateTo');
  const filterDate = document.getElementById('filterDate');
  const filterDept = document.getElementById('filterDept');
  const filterPayer = document.getElementById('filterPayer');
  const btnResetFilter = document.getElementById('btnResetFilter');

  let currentFilterType = (filterMode && filterMode.value === 'date' && filterDateFrom && filterDateFrom.value)
    ? 'date_range'
    : (filterMonthFrom && filterMonthFrom.value && filterMonthFrom.value !== 'all') ? 'single_month' : 'year';

  // Chuyển đổi giữa chế độ Lọc theo Tháng (hiển thị cả tháng, ẩn ngày) và Lọc theo Ngày (hiển thị ngày, ẩn tháng)
  function updateFilterModeUI(mode) {
    if (mode === 'month') {
      // Khi chọn tháng: hiển thị cả tháng, ẩn ngày
      if (groupMonthFrom) groupMonthFrom.style.display = 'flex';
      if (groupMonthTo) groupMonthTo.style.display = 'flex';
      if (groupDateFrom) groupDateFrom.style.display = 'none';
      if (groupDateTo) groupDateTo.style.display = 'none';

      // Xóa giá trị ngày để hệ thống tập trung lọc theo tháng
      if (filterDateFrom) filterDateFrom.value = '';
      if (filterDateTo) filterDateTo.value = '';
      if (filterDate) filterDate.value = '';

      const mfVal = filterMonthFrom ? filterMonthFrom.value : '';
      const mtVal = filterMonthTo ? filterMonthTo.value : '';
      if (mfVal === 'all' || !mfVal) {
        currentFilterType = 'year';
      } else if (mtVal && mtVal !== '' && mtVal !== mfVal) {
        currentFilterType = 'month_range';
      } else {
        currentFilterType = 'single_month';
      }
    } else {
      // Ngược lại (chọn ngày): hiển thị ngày, ẩn tháng
      if (groupMonthFrom) groupMonthFrom.style.display = 'none';
      if (groupMonthTo) groupMonthTo.style.display = 'none';
      if (groupDateFrom) groupDateFrom.style.display = 'flex';
      if (groupDateTo) groupDateTo.style.display = 'flex';

      // Nếu Từ ngày đang trống -> gán ngày hôm nay
      if (filterDateFrom && !filterDateFrom.value) {
        const now = new Date();
        const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
        filterDateFrom.value = todayStr;
      }
      currentFilterType = 'date_range';
    }
  }

  if (filterMode) {
    filterMode.addEventListener('change', function() {
      updateFilterModeUI(this.value);
      if (typeof window.fetchDashboardData === 'function') {
        window.fetchDashboardData();
      }
    });
  }

  function getLastDayOfMonth(dateStr) {
    if (!dateStr) return '';
    const parts = dateStr.split('-');
    if (parts.length < 2) return dateStr;
    const y = parseInt(parts[0], 10);
    const m = parseInt(parts[1], 10);
    const lastDay = new Date(y, m, 0).getDate();
    return `${y}-${String(m).padStart(2, '0')}-${String(lastDay).padStart(2, '0')}`;
  }

  // 1. Lọc theo năm / từ tháng (filterMonthFrom)
  if (filterMonthFrom) {
    filterMonthFrom.addEventListener('change', function() {
      // Khi chọn Tháng / Khoảng tháng / 12 tháng -> xóa trắng ô ngày (nếu có)
      if (filterDateFrom) filterDateFrom.value = '';
      if (filterDateTo) filterDateTo.value = '';
      if (filterDate) filterDate.value = '';

      const grpTo = document.getElementById('groupMonthTo');

      if (!this.value || this.value === 'all') {
        // Chọn Cả năm (12 tháng)
        currentFilterType = 'year';
        if (filterMonthTo) {
          filterMonthTo.value = '';
          filterMonthTo.disabled = true;
        }
        if (grpTo) grpTo.style.opacity = '0.5';
      } else {
        // Chọn tháng cụ thể
        if (filterMonthTo) filterMonthTo.disabled = false;
        if (grpTo) grpTo.style.opacity = '1';

        const valFrom = parseInt(this.value, 10);
        if (filterMonthTo && filterMonthTo.value) {
          const valTo = parseInt(filterMonthTo.value, 10);
          if (valTo < valFrom) {
            filterMonthTo.value = this.value;
          }
        }

        if (!filterMonthTo || !filterMonthTo.value || filterMonthTo.value === this.value) {
          currentFilterType = 'single_month';
        } else {
          currentFilterType = 'month_range';
        }
      }
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  // 2. Lọc đến tháng (filterMonthTo)
  if (filterMonthTo) {
    filterMonthTo.addEventListener('change', function() {
      // Khi chọn khoảng tháng -> xóa trắng ô ngày (nếu có)
      if (filterDateFrom) filterDateFrom.value = '';
      if (filterDateTo) filterDateTo.value = '';
      if (filterDate) filterDate.value = '';

      if (!this.value) {
        if (filterMonthFrom && filterMonthFrom.value && filterMonthFrom.value !== 'all') {
          currentFilterType = 'single_month';
        } else {
          currentFilterType = 'year';
        }
      } else {
        const valTo = parseInt(this.value, 10);
        if (!filterMonthFrom || !filterMonthFrom.value || filterMonthFrom.value === 'all') {
          filterMonthFrom.value = this.value;
        } else {
          const valFrom = parseInt(filterMonthFrom.value, 10);
          if (valTo < valFrom) {
            filterMonthFrom.value = this.value;
          }
        }

        if (filterMonthFrom && filterMonthFrom.value === this.value) {
          currentFilterType = 'single_month';
        } else {
          currentFilterType = 'month_range';
        }
      }
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  // 3. Lọc từ ngày (filterDateFrom)
  if (filterDateFrom) {
    filterDateFrom.addEventListener('change', function() {
      if (this.value) {
        currentFilterType = 'date_range';
        // Đến ngày: giống Đến tháng, không hiện (để trống). Chỉ chỉnh nếu Đến ngày đang có giá trị mà nhỏ hơn Từ ngày
        if (filterDateTo && filterDateTo.value && filterDateTo.value < this.value) {
          filterDateTo.value = this.value;
        }

        // Đồng bộ hiển thị Năm & Tháng trên dropdown
        const parts = this.value.split('-');
        if (parts.length === 3) {
          const y = parts[0];
          const m = parseInt(parts[1], 10);
          if (filterYear) filterYear.value = y;
          if (filterMonthFrom) filterMonthFrom.value = String(m);
          if (filterMonthTo) {
            filterMonthTo.value = (filterDateTo && filterDateTo.value) ? String(parseInt(filterDateTo.value.split('-')[1], 10)) : '';
            filterMonthTo.disabled = false;
          }
          const grpTo = document.getElementById('groupMonthTo');
          if (grpTo) grpTo.style.opacity = '1';
        }
      }
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  // 4. Lọc đến ngày (filterDateTo)
  if (filterDateTo) {
    filterDateTo.addEventListener('change', function() {
      if (this.value) {
        currentFilterType = 'date_range';
        if (filterDateFrom && (!filterDateFrom.value || filterDateFrom.value > this.value)) {
          filterDateFrom.value = this.value;
        }
      } else {
        // Nếu người dùng xóa Đến ngày -> quay về xem 1 ngày
        if (filterDateFrom && filterDateFrom.value) {
          currentFilterType = 'date_range';
        }
      }
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  // 5. Lọc theo năm (filterYear)
  if (filterYear) {
    filterYear.addEventListener('change', function() {
      const y = this.value;
      if (filterDateFrom && filterDateFrom.value) {
        const pF = filterDateFrom.value.split('-');
        if (pF.length === 3) filterDateFrom.value = `${y}-${pF[1]}-${pF[2]}`;
      }
      if (filterDateTo && filterDateTo.value) {
        const pT = filterDateTo.value.split('-');
        if (pT.length === 3) filterDateTo.value = `${y}-${pT[1]}-${pT[2]}`;
      }
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  if (filterDept) {
    filterDept.addEventListener('change', function() {
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  if (filterPayer) {
    filterPayer.addEventListener('change', function() {
      if (typeof window.fetchDashboardData === 'function') window.fetchDashboardData();
    });
  }

  window.getFilterParams = function() {
    const y = filterYear ? filterYear.value : '2026';
    const mf = (filterMonthFrom && filterMonthFrom.value) ? filterMonthFrom.value : '';
    const mt = (filterMonthTo && filterMonthTo.value) ? filterMonthTo.value : '';
    const df = (filterDateFrom && filterDateFrom.value) ? filterDateFrom.value : '';
    const dt = (filterDateTo && filterDateTo.value) ? filterDateTo.value : '';
    const fMode = filterMode ? filterMode.value : (df ? 'date' : 'month');

    let fType = currentFilterType;
    let mFrom = mf;
    let mTo = mt;
    let dFrom = '';
    let dTo = '';

    if (fMode === 'date' && df) {
      // Chế độ Lọc theo Ngày: hiển thị ngày, ẩn tháng
      fType = 'date_range';
      dFrom = df;
      dTo = dt ? dt : df; // Đến ngày để trống thì xem 1 ngày
      const parts = df.split('-');
      if (parts.length === 3) {
        mFrom = String(parseInt(parts[1], 10));
        mTo = dt ? String(parseInt(dt.split('-')[1], 10)) : mFrom;
      }
    } else {
      // Chế độ Lọc theo Tháng: hiển thị cả tháng, ẩn ngày
      if (mf === 'all' || !mf) {
        fType = 'year';
        mFrom = 'all';
        mTo = '12';
      } else if (mt && mt !== '' && mt !== mf) {
        fType = 'month_range';
        mTo = mt;
      } else {
        fType = 'single_month';
        mTo = mf;
      }
    }

    const rawDept = filterDept ? filterDept.value : 'all';
    const isGroup = (rawDept === 'all_split') ? 0 : 1;
    const dept = (rawDept === 'all_split') ? 'all' : rawDept;
    const payer = filterPayer ? filterPayer.value : 'all';
    return `filter_type=${fType}&year=${y}&month_from=${mFrom}&month_to=${mTo}&month=${mTo}&date_from=${dFrom}&date_to=${dTo}&department_id=${dept}&group_dept=${isGroup}&payer_type_id=${payer}`;
  };

  if (btnResetFilter) {
    btnResetFilter.addEventListener('click', function() {
      const now = new Date();
      const currentMonth = String(now.getMonth() + 1);
      const isOutpatientPage = window.location.pathname.indexOf('outpatient') !== -1;

      if (filterYear) filterYear.value = String(now.getFullYear() >= 2026 ? now.getFullYear() : '2026');

      if (filterMode) {
        const defaultMode = isOutpatientPage ? 'date' : 'month';
        filterMode.value = defaultMode;
        updateFilterModeUI(defaultMode);
      }

      if (isOutpatientPage && filterDateFrom) {
        // Ngoại trú: reset về ngày hôm nay, Đến ngày không hiện (giống Đến tháng)
        const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
        filterDateFrom.value = todayStr;
        if (filterDateTo) filterDateTo.value = ''; // Đến ngày: không hiện
        if (filterDate) filterDate.value = todayStr;
        currentFilterType = 'date_range';
        if (filterMonthFrom) filterMonthFrom.value = currentMonth;
        if (filterMonthTo) {
          filterMonthTo.value = ''; // Đến tháng: không hiện
          filterMonthTo.disabled = false;
        }
      } else {
        // Tổng quan hoặc các trang khác: reset về tháng hiện tại, xóa ngày
        currentFilterType = 'single_month';
        if (filterMonthFrom) filterMonthFrom.value = currentMonth;
        if (filterMonthTo) {
          filterMonthTo.value = '';
          filterMonthTo.disabled = false;
        }
        if (filterDateFrom) filterDateFrom.value = '';
        if (filterDateTo) filterDateTo.value = '';
        if (filterDate) filterDate.value = '';
      }

      const grpTo = document.getElementById('groupMonthTo');
      if (grpTo) grpTo.style.opacity = '1';

      if (filterDept) filterDept.value = 'all';
      if (filterPayer) filterPayer.value = 'all';

      if (typeof window.fetchDashboardData === 'function') {
        window.fetchDashboardData();
      }
    });
  }

  // 7. REALTIME TABLE SEARCH FILTER
  const tableSearchInput = document.getElementById('tableSearchInput');
  if (tableSearchInput) {
    tableSearchInput.addEventListener('input', function() {
      const q = this.value.toLowerCase().trim();
      const rows = document.querySelectorAll('#iocTableBody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.indexOf(q) !== -1 ? '' : 'none';
      });
    });
  }
})();
</script>
</body>
</html>
