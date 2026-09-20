<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI STATS ROW (TRÊN CÙNG) -->
  <?php $pk = $pharmacy['kpi'] ?? []; ?>
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng mặt hàng tồn kho</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-boxes-stacked"></i></div>
      </div>
      <div class="kpi-value" id="kpiPharTotal"><?= number_format($pk['tong_mat_hang'] ?? 485) ?></div>
      <div class="kpi-subtext" id="kpiPharTotalSub">320 Thuốc &bull; 165 Vật tư y tế</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng giá trị tồn kho</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-sack-dollar"></i></div>
      </div>
      <div class="kpi-value" id="kpiPharValue"><?= number_format($pk['tong_gia_tri_ty'] ?? 0.49, 2) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">tỷ VNĐ</span></div>
      <div class="kpi-subtext" id="kpiPharValueSub">Đảm bảo cơ số thuốc 45 ngày</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Thuốc phát trong ngày</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-prescription-bottle-medical"></i></div>
      </div>
      <div class="kpi-value" id="kpiPharDispensed"><?= number_format($pk['don_trong_ngay'] ?? 480) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">đơn</span></div>
      <div class="kpi-subtext" id="kpiPharDispensedSub">360 BHYT &bull; 120 Thu phí</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ đáp ứng đơn thuốc</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-circle-check"></i></div>
      </div>
      <div class="kpi-value" id="kpiPharFulfillRate"><?= ($pk['ty_le_dap_ung'] ?? 98.5) ?>%</div>
      <div class="kpi-subtext" id="kpiPharFulfillRateSub"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đạt chỉ tiêu</span> Bộ Y tế (≥ 95%)</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Thuốc cận hạn (&lt; 3 tháng)</span>
        <div class="kpi-icon-wrap" style="color:var(--warn); background:rgba(241,196,15,0.1);"><i class="fa-solid fa-calendar-xmark"></i></div>
      </div>
      <div class="kpi-value" id="kpiPharNearExpiry" style="color:var(--warn);"><?= number_format($pk['can_han'] ?? 2) ?> <span style="font-size:14px; font-weight:600; color:var(--warn);">mặt hàng</span></div>
      <div class="kpi-subtext" id="kpiPharNearExpirySub">Đã lập kế hoạch luân chuyển sử dụng</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Mặt hàng dưới cơ số an toàn</span>
        <div class="kpi-icon-wrap" style="color:var(--bad); background:rgba(255,92,92,0.1);"><i class="fa-solid fa-triangle-exclamation"></i></div>
      </div>
      <div class="kpi-value" id="kpiPharLowStock" style="color:var(--bad);"><?= number_format($pk['duoi_co_so'] ?? 2) ?> <span style="font-size:14px; font-weight:600; color:var(--bad);">mặt hàng</span></div>
      <div class="kpi-subtext" id="kpiPharLowStockSub">Đã gửi yêu cầu nhập bổ sung</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ DƯỢC CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Treemap (Giá trị tồn theo nhóm dược lý) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-table-cells-large"></i> Biểu đồ Cây (Treemap): Giá Trị Tồn Kho Theo Nhóm Thuốc</div>
          <div class="chart-subtitle">Phân bổ giá trị dự trữ vốn tồn kho Dược</div>
        </div>
      </div>
      <div class="chart-body" id="chartPharTreemap"></div>
    </div>

    <!-- Biểu đồ 2: Combo Chart (Nhập vs Xuất vs Tồn kho 7 ngày) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-line"></i> Biểu đồ Kết hợp: Nhập - Xuất - Tồn Kho Dược (7 Ngày)</div>
          <div class="chart-subtitle">Cột đo giá trị nhập/xuất & Đường theo dõi tồn kho lũy kế</div>
        </div>
      </div>
      <div class="chart-body" id="chartPharCombo"></div>
    </div>

    <!-- Biểu đồ 3: Donut Chart (Hạn dùng FEFO) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Cơ Cấu Hạn Dùng Thuốc</div>
          <div class="chart-subtitle">Cảnh báo cận hạn FEFO</div>
        </div>
      </div>
      <div class="chart-body" id="chartPharExpiryDonutNew"></div>
    </div>

    <!-- Biểu đồ 4: Horizontal Bar (Top thuốc xuất dùng) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-pills"></i> Cột Ngang: Top Mặt Hàng Thuốc & VTYT Xuất Dùng Nhiều Nhất</div>
          <div class="chart-subtitle">Số lượng xuất phục vụ điều trị ngoại trú và nội trú trong ngày</div>
        </div>
      </div>
      <div class="chart-body" id="chartPharTopItems"></div>
    </div>

    <!-- Biểu đồ 5: Gauge Chart (Đáp ứng cơ số cấp cứu) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-gauge"></i> Cơ Số An Toàn</div>
          <div class="chart-subtitle">Tỷ lệ đảm bảo cơ số</div>
        </div>
      </div>
      <div class="chart-body" id="chartPharGauge"></div>
    </div>

    <!-- Biểu đồ 6: Status Matrix Tủ trực Khoa lâm sàng -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-kit-medical"></i> Tủ Trực Khoa Phòng</div>
          <div class="chart-subtitle">Cơ số cấp cứu 24/7</div>
        </div>
      </div>
      <div class="chart-body" style="display:flex; flex-direction:column; justify-content:center; gap:8px;">
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Tủ trực HSCC</span> <span class="status-pill ok">100%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Thuốc cấp cứu &bull; Đầy đủ</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Tủ trực Ngoại</span> <span class="status-pill ok">96%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Giảm đau / Kháng sinh &bull; Đầy đủ</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Tủ trực Nội</span> <span class="status-pill ok">95%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Tim mạch / Huyết áp &bull; Đầy đủ</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Tủ trực Nhi</span> <span class="status-pill ok">98%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Hạ sốt / Kháng sinh &bull; Đầy đủ</div>
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
        <span>Bảng Theo dõi Xuất Nhập Tồn Thuốc & Vật tư y tế theo Thời gian thực (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm tên thuốc, mã, vị trí kho..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã Dược</th>
            <th>Tên Thuốc / Hoạt chất / Quy cách</th>
            <th>ĐVT</th>
            <th>Nguồn cấp</th>
            <th style="text-align:right;">Số lượng xuất</th>
            <th style="text-align:right;">Đơn giá</th>
            <th style="text-align:right;">Thành tiền xuất</th>
            <th style="text-align:right;">Tồn kho thực tế</th>
            <th style="text-align:center;">Hạn sử dụng</th>
            <th>Kho lưu trữ</th>
            <th>Mức cơ số</th>
            <th style="text-align:center;">Tình trạng tồn</th>
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
let chartPharTreemap = null;
let chartPharCombo = null;
let chartPharDonut = null;
let chartPharTop = null;
let chartPharGauge = null;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Treemap Dược
  const treemapData = <?= json_encode($pharmacy['charts']['treemap'] ?? []) ?>;
  chartPharTreemap = new ApexCharts(document.getElementById('chartPharTreemap'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'treemap', height: 260 },
    colors: ['#00f2fe', '#39a0ff', '#20c6b7', '#ffc107', '#ff5c5c', '#9b7cff'],
    series: [{
      data: treemapData.length ? treemapData : [
        { x: 'Kháng sinh', y: 68.0 },
        { x: 'Dịch truyền', y: 45.0 },
        { x: 'Tim mạch/Huyết áp', y: 42.0 },
        { x: 'VTYT tiêu hao', y: 31.0 },
        { x: 'Hóa chất XN', y: 30.0 },
        { x: 'Hạ sốt/Giảm đau', y: 29.0 }
      ]
    }],
    plotOptions: { treemap: { distributed: true, enableShades: false } }
  });
  chartPharTreemap.render();

  // 2. Combo Chart Nhập - Xuất - Tồn
  const pharCombo = <?= json_encode($pharmacy['charts']['combo'] ?? []) ?>;
  chartPharCombo = new ApexCharts(document.getElementById('chartPharCombo'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'line', height: 260 },
    colors: ['#00f2fe', '#ff9100', '#2ecc71'],
    stroke: { width: [0, 0, 3], curve: 'smooth' },
    plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
    series: [
      { name: 'Nhập kho (Tr đ)', type: 'column', data: pharCombo.imports || [105, 98, 115, 108, 125, 118, 110, 130, 142, 135, 148, 155] },
      { name: 'Xuất kho (Tr đ)', type: 'column', data: pharCombo.exports || [92, 86, 102, 95, 110, 104, 98, 116, 128, 121, 134, 140] },
      { name: 'Tồn kho lũy kế (Tr đ)', type: 'line', data: pharCombo.stocks || [470, 482, 495, 508, 523, 537, 490, 504, 518, 532, 546, 561] }
    ],
    xaxis: {
      categories: pharCombo.categories || ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: [
      { title: { text: 'Nhập / Xuất (Tr đ)', style: { color: '#00f2fe' } }, labels: { style: { colors: '#85a4c4' } } },
      { opposite: true, title: { text: 'Tồn kho (Tr đ)', style: { color: '#2ecc71' } }, labels: { style: { colors: '#85a4c4' } } }
    ],
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartPharCombo.render();

  // 3. Donut Hạn dùng
  const fefoDonut = <?= json_encode($pharmacy['charts']['fefo_donut'] ?? []) ?>;
  chartPharDonut = new ApexCharts(document.getElementById('chartPharExpiryDonutNew'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 260 },
    colors: ['#2ecc71', '#39a0ff', '#f1c40f'],
    labels: fefoDonut.labels || ['Hạn > 12 tháng (An toàn)', 'Hạn 6-12 tháng (Theo dõi)', 'Cận hạn < 3 tháng (Cảnh báo)'],
    series: fefoDonut.series || [78.5, 16.5, 5.0],
    legend: { position: 'bottom', labels: { colors: '#b2cbe4' } }
  });
  chartPharDonut.render();

  // 4. Horizontal Bar: Top Thuốc xuất dùng (Từ CSDL ioc_pharmacy_inventory)
  const pharTop = <?= json_encode($pharmacy['charts']['top_items'] ?? []) ?>;
  chartPharTop = new ApexCharts(document.getElementById('chartPharTopItems'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 250 },
    colors: ['#20c6b7'],
    plotOptions: { bar: { horizontal: true, barHeight: '50%', borderRadius: 4 } },
    series: [{
      name: 'Số lượng xuất',
      data: pharTop.series || [2850, 1420, 890, 750, 680, 580]
    }],
    xaxis: { labels: { style: { colors: '#85a4c4' } } },
    yaxis: {
      categories: pharTop.categories || ['Bơm tiêm 5ml', 'Paracetamol 500mg', 'Amlodipin 5mg', 'Omeprazol 20mg', 'Metformin 850mg', 'Cefuroxim 500mg'],
      labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
    }
  });
  chartPharTop.render();

  // 5. Gauge
  chartPharGauge = new ApexCharts(document.getElementById('chartPharGauge'), {
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
    series: [<?= (float)($pharmacy['charts']['safety_gauge'] ?? 98.5) ?>],
    labels: ['Cơ số an toàn']
  });
  chartPharGauge.render();
}

// ================= CẬP NHẬT BẢNG VÀ BIỂU ĐỒ KHI LỌC DỮ LIỆU =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : 'year=2026&quarter=3&month=8';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // Cập nhật Bảng Tồn kho & Xuất dùng chi tiết (Bộ lọc chỉ thay đổi bảng, không thay đổi biểu đồ)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.pharmacy) {
      tbody.innerHTML = '';
      let sAmount = 0;

      data.tables.pharmacy.forEach(row => {
        const val = parseInt(String(row.col5).replace(/\D/g, '')) || 0;
        sAmount += val;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td>${row.col1}</td>
          <td><span style="color:#20c6b7;">${row.col2}</span></td>
          <td style="text-align:right; font-weight:700;">${row.col3}</td>
          <td style="text-align:right;">${row.col4}</td>
          <td style="text-align:right; color:var(--gold); font-weight:700;">${row.col5}</td>
          <td style="text-align:right; font-weight:700; color:#fff;">${row.col6}</td>
          <td style="text-align:center; color:var(--teal);">${row.col7}</td>
          <td>${row.col8}</td>
          <td>${row.col9}</td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type}">${row.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        tfoot.innerHTML = `
          <tr>
            <td colspan="7" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Tiền Xuất Thuốc & VTYT Toàn Viện:</td>
            <td style="text-align:right; color:var(--gold); font-weight:800;">${sAmount.toLocaleString('vi-VN')} đ</td>
            <td colspan="4">--</td>
            <td style="text-align:center;"><span class="status-pill ok">Đầy đủ cơ số</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load Kho Dược:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
