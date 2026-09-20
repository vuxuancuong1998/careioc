<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI STATS ROW (TRÊN CÙNG) -->
  <?php $fk = $finance['kpi'] ?? []; ?>
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng doanh thu viện phí</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-coins"></i></div>
      </div>
      <div class="kpi-value" id="kpiFinanceTotal" style="color:var(--gold);"><?= number_format($fk['tong_doanh_thu'] ?? 131.1, 1) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">triệu VNĐ</span></div>
      <div class="kpi-subtext" id="kpiFinanceTotalSub"><span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> <?= $fk['growth_label'] ?? '+20.0%' ?></span> <?= $fk['growth_subtext'] ?? 'so với kỳ trước' ?></div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">BHYT chi trả quyết toán</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-file-invoice-dollar"></i></div>
      </div>
      <div class="kpi-value" id="kpiFinanceBhyt"><?= number_format($fk['bhyt'] ?? 107.5, 1) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">triệu VNĐ</span></div>
      <div class="kpi-subtext" id="kpiFinanceBhytSub">Chiếm <?= ($fk['bhyt_percent'] ?? 82.0) ?>% cơ cấu doanh thu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Viện phí thu trực tiếp</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-hand-holding-dollar"></i></div>
      </div>
      <div class="kpi-value" id="kpiFinanceDirect"><?= number_format($fk['thu_phi'] ?? 23.6, 1) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">triệu VNĐ</span></div>
      <div class="kpi-subtext" id="kpiFinanceDirectSub">Chiếm <?= ($fk['thu_phi_percent'] ?? 18.0) ?>% viện phí tự chi trả</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tạm ứng nội trú trong ngày</span>
        <div class="kpi-icon-wrap" style="color:var(--blue); background:rgba(57,160,255,0.1);"><i class="fa-solid fa-money-bill-wave"></i></div>
      </div>
      <div class="kpi-value" id="kpiFinanceDeposit"><?= number_format($fk['tam_ung'] ?? 38.0, 1) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">triệu VNĐ</span></div>
      <div class="kpi-subtext" id="kpiFinanceDepositSub"><?= number_format($fk['tam_ung_ca'] ?? 19) ?> lượt bệnh nhân nhập viện</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ TT Không tiền mặt (QR)</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-qrcode"></i></div>
      </div>
      <div class="kpi-value" id="kpiFinanceCashlessRate"><?= ($fk['khong_tien_mat'] ?? 72.0) ?>%</div>
      <div class="kpi-subtext" id="kpiFinanceCashlessRateSub"><span style="color:var(--ok);"><i class="fa-solid fa-arrow-trend-up"></i> +12%</span> thanh toán qua VietQR/POS</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Chi phí bình quân / Lượt khám</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-calculator"></i></div>
      </div>
      <div class="kpi-value" id="kpiFinanceAvgVisit"><?= $fk['chi_phi_bq_kham'] ?? '1.092.750 đ' ?> <span style="font-size:14px; font-weight:600; color:var(--muted);"></span></div>
      <div class="kpi-subtext" id="kpiFinanceAvgVisitSub">Đạt chỉ tiêu kiểm soát chi phí BHYT</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ TÀI CHÍNH CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Waterfall Chart (Dòng tiền viện phí) -->
    <div class="chart-card col-7">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-water"></i> Biểu đồ Thác Nước (Waterfall): Phân Tích Dòng Tiền Viện Phí Hôm Nay (Triệu Đ)</div>
          <div class="chart-subtitle">Thu BHYT + Thu Trực tiếp - Tiền thuốc/VTYT - Chi phí vận hành = Thặng dư dịch vụ</div>
        </div>
      </div>
      <div class="chart-body" id="chartFinanceWaterfall"></div>
    </div>

    <!-- Biểu đồ 2: Treemap (Doanh thu theo khoa) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-table-cells-large"></i> Biểu đồ Cây (Treemap): Cơ Cấu Doanh Thu Theo Khoa Phòng</div>
          <div class="chart-subtitle">Tỷ trọng đóng góp nguồn thu viện phí toàn viện</div>
        </div>
      </div>
      <div class="chart-body" id="chartFinanceTreemap"></div>
    </div>

    <!-- Biểu đồ 3: Combo Chart (Doanh thu & Số ca 7 ngày) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-line"></i> Biểu đồ Kết Hợp: Doanh Thu & Lượt Bệnh Nhân (7 Ngày)</div>
          <div class="chart-subtitle">Cột đo doanh thu (Triệu đ) & Đường đo lượt khám thanh toán</div>
        </div>
      </div>
      <div class="chart-body" id="chartFinanceComboTrend"></div>
    </div>

    <!-- Biểu đồ 4: Donut (Phương thức thanh toán) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-credit-card"></i> Phương Thức TT</div>
          <div class="chart-subtitle">Tỷ lệ không dùng tiền mặt</div>
        </div>
      </div>
      <div class="chart-body" id="chartPayMethodDonut"></div>
    </div>

    <!-- Biểu đồ 5: Gauge (Chỉ tiêu không tiền mặt) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-qrcode"></i> Mục Tiêu VietQR</div>
          <div class="chart-subtitle">Chỉ tiêu CĐS: ≥ 50%</div>
        </div>
      </div>
      <div class="chart-body" id="chartCashlessGauge"></div>
    </div>
  </section>

  <!-- [NOTE]: Bộ lọc đa tiêu chí đã được đưa lên đầu trang tại topbar.php -->

  <!-- 4. BẢNG DỮ LIỆU CHI TIẾT (Ở DƯỚI - KHI LỌC BẢNG THAY ĐỔI, BIỂU ĐỒ GIỮ NGUYÊN) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Quyết toán Doanh thu Viện phí Chi tiết theo Khoản mục (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm khoản mục, nguồn thu..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã Mục</th>
            <th>Tên Khoản Mục Viện Phí</th>
            <th style="text-align:right;">Sản lượng</th>
            <th style="text-align:right;">Doanh thu BHYT</th>
            <th style="text-align:right;">Viện phí trực tiếp</th>
            <th style="text-align:right;">Tổng thực thu</th>
            <th style="text-align:right;">Tỷ trọng %</th>
            <th style="text-align:right;">Đã thanh toán</th>
            <th style="text-align:right;">Nợ đọng</th>
            <th>Đơn vị phát sinh</th>
            <th>Thu ngân phụ trách</th>
            <th style="text-align:center;">Trạng thái quỹ</th>
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
let chartWaterfall = null;
let chartTreemap = null;
let chartCombo = null;
let chartDonut = null;
let chartGauge = null;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Waterfall
  const waterfallData = <?= json_encode($finance['charts']['waterfall'] ?? []) ?>;
  chartWaterfall = new ApexCharts(document.getElementById('chartFinanceWaterfall'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 260 },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%',
        borderRadius: 4,
        colors: {
          ranges: [
            { from: -1000, to: -0.1, color: '#ff5c5c' },
            { from: 0, to: 1000, color: '#20c6b7' }
          ]
        }
      }
    },
    series: [{
      name: 'Triệu VNĐ',
      data: waterfallData.length ? waterfallData : [
        { x: 'Thu BHYT', y: 107.5 },
        { x: '(+) Thu VP', y: 23.6 },
        { x: '(-) Thuốc/VTYT', y: -46.8 },
        { x: '(-) Vận hành', y: -32.8 },
        { x: '(=) Thặng dư', y: 51.5 }
      ]
    }],
    xaxis: { labels: { style: { colors: '#85a4c4', fontSize: '11px' } } },
    yaxis: { labels: { style: { colors: '#85a4c4' } } }
  });
  chartWaterfall.render();

  // 2. Treemap
  const finTreemapData = <?= json_encode($finance['charts']['treemap'] ?? []) ?>;
  chartTreemap = new ApexCharts(document.getElementById('chartFinanceTreemap'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'treemap', height: 260 },
    colors: ['#00f2fe', '#39a0ff', '#20c6b7', '#ffc107', '#ff5c5c', '#9b7cff'],
    series: [{
      data: finTreemapData.length ? finTreemapData : [
        { x: 'Khoa Nội (Giường & ĐT)', y: 46.2 },
        { x: 'Khoa Dược (Thuốc BHYT)', y: 36.3 },
        { x: 'Khoa Xét nghiệm LIS', y: 21.6 },
        { x: 'Khoa CĐHA (RIS/PACS)', y: 16.8 },
        { x: 'Khoa Khám bệnh', y: 10.2 },
        { x: 'VTYT tiêu hao & Khác', y: 10.5 }
      ]
    }],
    plotOptions: { treemap: { distributed: true, enableShades: false } }
  });
  chartTreemap.render();

  // 3. Combo Trend
  const finCombo = <?= json_encode($finance['charts']['combo'] ?? []) ?>;
  chartCombo = new ApexCharts(document.getElementById('chartFinanceComboTrend'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'line', height: 250 },
    colors: ['#00f2fe', '#ff9100'],
    stroke: { width: [0, 3], curve: 'smooth' },
    plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
    series: [
      { name: 'Tổng thu (Triệu đ)', type: 'column', data: finCombo.revenues || [103.8, 96.2, 113.6, 107.1, 122.4, 114.7, 109.3, 131.1, 147.5, 139.9, 155.2, 163.9] },
      { name: 'Lượt ca thanh toán (Lượt)', type: 'line', data: finCombo.visits || [95, 88, 104, 98, 112, 105, 100, 120, 135, 128, 142, 150] }
    ],
    xaxis: {
      categories: finCombo.categories || ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7 (100)', 'T8 (+20%)', 'T9', 'T10', 'T11', 'T12'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: [
      { title: { text: 'Doanh thu (Tr đ)', style: { color: '#00f2fe' } }, labels: { style: { colors: '#85a4c4' } } },
      { opposite: true, title: { text: 'Lượt ca', style: { color: '#ff9100' } }, labels: { style: { colors: '#85a4c4' } } }
    ],
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartCombo.render();

  // 4. Donut Pay Method
  const payDonut = <?= json_encode($finance['charts']['pay_donut'] ?? []) ?>;
  chartDonut = new ApexCharts(document.getElementById('chartPayMethodDonut'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 250 },
    colors: ['#2ecc71', '#39a0ff', '#20c6b7', '#f1c40f'],
    labels: payDonut.labels || ['Quét VietQR', 'Thẻ POS', 'Chuyển khoản NH', 'Tiền mặt'],
    series: payDonut.series || [45.0, 18.0, 10.0, 27.0],
    legend: { position: 'bottom', labels: { colors: '#b2cbe4' } }
  });
  chartDonut.render();

  // 5. Gauge Cashless
  chartGauge = new ApexCharts(document.getElementById('chartCashlessGauge'), {
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
    series: [<?= (float)($finance['charts']['cashless_gauge'] ?? 72.0) ?>],
    labels: ['Không tiền mặt']
  });
  chartGauge.render();
}

// ================= CẬP NHẬT BẢNG VÀ BIỂU ĐỒ KHI LỌC DỮ LIỆU =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : 'year=2026&quarter=3&month=8';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // Cập nhật Bảng Dữ liệu Quyết toán (Bộ lọc chỉ thay đổi bảng, không thay đổi biểu đồ)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.finance) {
      tbody.innerHTML = '';
      let sBhyt = 0, sVp = 0, sTot = 0;

      data.tables.finance.forEach(row => {
        const b = parseInt(String(row.col2).replace(/\D/g, '')) || 0;
        const v = parseInt(String(row.col3).replace(/\D/g, '')) || 0;
        const t = parseInt(String(row.col4).replace(/\D/g, '')) || 0;
        sBhyt += b; sVp += v; sTot += t;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right;">${row.col1}</td>
          <td style="text-align:right; color:#20c6b7;">${row.col2}</td>
          <td style="text-align:right; color:#ff9100;">${row.col3}</td>
          <td style="text-align:right; color:var(--gold); font-weight:700;">${row.col4}</td>
          <td style="text-align:right; font-weight:700;">${row.col5}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">${row.col6}</td>
          <td style="text-align:right;">${row.col7}</td>
          <td>${row.col9}</td>
          <td>${row.col11}</td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type}">${row.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        tfoot.innerHTML = `
          <tr>
            <td colspan="4" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Doanh Thu Viện Phí Toàn Viện:</td>
            <td style="text-align:right; color:#20c6b7; font-weight:800;">${sBhyt.toLocaleString('vi-VN')} đ</td>
            <td style="text-align:right; color:#ff9100; font-weight:800;">${sVp.toLocaleString('vi-VN')} đ</td>
            <td style="text-align:right; color:var(--gold); font-weight:800;">${sTot.toLocaleString('vi-VN')} đ</td>
            <td style="text-align:right;">100%</td>
            <td colspan="4">--</td>
            <td style="text-align:center;"><span class="status-pill ok">Khớp quỹ</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load Tài chính:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
