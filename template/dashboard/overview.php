<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI CARDS ROW (TRÊN CÙNG) -->
  <?php 
  $ov = $overview ?? []; 
  $kg = $kpi_growth ?? [];
  ?>
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label" id="kpiLabelKy">Số lượt khám tháng này </span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-user-doctor"></i></div>
      </div>
      <div class="kpi-value" id="kpiKhamNgay"><?= number_format($ov['kham_ngay'] ?? $kg['visits'] ?? 120) ?></div>
      <div class="kpi-subtext" id="kpiGrowthSubtext"><span class="trend-up" id="kpiGrowthBadge"><i class="fa-solid fa-arrow-trend-up"></i> <?= $ov['growth_label'] ?? $kg['growth_label'] ?? '+20.0%' ?></span> <?= $ov['growth_subtext'] ?? $kg['subtext'] ?? 'so với Tháng 7' ?></div>
    </div>

    <!-- [NOTE]: Thẻ chỉ số Lũy kế từ đầu năm (YTD) đã được chuyển thành ghi chú theo yêu cầu, không xóa
    <div class="kpi-card" id="noteKpiLuyKeYtdWrapper" style="display:none;">
      <div class="kpi-top">
        <span class="kpi-label">Lũy kế từ đầu năm (YTD)</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-arrow-trend-up"></i></div>
      </div>
      <div class="kpi-value" id="kpiLuyKeYtd" style="color:var(--gold);"><?= number_format($ov['cumulative_ytd'] ?? $kg['cumulative_ytd'] ?? 822) ?> <span style="font-size:13px; font-weight:600; color:var(--muted);">lượt</span></div>
      <div class="kpi-subtext" id="kpiTargetRate"><span style="color:var(--ok);"><i class="fa-solid fa-bullseye"></i> <?= ($ov['target_completion_rate'] ?? $kg['target_completion_rate'] ?? 60.9) ?>%</span> chỉ tiêu năm 2026</div>
    </div>
    -->

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label" id="kpiLabelCongSuat">Công suất sử dụng giường</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-chart-pie"></i></div>
      </div>
      <div class="kpi-value" id="kpiCongSuatGiuong"><?= ($ov['cong_suat_giuong'] ?? 78.0) ?>%</div>
      <div class="kpi-subtext" id="kpiSubtextCongSuat"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đạt chuẩn</span> (Chỉ tiêu ≤ 85%)</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label" id="kpiLabelDangDieuTri">Đang điều trị nội trú</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-bed-pulse"></i></div>
      </div>
      <div class="kpi-value" id="kpiDangDieuTri"><?= number_format($ov['dang_dieu_tri'] ?? 82) ?></div>
      <div class="kpi-subtext" id="kpiSubtextDangDieuTri"><span class="trend-up"><i class="fa-solid fa-user-plus"></i> +<?= number_format($ov['admissions'] ?? 19) ?> ca</span> nhập viện trong kỳ</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label" id="kpiLabelRaVien">Bệnh nhân ra viện</span>
        <div class="kpi-icon-wrap" style="color:var(--blue); background:rgba(57,160,255,0.1);"><i class="fa-solid fa-person-walking-arrow-right"></i></div>
      </div>
      <div class="kpi-value" id="kpiRaVien"><?= number_format($ov['ra_vien'] ?? 37) ?></div>
      <div class="kpi-subtext" id="kpiSubtextRaVien"><span class="trend-up"><i class="fa-solid fa-clock-rotate-left"></i> <?= $ov['ngay_dt_tb'] ?? 6.4 ?> ngày</span> điều trị TB</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label" id="kpiLabelEmr">Tỷ lệ Ký số EMR</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-file-signature"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmrRate"><?= ($ov['emr_rate'] ?? 94.2) ?>%</div>
      <div class="kpi-subtext" id="kpiSubtextEmr"><span style="color:var(--ok);"><i class="fa-solid fa-circle-check"></i> 100%</span> liên thông BHXH</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ TRỰC QUAN HÓA (ĐA DẠNG LOẠI BIỂU ĐỒ CHO MÀN HÌNH LỚN) -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Combo Chart 2 Cột: Sản lượng Khám Ngoại trú & Điều trị Nội trú từng tháng (ĐỂ RIÊNG HÀNG ĐẦU TIÊN) -->
    <div class="chart-card col-12">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-column"></i>Số Lượng Bệnh Nhân Khám Nội Trú và Ngoại Trú</div>
          <div class="chart-subtitle">So sánh sản lượng Khám Ngoại trú (Cột Xanh) và Điều trị Nội trú (Cột Cam) theo từng tháng được chọn trong kỳ</div>
        </div>
      </div>
      <div class="chart-body" id="chartOverviewCombo"></div>
    </div>

    <!-- Biểu đồ 2: Treemap (Số lượng bệnh nhân từng khoa, hiển thị số lượng ở góc) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-table-cells-large"></i> Số Lượng Bệnh Nhân Khám Theo Từng Khoa</div>
          <div class="chart-subtitle">Số lượng bệnh nhân khám và điều trị phân bổ theo từng khoa lâm sàng</div>
        </div>
      </div>
      <div class="chart-body" id="chartOverviewTreemap"></div>
    </div>

    <!-- Biểu đồ 3: Donut Chart Cơ Cấu Đối Tượng Chi Trả -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Tỷ Lệ Chi Trả</div>
          <div class="chart-subtitle">Tỷ lệ người bệnh BHYT và Viện phí</div>
        </div>
      </div>
      <div class="chart-body" id="chartPayerDonut"></div>
    </div>

    <!-- Lưới Ma Trận Trạng Thái: 8 Hệ Thống Lõi (Đặt chung hàng với 2 biểu đồ Treemap và Donut trên nó) -->
    <div class="chart-card col-4" id="statusGridCard">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-network-wired"></i>Tình Trạng hoạt động các hệ thống</div>
          <div class="chart-subtitle">Giám sát hạ tầng, phần mềm nghiệp vụ & kết nối liên thông toàn viện</div>
        </div>
      </div>
      <div class="chart-body" style="padding: 4px 0; min-height: 260px; display: flex; align-items: center;">
        <div class="status-matrix-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; width: 100%;">
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>HIS CORE</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">Khám & Điều trị</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">Uptime: 99.99%</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>LIS XN</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">Kết nối máy XN</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">6/6 Máy sẵn sàng</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>RIS/PACS</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">Lưu trữ ảnh số</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">DICOM 100%</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>EMR</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">Bệnh án điện tử</div>
            <div class="status-matrix-val" id="statusGridEmr" style="font-size: 10px; color: var(--sub);">Ký số: <?= ($ov['emr_rate'] ?? 94.2) ?>%</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>CỔNG BHXH</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">Giám định XML</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">Thông suốt 24/7</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>DATABASE</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">MySQL Master</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">Độ trễ: 0.8ms</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>UPS NGUỒN</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">Ắc quy Server</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">100% Sạc đầy</div>
          </div>
          <div class="status-matrix-item" style="padding: 7px 10px; gap: 2px;">
            <div class="status-matrix-header" style="font-size: 10px;"><span>FIREWALL</span> <span class="status-pill ok" style="font-size: 8.5px; padding: 1px 5px;">ONLINE</span></div>
            <div class="status-matrix-name" style="font-size: 11px;">An ninh mạng</div>
            <div class="status-matrix-val" style="font-size: 10px; color: var(--sub);">0 Sự cố</div>
          </div>
        </div>
      </div>
    </div>

    <!-- [NOTE]: Biểu đồ Đồng Hồ Đo (Gauge): Công Suất Giường đã được bỏ theo yêu cầu, chuyển thành ghi chú lưu trữ
    <div class="chart-card col-3" id="noteBedGaugeWrapper" style="display:none;">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-gauge-high"></i> Đồng Hồ Đo (Gauge): Công Suất Giường (ĐÃ BỎ)</div>
          <div class="chart-subtitle">Tỷ lệ thực tế / ngưỡng an toàn 85%</div>
        </div>
      </div>
      <div class="chart-body" id="chartBedGauge"></div>
    </div>
    -->

    <!-- [NOTE]: Biểu đồ Mạng Nhện (Radar): 6 Trụ Cột Điều Hành Y Tế đã được bỏ theo yêu cầu, chuyển thành ghi chú lưu trữ
    <div class="chart-card col-4" id="noteRadarPillarsWrapper" style="display:none;">
      <div class="chart-header">
        <div class="chart-title"><i class="fa-solid fa-circle-nodes"></i> Biểu đồ Mạng Nhện (Radar): 6 Trụ Cột Điều Hành Y Tế (ĐÃ BỎ)</div>
      </div>
      <div class="chart-body" id="chartRadarPillars"></div>
    </div>
    -->

    <!-- [NOTE]: Biểu đồ Nhiệt (Heatmap): Mật Độ Lưu Lượng Bệnh Nhân Theo Giờ & Thứ đã được bỏ theo yêu cầu (hệ thống IOC theo ngày/tháng/năm, không theo giờ)
    <div class="chart-card col-7" id="noteHeatmapWrapper" style="display:none;">
      <div class="chart-header">
        <div class="chart-title"><i class="fa-solid fa-fire-flame-curved"></i> Biểu đồ Nhiệt (Heatmap): Mật Độ Theo Giờ & Thứ (ĐÃ BỎ - IOC KHÔNG THEO GIỜ)</div>
      </div>
      <div class="chart-body" id="chartOverviewHeatmap"></div>
    </div>
    -->
  </section>

  <!-- [NOTE]: Bộ lọc ở vị trí giữa trang đã được chuyển lên đầu trang tại template/dashboard/topbar.php -->

  <!-- 4. BẢNG THÔNG TIN CHI TIẾT (CẬP NHẬT ĐỒNG BỘ THEO BỘ LỌC) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Tổng hợp Khám bệnh & Điều trị Nội trú theo Khoa / Phòng (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm kiếm nhanh tên khoa, mã..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead id="iocTableHead">
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã Kỳ</th>
            <th>Kỳ Báo Cáo / Phân Tích</th>
            <th style="text-align:right;">Sản Lượng Kỳ Này</th>
            <th style="text-align:right;">Cùng Kỳ Trước</th>
            <th style="text-align:right;">Tăng/Giảm (+/-)</th>
            <th style="text-align:right;">% Tăng Trưởng MoM</th>
            <th style="text-align:right;">Lũy Kế Năm (YTD)</th>
            <th style="text-align:right;">Chỉ Tiêu Năm</th>
            <th style="text-align:right;">% Đạt Chỉ Tiêu</th>
            <th style="text-align:right;">Cùng Kỳ Năm Trước</th>
            <th style="text-align:right;">% Tăng Trưởng YoY</th>
            <th style="text-align:right;">Năng Suất BQ</th>
            <th style="text-align:center;">Đánh Giá Xu Hướng</th>
          </tr>
        </thead>
        <tbody id="iocTableBody">
          <!-- Render dynamically via JS -->
        </tbody>
        <tfoot id="iocTableFoot">
          <!-- Render totals -->
        </tfoot>
      </table>
    </div>
  </section>
</main>

<?php include __DIR__ . '/footer_common.php'; ?>

<script>
let chartOverviewCombo, chartOverviewTreemap, chartPayerDonut, chartBedGauge;
// [NOTE]: chartRadarPillars và chartOverviewHeatmap đã được bỏ theo yêu cầu

// ================= KHỞI TẠO CÁC BIỂU ĐỒ MÀN HÌNH LỚN TRANG TỔNG QUAN =================
function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Combo Chart: 2 cột Khám Ngoại trú & Điều trị Nội trú theo từng tháng (Mặc định: Tháng 8/2026)
  const initialCombo = <?= json_encode($combo_chart ?? $overview['combo_chart'] ?? [
    'categories' => ['Tháng 8'],
    'series' => [
      ['name' => 'Khám Ngoại trú', 'type' => 'column', 'data' => [120]],
      ['name' => 'Điều trị Nội trú', 'type' => 'column', 'data' => [37]]
    ]
  ]) ?>;

  const initCatCount = (initialCombo.categories && initialCombo.categories.length) || 1;

  chartOverviewCombo = new ApexCharts(document.getElementById('chartOverviewCombo'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 280 },
    colors: ['#00f2fe', '#ff9100'],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: initCatCount <= 2 ? '28%' : (initCatCount <= 4 ? '40%' : '52%'),
        borderRadius: 4,
        dataLabels: {
          position: 'top',
          hideOverflowingLabels: true
        }
      }
    },
    dataLabels: {
      enabled: true,
      offsetY: -10,
      style: {
        fontSize: '10px',
        fontWeight: '700',
        colors: ['#00f2fe', '#ff9100']
      },
      background: {
        enabled: true,
        foreColor: '#071626',
        padding: 3,
        borderRadius: 3,
        borderWidth: 1,
        borderColor: 'rgba(255, 255, 255, 0.15)',
        opacity: 0.92
      },
      formatter: function(val, opt) {
        if (!val || val === 0) return '';
        const catCount = opt?.w?.globals?.labels?.length || initCatCount;
        if (catCount <= 3) {
          return Number(val).toLocaleString('vi-VN') + ' ca';
        }
        if (val >= 1000) {
          return (val / 1000).toFixed(1).replace('.0', '') + 'k';
        }
        return val;
      }
    },
    stroke: { show: true, width: 2, colors: ['transparent'] },
    series: initialCombo.series || [
      { name: 'Khám Ngoại trú', data: initialCombo.outpatient || [120] },
      { name: 'Điều trị Nội trú', data: initialCombo.inpatient || [37] }
    ],
    xaxis: {
      categories: initialCombo.categories || ['Tháng 8'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: {
      title: { text: 'Số lượng bệnh nhân (lượt/ca)', style: { color: '#00f2fe', fontSize: '11px' } },
      labels: { 
        style: { colors: '#85a4c4' },
        formatter: function(val) {
          if (val >= 1000) return (val / 1000).toFixed(0) + 'k';
          return val;
        }
      }
    },
    tooltip: {
      shared: true,
      intersect: false,
      theme: 'dark',
      y: {
        formatter: function(val, { seriesIndex }) {
          return Number(val).toLocaleString('vi-VN') + ' ' + (seriesIndex === 0 ? 'lượt khám ngoại trú' : 'ca điều trị nội trú');
        }
      }
    },
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartOverviewCombo.render();

  // 2. Treemap Chart: Số lượng bệnh nhân từng khoa, hiển thị số lượng ở góc
  <?php
  $initTreemap = $treemap ?? $overview['treemap'] ?? [];
  if (empty($initTreemap)) {
      $outDeptList = $outpatient['by_department'] ?? $departments ?? [];
      foreach (array_slice($outDeptList, 0, 7) as $dv) {
          $initTreemap[] = [
              'x' => str_replace('Khoa ', '', $dv['department_name']),
              'y' => max(5, (int)($dv['visits'] ?? $dv['department_bed'] ?? 20))
          ];
      }
  }
  if (empty($initTreemap)) {
      $initTreemap = [
          ['x' => 'Khám bệnh', 'y' => 45],
          ['x' => 'Nội TH', 'y' => 35],
          ['x' => 'Ngoại TH', 'y' => 28],
          ['x' => 'Nhi', 'y' => 22],
          ['x' => 'Phụ sản', 'y' => 18],
          ['x' => 'HSCC', 'y' => 15]
      ];
  }
  ?>
  function renderIocTreemap(containerId, rawData) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const items = (rawData || []).map(r => ({
      name: r.x || r.name || '',
      value: Number(r.y || r.value || 0)
    })).filter(r => r.value > 0).sort((a, b) => b.value - a.value);

    if (items.length === 0) {
      container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#85a4c4;font-size:12.5px;">Chưa có dữ liệu phân bổ khoa</div>';
      return;
    }

    const total = items.reduce((s, i) => s + i.value, 0);

    const palette = [
      { bg: 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)', border: 'rgba(56, 189, 248, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%)', border: 'rgba(96, 165, 250, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #0f766e 0%, #115e59 100%)', border: 'rgba(45, 212, 191, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #c2410c 0%, #9a3412 100%)', border: 'rgba(251, 146, 60, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%)', border: 'rgba(167, 139, 250, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #15803d 0%, #166534 100%)', border: 'rgba(74, 222, 128, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #be123c 0%, #9f1239 100%)', border: 'rgba(251, 113, 133, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #0e7490 0%, #155e75 100%)', border: 'rgba(34, 211, 238, 0.45)', numColor: '#ffd166' }
    ];

    const W = container.clientWidth || 480;
    const H = 270;
    const GAP = 6;

    const shortNames = {
      'Khám bệnh': 'Khám bệnh',
      'Nội tổng hợp': 'Nội tổng hợp',
      'Ngoại tổng hợp': 'Ngoại TH',
      'Nhi đồng': 'Nhi đồng',
      'Phụ sản': 'Phụ sản',
      'Hồi sức cấp cứu & Chống độc': 'HSCC',
      'HSCC & Chống độc': 'HSCC',
      'Liên chuyên khoa (Mắt - TMH - RHM)': 'Liên CK',
      'Liên chuyên khoa': 'Liên CK',
      'Y học Cổ truyền & PHCN': 'YHCT',
      'YHCT & PHCN': 'YHCT'
    };

    let cells = [];

    if (items.length <= 2) {
      const w0 = items.length === 1 ? W : Math.round((W - GAP) * (items[0].value / total));
      cells.push({ item: items[0], x: 0, y: 0, w: w0, h: H, pal: palette[0] });
      if (items.length === 2) {
        cells.push({ item: items[1], x: w0 + GAP, y: 0, w: W - w0 - GAP, h: H, pal: palette[1] });
      }
    } else {
      const sumCol1 = items[0].value + (items[1] ? items[1].value : 0);
      const wCol1 = Math.max(150, Math.min(Math.round(W * 0.46), Math.round(W * (sumCol1 / total))));

      const hCol1_1 = items[1] ? Math.round((H - GAP) * (items[0].value / sumCol1)) : H;
      const hCol1_2 = H - hCol1_1 - GAP;

      cells.push({ item: items[0], x: 0, y: 0, w: wCol1, h: hCol1_1, pal: palette[0] });
      if (items[1]) {
        cells.push({ item: items[1], x: 0, y: hCol1_1 + GAP, w: wCol1, h: hCol1_2, pal: palette[1] });
      }

      const remainingW = W - wCol1 - GAP;
      const col2Items = items.slice(2, 4);
      const col3Items = items.slice(4);

      if (col3Items.length === 0) {
        if (col2Items.length === 1) {
          cells.push({ item: col2Items[0], x: wCol1 + GAP, y: 0, w: remainingW, h: H, pal: palette[2] });
        } else if (col2Items.length === 2) {
          const sumC2 = col2Items[0].value + col2Items[1].value;
          const hC2_1 = Math.round((H - GAP) * (col2Items[0].value / sumC2));
          cells.push({ item: col2Items[0], x: wCol1 + GAP, y: 0, w: remainingW, h: hC2_1, pal: palette[2] });
          cells.push({ item: col2Items[1], x: wCol1 + GAP, y: hC2_1 + GAP, w: remainingW, h: H - hC2_1 - GAP, pal: palette[3] });
        }
      } else {
        const sumC2 = col2Items.reduce((s, i) => s + i.value, 0);
        const sumC3 = col3Items.reduce((s, i) => s + i.value, 0);
        const wCol2 = Math.max(90, Math.round((remainingW - GAP) * (sumC2 / (sumC2 + sumC3))));
        const wCol3 = remainingW - wCol2 - GAP;

        if (col2Items.length === 1) {
          cells.push({ item: col2Items[0], x: wCol1 + GAP, y: 0, w: wCol2, h: H, pal: palette[2] });
        } else {
          const hC2_1 = Math.round((H - GAP) * (col2Items[0].value / sumC2));
          cells.push({ item: col2Items[0], x: wCol1 + GAP, y: 0, w: wCol2, h: hC2_1, pal: palette[2] });
          cells.push({ item: col2Items[1], x: wCol1 + GAP, y: hC2_1 + GAP, w: wCol2, h: H - hC2_1 - GAP, pal: palette[3] });
        }

        const subW = Math.floor((wCol3 - GAP) / 2);
        const subH = Math.floor((H - GAP) / 2);

        col3Items.forEach((it, idx) => {
          const row = idx >= 2 ? 1 : 0;
          const col = idx % 2;
          const cx = wCol1 + GAP + wCol2 + GAP + (col * (subW + GAP));
          const cy = row * (subH + GAP);
          const cw = (col === 1) ? (wCol3 - subW - GAP) : subW;
          const ch = (row === 1) ? (H - subH - GAP) : subH;
          cells.push({ item: it, x: cx, y: cy, w: cw, h: ch, pal: palette[4 + idx] || palette[0] });
        });
      }
    }

    let html = `<div class="ioc-treemap-board" style="position:relative; width:100%; height:${H}px; overflow:hidden; border-radius:8px;">`;

    cells.forEach((cell, idx) => {
      const it = cell.item;
      const pct = ((it.value / total) * 100).toFixed(1);
      const displayName = shortNames[it.name] || it.name;
      const isSmall = cell.w < 85 || cell.h < 75;
      const isMedium = cell.w < 135 && !isSmall;

      html += `
        <div class="ioc-treemap-tile" style="
          position: absolute;
          left: ${cell.x}px;
          top: ${cell.y}px;
          width: ${cell.w}px;
          height: ${cell.h}px;
          background: ${cell.pal.bg};
          border: 1px solid ${cell.pal.border};
          border-radius: 6px;
          padding: ${isSmall ? '5px 6px' : '7px 9px'};
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          cursor: pointer;
          transition: transform 0.16s ease, filter 0.16s ease, box-shadow 0.16s ease;
          box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 2px 5px rgba(0,0,0,0.25);
          overflow: hidden;
        " onmouseenter="this.style.transform='translateY(-2px) scale(1.015)'; this.style.zIndex='10'; this.style.filter='brightness(1.15)';"
           onmouseleave="this.style.transform='none'; this.style.zIndex='1'; this.style.filter='none';"
           title="${it.name}: ${Number(it.value).toLocaleString('vi-VN')} bệnh nhân (${pct}%)">
          
          <!-- GÓC TRÊN BÊN TRÁI: TÊN KHOA -->
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:3px;">
            <span style="
              font-size: ${isSmall ? '10px' : (isMedium ? '11px' : '12px')};
              font-weight: 700;
              color: #ffffff;
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
              line-height: 1.2;
              letter-spacing: 0.1px;
            ">${displayName}</span>
            ${!isSmall ? `<span style="font-size:9.5px; font-weight:600; color:rgba(255,255,255,0.7); background:rgba(0,0,0,0.22); padding:1px 4px; border-radius:3px; white-space:nowrap;">${pct}%</span>` : ''}
          </div>

          <!-- GÓC DƯỚI BÊN PHẢI: SỐ LƯỢNG BỆNH NHÂN NỔI BẬT Ở GÓC -->
          <div style="align-self:flex-end; display:flex; align-items:baseline; gap:2px;">
            <span style="
              font-size: ${isSmall ? '14px' : (isMedium ? '17px' : '20px')};
              font-weight: 900;
              color: ${cell.pal.numColor};
              line-height: 1;
              letter-spacing: -0.5px;
            ">${Number(it.value).toLocaleString('vi-VN')}</span>
            <span style="
              font-size: ${isSmall ? '8.5px' : '10px'};
              font-weight: 700;
              color: rgba(255,255,255,0.78);
            ">BN</span>
          </div>
        </div>
      `;
    });

    html += `</div>`;
    container.innerHTML = html;
  }

  let currentTreemapData = <?= json_encode($initTreemap) ?>;
  renderIocTreemap('chartOverviewTreemap', currentTreemapData);

  chartOverviewTreemap = {
    updateSeries: function(series) {
      if (series && series[0] && series[0].data) {
        currentTreemapData = series[0].data;
        renderIocTreemap('chartOverviewTreemap', currentTreemapData);
      }
    }
  };

  window.addEventListener('resize', function() {
    renderIocTreemap('chartOverviewTreemap', currentTreemapData);
  });

  // 3. Donut Chart
  <?php
  $outPayerList = $outpatient['by_payer'] ?? $payerTypes ?? [];
  $ovPayerLabels = [];
  $ovPayerSeries = [];
  foreach ($outPayerList as $pv) {
      $ovPayerLabels[] = $pv['payer_type_name'];
      $ovPayerSeries[] = max(1, (int)($pv['visits'] ?? 50));
  }
  if (empty($ovPayerSeries)) {
      $ovPayerLabels = ['Khám BHYT', 'Viện phí / Thu phí'];
      $ovPayerSeries = [85, 15];
  }
  ?>
  const _initPayerTotal = <?= array_sum($ovPayerSeries) ?>;
  chartPayerDonut = new ApexCharts(document.getElementById('chartPayerDonut'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 280 },
    colors: ['#00f2fe', '#39a0ff', '#ff9100', '#a78bfa', '#34d399'],
    labels: <?= json_encode($ovPayerLabels) ?>,
    series: <?= json_encode($ovPayerSeries) ?>,
    dataLabels: {
      enabled: true,
      formatter: (val, opts) => {
        if (val < 8) return ''; // ẩn label trên phần quá nhỏ
        const count = opts.w.config.series[opts.seriesIndex];
        return count.toLocaleString('vi-VN') + ' | ' + val.toFixed(1) + '%';
      },
      style: { fontSize: '12px', fontWeight: '700', colors: ['#ffffff'] },
      dropShadow: { enabled: true, blur: 4, opacity: 0.5 }
    },
    legend: {
      position: 'bottom',
      labels: { colors: '#b2cbe4' },
      fontSize: '12px',
      formatter: (seriesName, opts) => {
        const count = opts.w.config.series[opts.seriesIndex];
        return seriesName + ': <b>' + count.toLocaleString('vi-VN') + '</b>';
      }
    },
    plotOptions: {
      pie: {
        donut: {
          size: '65%',
          labels: {
            show: true,
            name: { show: true, fontSize: '12px', color: '#85a4c4', offsetY: -8 },
            value: {
              show: true,
              fontSize: '20px',
              fontWeight: '700',
              color: '#e2f0ff',
              offsetY: 4,
              formatter: (val) => Number(val).toLocaleString('vi-VN')
            },
            total: {
              show: true,
              label: 'Tổng lượt',
              color: '#85a4c4',
              fontSize: '12px',
              formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString('vi-VN')
            }
          }
        }
      }
    },
    tooltip: {
      y: { formatter: (val, opts) => {
        const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
        const pct = total > 0 ? (val / total * 100).toFixed(1) : 0;
        return val.toLocaleString('vi-VN') + ' lượt (' + pct + '%)';
      }}
    }
  });
  chartPayerDonut.render();


  // [NOTE]: Biểu đồ Đồng Hồ Đo (Gauge): Công Suất Giường đã được chuyển thành ghi chú theo yêu cầu
  const elBedGauge = document.getElementById('chartBedGauge');
  if (elBedGauge) {
    chartBedGauge = new ApexCharts(elBedGauge, {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'radialBar', height: 250 },
      plotOptions: {
        radialBar: {
          startAngle: -135,
          endAngle: 135,
          hollow: { size: '65%' },
          dataLabels: {
            name: { fontSize: '12px', color: '#85a4c4', offsetY: -8 },
            value: { fontSize: '24px', fontWeight: 800, color: '#ffc107', offsetY: 6, formatter: v => v + '%' }
          }
        }
      },
      colors: ['#ffc107'],
      series: [<?= (float)($ov['cong_suat_giuong'] ?? 78.0) ?>],
      labels: ['Công suất sử dụng']
    });
    chartBedGauge.render();
  }

  // [NOTE]: Biểu đồ Radar và Heatmap đã được bỏ theo yêu cầu hệ thống IOC:
  // - Radar: Bỏ để tinh gọn và tập trung vào chỉ số vận hành cốt lõi.
  // - Heatmap: Bỏ vì hệ thống IOC chuẩn hóa theo ngày/tháng/năm, không theo giờ.
}

// ================= CẬP NHẬT BẢNG & BIỂU ĐỒ & CHỈ SỐ KHI BỘ LỌC THAY ĐỔI =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : 'year=2026&month_from=8&month_to=8&month=8';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // 1. CẬP NHẬT ĐẦY ĐỦ CÁC CHỈ SỐ KPI TRÊN CÙNG THEO BỘ LỌC
    if (data.overview) {
      const ov = data.overview;
      
      // Thẻ 1: Số lượt khám trong kỳ (Cập nhật cả tiêu đề lẫn số liệu)
      const elLabelKy = document.getElementById('kpiLabelKy');
      if (elLabelKy) {
        if (ov.period_label) {
          elLabelKy.textContent = (ov.period_type === 'year' ? 'Tổng lượt khám ' : 'Số lượt khám ') + ov.period_label;
        } else {
          elLabelKy.textContent = 'Số lượt khám trong kỳ';
        }
      }
      const elKham = document.getElementById('kpiKhamNgay');
      if (elKham) elKham.textContent = Number(ov.kham_ngay || 0).toLocaleString('vi-VN');
      
      const elBadge = document.getElementById('kpiGrowthBadge');
      if (elBadge) elBadge.innerHTML = `<i class="fa-solid fa-arrow-trend-up"></i> ${ov.growth_label || '+0%'}`;

      const elSubtext = document.getElementById('kpiGrowthSubtext');
      if (elSubtext && ov.growth_subtext) {
        elSubtext.innerHTML = `<span class="trend-up" id="kpiGrowthBadge"><i class="fa-solid fa-arrow-trend-up"></i> ${ov.growth_label || '+0%'}</span> ${ov.growth_subtext}`;
      }

      const elLuyKe = document.getElementById('kpiLuyKeYtd');
      if (elLuyKe) elLuyKe.innerHTML = `${Number(ov.cumulative_ytd || 0).toLocaleString('vi-VN')} <span style="font-size:13px; font-weight:600; color:var(--muted);">lượt</span>`;

      const elTarget = document.getElementById('kpiTargetRate');
      if (elTarget) elTarget.innerHTML = `<span style="color:var(--ok);"><i class="fa-solid fa-bullseye"></i> ${ov.target_completion_rate || 0}%</span> chỉ tiêu năm ${data.year || 2026}`;

      // Thẻ 2: Công suất sử dụng giường
      const elCongSuat = document.getElementById('kpiCongSuatGiuong');
      if (elCongSuat) elCongSuat.textContent = (ov.cong_suat_giuong || 0) + '%';
      const elSubCongSuat = document.getElementById('kpiSubtextCongSuat');
      if (elSubCongSuat) {
        const cs = Number(ov.cong_suat_giuong || 0);
        if (cs <= 85) {
          elSubCongSuat.innerHTML = `<span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đạt chuẩn</span> (Chỉ tiêu ≤ 85%)`;
        } else {
          elSubCongSuat.innerHTML = `<span style="color:var(--warn);"><i class="fa-solid fa-triangle-exclamation"></i> Vượt ngưỡng</span> (Chỉ tiêu ≤ 85%)`;
        }
      }

      // Thẻ 3: Đang điều trị nội trú
      const elDangDT = document.getElementById('kpiDangDieuTri');
      if (elDangDT) elDangDT.textContent = Number(ov.dang_dieu_tri || 0).toLocaleString('vi-VN');
      const elSubDangDT = document.getElementById('kpiSubtextDangDieuTri');
      if (elSubDangDT) {
        const adm = Number(ov.admissions || 0);
        elSubDangDT.innerHTML = `<span class="trend-up"><i class="fa-solid fa-user-plus"></i> +${adm.toLocaleString('vi-VN')} ca</span> nhập viện trong kỳ`;
      }

      // Thẻ 4: Bệnh nhân ra viện
      const elRaVien = document.getElementById('kpiRaVien');
      if (elRaVien) elRaVien.textContent = Number(ov.ra_vien || 0).toLocaleString('vi-VN');
      const elSubRaVien = document.getElementById('kpiSubtextRaVien');
      if (elSubRaVien) {
        elSubRaVien.innerHTML = `<span class="trend-up"><i class="fa-solid fa-clock-rotate-left"></i> ${ov.ngay_dt_tb || 6.4} ngày</span> điều trị TB`;
      }

      // Thẻ 5: Tỷ lệ Ký số EMR
      const elEmr = document.getElementById('kpiEmrRate');
      if (elEmr) elEmr.textContent = (ov.emr_rate || 94.2) + '%';
    }

    // 2. CẬP NHẬT BIỂU ĐỒ COMBO 2 CỘT (NGOẠI TRÚ & NỘI TRÚ TỪNG THÁNG)
    const cb = data.combo_chart || data.overview?.combo_chart;
    if (cb && chartOverviewCombo) {
      const catCount = (cb.categories || []).length;
      chartOverviewCombo.updateOptions({
        xaxis: { categories: cb.categories || [] },
        plotOptions: {
          bar: {
            columnWidth: catCount <= 2 ? '28%' : (catCount <= 4 ? '40%' : '52%'),
            borderRadius: 4,
            dataLabels: {
              position: 'top',
              hideOverflowingLabels: true
            }
          }
        },
        dataLabels: {
          enabled: true,
          offsetY: -10,
          style: {
            fontSize: '10px',
            fontWeight: '700',
            colors: ['#00f2fe', '#ff9100']
          },
          background: {
            enabled: true,
            foreColor: '#071626',
            padding: 3,
            borderRadius: 3,
            borderWidth: 1,
            borderColor: 'rgba(255, 255, 255, 0.15)',
            opacity: 0.92
          },
          formatter: function(val) {
            if (!val || val === 0) return '';
            if (catCount <= 3) {
              return Number(val).toLocaleString('vi-VN') + ' ca';
            }
            if (val >= 1000) {
              return (val / 1000).toFixed(1).replace('.0', '') + 'k';
            }
            return val;
          }
        }
      });
      chartOverviewCombo.updateSeries(cb.series || [
        { name: 'Khám Ngoại trú', data: cb.outpatient || [] },
        { name: 'Điều trị Nội trú', data: cb.inpatient || [] }
      ]);
    }

    // 3. CẬP NHẬT BIỂU ĐỒ TREEMAP (SỐ LƯỢNG BỆNH NHÂN TỪNG KHOA)
    const tm = data.treemap || data.overview?.treemap;
    if (tm && chartOverviewTreemap) {
      chartOverviewTreemap.updateSeries([{ data: tm }]);
    }

    // 4. CẬP NHẬT BIỂU ĐỒ DONUT ĐỐI TƯỢNG CHI TRẢ & GAUGE CÔNG SUẤT GIƯỜNG
    if (data.outpatient && data.outpatient.by_payer && chartPayerDonut) {
      const pLabels = data.outpatient.by_payer.map(p => p.payer_type_name);
      const pSeries = data.outpatient.by_payer.map(p => Number(p.visits || 0));
      if (pSeries.length > 0) {
        chartPayerDonut.updateOptions({ labels: pLabels }, false);
        chartPayerDonut.updateSeries(pSeries);
      }
    }
    if (data.overview && data.overview.cong_suat_giuong !== undefined && typeof chartBedGauge !== 'undefined' && chartBedGauge) {
      chartBedGauge.updateSeries([Number(data.overview.cong_suat_giuong)]);
    }

    // 5. CẬP NHẬT BẢNG CHI TIẾT BÊN DƯỚI THEO BỘ LỌC
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');
    const rows = (data.tables && data.tables.cumulative && data.tables.cumulative.length > 0) ? data.tables.cumulative : (data.tables?.kcb || []);

    if (tbody && rows.length > 0) {
      tbody.innerHTML = '';
      let sumCol1 = 0, sumCol2 = 0, sumCol3 = 0, lastCum = 0, annualTarget = 45000;

      rows.forEach(row => {
        const v1 = parseInt(String(row.col1).replace(/\D/g, '')) || 0;
        const v2 = parseInt(String(row.col2).replace(/\D/g, '')) || 0;
        const v3 = parseInt(String(row.col3).replace(/[^\d-]/g, '')) || 0;
        const v5 = parseInt(String(row.col5).replace(/\D/g, '')) || 0;
        const v6 = parseInt(String(row.col6).replace(/\D/g, '')) || 0;

        sumCol1 += v1;
        sumCol2 += v2;
        sumCol3 += v3;
        if (v5 > lastCum) lastCum = v5;
        if (v6 > 0) annualTarget = v6;

        const isHighlight = row.name && row.name.indexOf('★') !== -1;
        const tr = document.createElement('tr');
        if (isHighlight) {
          tr.style.background = 'rgba(0, 242, 254, 0.08)';
          tr.style.fontWeight = '700';
        }

        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right; font-weight:700; color:#fff;">${row.col1}</td>
          <td style="text-align:right; color:#85a4c4;">${row.col2}</td>
          <td style="text-align:right; font-weight:600; color:${v3 >= 0 ? '#2ecc71' : '#ff5c5c'};">${row.col3}</td>
          <td style="text-align:right; font-weight:700; color:${v3 >= 0 ? '#2ecc71' : '#ff5c5c'};">${row.col4}</td>
          <td style="text-align:right; font-weight:700; color:#ffc107;">${row.col5}</td>
          <td style="text-align:right; color:#85a4c4;">${row.col6}</td>
          <td style="text-align:right; font-weight:700; color:var(--teal);">${row.col7}</td>
          <td style="text-align:right; color:#85a4c4;">${row.col8}</td>
          <td style="text-align:right; font-weight:600; color:#20c6b7;">${row.col9}</td>
          <td style="text-align:right; color:#b2cbe4;">${row.col10}</td>
          <td style="text-align:center;">
            <span class="status-pill ${row.status_type}">${row.status}</span>
          </td>
        `;
        tbody.appendChild(tr);
      });

      const avgGrowth = sumCol2 > 0 ? (((sumCol1 - sumCol2) / sumCol2) * 100).toFixed(1) : '0.0';
      const cumRate = annualTarget > 0 ? ((lastCum / annualTarget) * 100).toFixed(1) : '60.9';

      tfoot.innerHTML = `
        <tr>
          <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Hợp Toàn Kỳ Phân Tích:</td>
          <td style="text-align:right; font-weight:800; color:#fff;">${sumCol1.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:#85a4c4;">${sumCol2.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; font-weight:700; color:${sumCol3 >= 0 ? '#2ecc71' : '#ff5c5c'};">${sumCol3 >= 0 ? '+' : ''}${sumCol3.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; font-weight:800; color:${avgGrowth >= 0 ? '#2ecc71' : '#ff5c5c'};">${avgGrowth >= 0 ? '+' : ''}${avgGrowth}%</td>
          <td style="text-align:right; font-weight:800; color:#ffc107;">${lastCum.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:#85a4c4;">${annualTarget.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; font-weight:800; color:var(--teal);">${cumRate}%</td>
          <td style="text-align:right;">--</td>
          <td style="text-align:right;">--</td>
          <td style="text-align:right;">--</td>
          <td style="text-align:center;"><span class="status-pill ok">Tiến độ tốt</span></td>
        </tr>
      `;
    }
  } catch (err) {
    console.warn("Lỗi fetch dữ liệu:", err);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
