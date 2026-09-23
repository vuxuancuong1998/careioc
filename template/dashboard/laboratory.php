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
        <span class="kpi-label">Tổng mẫu xét nghiệm</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-vial"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisTotal"><?= number_format($lis['total'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiLisGrowth"><span class="trend-up" id="kpiLisTrendBadge"><i class="fa-solid fa-arrow-trend-up"></i> <?= $lis['growth_label'] ?? '+0.0%' ?></span> <span id="kpiLisSubtextDesc"><?= $lis['growth_subtext'] ?? 'so với kỳ trước' ?></span></div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Mẫu xét nghiệm BHYT</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-id-card"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisBhyt"><?= number_format($lis['bhyt'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiLisBhytSub">Chiếm <?= ($lis['total'] ?? 0) > 0 ? round((($lis['bhyt'] ?? 0) / $lis['total']) * 100, 1) : 0 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Mẫu xét nghiệm Viện phí</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-coins"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisSelfPay"><?= number_format($lis['self_pay'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiLisSelfPaySub">Chiếm <?= ($lis['total'] ?? 0) > 0 ? round((($lis['self_pay'] ?? 0) / $lis['total']) * 100, 1) : 0 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xét nghiệm Ngoại trú</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-hospital-user"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisOutpatient"><?= number_format($lis['outpatient'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiLisOutpatientSub">Chiếm <?= ($lis['total'] ?? 0) > 0 ? round((($lis['outpatient'] ?? 0) / $lis['total']) * 100, 1) : 0 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xét nghiệm Nội trú</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-bed-pulse"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisInpatient"><?= number_format($lis['inpatient'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiLisInpatientSub">Chiếm <?= ($lis['total'] ?? 0) > 0 ? round((($lis['inpatient'] ?? 0) / $lis['total']) * 100, 1) : 0 ?>% tổng số mẫu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ xét nghiệm BHYT</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-shield-halved"></i></div>
      </div>
      <div class="kpi-value" id="kpiLisBhytRate"><?= ($lis['total'] ?? 0) > 0 ? round((($lis['bhyt'] ?? 0) / $lis['total']) * 100, 1) : 0 ?>%</div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đối tượng BHYT</span> chiếm đa số</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ XÉT NGHIỆM LIS CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Treemap (Sản lượng chuyên khoa, phong cách Bento Card số nổi bật ở góc như trang Tổng quan) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-table-cells-large"></i> Sản Lượng Theo Chuyên Khoa Xét Nghiệm</div>
          <div class="chart-subtitle">Phân bổ số lượng mẫu thực hiện tại từng phòng chuyên khoa (<span id="lisTreemapTotalSub" style="color:var(--teal); font-weight:600;"><?= number_format($lisTot) ?> mẫu</span>)</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisTreemap"></div>
    </div>

    <!-- Biểu đồ 2: Multi-Line Chart (Diễn biến sản lượng theo chuyên khoa) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-line"></i> Diễn Biến Sản Lượng Theo Chuyên Khoa</div>
          <div class="chart-subtitle">Theo dõi biến động số lượng mẫu Huyết học, Sinh hóa và Vi sinh theo thời gian</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisMultiLine"></div>
    </div>

    <!-- Biểu đồ 3: Donut Chart (Cơ cấu nguồn gửi mẫu) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Cơ Cấu Nguồn Bệnh Nhân Gửi Mẫu</div>
          <div class="chart-subtitle">Tỷ lệ phân bổ mẫu chỉ định từ Khám Ngoại trú và Điều trị Nội trú</div>
        </div>
      </div>
      <div class="chart-body" id="chartLisSourceDonut"></div>
    </div>
  </section>

  <!-- 3. BẢNG DỮ LIỆU CHI TIẾT ĐỘNG (100% DỮ LIỆU THỰC TỪ CSDL) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Tổng Hợp Sản Lượng Xét Nghiệm Toàn Viện</span>
        <span id="tableFilterLabel" style="font-size:12px; font-weight:400; color:var(--teal); margin-left:8px;"></span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm kiếm theo kỳ báo cáo, chuyên khoa xét nghiệm, mã khoa..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table" id="lisTable">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th id="thCode" style="min-width:90px;">Mã kỳ / Mã khoa</th>
            <th id="thName" style="min-width:220px;">Kỳ báo cáo / Chuyên khoa xét nghiệm</th>
            <th style="text-align:right;">Tổng số mẫu</th>
            <th style="text-align:right;">Mẫu BHYT</th>
            <th style="text-align:right;">Mẫu Viện phí</th>
            <th style="text-align:right;">Ngoại trú</th>
            <th style="text-align:right;">Nội trú</th>
            <th style="text-align:right;">Tỷ lệ BHYT</th>
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
$lisTot = (int)($lis['total'] ?? 0);
$lisOut = (int)($lis['outpatient'] ?? 0);
$lisIn = (int)($lis['inpatient'] ?? 0);
$treemapLis = $lis['charts']['treemap'] ?? [];
$multiLineData = $lis['charts']['multiline'] ?? [
  'categories' => [],
  'series' => []
];
$sourceDonutData = $lis['charts']['source_donut'] ?? [$lisOut, $lisIn];
?>
<script>
let chartLisTreemap = null;
let chartLisMultiLine = null;
let chartLisSourceDonut = null;

function initCharts() {
  const isLight = document.documentElement.getAttribute('data-theme') === 'light';
  const chartTheme = {
    theme: { mode: isLight ? 'light' : 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: isLight ? 'rgba(203, 213, 225, 0.7)' : 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Treemap: Bento Card Grid phong cách "Số Lượng Bệnh Nhân Khám Theo Từng Khoa" (Số lượng ở góc nổi bật)
  function renderLisTreemap(containerId, rawData) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const items = (rawData || []).map(r => ({
      name: r.x || r.name || '',
      value: Number(r.y || r.value || 0)
    })).filter(r => r.value > 0).sort((a, b) => b.value - a.value);

    if (items.length === 0) {
      container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#85a4c4;font-size:12.5px;">Chưa có dữ liệu mẫu xét nghiệm</div>';
      return;
    }

    const total = items.reduce((s, i) => s + i.value, 0);

    const palette = [
      { bg: 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)', border: 'rgba(56, 189, 248, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #0f766e 0%, #115e59 100%)', border: 'rgba(45, 212, 191, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%)', border: 'rgba(167, 139, 250, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #c2410c 0%, #9a3412 100%)', border: 'rgba(251, 146, 60, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #15803d 0%, #166534 100%)', border: 'rgba(74, 222, 128, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #be123c 0%, #9f1239 100%)', border: 'rgba(251, 113, 133, 0.45)', numColor: '#ffd166' },
      { bg: 'linear-gradient(135deg, #0e7490 0%, #155e75 100%)', border: 'rgba(34, 211, 238, 0.45)', numColor: '#ffd166' }
    ];

    const W = container.clientWidth || 450;
    const H = 260;
    const GAP = 6;

    const shortNames = {
      'Huyết học': 'Huyết học',
      'Huyết học Laser': 'Huyết học',
      'Sinh hóa': 'Sinh hóa',
      'Sinh hóa máu': 'Sinh hóa',
      'Vi sinh': 'Vi sinh & Ký sinh',
      'Vi sinh & Ký sinh': 'Vi sinh',
      'Vi sinh ký sinh': 'Vi sinh',
      'Nước tiểu': 'Nước tiểu',
      'Nước tiểu 10TS': 'Nước tiểu 10TS',
      'Điện giải đồ': 'Điện giải đồ',
      'Đông máu': 'Đông máu'
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
      const wCol1 = Math.max(150, Math.min(Math.round(W * 0.52), Math.round(W * (sumCol1 / total))));

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
           title="${it.name}: ${Number(it.value).toLocaleString('vi-VN')} mẫu (${pct}%)">
          
          <!-- GÓC TRÊN BÊN TRÁI: TÊN CHUYÊN KHOA & % -->
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

          <!-- GÓC DƯỚI BÊN PHẢI: SỐ LƯỢNG MẪU NỔI BẬT Ở GÓC -->
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
            ">MẪU</span>
          </div>
        </div>
      `;
    });

    html += `</div>`;
    container.innerHTML = html;
  }

  let currentLisTreemapData = <?= json_encode($treemapLis, JSON_UNESCAPED_UNICODE) ?>;
  renderLisTreemap('chartLisTreemap', currentLisTreemapData);

  chartLisTreemap = {
    updateSeries: function(series) {
      if (series && series[0] && series[0].data) {
        currentLisTreemapData = series[0].data;
        renderLisTreemap('chartLisTreemap', currentLisTreemapData);
      }
    }
  };

  window.addEventListener('resize', function() {
    renderLisTreemap('chartLisTreemap', currentLisTreemapData);
  });

  // 2. Multi-Line Chart
  try {
    const isL = document.documentElement.getAttribute('data-theme') === 'light';
    const mlColors = isL ? ['#0284c7', '#0d9488', '#ea580c'] : ['#00f2fe', '#20c6b7', '#ff9100'];
    chartLisMultiLine = new ApexCharts(document.getElementById('chartLisMultiLine'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'line', height: 260 },
      colors: mlColors,
      stroke: { curve: 'smooth', width: 2.5 },
      series: <?= json_encode($multiLineData['series'], JSON_UNESCAPED_UNICODE) ?>,
      xaxis: {
        categories: <?= json_encode($multiLineData['categories'], JSON_UNESCAPED_UNICODE) ?>,
        labels: { style: { colors: isL ? '#334155' : '#85a4c4', fontSize: '11px' } }
      },
      yaxis: { labels: { style: { colors: isL ? '#334155' : '#85a4c4' } } },
      legend: { position: 'top', labels: { colors: isL ? '#1e293b' : '#b2cbe4' } }
    });
    chartLisMultiLine.render();
    window.chartLisMultiLine = chartLisMultiLine;
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisMultiLine:', e);
  }

  // 3. Donut Chart
  try {
    const isL = document.documentElement.getAttribute('data-theme') === 'light';
    const donutColors = isL ? ['#0284c7', '#3b82f6'] : ['#00f2fe', '#39a0ff'];
    chartLisSourceDonut = new ApexCharts(document.getElementById('chartLisSourceDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 260 },
      colors: donutColors,
      labels: ['Ngoại trú', 'Nội trú'],
      series: <?= json_encode($sourceDonutData, JSON_UNESCAPED_UNICODE) ?>,
      legend: { position: 'bottom', labels: { colors: isL ? '#1e293b' : '#b2cbe4' } }
    });
    chartLisSourceDonut.render();
    window.chartLisSourceDonut = chartLisSourceDonut;
  } catch(e) {
    console.error('Lỗi khởi tạo chartLisSourceDonut:', e);
  }
}

// Lắng nghe sự kiện chuyển đổi Sáng / Tối để đổi màu chữ trục, màu đường biểu đồ và lưới
window.addEventListener('themeChanged', function(e) {
  const isL = e.detail && e.detail.theme === 'light';
  const labelColor = isL ? '#334155' : '#85a4c4';
  const legendColor = isL ? '#1e293b' : '#b2cbe4';
  const gridBorder = isL ? 'rgba(203, 213, 225, 0.7)' : 'rgba(38, 76, 115, 0.35)';
  const mlColors = isL ? ['#0284c7', '#0d9488', '#ea580c'] : ['#00f2fe', '#20c6b7', '#ff9100'];
  const donutColors = isL ? ['#0284c7', '#3b82f6'] : ['#00f2fe', '#39a0ff'];

  if (window.chartLisMultiLine && typeof window.chartLisMultiLine.updateOptions === 'function') {
    window.chartLisMultiLine.updateOptions({
      theme: { mode: isL ? 'light' : 'dark' },
      colors: mlColors,
      grid: { borderColor: gridBorder },
      xaxis: { labels: { style: { colors: labelColor } } },
      yaxis: { labels: { style: { colors: labelColor } } },
      legend: { labels: { colors: legendColor } }
    }, false, false);
  }
  if (window.chartLisSourceDonut && typeof window.chartLisSourceDonut.updateOptions === 'function') {
    window.chartLisSourceDonut.updateOptions({
      theme: { mode: isL ? 'light' : 'dark' },
      colors: donutColors,
      legend: { labels: { colors: legendColor } }
    }, false, false);
  }
});

// ================= CẬP NHẬT TOÀN DIỆN KHI LỌC DỮ LIỆU (KPI, CHARTS & TABLE) =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : '';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // 1. Cập nhật 6 thẻ KPI LIS (100% dữ liệu thực từ CSDL)
    if (data.lis) {
      const l = data.lis;
      const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.innerHTML = val; };
      setEl('kpiLisTotal', Number(l.total || 0).toLocaleString('vi-VN'));
      setEl('kpiLisBhyt', Number(l.bhyt || 0).toLocaleString('vi-VN'));
      setEl('kpiLisSelfPay', Number(l.self_pay || 0).toLocaleString('vi-VN'));
      setEl('kpiLisOutpatient', Number(l.outpatient || 0).toLocaleString('vi-VN'));
      setEl('kpiLisInpatient', Number(l.inpatient || 0).toLocaleString('vi-VN'));
      setEl('kpiLisBhytRate', `${l.bhyt_rate || (l.total > 0 ? ((l.bhyt / l.total) * 100).toFixed(1) : '0')}%`);

      const bhytPct = l.total > 0 ? ((l.bhyt / l.total) * 100).toFixed(1) : '0';
      setEl('kpiLisBhytSub', `Chiếm ${bhytPct}% tổng số mẫu`);
      const vpPct = l.total > 0 ? ((l.self_pay / l.total) * 100).toFixed(1) : '0';
      setEl('kpiLisSelfPaySub', `Chiếm ${vpPct}% tổng số mẫu`);
      const outPct = l.total > 0 ? ((l.outpatient / l.total) * 100).toFixed(1) : '0';
      setEl('kpiLisOutpatientSub', `Chiếm ${outPct}% tổng số mẫu`);
      const inPct = l.total > 0 ? ((l.inpatient / l.total) * 100).toFixed(1) : '0';
      setEl('kpiLisInpatientSub', `Chiếm ${inPct}% tổng số mẫu`);

      const elBadge = document.getElementById('kpiLisTrendBadge');
      if (elBadge && l.growth_label) {
        const isUp = !String(l.growth_label).startsWith('-');
        elBadge.className = isUp ? 'trend-up' : 'trend-down';
        elBadge.innerHTML = `<i class="fa-solid fa-arrow-trend-${isUp ? 'up' : 'down'}"></i> ${l.growth_label}`;
      }
      const elSubDesc = document.getElementById('kpiLisSubtextDesc');
      if (elSubDesc && l.growth_subtext) elSubDesc.textContent = l.growth_subtext;

      // 2. Cập nhật Biểu đồ ApexCharts (Treemap, Multi-Line, Donut)
      const elTreemapSub = document.getElementById('lisTreemapTotalSub');
      if (elTreemapSub && l.total) elTreemapSub.textContent = Number(l.total).toLocaleString('vi-VN') + ' mẫu';

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
      }
    }

    const tblLbl = document.getElementById('tableFilterLabel');
    if (tblLbl && data.date) {
      tblLbl.textContent = `(Dữ liệu: ${data.date})`;
    }

    // 3. Cập nhật Bảng Dữ liệu Xét nghiệm (9 cột thực tế từ CSDL)
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
          <td style="text-align:right; color:var(--teal); font-weight:700;">${cBh.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:var(--warn); font-weight:700;">${cVp.toLocaleString('vi-VN')}</td>
          <td style="text-align:right;">${cOut.toLocaleString('vi-VN')}</td>
          <td style="text-align:right;">${cIn.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">${row.col6}</td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        const rateAllBh = sTot > 0 ? ((sBh / sTot) * 100).toFixed(1) : '0';
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Mẫu Xét Nghiệm Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sTot.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--teal); font-weight:800;">${sBh.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--warn); font-weight:800;">${sVp.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:700;">${sOut.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:700;">${sIn.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:800; color:var(--ok);">${rateAllBh}%</td>
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
