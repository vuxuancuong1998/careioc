<?php
$currentMenu = $active_menu ?? 'overview';
?>
<!-- SIDEBAR MENU BÊN TRÁI -->
<aside class="ioc-sidebar" id="iocSidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">
      <i class="fa-solid fa-hospital"></i>
    </div>
    <div class="sidebar-title">
      <span class="app-name">CARE IOC Y TẾ</span>
      <span class="app-sub">BVĐK KV ĐĂK TÔ</span>
    </div>
    <button type="button" class="sidebar-toggle-btn" id="btnToggleSidebar" title="Đóng / Mở menu bên trái">
      <i class="fa-solid fa-angles-left" id="toggleIcon"></i>
    </button>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Trung tâm chỉ huy</div>
    
    <a href="dashboard/overview" class="nav-link <?= in_array($currentMenu, ['index', 'overview']) ? 'active' : '' ?>" data-tooltip="Tổng quan điều hành">
      <i class="fa-solid fa-chart-pie"></i>
      <span>Tổng quan điều hành</span>
    </a>

    <div class="nav-section-label">Khối Chuyên môn & Lâm sàng</div>

    <a href="dashboard/outpatient" class="nav-link <?= in_array($currentMenu, ['kcb', 'outpatient']) ? 'active' : '' ?>" data-tooltip="KCB Ngoại trú (Mục X.1)">
      <i class="fa-solid fa-user-doctor"></i>
      <span>KCB Ngoại trú</span>
    </a>

    <a href="dashboard/inpatient" class="nav-link <?= in_array($currentMenu, ['noitru', 'inpatient']) ? 'active' : '' ?>" data-tooltip="Điều trị Nội trú & Giường (Mục X.2)">
      <i class="fa-solid fa-bed-pulse"></i>
      <span>Nội trú & Giường bệnh</span>
    </a>

    <div class="nav-section-label">Cận Lâm Sàng</div>

    <a href="dashboard/laboratory" class="nav-link <?= in_array($currentMenu, ['xetnghiem', 'laboratory']) ? 'active' : '' ?>" data-tooltip="Xét nghiệm LIS (Mục V.4)">
      <i class="fa-solid fa-vial-virus"></i>
      <span>Xét nghiệm (LIS)</span>
    </a>

    <a href="dashboard/radiology" class="nav-link <?= in_array($currentMenu, ['cdha', 'radiology']) ? 'active' : '' ?>" data-tooltip="CĐHA RIS/PACS (Mục V.5)">
      <i class="fa-solid fa-x-ray"></i>
      <span>CĐHA (RIS/PACS)</span>
    </a>

    <div class="nav-section-label">Hậu cần & Tài chính</div>

    <a href="dashboard/pharmacy" class="nav-link <?= in_array($currentMenu, ['duoc', 'pharmacy']) ? 'active' : '' ?>" data-tooltip="Dược - Vật tư y tế (Mục V.6)">
      <i class="fa-solid fa-pills"></i>
      <span>Dược & Vật tư y tế</span>
    </a>

    <a href="dashboard/finance" class="nav-link <?= in_array($currentMenu, ['taichinh', 'finance']) ? 'active' : '' ?>" data-tooltip="Tài chính - Viện phí (Mục V.7)">
      <i class="fa-solid fa-coins"></i>
      <span>Tài chính - Viện phí</span>
    </a>

    <div class="nav-section-label">Chuyển đổi số & An ninh</div>

    <a href="dashboard/emr" class="nav-link <?= ($currentMenu === 'emr') ? 'active' : '' ?>" data-tooltip="Chuyển đổi số & EMR (Mục X.3)">
      <i class="fa-solid fa-file-medical"></i>
      <span>Bệnh án điện tử EMR</span>
    </a>

    <a href="dashboard/infrastructure" class="nav-link <?= in_array($currentMenu, ['hatang', 'infrastructure']) ? 'active' : '' ?>" data-tooltip="Hạ tầng CNTT & Cảnh báo">
      <i class="fa-solid fa-server"></i>
      <span>Hạ tầng CNTT & ATTT</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div style="display:flex; align-items:center; gap:6px;">
      <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#2ecc71; box-shadow:0 0 6px #2ecc71;"></span>
      <span>HIS/LIS Online</span>
    </div>
    <span style="color:var(--accent); font-weight:700;">v2.6 Pro</span>
  </div>
</aside>
