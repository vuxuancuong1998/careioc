<!-- BỘ LỌC ĐA TIÊU CHÍ CARE IOC (ĐẶT Ở ĐẦU TRANG - TÍCH HỢP TRÊN TOPBAR) -->
<div class="ioc-filter-bar" id="iocFilterBar">
  <div class="filter-group">
    <label><i class="fa-regular fa-calendar-days"></i> Năm:</label>
    <select class="filter-select" id="filterYear">
      <option value="2026" selected>Năm 2026</option>
      <option value="2025">Năm 2025</option>
      <option value="2024">Năm 2024</option>
    </select>
  </div>

  <!-- [NOTE]: Bộ lọc Quý (filterQuarter) đã được bỏ theo yêu cầu hệ thống chuyển sang chọn khoảng tháng -->

  <?php
  $isOverview   = isset($active_menu) && $active_menu === 'overview';
  $isDayDefault = isset($active_menu) && in_array($active_menu, ['outpatient', 'inpatient']);
  $defaultDate  = $isDayDefault ? date('Y-m-d') : '';
  $initialMode  = $isDayDefault ? 'date' : 'month';
  ?>

  <?php if (!$isOverview): ?>
  <div class="filter-group" id="groupFilterMode">
    <label><i class="fa-solid fa-layer-group"></i> Lọc theo:</label>
    <select class="filter-select" id="filterMode">
      <option value="date" <?= ($initialMode === 'date') ? 'selected' : '' ?>>Theo ngày</option>
      <option value="month" <?= ($initialMode === 'month') ? 'selected' : '' ?>>Theo tháng</option>
    </select>
  </div>
  <?php endif; ?>

  <div class="filter-group" id="groupMonthFrom" style="<?= (!$isOverview && $initialMode === 'date') ? 'display:none;' : '' ?>">
    <label><i class="fa-solid fa-calendar-check"></i> Từ tháng:</label>
    <select class="filter-select" id="filterMonthFrom">
      <option value="">Chọn tháng</option>
      <option value="all">Cả năm (12 tháng)</option>
      <?php $currentMonth = (int)date('n'); ?>
      <?php for ($m = 1; $m <= 12; $m++): ?>
        <option value="<?= $m ?>" <?= ($m === $currentMonth) ? 'selected' : '' ?>>Tháng <?= $m ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="filter-group" id="groupMonthTo" style="<?= (!$isOverview && $initialMode === 'date') ? 'display:none;' : '' ?>">
    <label><i class="fa-solid fa-arrow-right"></i> Đến tháng:</label>
    <select class="filter-select" id="filterMonthTo">
      <option value="" selected>-- Chọn tháng --</option>
      <option value="1">Tháng 1</option>
      <option value="2">Tháng 2</option>
      <option value="3">Tháng 3</option>
      <option value="4">Tháng 4</option>
      <option value="5">Tháng 5</option>
      <option value="6">Tháng 6</option>
      <option value="7">Tháng 7</option>
      <option value="8">Tháng 8</option>
      <option value="9">Tháng 9</option>
      <option value="10">Tháng 10</option>
      <option value="11">Tháng 11</option>
      <option value="12">Tháng 12</option>
    </select>
  </div>

  <?php if (!$isOverview): ?>
  <div class="filter-group" id="groupDateFrom" style="<?= ($initialMode === 'month') ? 'display:none;' : '' ?>">
    <label><i class="fa-regular fa-calendar"></i> Từ ngày:</label>
    <input type="date" class="filter-select" id="filterDateFrom"
           value="<?= $defaultDate ?>"
           min="2024-01-01" max="2027-12-31"
           title="Chọn từ ngày" />
  </div>

  <div class="filter-group" id="groupDateTo" style="<?= ($initialMode === 'month') ? 'display:none;' : '' ?>">
    <label><i class="fa-solid fa-arrow-right"></i> Đến ngày:</label>
    <input type="date" class="filter-select" id="filterDateTo"
           value=""
           min="2024-01-01" max="2027-12-31"
           title="Đến ngày (để trống nếu chỉ xem một ngày)" />
    <input type="hidden" id="filterDate" value="<?= $defaultDate ?>" />
  </div>
  <?php endif; ?>

  <div class="filter-group">
    <label><i class="fa-solid fa-hospital-alt"></i> Khoa/Phòng:</label>
    <select class="filter-select" id="filterDept">
      <option value="all">-- Tất cả khoa phòng --</option>
      <?php if (!empty($departments)): ?>
        <?php foreach ($departments as $dept): ?>
          <option value="<?= (int)$dept['id'] ?>"><?= htmlspecialchars($dept['department_name']) ?> (<?= htmlspecialchars($dept['department_code']) ?>)</option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>

  <!-- [NOTE]: Bộ lọc "Đối tượng:" (filterPayer) đã được bỏ theo yêu cầu, chuyển thành ghi chú lưu trữ
  <div class="filter-group">
    <label><i class="fa-solid fa-id-card-clip"></i> Đối tượng:</label>
    <select class="filter-select" id="filterPayer">
      <option value="all">-- Tất cả đối tượng --</option>
      <?php if (!empty($payerTypes)): ?>
        <?php foreach ($payerTypes as $payer): ?>
          <option value="<?= (int)$payer['id'] ?>"><?= htmlspecialchars($payer['payer_type_name']) ?></option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="1">BHYT</option>
        <option value="2">Viện phí (Thu phí)</option>
      <?php endif; ?>
    </select>
  </div>
  -->

  <div class="filter-group" style="margin-left:auto; display:flex; gap:8px;">
    <!-- [NOTE]: Nút "Lọc dữ liệu" đã được bỏ theo yêu cầu, hệ thống tự động lọc khi thay đổi giá trị (auto-filter on change)
    <button type="button" class="btn-filter-apply" id="btnApplyFilter" style="display:none;">
      <i class="fa-solid fa-filter"></i> Lọc dữ liệu
    </button>
    -->
    <button type="button" class="btn-filter-reset" id="btnResetFilter" title="Khôi phục mặc định">
      <i class="fa-solid fa-arrow-rotate-left"></i> Đặt lại
    </button>
  </div>
</div>
