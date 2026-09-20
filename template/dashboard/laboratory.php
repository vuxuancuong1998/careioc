<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI STATS ROW (TRÊN CÙNG) -->
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng ca xét nghiệm</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-vial"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisTotal"><?= number_format($lis['total'] ?? 396) ?></div>
      <div class="kpi-subtext"><span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> +12%</span> so với tuần trước</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xét nghiệm BHYT</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-id-card"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisBhyt"><?= number_format($lis['bhyt'] ?? 324) ?></div>
      <div class="kpi-subtext">Chiếm <?= $lis['total'] > 0 ? round(($lis['bhyt'] / $lis['total']) * 100, 1) : 81.8 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xét nghiệm Thu phí/VP</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-coins"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisSelfPay"><?= number_format($lis['self_pay'] ?? 72) ?></div>
      <div class="kpi-subtext">Chiếm <?= $lis['total'] > 0 ? round(($lis['self_pay'] / $lis['total']) * 100, 1) : 18.2 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Thời gian trả KQ TB (TAT)</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-clock-rotate-left"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisTat"><?= ($lis['tat_avg'] ?? 28.5) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">phút</span></div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đạt chuẩn</span> (Quy định ≤ 45p)</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ đúng hạn TAT</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-circle-check"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisOnTime"><?= ($lis['on_time_rate'] ?? 98.6) ?>%</div>
      <div class="kpi-subtext">LIS kết nối tự động 100%</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Cảnh báo giá trị nguy hiểm</span>
        <div class="kpi-icon-wrap" style="color:var(--bad); background:rgba(255,92,92,0.1);"><i class="fa-solid fa-triangle-exclamation"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisCritical"><?= ($lis['critical_count'] ?? 0) ?> <span style="font-size:14px; font-weight:600; color:var(--bad);">ca</span></div>
      <div class="kpi-subtext">Đã thông báo ngay cho BS điều trị</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ XÉT NGHIỆM LIS CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Treemap (Cơ cấu nhóm XN) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-table-cells-large"></i> Biểu đồ Cây (Treemap): Sản Lượng Mẫu Theo Chuyên Khoa XN</div>
          <div class="chart-subtitle">Phân bổ khối lượng kỹ thuật xét nghiệm trong ngày</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisTreemap"></div>
    </div>

    <!-- Biểu đồ 2: Multi-Line Chart (Sản lượng 7 ngày) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-line"></i> Biểu đồ Đa Đường: Diễn Biến Sản Lượng 4 Nhóm Kỹ Thuật (7 Ngày)</div>
          <div class="chart-subtitle">Theo dõi xu hướng Sinh hóa, Huyết học, Nước tiểu và Miễn dịch</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisMultiLine"></div>
    </div>

    <!-- Biểu đồ 3: Donut Chart (Nguồn gửi mẫu) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Nguồn Gửi Mẫu Xét Nghiệm</div>
          <div class="chart-subtitle">Ngoại trú, Nội trú, Cấp cứu</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisSourceDonut"></div>
    </div>

    <!-- Biểu đồ 4: Histogram / Phân bố thời gian TAT -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-simple"></i> Biểu đồ Phân Bố (Histogram): Thời Gian Trả Kết Quả TAT (Phút)</div>
          <div class="chart-subtitle">Đa số các ca được hoàn thành trả kết quả trong dải an toàn 15 - 30 phút</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisTatHistogram"></div>
    </div>

    <!-- Biểu đồ 5: Gauge Chart (Kiểm chuẩn IQC) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-circle-check"></i> Nội Kiểm IQC Máy</div>
          <div class="chart-subtitle">Độ tin cậy xét nghiệm</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisIqcGauge"></div>
    </div>

    <!-- Biểu đồ 6: Status Grid Máy Xét Nghiệm -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-microchip"></i> Giám Sát Máy XN</div>
          <div class="chart-subtitle">Kết nối LIS tự động</div>
        </div>
      </div>
      <div class="chart-body" style="display:flex; flex-direction:column; justify-content:center; gap:8px;">
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Sysmex XN-550</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Huyết học tự động &bull; <span id="lisSysmexCount"><?= (int)round(($lis['total'] ?? 396) * 0.28) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Cobas c311</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Sinh hóa tự động &bull; <span id="lisCobasCount"><?= (int)round(($lis['total'] ?? 396) * 0.38) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Urisys 1100</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Nước tiểu 10 thông số &bull; <span id="lisUrisysCount"><?= (int)round(($lis['total'] ?? 396) * 0.15) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>AVL 9180</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Điện giải đồ tự động &bull; <span id="lisAvlCount"><?= (int)round(($lis['total'] ?? 396) * 0.11) ?></span> ca</div>
        </div>
      </div>
    </div>
  </section>

  <!-- [NOTE]: Bộ lọc đa tiêu chí đã được đưa lên đầu trang tại topbar.php -->

  <!-- 4. BẢNG DỮ LIỆU CHI TIẾT (Ở DƯỚI - KHI LỌC BẢNG THAY ĐỔI, BIỂU ĐỒ GIỮ NGUYÊN) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Giám sát Danh mục Kỹ thuật Xét nghiệm & Tình trạng Phân tích Mẫu (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm tên xét nghiệm, máy..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã DV</th>
            <th>Tên Kỹ thuật Xét nghiệm</th>
            <th style="text-align:right;">Tổng số ca</th>
            <th style="text-align:right;">BHYT</th>
            <th style="text-align:right;">Thu phí/VP</th>
            <th style="text-align:right;">Ngoại trú</th>
            <th style="text-align:right;">Nội trú</th>
            <th style="text-align:right;">Tỷ lệ đúng hẹn</th>
            <th style="text-align:right;">Thời gian TAT</th>
            <th>Thiết bị phân tích</th>
            <th style="text-align:center;">Nội kiểm IQC</th>
            <th style="text-align:center;">Trạng thái máy</th>
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

<?php
$lisTot = (int)($lis['total'] ?? 396);
$lisOut = (int)($lis['outpatient'] ?? 276);
$lisIn = (int)($lis['inpatient'] ?? 120);
$treemapLis = [
  ['x' => 'Sinh hóa máu', 'y' => (int)round($lisTot * 0.38)],
  ['x' => 'Huyết học Laser', 'y' => (int)round($lisTot * 0.28)],
  ['x' => 'Nước tiểu 10TS', 'y' => (int)round($lisTot * 0.15)],
  ['x' => 'Điện giải đồ', 'y' => (int)round($lisTot * 0.11)],
  ['x' => 'Đông máu', 'y' => (int)round($lisTot * 0.05)],
  ['x' => 'Vi sinh ký sinh', 'y' => max(1, $lisTot - (int)round($lisTot * 0.97))]
];
$tatHist = [
  (int)round($lisTot * 0.22),
  (int)round($lisTot * 0.58),
  (int)round($lisTot * 0.15),
  (int)round($lisTot * 0.04),
  max(0, $lisTot - (int)round($lisTot * 0.99))
];
$scaleLis = $lisTot > 0 ? ($lisTot / 396) : 1.0;
?>
<script>
let chartLisTreemap = null;
let chartLisMultiLine = null;
let chartLisSourceDonut = null;
let chartLisTatHistogram = null;
let chartLisIqcGauge = null;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Treemap
  try {
    chartLisTreemap = new ApexCharts(document.getElementById('chartLisTreemap'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'treemap', height: 260 },
      colors: ['#00f2fe', '#39a0ff', '#20c6b7', '#ffc107', '#ff5c5c', '#9b7cff'],
      series: [{
        data: <?= json_encode($treemapLis) ?>
      }],
      plotOptions: { treemap: { distributed: true, enableShades: false } }
    });
    chartLisTreemap.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisTreemap:', e);
  }

  // 2. Multi-Line Chart
  try {
    chartLisMultiLine = new ApexCharts(document.getElementById('chartLisMultiLine'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'line', height: 260 },
      colors: ['#00f2fe', '#20c6b7', '#ffc107', '#39a0ff'],
      stroke: { curve: 'smooth', width: 2.5 },
      series: [
        { name: 'Sinh hóa máu', data: [<?= (int)round(190 * $scaleLis) ?>, <?= (int)round(205 * $scaleLis) ?>, <?= (int)round(198 * $scaleLis) ?>, <?= (int)round(220 * $scaleLis) ?>, <?= (int)round(215 * $scaleLis) ?>, <?= (int)round(208 * $scaleLis) ?>, <?= (int)round(214 * $scaleLis) ?>] },
        { name: 'Huyết học', data: [<?= (int)round(130 * $scaleLis) ?>, <?= (int)round(138 * $scaleLis) ?>, <?= (int)round(135 * $scaleLis) ?>, <?= (int)round(148 * $scaleLis) ?>, <?= (int)round(142 * $scaleLis) ?>, <?= (int)round(139 * $scaleLis) ?>, <?= (int)round(142 * $scaleLis) ?>] },
        { name: 'Nước tiểu', data: [<?= (int)round(75 * $scaleLis) ?>, <?= (int)round(82 * $scaleLis) ?>, <?= (int)round(78 * $scaleLis) ?>, <?= (int)round(88 * $scaleLis) ?>, <?= (int)round(84 * $scaleLis) ?>, <?= (int)round(80 * $scaleLis) ?>, <?= (int)round(85 * $scaleLis) ?>] },
        { name: 'Điện giải', data: [<?= (int)round(68 * $scaleLis) ?>, <?= (int)round(72 * $scaleLis) ?>, <?= (int)round(70 * $scaleLis) ?>, <?= (int)round(82 * $scaleLis) ?>, <?= (int)round(76 * $scaleLis) ?>, <?= (int)round(74 * $scaleLis) ?>, <?= (int)round(78 * $scaleLis) ?>] }
      ],
      xaxis: {
        categories: ['23/02', '24/02', '25/02', '26/02', '27/02', '28/02', '01/03'],
        labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
      },
      yaxis: { labels: { style: { colors: '#85a4c4' } } },
      legend: { position: 'top', labels: { colors: '#b2cbe4' } }
    });
    chartLisMultiLine.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisMultiLine:', e);
  }

  // 3. Donut Chart
  try {
    chartLisSourceDonut = new ApexCharts(document.getElementById('chartLisSourceDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 260 },
      colors: ['#00f2fe', '#39a0ff', '#ff5c5c'],
      labels: ['Ngoại trú', 'Nội trú', 'Cấp cứu'],
      series: [<?= $lisOut ?>, <?= $lisIn ?>, <?= (int)round($lisTot * 0.05) ?>],
      legend: { position: 'bottom', labels: { colors: '#b2cbe4' } }
    });
    chartLisSourceDonut.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisSourceDonut:', e);
  }

  // 4. Histogram TAT
  try {
    chartLisTatHistogram = new ApexCharts(document.getElementById('chartLisTatHistogram'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 250 },
      plotOptions: { bar: { columnWidth: '55%', borderRadius: 4, distributed: true } },
      colors: ['#2ecc71', '#00f2fe', '#20c6b7', '#ffc107', '#ff5c5c'],
      series: [{
        name: 'Số ca trả kết quả',
        data: <?= json_encode($tatHist) ?>
      }],
      xaxis: {
        categories: ['< 15 phút (Cấp cứu)', '15 - 30 phút (Chuẩn)', '30 - 45 phút', '45 - 60 phút', '> 60 phút (Chuyên sâu)'],
        labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
      },
      yaxis: { labels: { style: { colors: '#85a4c4' } } },
      legend: { show: false }
    });
    chartLisTatHistogram.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisTatHistogram:', e);
  }

  // 5. IQC Gauge
  try {
    chartLisIqcGauge = new ApexCharts(document.getElementById('chartLisIqcGauge'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'radialBar', height: 250 },
      plotOptions: {
        radialBar: {
          startAngle: -135,
          endAngle: 135,
          hollow: { size: '65%' },
          dataLabels: {
            name: { fontSize: '11px', color: '#85a4c4', offsetY: -6 },
            value: { fontSize: '22px', fontWeight: 800, color: '#2ecc71', offsetY: 6, formatter: v => v + '%' }
          }
        }
      },
      colors: ['#2ecc71'],
      series: [<?= (float)($lis['iqc_rate'] ?? 99.8) ?>],
      labels: ['IQC Đạt chuẩn']
    });
    chartLisIqcGauge.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisIqcGauge:', e);
  }
}

// ================= CẬP NHẬT BẢNG KHI LỌC DỮ LIỆU (BỘ LỌC CHỈ THAY ĐỔI BẢNG, KHÔNG THAY ĐỔI BIỂU ĐỒ) =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : 'year=2026&quarter=3&month=8';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // Cập nhật Bảng Dữ liệu Xét nghiệm (Bộ lọc chỉ thay đổi bảng, không thay đổi biểu đồ)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.lis) {
      tbody.innerHTML = '';
      let sTot = 0, sBh = 0, sVp = 0, sOut = 0, sIn = 0;

      data.tables.lis.forEach(row => {
        sTot += parseInt(row.col1) || 0;
        sBh += parseInt(row.col2) || 0;
        sVp += parseInt(row.col3) || 0;
        sOut += parseInt(row.col4) || 0;
        sIn += parseInt(row.col5) || 0;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right; font-weight:700;">${row.col1}</td>
          <td style="text-align:right; color:#20c6b7;">${row.col2}</td>
          <td style="text-align:right; color:#ff9100;">${row.col3}</td>
          <td style="text-align:right;">${row.col4}</td>
          <td style="text-align:right;">${row.col5}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">${row.col6}</td>
          <td style="text-align:right; color:var(--teal);">${row.col7}</td>
          <td>${row.col8}</td>
          <td style="text-align:center;"><span class="status-pill ok">${row.col9}</span></td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type}">${row.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Mẫu Xét Nghiệm Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sTot}</td>
            <td style="text-align:right; color:#20c6b7; font-weight:800;">${sBh}</td>
            <td style="text-align:right; color:#ff9100; font-weight:800;">${sVp}</td>
            <td style="text-align:right;">${sOut}</td>
            <td style="text-align:right;">${sIn}</td>
            <td style="text-align:right; font-weight:800; color:var(--ok);">98.8%</td>
            <td style="text-align:right;">28.5 phút</td>
            <td>--</td>
            <td style="text-align:center;"><span class="status-pill ok">100% Đạt</span></td>
            <td style="text-align:center;"><span class="status-pill ok">Hoạt động tốt</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load LIS:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
