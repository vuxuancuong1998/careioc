<!-- TOPBAR HEADER -->
<header class="ioc-topbar">
  <div class="topbar-left">
    <div class="topbar-page-info">
      <h1>
        <i class="fa-solid fa-hospital-user" style="color:var(--accent);"></i>
        <?= htmlspecialchars($page_title ?? 'Trung tâm Điều hành Y tế Thông minh') ?>
      </h1>
      <div class="hospital-badge">
        <i class="fa-solid fa-location-dot"></i>
        <span>BỆNH VIỆN ĐA KHOA KHU VỰC ĐĂK TÔ &bull; TỈNH KON TUM</span>
      </div>
    </div>
  </div>

  <div class="topbar-right">
    <div class="live-clock-badge" id="liveClock">
      <i class="fa-solid fa-clock"></i>
      <span id="clockTime">--:--:--</span>
      <span id="clockDate" style="color:var(--muted); font-size:11px; font-weight:500; margin-left:4px;">--/--/----</span>
    </div>

    <button type="button" class="topbar-btn" id="btnWallboard" title="Bật chế độ Wallboard tự động luân chuyển trang trên màn hình lớn">
      <i class="fa-solid fa-tv"></i>
      <span>Wallboard</span>
    </button>

    <button type="button" class="topbar-btn" id="btnFullscreen" title="Xem toàn màn hình (F11)">
      <i class="fa-solid fa-expand"></i>
      <span>Toàn màn hình</span>
    </button>

    <button type="button" class="topbar-btn" id="btnRefresh" title="Làm mới dữ liệu từ CSDL">
      <i class="fa-solid fa-rotate"></i>
      <span>Làm mới</span>
    </button>
  </div>
</header>

<!-- BỘ LỌC ĐA TIÊU CHÍ ĐƯỢC ĐƯA LÊN ĐẦU TRANG THEO YÊU CẦU -->
<?php include __DIR__ . '/filter.php'; ?>
