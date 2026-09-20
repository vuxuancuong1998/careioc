<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI STATS ROW (TRÊN CÙNG) -->
  <?php $ik = $inpatient['kpi'] ?? []; ?>
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng giường thực kê</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-bed"></i></div>
      </div>
      <div class="kpi-value" id="kpiGiuongThucKe"><?= number_format($ik['giuong_thuc_ke'] ?? 100) ?></div>
      <div class="kpi-subtext">Chỉ tiêu kế hoạch: 180 giường</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Bệnh nhân đang nằm</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-hospital-user"></i></div>
      </div>
      <div class="kpi-value" id="kpiGiuongDangDung"><?= number_format($ik['giuong_dang_dung'] ?? 78) ?></div>
      <div class="kpi-subtext"><span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> +4 ca</span> so với hôm qua</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Công suất sử dụng giường</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-chart-pie"></i></div>
      </div>
      <div class="kpi-value" id="kpiOccupancyPercent"><?= ($ik['cong_suat'] ?? 78.0) ?>%</div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Trong ngưỡng</span> an toàn (≤ 85%)</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Nhập viện mới trong ngày</span>
        <div class="kpi-icon-wrap" style="color:var(--blue); background:rgba(57,160,255,0.1);"><i class="fa-solid fa-user-plus"></i></div>
      </div>
      <div class="kpi-value" id="kpiNhapVien"><?= number_format($ik['nhap_vien'] ?? 14) ?></div>
      <div class="kpi-subtext">Từ PK: 10 ca &bull; Cấp cứu: 4 ca</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Xuất viện trong ngày</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-person-walking-arrow-right"></i></div>
      </div>
      <div class="kpi-value" id="kpiXuatVien"><?= number_format($ik['xuat_vien'] ?? 37) ?></div>
      <div class="kpi-subtext">Khỏi bệnh/Đỡ: 36 &bull; Chuyển: 1</div>
    </div>

    <!-- [NOTE]: Thẻ KPI: Ngày Điều Trị Trung Bình (ALOS - Average Length of Stay)
         - Mục tiêu: Giám sát số ngày nằm viện điều trị trung bình của bệnh nhân nội trú
         - Dữ liệu: ioc_inpatient_daily.avg_length_of_stay
         - Đơn vị: ngày (chỉ tiêu quy định: ≤ 7.0 ngày)
         - ID: kpiNgayDtTb
    -->
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ NỘI TRÚ CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Waterfall Chart (Biến động dòng bệnh nhân) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-water"></i> Thống kê Bệnh Nhân Nội Trú</div>
          <div class="chart-subtitle">Đầu ngày &rarr; (+) Vào viện &rarr; (-) Ra viện &rarr; (-) Chuyển viện &rarr; (=) Đang nằm cuối ngày</div>
        </div>
      </div>
      <div class="chart-body" id="chartInpatientWaterfall"></div>
    </div>

    <!-- [NOTE]: Biểu đồ Lưới Waffle: Tỷ Lệ Lấp Đầy 100 Giường Bệnh Toàn Viện
         - Mục tiêu: Lưới 10x10 ô minh họa trực quan công suất sử dụng giường bệnh toàn viện
         - Dữ liệu: ioc_inpatient_daily (giuong_dang_dung, giuong_trong)
         - Chart type: Waffle grid 10x10 ô | ID: waffleBedsGrid
    -->

    <!-- Biểu đồ 2: Stacked Bar (Giường có người vs Giường trống theo khoa) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-bed"></i> Tình Trạng Giường Bệnh Từng Khoa</div>
          <div class="chart-subtitle">Giường đang nằm vs Giường sẵn sàng tiếp nhận theo từng khoa lâm sàng</div>
        </div>
      </div>
      <div class="chart-body" id="chartBedStacked"></div>
    </div>

    <!-- [NOTE]: Đồng Hồ Đo Công Suất
         - Mục tiêu: Giám sát công suất sử dụng giường bệnh so với ngưỡng an toàn (≤ 85%)
         - Dữ liệu: ioc_inpatient_daily.bed_occupancy_percent
         - Chart type: radialBar / gauge | colors: #ffc107 | ID: chartBedGaugeNoitru | col-3
    -->

    <!-- [NOTE]: Đánh Giá Điều Trị
         - Mục tiêu: Đánh giá 5 tiêu chí chuyên môn: Khỏi/Đỡ, Đúng phác đồ, Ký số BA, Hạn lưu viện, BHYT đạt
         - Dữ liệu: ioc_quality_kpi_daily
         - Chart type: radar | colors: #20c6b7 | ID: chartTreatmentRadar | col-3
    -->

    <!-- [NOTE]: Biểu đồ Miền (Area Chart): Diễn Biến Bệnh Nhân Đang Nằm Điều Trị 14 Ngày Qua
         - Mục tiêu: So sánh xu hướng lưu bệnh nhân tại Khoa Nội tổng hợp, Khoa Ngoại và Khoa Nhi
         - Dữ liệu: ioc_inpatient_daily (inpatient_report_date, inpatient_department_id, inpatient_occupied_beds)
         - Chart type: area / multi-series | colors: #00f2fe, #39a0ff, #ffc107 | ID: chartInpatientTrendArea | col-12
    -->
  </section>

  <!-- [NOTE]: Bộ lọc đa tiêu chí đã được đưa lên đầu trang tại topbar.php -->

  <!-- 4. BẢNG DỮ LIỆU CHI TIẾT (Ở DƯỚI - KHI LỌC BẢNG THAY ĐỔI, BIỂU ĐỒ GIỮ NGUYÊN) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Theo dõi Giường bệnh &amp; Chỉ số Điều trị Nội trú theo Khoa</span>
        <span id="tableFilterLabel" style="font-size:12px; font-weight:500; color:var(--accent); margin-left:8px;"></span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm tên khoa, chỉ số..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã Khoa</th>
            <th>Tên Khoa Lâm Sàng</th>
            <th style="text-align:right;">Giường KH</th>
            <th style="text-align:right;">Giường TK</th>
            <th style="text-align:right;">Đầu ngày</th>
            <th style="text-align:right;">Vào viện</th>
            <th style="text-align:right;">Ra viện</th>
            <th style="text-align:right;">Chuyển viện</th>
            <th style="text-align:right;">Tử vong</th>
            <th style="text-align:right;">Đang nằm</th>
            <th style="text-align:right;">Công suất %</th>
            <th style="text-align:right;">Ngày ĐT TB</th>
            <th style="text-align:center;">Đánh giá cảnh báo</th>
          </tr>
        </thead>
        <tbody id="iocTableBody">
          <!-- Render via JS -->
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
let chartInpatientWaterfall = null;
let chartBedStacked = null;
let chartBedGauge = null;
let chartTreatmentRadar = null;
let chartInpatientTrendArea = null;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Waterfall Chart Dòng Bệnh Nhân Nội Trú
  chartInpatientWaterfall = new ApexCharts(document.getElementById('chartInpatientWaterfall'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 260 },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%',
        borderRadius: 4,
        colors: {
          ranges: [
            { from: -100, to: -0.1, color: '#ff5c5c' },
            { from: 0, to: 100, color: '#20c6b7' }
          ]
        }
      }
    },
    series: [{
      name: 'Số ca',
      data: [
        { x: 'Đầu kỳ', y: <?= (int)($inpatient['kpi']['dau_ky'] ?? 80) ?> },
        { x: '(+) Vào viện', y: <?= (int)($inpatient['kpi']['nhap_vien'] ?? 22) ?> },
        { x: '(-) Ra viện', y: -<?= (int)($inpatient['kpi']['xuat_vien'] ?? 37) ?> },
        { x: '(-) Chuyển viện', y: -<?= (int)($inpatient['kpi']['chuyen_tuyen'] ?? 3) ?> },
        { x: '(-) Tử vong', y: -<?= (int)($inpatient['kpi']['tu_vong'] ?? 0) ?> },
        { x: '(=) Hiện nằm', y: <?= (int)($inpatient['kpi']['giuong_dang_dung'] ?? 78) ?> }
      ]
    }],
    xaxis: { labels: { style: { colors: '#85a4c4', fontSize: '11px' } } },
    yaxis: { labels: { style: { colors: '#85a4c4' } } }
  });
  chartInpatientWaterfall.render();

  // 2. Render Waffle Grid (10x10 ô)
  renderWaffle(<?= (int)($inpatient['kpi']['giuong_dang_dung'] ?? 78) ?>);

  // 3. Stacked Bar: Giường có người vs Giường trống (Từ CSDL ioc_inpatient_daily)
  const inBedStacked = <?= json_encode($inpatient['charts']['bed_stacked'] ?? []) ?>;
  chartBedStacked = new ApexCharts(document.getElementById('chartBedStacked'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 260, stacked: true },
    colors: ['#00f2fe', 'rgba(38, 76, 115, 0.5)'],
    plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 4 } },
    series: inBedStacked.series || [
      { name: 'Giường đang có bệnh nhân', data: [20, 26, 21, 15, 12] },
      { name: 'Giường còn trống', data: [0, 4, 4, 5, 3] }
    ],
    xaxis: {
      categories: inBedStacked.categories || ['Khoa HSCC', 'Khoa Nội TH', 'Khoa Ngoại TH', 'Khoa Nhi', 'Khoa Phụ sản'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: { labels: { style: { colors: '#85a4c4' } } },
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartBedStacked.render();

  // 4. Gauge Chart (chuyển sang note)
  const elBedGauge = document.getElementById('chartBedGaugeNoitru');
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
            name: { fontSize: '11px', color: '#85a4c4', offsetY: -6 },
            value: { fontSize: '22px', fontWeight: 800, color: '#ffc107', offsetY: 6, formatter: v => v + '%' }
          }
        }
      },
      colors: ['#ffc107'],
      series: [<?= (float)($inpatient['kpi']['cong_suat'] ?? 78.0) ?>],
      labels: ['Công suất giường']
    });
    chartBedGauge.render();
  }

  // 5. Radar Chart (chuyển sang note)
  const elRadar = document.getElementById('chartTreatmentRadar');
  if (elRadar) {
    chartTreatmentRadar = new ApexCharts(elRadar, {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'radar', height: 250 },
      colors: ['#20c6b7'],
      series: [{ name: 'Chất lượng đạt (%)', data: [96, 92, 98, 95, 99] }],
      labels: ['Khỏi / Đỡ', 'Đúng phác đồ', 'Ký số BA', 'Hạn lưu viện', 'BHYT đạt'],
      yaxis: { max: 100, labels: { style: { colors: '#85a4c4' } } }
    });
    chartTreatmentRadar.render();
  }

  // 6. Multi-Area Chart Diễn biến giường bệnh theo ngày (chuyển sang note)
  const elTrendArea = document.getElementById('chartInpatientTrendArea');
  if (elTrendArea) {
    const inTrend = <?= json_encode($inpatient['charts']['trend_area'] ?? []) ?>;
    chartInpatientTrendArea = new ApexCharts(elTrendArea, {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'area', height: 210 },
      colors: ['#00f2fe', '#39a0ff', '#ffc107'],
      series: inTrend.series || [
        { name: 'Khoa Nội TH', data: [24, 25, 26, 28, 27, 26, 26] },
        { name: 'Khoa Ngoại TH', data: [19, 20, 21, 22, 21, 20, 21] },
        { name: 'Khoa Nhi', data: [14, 15, 14, 16, 15, 14, 15] }
      ],
      xaxis: {
        categories: inTrend.categories || ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
      },
      yaxis: { labels: { style: { colors: '#85a4c4' } } },
      stroke: { curve: 'smooth', width: 2.2 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } }
    });
    chartInpatientTrendArea.render();
  }
}

function renderWaffle(occupied) {
  const waffleGrid = document.getElementById('waffleBedsGrid');
  if (!waffleGrid) return;
  waffleGrid.innerHTML = '';
  const occ = Math.min(100, Math.max(0, parseInt(occupied) || 78));
  for (let i = 1; i <= 100; i++) {
    const cell = document.createElement('div');
    cell.className = 'waffle-cell' + (i <= occ ? ' filled-accent' : '');
    cell.title = `Giường số ${i}: ` + (i <= occ ? 'Đang có bệnh nhân' : 'Còn trống');
    waffleGrid.appendChild(cell);
  }
}

// ================= CẬP NHẬT BẢNG VÀ BIỂU ĐỒ KHI LỌC DỮ LIỆU =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : '';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // 1. Cập nhật các chỉ số KPI trên cùng
    if (data.inpatient && data.inpatient.kpi) {
      const ik = data.inpatient.kpi;
      const elGtk = document.getElementById('kpiGiuongThucKe');
      if (elGtk) elGtk.textContent = Number(ik.giuong_thuc_ke || 0).toLocaleString('vi-VN');
      const elGdd = document.getElementById('kpiGiuongDangDung');
      if (elGdd) elGdd.textContent = Number(ik.giuong_dang_dung || 0).toLocaleString('vi-VN');
      const elCs = document.getElementById('kpiOccupancyPercent');
      if (elCs) elCs.textContent = (ik.cong_suat || 0) + '%';
      const elNv = document.getElementById('kpiNhapVien');
      if (elNv) elNv.textContent = Number(ik.nhap_vien || 0).toLocaleString('vi-VN');
      const elXv = document.getElementById('kpiXuatVien');
      if (elXv) elXv.textContent = Number(ik.xuat_vien || 0).toLocaleString('vi-VN');
      const elNdt = document.getElementById('kpiNgayDtTb');
      if (elNdt) elNdt.innerHTML = `${ik.ngay_dt_tb || 6.4} <span style="font-size:14px; font-weight:600; color:var(--muted);">ngày</span>`;

      if (typeof renderWaffle === 'function') {
        renderWaffle(Number(ik.giuong_dang_dung || 78));
      }
    }

    const tblLbl = document.getElementById('tableFilterLabel');
    if (tblLbl && data.date) {
      tblLbl.textContent = `(Dữ liệu: ${data.date})`;
    }

    // 2. Cập nhật các biểu đồ nội trú
    if (data.inpatient && data.inpatient.charts) {
      const ic = data.inpatient.charts;
      if (ic.waterfall && typeof chartInpatientWaterfall !== 'undefined' && chartInpatientWaterfall) {
        chartInpatientWaterfall.updateSeries([{ data: ic.waterfall }]);
      }
      if (ic.bed_stacked && typeof chartBedStacked !== 'undefined' && chartBedStacked) {
        chartBedStacked.updateOptions({ xaxis: { categories: ic.bed_stacked.categories || [] } });
        chartBedStacked.updateSeries(ic.bed_stacked.series || []);
      }
      if (ic.bed_gauge !== undefined && typeof chartBedGauge !== 'undefined' && chartBedGauge) {
        chartBedGauge.updateSeries([Number(ic.bed_gauge)]);
      }
      if (ic.trend_area && typeof chartInpatientTrendArea !== 'undefined' && chartInpatientTrendArea) {
        chartInpatientTrendArea.updateOptions({ xaxis: { categories: ic.trend_area.categories || [] } });
        chartInpatientTrendArea.updateSeries(ic.trend_area.series || []);
      }
    }

    // 3. Cập nhật Bảng Giường & Điều trị khoa phòng theo data.tables.inpatient
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');
    const rows = (data.tables && data.tables.inpatient && data.tables.inpatient.length > 0) ? data.tables.inpatient : (data.tables?.kcb || []);

    if (tbody && rows.length > 0) {
      tbody.innerHTML = '';
      let sP = 0, sA = 0, sOp = 0, sIn = 0, sOut = 0, sTr = 0, sD = 0, sCl = 0;

      rows.forEach(r => {
        const plan = parseInt(r.col7) || 0;
        const act = parseInt(r.col8) || 0;
        const adm = parseInt(r.col4) || 0;
        const dis = parseInt(r.col5) || 0;
        const ref = parseInt(r.col6) || 0;
        const occBeds = parseInt(r.col9) || 0;

        sP += plan;
        sA += act;
        sIn += adm;
        sOut += dis;
        sTr += ref;
        sCl += occBeds;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${r.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${r.code}</td>
          <td style="font-weight:600;">${r.name}</td>
          <td style="text-align:right;">${r.col7}</td>
          <td style="text-align:right;">${r.col8}</td>
          <td style="text-align:right;">${Math.max(0, act - occBeds)}</td>
          <td style="text-align:right; color:var(--teal); font-weight:700;">+${r.col4}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">-${r.col5}</td>
          <td style="text-align:right;">${r.col6}</td>
          <td style="text-align:right;">0</td>
          <td style="text-align:right; font-weight:700; color:#fff;">${r.col9}</td>
          <td style="text-align:right; font-weight:700; color:var(--accent);">${r.col10}</td>
          <td style="text-align:right;">${r.col11}</td>
          <td style="text-align:center;"><span class="status-pill ${r.status_type}">${r.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        const totalOccRate = sA > 0 ? ((sCl / sA) * 100).toFixed(1) : '78.0';
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Toàn Viện:</td>
            <td style="text-align:right;">${sP}</td>
            <td style="text-align:right;">${sA}</td>
            <td style="text-align:right;">${Math.max(0, sA - sCl)}</td>
            <td style="text-align:right; color:var(--teal); font-weight:800;">+${sIn}</td>
            <td style="text-align:right; color:var(--ok); font-weight:800;">-${sOut}</td>
            <td style="text-align:right;">${sTr}</td>
            <td style="text-align:right;">0</td>
            <td style="text-align:right; font-weight:800;">${sCl}</td>
            <td style="text-align:right; font-weight:800; color:var(--accent);">${totalOccRate}%</td>
            <td style="text-align:right;">6.4 ngày</td>
            <td style="text-align:center;"><span class="status-pill ok">Ổn định</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load Nội trú:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
