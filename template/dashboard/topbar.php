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
        <span>BỆNH VIỆN ĐA KHOA KHU VỰC ĐĂK TÔ &bull; TỈNH QUẢNG NGÃI</span>
      </div>
    </div>
  </div>

  <div class="topbar-right">
    <div class="live-clock-badge" id="liveClock">
      <i class="fa-solid fa-clock"></i>
      <span id="clockTime">--:--:--</span>
      <span id="clockDate" style="color:var(--muted); font-size:11px; font-weight:500; margin-left:4px;">--/--/----</span>
    </div>

    <button type="button" class="topbar-btn" id="btnThemeToggle" title="Chuyển đổi giao diện Sáng / Tối">
      <i class="fa-solid fa-sun" id="themeToggleIcon"></i>
      <span id="themeToggleText">Chế độ Sáng</span>
    </button>

    <!-- Nút điều chỉnh độ hiển thị màu nền -->
    <div class="bg-adjust-wrapper" id="bgAdjustWrapper">
      <button type="button" class="topbar-btn" id="btnBgAdjust" title="Điều chỉnh độ hiển thị màu nền / độ sáng gradient">
        <i class="fa-solid fa-circle-half-stroke" id="bgAdjustIcon"></i>
        <span id="bgAdjustText">Nền: 85%</span>
      </button>
      <div class="bg-adjust-popover" id="bgAdjustPopover">
        <div class="bg-adjust-header">
          <h4><i class="fa-solid fa-sliders"></i> Độ sáng màu nền</h4>
          <span class="bg-adjust-badge" id="bgOpacityValue">85%</span>
        </div>
        <div class="bg-slider-row">
          <input type="range" min="0" max="100" step="5" value="85" class="bg-slider" id="bgOpacityRange" />
          <div class="bg-slider-ticks">
            <span>0% (Tối giản)</span>
            <span>50%</span>
            <span>100% (Rực rỡ)</span>
          </div>
        </div>
        <div class="bg-presets-label">Chế độ hiển thị:</div>
        <div class="bg-presets-grid">
          <button type="button" class="bg-preset-btn" data-val="0">
            <i class="fa-solid fa-moon"></i> Tối giản (0%)
          </button>
          <button type="button" class="bg-preset-btn" data-val="40">
            <i class="fa-regular fa-eye"></i> Dịu mắt (40%)
          </button>
          <button type="button" class="bg-preset-btn active" data-val="85">
            <i class="fa-solid fa-gauge-high"></i> Chuẩn (85%)
          </button>
          <button type="button" class="bg-preset-btn" data-val="100">
            <i class="fa-solid fa-sun"></i> Rực rỡ (100%)
          </button>
        </div>
      </div>
    </div>
<!-- 
    <button type="button" class="topbar-btn" id="btnWallboard" title="Bật chế độ Wallboard tự động luân chuyển trang trên màn hình lớn">
      <i class="fa-solid fa-tv"></i>
      <span>Wallboard</span>
    </button> -->

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
