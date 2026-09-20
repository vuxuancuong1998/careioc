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
        <span class="kpi-label">Tổng ca Chẩn đoán hình ảnh</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-x-ray"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisTotal"><?= number_format($ris['total'] ?? 176) ?></div>
      <div class="kpi-subtext"><span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> +6.5%</span> so với TB tuần</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Chụp X-quang KTS (DR)</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-lungs"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisXray"><?= number_format($ris['xray_count'] ?? 102) ?></div>
      <div class="kpi-subtext">Chiếm <?= $ris['total'] > 0 ? round(($ris['xray_count'] / $ris['total']) * 100, 1) : 58.0 ?>% tổng số ca CĐHA</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Siêu âm màu Doppler</span>
        <div class="kpi-icon-wrap" style="color:var(--blue); background:rgba(57,160,255,0.1);"><i class="fa-solid fa-wave-square"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisUltrasound"><?= number_format($ris['ultrasound_count'] ?? 60) ?></div>
      <div class="kpi-subtext">Bao gồm SA Bụng, Tim, Mạch máu</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Chụp Cắt lớp vi tính CT</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-brain"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisCt"><?= number_format($ris['ct_count'] ?? 14) ?></div>
      <div class="kpi-subtext">Máy CT 32 lát cắt kết nối PACS</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ Lưu trữ & Đọc PACS</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-cloud-arrow-up"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisPacsRate"><?= ($ris['pacs_rate'] ?? 100) ?>%</div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Không dùng phim</span> (Tiết kiệm chi phí)</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Thời gian trả KQ hình ảnh</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-stopwatch"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisTat"><?= ($ris['tat_avg'] ?? 21.8) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">phút</span></div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Nhanh</span> hơn quy định 8.2p</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ CĐHA CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Funnel Chart (Tiến trình ca chụp) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-filter"></i> Biểu đồ Phễu: Luồng Tiến Trình Ca CĐHA</div>
          <div class="chart-subtitle">Từ chỉ định &rarr; Tiếp nhận &rarr; Chụp &rarr; Lưu PACS &rarr; Trả kết quả</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisFunnel"></div>
    </div>

    <!-- Biểu đồ 2: 100% Stacked Bar (Không in phim PACS vs Có in phim) -->
    <div class="chart-card col-5">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-film"></i> Cột Chồng 100%: Tỷ Lệ Không Dùng Phim Nhựa (Số Hóa PACS)</div>
          <div class="chart-subtitle">Đánh giá hiệu quả kinh tế tiết kiệm chi phí in ấn phim nhựa</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisFilmlessStacked"></div>
    </div>

    <!-- Biểu đồ 3: Donut Chart (Cơ cấu nguồn chi trả) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Nguồn Chi Trả CĐHA</div>
          <div class="chart-subtitle">Tỷ lệ BHYT vs Viện phí</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisPayerDonut"></div>
    </div>

    <!-- Biểu đồ 4: Clustered Bar (Sản lượng máy CĐHA) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-column"></i> Cột Nhóm: Sản Lượng Chụp Theo Thiết Bị & Đối Tượng</div>
          <div class="chart-subtitle">So sánh ca chụp diện BHYT và Viện phí dịch vụ trên từng chủng loại máy</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisModalityClustered"></div>
    </div>

    <!-- Biểu đồ 5: Bar Chart (Thời gian trả kết quả) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-clock"></i> Thời Gian Trả KQ (Phút)</div>
          <div class="chart-subtitle">Theo từng kỹ thuật</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisTatBars"></div>
    </div>

    <!-- Biểu đồ 6: Status Grid Máy CĐHA -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-server"></i> Trạng Thái Thiết Bị CĐHA</div>
          <div class="chart-subtitle">Kết nối PACS Server</div>
        </div>
      </div>
      <div class="chart-body" style="display:flex; flex-direction:column; justify-content:center; gap:8px;">
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Shimadzu DR</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">X-quang KTS &bull; <span id="risShimadzuCount"><?= (int)($ris['xray_count'] ?? 88) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>GE Logiq P9</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Siêu âm màu Doppler &bull; <span id="risGeCount"><?= (int)($ris['ultrasound_count'] ?? 62) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>CT 32 lát</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Cắt lớp vi tính &bull; <span id="risCtCount"><?= (int)($ris['ct_count'] ?? 14) ?></span> ca</div>
        </div>
        <div class="status-matrix-item" style="padding:6px 10px;">
          <div class="status-matrix-header"><span>Olympus</span> <span class="status-pill ok">OK</span></div>
          <div class="status-matrix-name" style="font-size:11px;">Nội soi tiêu hóa &bull; <span id="risOlympusCount"><?= max(2, (int)round(($ris['total'] ?? 176) * 0.05)) ?></span> ca</div>
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
        <span>Bảng Giám sát Chi tiết Kỹ thuật Chẩn đoán hình ảnh (RIS/PACS) (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm kỹ thuật chụp, thiết bị..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã Kỹ Thuật</th>
            <th>Tên Kỹ Thuật Chẩn Đoán Hình Ảnh</th>
            <th style="text-align:right;">Tổng ca</th>
            <th style="text-align:right;">BHYT</th>
            <th style="text-align:right;">Thu phí/VP</th>
            <th style="text-align:right;">Nгоại trú</th>
            <th style="text-align:right;">Nội trú</th>
            <th style="text-align:right;">Số phim in</th>
            <th style="text-align:right;">Phim hỏng</th>
            <th>Thiết bị thực hiện</th>
            <th style="text-align:center;">Kết nối PACS</th>
            <th style="text-align:right;">Thời gian trả KQ</th>
            <th style="text-align:center;">Trạng thái ca</th>
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
let chartRisFunnel = null;
let chartRisFilmlessStacked = null;
let chartRisPayerDonut = null;
let chartRisModalityClustered = null;
let chartRisTatBars = null;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };
  const risFunnelSteps = ['1. Chỉ định', '2. Tiếp nhận', '3. Chụp xong', '4. Lên PACS', '5. BS đọc & Trả KQ'];
  try {
    chartRisFunnel = new ApexCharts(document.getElementById('chartRisFunnel'), {
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
          const step = (opt && typeof opt.dataPointIndex !== 'undefined' && risFunnelSteps[opt.dataPointIndex]) ? risFunnelSteps[opt.dataPointIndex] : '';
          return step ? step + ': ' + val + ' ca' : val + ' ca';
        },
        style: { fontSize: '11px', colors: ['#fff'] }
      },
      series: [{
        name: 'Số ca',
        data: [<?= (int)($ris['total'] ?? 176) ?>, <?= (int)round(($ris['total'] ?? 176) * 0.99) ?>, <?= (int)round(($ris['total'] ?? 176) * 0.97) ?>, <?= (int)round(($ris['total'] ?? 176) * 0.97) ?>, <?= (int)round(($ris['total'] ?? 176) * 0.95) ?>]
      }],
      xaxis: {
        categories: ['1. Bác sĩ chỉ định', '2. Tiếp nhận tại phòng', '3. KTV chụp hoàn tất', '4. Đồng bộ lên PACS', '5. BS đọc & Trả KQ'],
        labels: { show: false }
      },
      legend: { show: false }
    });
    chartRisFunnel.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisFunnel:', e);
  }

  // 2. 100% Stacked Bar (Không phim PACS vs Có in phim)
  try {
    chartRisFilmlessStacked = new ApexCharts(document.getElementById('chartRisFilmlessStacked'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 260, stacked: true, stackType: '100%' },
      plotOptions: {
        bar: { horizontal: false, columnWidth: '45%', borderRadius: 4 }
      },
      colors: ['#00f2fe', '#ff9100'],
      series: [
        { name: 'Không in phim (Số hóa PACS %)', data: [100, 100, 100, 100, 100] },
        { name: 'Có in phim nhựa (%)', data: [0, 0, 0, 0, 0] }
      ],
      xaxis: {
        categories: ['X-Quang KTS', 'Siêu âm Doppler', 'CT-Scanner', 'Nội soi', 'Điện tim'],
        labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
      },
      yaxis: {
        max: 100,
        labels: {
          style: { colors: '#85a4c4' },
          formatter: v => v + '%'
        }
      },
      tooltip: {
        y: { formatter: v => v + '%' }
      },
      legend: { position: 'top', labels: { colors: '#b2cbe4' } }
    });
    chartRisFilmlessStacked.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisFilmlessStacked:', e);
  }

  // 3. Donut Nguồn Chi Trả CĐHA (BHYT vs Viện phí)
  <?php
  $risBhyt = (int)($ris['bhyt'] ?? 144);
  $risSelfPay = (int)($ris['self_pay'] ?? 32);
  $risTotal = (int)($ris['total'] ?? 176);
  ?>
  try {
    chartRisPayerDonut = new ApexCharts(document.getElementById('chartRisPayerDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 260 },
      colors: ['#00f2fe', '#ffc107'],
      labels: ['BHYT', 'Viện phí (Thu phí)'],
      series: [<?= $risBhyt ?>, <?= $risSelfPay ?>],
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              name: { show: true, color: '#85a4c4', fontSize: '11px', offsetY: -4 },
              value: { show: true, color: '#00f2fe', fontSize: '18px', fontWeight: 700, offsetY: 4, formatter: v => v + ' ca' },
              total: {
                show: true,
                label: 'Tổng ca',
                color: '#85a4c4',
                formatter: () => '<?= $risTotal ?> ca'
              }
            }
          }
        }
      },
      legend: { position: 'bottom', labels: { colors: '#b2cbe4' } }
    });
    chartRisPayerDonut.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisPayerDonut:', e);
  }

  // 4. Clustered Bar: Sản lượng chụp theo Thiết bị & Đối tượng
  <?php
  $xrayTot = (int)($ris['xray_count'] ?? 102);
  $xrayBhyt = (int)round($xrayTot * 0.82);
  $xrayVp = max(0, $xrayTot - $xrayBhyt);

  $saTot = (int)($ris['ultrasound_count'] ?? 60);
  $saBhyt = (int)round($saTot * 0.80);
  $saVp = max(0, $saTot - $saBhyt);

  $ctTot = (int)($ris['ct_count'] ?? 14);
  $ctBhyt = (int)round($ctTot * 0.75);
  $ctVp = max(0, $ctTot - $ctBhyt);

  $nsTot = max(2, (int)round(($ris['total'] ?? 176) * 0.05));
  $nsBhyt = (int)round($nsTot * 0.78);
  $nsVp = max(0, $nsTot - $nsBhyt);

  $ecgTot = max(2, (int)round(($ris['total'] ?? 176) * 0.03));
  $ecgBhyt = (int)round($ecgTot * 0.80);
  $ecgVp = max(0, $ecgTot - $ecgBhyt);
  ?>
  try {
    chartRisModalityClustered = new ApexCharts(document.getElementById('chartRisModalityClustered'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 260 },
      plotOptions: {
        bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 }
      },
      colors: ['#00f2fe', '#ffc107'],
      series: [
        { name: 'Khám BHYT', data: [<?= $xrayBhyt ?>, <?= $saBhyt ?>, <?= $ctBhyt ?>, <?= $nsBhyt ?>, <?= $ecgBhyt ?>] },
        { name: 'Viện phí / Dịch vụ', data: [<?= $xrayVp ?>, <?= $saVp ?>, <?= $ctVp ?>, <?= $nsVp ?>, <?= $ecgVp ?>] }
      ],
      xaxis: {
        categories: ['X-Quang KTS', 'Siêu âm Doppler', 'CT Scanner', 'Nội soi tiêu hóa', 'Điện tim'],
        labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
      },
      yaxis: {
        labels: {
          style: { colors: '#85a4c4' },
          formatter: v => Math.round(v) + ' ca'
        }
      },
      legend: { position: 'top', labels: { colors: '#b2cbe4' } }
    });
    chartRisModalityClustered.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisModalityClustered:', e);
  }

  // 5. Bar Chart Thời Gian Trả KQ theo Kỹ thuật (Phút)
  try {
    chartRisTatBars = new ApexCharts(document.getElementById('chartRisTatBars'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 260 },
      plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 4 } },
      colors: ['#20c6b7'],
      series: [{
        name: 'Thời gian TAT (phút)',
        data: [18, 14, 45, 25, 10]
      }],
      xaxis: {
        categories: ['X-Quang', 'Siêu âm', 'CT Scanner', 'Nội soi', 'Điện tim'],
        labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
      },
      yaxis: { labels: { style: { colors: '#85a4c4' } } }
    });
    chartRisTatBars.render();
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisTatBars:', e);
  }
}

// ================= CẬP NHẬT BẢNG KHI LỌC DỮ LIỆU (BỘ LỌC CHỈ THAY ĐỔI BẢNG, KHÔNG THAY ĐỔI BIỂU ĐỒ) =================
window.fetchDashboardData = async function() {
  const p = typeof getFilterParams === 'function' ? getFilterParams() : '';
  const queryStr = p.toString ? p.toString() : p;
  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // Cập nhật Bảng Dữ liệu CĐHA (Bộ lọc chỉ thay đổi bảng, không thay đổi biểu đồ)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.ris) {
      tbody.innerHTML = '';
      let sTot = 0, sBh = 0, sVp = 0, sOut = 0, sIn = 0;

      data.tables.ris.forEach(row => {
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
          <td style="text-align:right; color:var(--gold); font-weight:700;">${row.col6}</td>
          <td style="text-align:right;">${row.col7}</td>
          <td>${row.col8}</td>
          <td style="text-align:center;"><span class="status-pill ok"><i class="fa-solid fa-cloud-arrow-up"></i> ${row.col9}</span></td>
          <td style="text-align:right; color:var(--teal); font-weight:700;">${row.col10}</td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type}">${row.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Ca CĐHA Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sTot}</td>
            <td style="text-align:right; color:#20c6b7; font-weight:800;">${sBh}</td>
            <td style="text-align:right; color:#ff9100; font-weight:800;">${sVp}</td>
            <td style="text-align:right;">${sOut}</td>
            <td style="text-align:right;">${sIn}</td>
            <td style="text-align:right; color:var(--gold); font-weight:800;">146 tấm</td>
            <td style="text-align:right;">1 tấm</td>
            <td>--</td>
            <td style="text-align:center;"><span class="status-pill ok">100% PACS</span></td>
            <td style="text-align:right; color:var(--teal); font-weight:800;">21.8 phút</td>
            <td style="text-align:center;"><span class="status-pill ok">Hoàn tất</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load RIS/PACS:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
