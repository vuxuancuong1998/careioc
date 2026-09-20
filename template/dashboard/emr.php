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
        <span class="kpi-label">Tổng hồ sơ bệnh án EMR</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-file-medical"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmrTotal"><?= number_format($emr['total_records'] ?? 102) ?></div>
      <div class="kpi-subtext">Số hóa 100% hồ sơ nội trú</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ Ký số Token Bác sĩ</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-signature"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmrSignRate"><?= ($emr['sign_rate'] ?? 94.1) ?>%</div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đạt chuẩn</span> Thông tư 46/2018/TT-BYT</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Liên thông Cổng BHXH</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-cloud-arrow-up"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmrBhxhSync"><?= ($emr['bhxh_sync_rate'] ?? 100) ?>%</div>
      <div class="kpi-subtext">Đồng bộ tự động XML 4210</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Hồ sơ hoàn tất trong 24h</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-clock-check"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmr24hRate"><?= ($emr['close_24h_rate'] ?? 96.1) ?>%</div>
      <div class="kpi-subtext">Đảm bảo thủ tục thanh toán viện phí</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Bệnh án chậm duyệt (&gt; 48h)</span>
        <div class="kpi-icon-wrap" style="color:var(--warn); background:rgba(241,196,15,0.1);"><i class="fa-solid fa-triangle-exclamation"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmrDelayed" style="color:var(--warn);"><?= ($emr['delayed_count'] ?? 4) ?> <span style="font-size:14px; font-weight:600; color:var(--warn);">hồ sơ</span></div>
      <div class="kpi-subtext">Đã gửi nhắc nhở tới bác sĩ điều trị</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Mức độ Trưởng thành Số (CĐS)</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-trophy"></i></div>
      </div>
      <div class="kpi-value" id="kpiEmrMaturityLevel" style="color:var(--gold);"><?= ($emr['maturity_level'] ?? 'Mức 6') ?></div>
      <div class="kpi-subtext">Mục tiêu hướng tới Bệnh viện Thông minh</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ EMR CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Funnel Chart (Quy trình EMR) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-filter"></i> Biểu đồ Phễu: Quy Trình Lập & Ký Số Bệnh Án EMR</div>
          <div class="chart-subtitle">Tỷ lệ hoàn thành qua từng bước xử lý hồ sơ</div>
        </div>
      </div>
      <div class="chart-body" id="chartEmrFunnel"></div>
    </div>

    <!-- Biểu đồ 2: Radar Chart (Trưởng thành số) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-compass"></i> Mạng Nhện: Mức Độ Trưởng Thành Số (TT 54/BYT)</div>
          <div class="chart-subtitle">Đánh giá 6 nhóm tiêu chí bệnh viện số hóa</div>
        </div>
      </div>
      <div class="chart-body" id="chartEmrRadarMaturity"></div>
    </div>

    <!-- Biểu đồ 3: Waffle Grid (Bệnh viện không in giấy) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-border-all"></i> Biểu đồ Waffle: Tỷ Lệ Không In Giấy</div>
          <div class="chart-subtitle">Tiết kiệm <span id="emrPaperlessSubRate"><?= round($emr['sign_rate'] ?? 94.1) ?>%</span> chi phí lưu trữ hồ sơ giấy</div>
        </div>
      </div>
      <div class="chart-body" style="display:flex; align-items:center; justify-content:center;">
        <div class="waffle-container">
          <div class="waffle-grid" id="waffleEmrGrid"></div>
          <div style="margin-top:10px; font-size:11px; color:var(--sub); display:flex; gap:14px;">
            <span><span style="display:inline-block; width:10px; height:10px; background:#2ecc71; border-radius:2px; vertical-align:middle;"></span> Số hóa 100%: <b id="waffleEmrFilledText"><?= round($emr['sign_rate'] ?? 94.1) ?> ô</b></span>
            <span><span style="display:inline-block; width:10px; height:10px; background:rgba(38,76,115,0.4); border-radius:2px; vertical-align:middle;"></span> Còn tồn: <b id="waffleEmrRemainText"><?= 100 - round($emr['sign_rate'] ?? 94.1) ?> ô</b></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Biểu đồ 4: Clustered Bar (Ký số Bác sĩ vs Điều dưỡng theo khoa) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-signature"></i> Cột Nhóm: Tỷ Lệ Ký Số Token Bác Sĩ & Điều Dưỡng Theo Khoa</div>
          <div class="chart-subtitle">So sánh tiến độ ký số các văn bản y khoa điện tử</div>
        </div>
      </div>
      <div class="chart-body" id="chartEmrSigningByDept"></div>
    </div>

    <!-- Biểu đồ 5: Donut Chart (Tốc độ đóng hồ sơ) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-clock"></i> Tốc Độ Đóng BA</div>
          <div class="chart-subtitle">Thời gian hoàn tất ký duyệt</div>
        </div>
      </div>
      <div class="chart-body" id="chartEmrCloseSpeed"></div>
    </div>

    <!-- Biểu đồ 6: Status Grid Cổng BHXH -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-cloud-arrow-up"></i> Cổng Giám Định BHXH</div>
          <div class="chart-subtitle">5 Gói tin XML 4210</div>
        </div>
      </div>
      <div class="chart-body" style="display:flex; flex-direction:column; justify-content:center; gap:8px;">
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Gói 1 (Tổng hợp)</span> <span class="status-pill ok">100%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Đã chuyển: <span id="emrSyncHsCount"><?= number_format($emr['total_records'] ?? 102) ?></span> HS &bull; Hợp lệ</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Gói 2 (Thuốc)</span> <span class="status-pill ok">99.5%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Khớp danh mục BHYT &bull; Hợp lệ</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Gói 3 (Dịch vụ KT)</span> <span class="status-pill ok">100%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Khớp định mức kỹ thuật &bull; Hợp lệ</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Gói 4-5 (CLS/Diễn biến)</span> <span class="status-pill ok">99.0%</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Đính kèm kết quả &bull; Hợp lệ</div>
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
        <span>Bảng Giám sát Chi tiết Bệnh án Điện tử EMR & Ký số theo Khoa Phòng (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm tên khoa, bác sĩ..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã EMR</th>
            <th>Khoa Lâm Sàng Lập Hồ Sơ</th>
            <th style="text-align:right;">Tổng hồ sơ BA</th>
            <th style="text-align:right;">Đã lập EMR</th>
            <th style="text-align:right;">Đã ký số BS</th>
            <th style="text-align:right;">Tỷ lệ ký số</th>
            <th style="text-align:right;">Chậm duyệt (&gt;24h)</th>
            <th style="text-align:right;">Đã gửi Cổng BHXH</th>
            <th>Chuẩn liên thông</th>
            <th style="text-align:right;">Thời gian đóng BA</th>
            <th>Người phê duyệt</th>
            <th style="text-align:center;">Đánh giá</th>
          </tr>
        </thead>
        <tbody id="iocTableBody">
          <?php if (!empty($tables['emr'])): ?>
            <?php foreach ($tables['emr'] as $row): ?>
              <tr>
                <td style="text-align:center;"><?= $row['stt'] ?></td>
                <td style="font-weight:700; color:var(--accent);"><?= htmlspecialchars($row['code']) ?></td>
                <td style="font-weight:600;"><?= htmlspecialchars($row['name']) ?></td>
                <td style="text-align:right; font-weight:700;"><?= htmlspecialchars($row['col1']) ?></td>
                <td style="text-align:right; color:#20c6b7;"><?= htmlspecialchars($row['col2']) ?></td>
                <td style="text-align:right; font-weight:700; color:var(--ok);"><?= htmlspecialchars($row['col3']) ?></td>
                <td style="text-align:right; color:var(--accent); font-weight:700;"><?= htmlspecialchars($row['col4']) ?></td>
                <td style="text-align:right; color:var(--warn); font-weight:700;"><?= htmlspecialchars($row['col5']) ?></td>
                <td style="text-align:right;"><?= htmlspecialchars($row['col6']) ?></td>
                <td><?= htmlspecialchars($row['col7']) ?></td>
                <td style="text-align:right;"><?= htmlspecialchars($row['col8']) ?></td>
                <td><?= htmlspecialchars($row['col9']) ?></td>
                <td style="text-align:center;"><span class="status-pill <?= $row['status_type'] ?>"><?= htmlspecialchars($row['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
        <tfoot id="iocTableFoot">
          <?php if (!empty($tables['emr'])): ?>
            <tr style="font-weight:800; background:rgba(0,242,254,0.06); border-top:2px solid var(--border);">
              <td colspan="3" style="text-align:center; color:var(--accent);">TỔNG CỘNG TOÀN VIỆN</td>
              <td style="text-align:right; font-weight:800;"><?= number_format($emr['total_records'] ?? 102) ?> BA</td>
              <td style="text-align:right; color:#20c6b7;"><?= number_format($emr['total_records'] ?? 102) ?> (100%)</td>
              <td style="text-align:right; color:var(--ok); font-weight:800;"><?= number_format(round(($emr['total_records'] ?? 102) * (($emr['sign_rate'] ?? 94.1) / 100))) ?> BA</td>
              <td style="text-align:right; color:var(--accent); font-weight:800;"><?= $emr['sign_rate'] ?? 94.1 ?>%</td>
              <td style="text-align:right; color:var(--warn); font-weight:800;"><?= number_format($emr['delayed_count'] ?? 4) ?> ca</td>
              <td style="text-align:right;">0 ca</td>
              <td colspan="3">--</td>
              <td style="text-align:center;"><span class="status-pill ok">Xuất sắc</span></td>
            </tr>
          <?php endif; ?>
        </tfoot>
      </table>
    </div>
  </section>
</main>

<?php include __DIR__ . '/footer_common.php'; ?>

<script>
let chartEmrFunnel = null;
let chartEmrRadarMaturity = null;
let chartEmrSigning = null;
let chartEmrCloseSpeed = null;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Funnel EMR
  const emrFunnelSteps = ['1. Mở EMR', '2. Diễn biến', '3. BS ký số', '4. TK ký', '5. Lưu trữ'];
  try {
    chartEmrFunnel = new ApexCharts(document.getElementById('chartEmrFunnel'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 260 },
      plotOptions: {
        bar: {
          borderRadius: 4,
          horizontal: true,
          barHeight: '75%',
          isFunnel: true,
          distributed: true
        }
      },
      colors: ['#00f2fe', '#20c6b7', '#39a0ff', '#ffc107', '#2ecc71'],
      dataLabels: {
        enabled: true,
        formatter: function (val, opt) {
          const step = (opt && typeof opt.dataPointIndex !== 'undefined' && emrFunnelSteps[opt.dataPointIndex]) ? emrFunnelSteps[opt.dataPointIndex] : '';
          return step ? step + ': ' + val + ' HS' : val + ' HS';
        },
        style: { fontSize: '11px', colors: ['#fff'] }
      },
      series: [{
        name: 'Số hồ sơ',
        data: [<?= (int)($emr['total_records'] ?? 102) ?>, <?= (int)($emr['total_records'] ?? 102) ?>, <?= (int)($emr['signed_count'] ?? 96) ?>, <?= (int)($emr['close_24h_count'] ?? 98) ?>, <?= max(0, (int)($emr['close_24h_count'] ?? 98) - 2) ?>]
      }],
      xaxis: {
        categories: ['1. Mở hồ sơ EMR', '2. Diễn biến lâm sàng', '3. Ký số Bác sĩ', '4. Trưởng khoa ký', '5. Đóng lưu trữ'],
        labels: { show: false }
      },
      legend: { show: false }
    });
    chartEmrFunnel.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartEmrFunnel:', e);
  }

  // 2. Radar Chart (Mức độ trưởng thành số)
  chartEmrRadarMaturity = new ApexCharts(document.getElementById('chartEmrRadarMaturity'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'radar', height: 260 },
    colors: ['#20c6b7'],
    series: [{ name: 'Điểm đánh giá (%)', data: [95, 98, 96, 94, 96, 92] }],
    labels: ['Hạ tầng CNTT', 'Hệ thống HIS', 'Xét nghiệm LIS', 'Hình ảnh PACS', 'Bệnh án EMR', 'An toàn thông tin'],
    yaxis: { max: 100, labels: { style: { colors: '#85a4c4' } } }
  });
  chartEmrRadarMaturity.render();

  // 3. Waffle Grid EMR (10x10 ô)
  renderWaffleEmr(<?= round($emr['sign_rate'] ?? 94.1) ?>);

  // 4. Clustered Bar: Ký số BS vs ĐD
  chartEmrSigning = new ApexCharts(document.getElementById('chartEmrSigningByDept'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 250 },
    colors: ['#00f2fe', '#39a0ff'],
    plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 4 } },
    series: [
      { name: 'Bác sĩ ký số (%)', data: <?= json_encode($emr['charts']['signing_by_dept']['doctors'] ?? [100, 95.2, 93.5, 100, 96.0]) ?> },
      { name: 'Điều dưỡng ký số (%)', data: <?= json_encode($emr['charts']['signing_by_dept']['nurses'] ?? [98.5, 96.0, 94.0, 100, 95.0]) ?> }
    ],
    xaxis: {
      categories: ['Khoa HSCC', 'Khoa Nội TH', 'Khoa Ngoại TH', 'Khoa Phụ sản', 'Khoa Nhi'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: { max: 100, labels: { style: { colors: '#85a4c4' } } },
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartEmrSigning.render();

  // 5. Donut Chart: Tốc độ đóng BA
  chartEmrCloseSpeed = new ApexCharts(document.getElementById('chartEmrCloseSpeed'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 250 },
    colors: ['#2ecc71', '#39a0ff', '#ff5c5c'],
    labels: ['Trong 24 giờ', 'Từ 24 - 48h', 'Chậm > 48h'],
    series: [<?= ($emr['close_24h_rate'] ?? 96.1) ?>, <?= round((100 - ($emr['close_24h_rate'] ?? 96.1)) * 0.7, 1) ?>, <?= round((100 - ($emr['close_24h_rate'] ?? 96.1)) * 0.3, 1) ?>],
    legend: { position: 'bottom', labels: { colors: '#b2cbe4' } }
  });
  chartEmrCloseSpeed.render();
}

function renderWaffleEmr(pct) {
  const waffleEmr = document.getElementById('waffleEmrGrid');
  if (!waffleEmr) return;
  waffleEmr.innerHTML = '';
  const fillCount = Math.min(100, Math.max(0, Math.round(parseFloat(pct) || 94)));
  for (let i = 1; i <= 100; i++) {
    const cell = document.createElement('div');
    cell.className = 'waffle-cell' + (i <= fillCount ? ' filled-ok' : '');
    cell.title = `Hồ sơ ${i}%: ` + (i <= fillCount ? 'Số hóa 100% không giấy' : 'Hồ sơ còn giấy tờ kèm');
    waffleEmr.appendChild(cell);
  }
  const elFilled = document.getElementById('waffleEmrFilledText');
  const elRemain = document.getElementById('waffleEmrRemainText');
  if (elFilled) elFilled.textContent = fillCount + ' ô';
  if (elRemain) elRemain.textContent = (100 - fillCount) + ' ô';
}

// ================= CẬP NHẬT BẢNG VÀ BIỂU ĐỒ KHI LỌC DỮ LIỆU =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : 'year=2026&quarter=3&month=8';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // Cập nhật Bảng Dữ liệu EMR (Bộ lọc chỉ thay đổi bảng, không thay đổi biểu đồ)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.emr) {
      tbody.innerHTML = '';
      let sTotal = 0, sSigned = 0, sDelayed = 0;

      data.tables.emr.forEach(row => {
        const t = parseInt(row.col1) || 0;
        const s = parseInt(row.col3) || 0;
        const d = parseInt(row.col5) || 0;
        sTotal += t; sSigned += s; sDelayed += d;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right; font-weight:700;">${row.col1}</td>
          <td style="text-align:right; color:#20c6b7;">${row.col2}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">${row.col3}</td>
          <td style="text-align:right; font-weight:700; color:var(--accent);">${row.col7}</td>
          <td style="text-align:right; color:var(--warn); font-weight:700;">${row.col5}</td>
          <td style="text-align:right; color:#fff;">${row.col4}</td>
          <td>${row.col9}</td>
          <td style="text-align:right; color:var(--teal);">${row.col10}</td>
          <td>${row.col11}</td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type}">${row.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        const signPct = sTotal > 0 ? ((sSigned / sTotal) * 100).toFixed(1) : '94.1';
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Bệnh Án EMR Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sTotal} BA</td>
            <td style="text-align:right;">${sTotal} (100%)</td>
            <td style="text-align:right; color:var(--ok); font-weight:800;">${sSigned} BA</td>
            <td style="text-align:right; color:var(--accent); font-weight:800;">${signPct}%</td>
            <td style="text-align:right; color:var(--warn);">${sDelayed} ca</td>
            <td style="text-align:right;">${sSigned} BA</td>
            <td colspan="3">--</td>
            <td style="text-align:center;"><span class="status-pill ok">Xuất sắc</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load EMR:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
