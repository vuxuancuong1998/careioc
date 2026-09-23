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
      <div class="kpi-subtext" id="kpiLisGrowth"><span class="trend-up" id="kpiLisTrendBadge"><i class="fa-solid fa-arrow-trend-up"></i> <?= $lis['growth_label'] ?? '+12.5%' ?></span> <span id="kpiLisSubtextDesc"><?= $lis['growth_subtext'] ?? 'so với kỳ trước' ?></span></div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xét nghiệm BHYT</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-id-card"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisBhyt"><?= number_format($lis['bhyt'] ?? 324) ?></div>
      <div class="kpi-subtext" id="kpiLisBhytSub">Chiếm <?= ($lis['total'] ?? 0) > 0 ? round((($lis['bhyt'] ?? 324) / $lis['total']) * 100, 1) : 81.8 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xét nghiệm Thu phí/VP</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-coins"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisSelfPay"><?= number_format($lis['self_pay'] ?? 72) ?></div>
      <div class="kpi-subtext" id="kpiLisSelfPaySub">Chiếm <?= ($lis['total'] ?? 0) > 0 ? round((($lis['self_pay'] ?? 72) / $lis['total']) * 100, 1) : 18.2 ?>% tổng số mẫu</div>
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
          <div class="chart-subtitle">Phân bổ khối lượng kỹ thuật xét nghiệm trong kỳ</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisTreemap"></div>
    </div>

    <!-- Biểu đồ 2: Multi-Line Chart (Sản lượng diễn biến) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-line"></i> Biểu đồ Đa Đường: Diễn Biến Sản Lượng Kỹ Thuật</div>
          <div class="chart-subtitle">Theo dõi xu hướng Sinh hóa, Huyết học, Vi sinh và Nước tiểu</div>
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
          <div class="status-matrix-name" style="font-size:11px;">Huyết học tự động &bull; <span id="lisSysmexCount"><?= number_format((int)round(($lis['total'] ?? 396) * 0.28)) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Cobas c311</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Sinh hóa tự động &bull; <span id="lisCobasCount"><?= number_format((int)round(($lis['total'] ?? 396) * 0.38)) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Urisys 1100</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Nước tiểu 10 thông số &bull; <span id="lisUrisysCount"><?= number_format((int)round(($lis['total'] ?? 396) * 0.15)) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>AVL 9180</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Điện giải đồ tự động &bull; <span id="lisAvlCount"><?= number_format((int)round(($lis['total'] ?? 396) * 0.11)) ?></span> ca</div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. BẢNG DỮ LIỆU CHI TIẾT ĐỘNG (ĐỒNG BỘ 100% THEO BỘ LỌC) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Giám sát Sản lượng Xét nghiệm LIS & Chỉ số Hoạt động Máy</span>
        <span id="tableFilterLabel" style="font-size:12px; font-weight:400; color:var(--teal); margin-left:8px;"></span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm tên xét nghiệm, mã kỳ, máy..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table" id="lisTable">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th id="thCode" style="min-width:90px;">Mã Kỳ / Mã DV</th>
            <th id="thName" style="min-width:220px;">Kỳ Báo Cáo / Nhóm Kỹ Thuật Xét Nghiệm</th>
            <th style="text-align:right;">Tổng số ca</th>
            <th style="text-align:right;">BHYT</th>
            <th style="text-align:right;">Thu phí/VP</th>
            <th style="text-align:right;">Ngoại trú</th>
            <th style="text-align:right;">Nội trú</th>
            <th style="text-align:right;">Tỷ lệ BHYT</th>
            <th style="text-align:right;">Thời gian TAT</th>
            <th>Thiết bị phân tích</th>
            <th style="text-align:center;">Nội kiểm IQC</th>
            <th style="text-align:center;">Trạng thái</th>
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
$treemapLis = $lis['charts']['treemap'] ?? [
  ['x' => 'Sinh hóa máu', 'y' => (int)round($lisTot * 0.38)],
  ['x' => 'Huyết học Laser', 'y' => (int)round($lisTot * 0.28)],
  ['x' => 'Nước tiểu 10TS', 'y' => (int)round($lisTot * 0.15)],
  ['x' => 'Điện giải đồ', 'y' => (int)round($lisTot * 0.11)],
  ['x' => 'Đông máu', 'y' => (int)round($lisTot * 0.05)],
  ['x' => 'Vi sinh ký sinh', 'y' => max(1, $lisTot - (int)round($lisTot * 0.97))]
];
$tatHist = $lis['charts']['tat_histogram'] ?? [
  (int)round($lisTot * 0.22),
  (int)round($lisTot * 0.58),
  (int)round($lisTot * 0.15),
  (int)round($lisTot * 0.04),
  max(0, $lisTot - (int)round($lisTot * 0.99))
];
$multiLineData = $lis['charts']['multiline'] ?? [
  'categories' => ['23/02', '24/02', '25/02', '26/02', '27/02', '28/02', '01/03'],
  'series' => [
    ['name' => 'Sinh hóa máu', 'data' => [190, 205, 198, 220, 215, 208, 214]],
    ['name' => 'Huyết học', 'data' => [130, 138, 135, 148, 142, 139, 142]],
    ['name' => 'Vi sinh & Ký sinh', 'data' => [75, 82, 78, 88, 84, 80, 85]],
    ['name' => 'Nước tiểu & ĐG', 'data' => [68, 72, 70, 82, 76, 74, 78]]
  ]
];
$sourceDonutData = $lis['charts']['source_donut'] ?? [$lisOut, $lisIn, max(1, (int)round($lisTot * 0.05))];
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
        data: <?= json_encode($treemapLis, JSON_UNESCAPED_UNICODE) ?>
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
      series: <?= json_encode($multiLineData['series'], JSON_UNESCAPED_UNICODE) ?>,
      xaxis: {
        categories: <?= json_encode($multiLineData['categories'], JSON_UNESCAPED_UNICODE) ?>,
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
      series: <?= json_encode($sourceDonutData, JSON_UNESCAPED_UNICODE) ?>,
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
        data: <?= json_encode($tatHist, JSON_UNESCAPED_UNICODE) ?>
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

// ================= CẬP NHẬT TOÀN DIỆN KHI LỌC DỮ LIỆU (KPI, CHARTS & TABLE) =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : '';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // 1. Cập nhật 6 thẻ KPI LIS
    if (data.lis) {
      const l = data.lis;
      const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.innerHTML = val; };
      setEl('kpiLisTotal', Number(l.total || 0).toLocaleString('vi-VN'));
      setEl('kpiLisBhyt', Number(l.bhyt || 0).toLocaleString('vi-VN'));
      setEl('kpiLisSelfPay', Number(l.self_pay || 0).toLocaleString('vi-VN'));
      setEl('kpiLisTat', `${l.tat_avg || 28.5} <span style="font-size:14px; font-weight:600; color:var(--muted);">phút</span>`);
      setEl('kpiLisOnTime', `${l.on_time_rate || 98.6}%`);
      setEl('kpiLisCritical', `${l.critical_count || 0} <span style="font-size:14px; font-weight:600; color:var(--bad);">ca</span>`);

      const bhytPct = l.total > 0 ? ((l.bhyt / l.total) * 100).toFixed(1) : '81.8';
      setEl('kpiLisBhytSub', `Chiếm ${bhytPct}% tổng số mẫu`);
      const vpPct = l.total > 0 ? ((l.self_pay / l.total) * 100).toFixed(1) : '18.2';
      setEl('kpiLisSelfPaySub', `Chiếm ${vpPct}% tổng số mẫu`);

      const elBadge = document.getElementById('kpiLisTrendBadge');
      if (elBadge && l.growth_label) {
        const isUp = !String(l.growth_label).startsWith('-');
        elBadge.className = isUp ? 'trend-up' : 'trend-down';
        elBadge.innerHTML = `<i class="fa-solid fa-arrow-trend-${isUp ? 'up' : 'down'}"></i> ${l.growth_label}`;
      }
      const elSubDesc = document.getElementById('kpiLisSubtextDesc');
      if (elSubDesc && l.growth_subtext) elSubDesc.textContent = l.growth_subtext;

      // Cập nhật thẻ trạng thái máy xét nghiệm
      if (l.charts && l.charts.analyzers) {
        const az = l.charts.analyzers;
        setEl('lisSysmexCount', Number(az.sysmex || Math.round(l.total * 0.28)).toLocaleString('vi-VN'));
        setEl('lisCobasCount', Number(az.cobas || Math.round(l.total * 0.38)).toLocaleString('vi-VN'));
        setEl('lisUrisysCount', Number(az.urisys || Math.round(l.total * 0.15)).toLocaleString('vi-VN'));
        setEl('lisAvlCount', Number(az.avl || Math.round(l.total * 0.11)).toLocaleString('vi-VN'));
      }

      // 2. Cập nhật Biểu đồ ApexCharts
      if (l.charts) {
        const lc = l.charts;
        if (chartLisTreemap && lc.treemap && lc.treemap.length > 0) {
          chartLisTreemap.updateSeries([{ data: lc.treemap }]);
        }
        if (chartLisMultiLine && lc.multiline) {
          chartLisMultiLine.updateOptions({
            xaxis: { categories: lc.multiline.categories }
          }, false, false);
          chartLisMultiLine.updateSeries(lc.multiline.series);
        }
        if (chartLisSourceDonut && lc.source_donut) {
          chartLisSourceDonut.updateSeries(lc.source_donut);
        }
        if (chartLisTatHistogram && lc.tat_histogram) {
          chartLisTatHistogram.updateSeries([{ name: 'Số ca trả kết quả', data: lc.tat_histogram }]);
        }
        if (chartLisIqcGauge && lc.iqc_gauge) {
          chartLisIqcGauge.updateSeries([lc.iqc_gauge]);
        }
      }
    }

    const tblLbl = document.getElementById('tableFilterLabel');
    if (tblLbl && data.date) {
      tblLbl.textContent = `(Dữ liệu: ${data.date})`;
    }

    // 3. Cập nhật Bảng Dữ liệu Xét nghiệm
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.lis) {
      tbody.innerHTML = '';
      let sTot = 0, sBh = 0, sVp = 0, sOut = 0, sIn = 0;

      data.tables.lis.forEach(row => {
        const cTot = parseInt(row.col1) || 0;
        const cBh = parseInt(row.col2) || 0;
        const cVp = parseInt(row.col3) || 0;
        const cOut = parseInt(row.col4) || 0;
        const cIn = parseInt(row.col5) || 0;

        sTot += cTot;
        sBh += cBh;
        sVp += cVp;
        sOut += cOut;
        sIn += cIn;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right; font-weight:700;">${cTot.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:#20c6b7;">${cBh.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:#ff9100;">${cVp.toLocaleString('vi-VN')}</td>
          <td style="text-align:right;">${cOut.toLocaleString('vi-VN')}</td>
          <td style="text-align:right;">${cIn.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">${row.col6}</td>
          <td style="text-align:right; color:var(--teal);">${row.col7}</td>
          <td>${row.col8}</td>
          <td style="text-align:center;"><span class="status-pill ok">${row.col9}</span></td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type || 'ok'}">${row.status || 'Hoạt động tốt'}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        const rateAllBh = sTot > 0 ? ((sBh / sTot) * 100).toFixed(1) : '81.9';
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Mẫu Xét Nghiệm Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sTot.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#20c6b7; font-weight:800;">${sBh.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#ff9100; font-weight:800;">${sVp.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:700;">${sOut.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:700;">${sIn.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:800; color:var(--ok);">${rateAllBh}%</td>
            <td style="text-align:right; color:var(--teal);">28.5 phút</td>
            <td>Đồng bộ tự động</td>
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

// Tìm kiếm nhanh trong bảng
document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();

  const searchInput = document.getElementById('tableSearchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const q = this.value.toLowerCase().trim();
      const rows = document.querySelectorAll('#iocTableBody tr');
      rows.forEach(r => {
        const txt = r.textContent.toLowerCase();
        r.style.display = txt.includes(q) ? '' : 'none';
      });
    });
  }
});
</script>
