<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI STATS ROW (TRÊN CÙNG - 4 THẺ CHỈ SỐ HOẠT ĐỘNG THỰC TẾ) -->
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng ca Chẩn đoán hình ảnh</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-x-ray"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisTotal"><?= number_format($ris['total'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiRisTotalSub">
        <span id="kpiRisTrendBadge" class="<?= (($ris['growth_label'] ?? '') !== '' && ($ris['growth_label'][0] ?? '') === '-') ? 'trend-down' : 'trend-up' ?>">
          <i class="fa-solid <?= (($ris['growth_label'] ?? '') !== '' && ($ris['growth_label'][0] ?? '') === '-') ? 'fa-arrow-trend-down' : 'fa-arrow-trend-up' ?>"></i> <?= htmlspecialchars($ris['growth_label'] ?? '+0.0%') ?>
        </span> 
        <span id="kpiRisSubtextDesc"><?= htmlspecialchars($ris['growth_subtext'] ?? 'so với kỳ trước') ?></span>
      </div>
    </div>

    <?php 
    $catStyles = [
        1 => ['icon' => 'fa-solid fa-lungs', 'color' => 'var(--teal)', 'bg' => 'rgba(32,198,183,0.1)'],
        2 => ['icon' => 'fa-solid fa-wave-square', 'color' => 'var(--blue)', 'bg' => 'rgba(57,160,255,0.1)'],
        3 => ['icon' => 'fa-solid fa-brain', 'color' => 'var(--gold)', 'bg' => 'rgba(255,193,7,0.1)'],
        4 => ['icon' => 'fa-solid fa-magnet', 'color' => 'var(--purple)', 'bg' => 'rgba(155,124,255,0.1)']
    ];
    if (!empty($ris['categories'])):
      foreach ($ris['categories'] as $cat): 
        $cId = (int)$cat['id'];
        $cName = $cat['ris_category_name']; // Lấy trực tiếp từ ris_category_name của bảng ioc_ris_categories, 
        $cScans = (int)($cat['total_scans'] ?? 0);
        $cPct = ($ris['total'] ?? 0) > 0 ? round(($cScans / $ris['total']) * 100, 1) : 0;
        $cStyle = $catStyles[$cId] ?? ['icon' => 'fa-solid fa-stethoscope', 'color' => 'var(--accent)', 'bg' => 'rgba(0,242,254,0.1)'];
    ?>
    <div class="kpi-card" data-cat-id="<?= $cId ?>">
      <div class="kpi-top">
        <span class="kpi-label" id="kpiRisCatLabel_<?= $cId ?>"><?= htmlspecialchars($cName) ?></span>
        <div class="kpi-icon-wrap" style="color:<?= $cStyle['color'] ?>; background:<?= $cStyle['bg'] ?>;"><i class="<?= $cStyle['icon'] ?>"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisCatVal_<?= $cId ?>"><?= number_format($cScans) ?></div>
      <div class="kpi-subtext" id="kpiRisCatSub_<?= $cId ?>">Chiếm <span id="kpiRisCatPct_<?= $cId ?>"><?= $cPct ?>%</span> tổng số ca CĐHA</div>
    </div>
    <?php 
      endforeach; 
    endif;
    ?>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Ca CĐHA BHYT</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-id-card"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisBhyt"><?= number_format($ris['bhyt'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiRisBhytSub">Chiếm <?= ($ris['total'] ?? 0) > 0 ? round((($ris['bhyt'] ?? 0) / $ris['total']) * 100, 1) : 0 ?>% tổng số ca</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Ca CĐHA Viện phí</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-coins"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisSelfPay"><?= number_format($ris['self_pay'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiRisSelfPaySub">Chiếm <?= ($ris['total'] ?? 0) > 0 ? round((($ris['self_pay'] ?? 0) / $ris['total']) * 100, 1) : 0 ?>% tổng số ca</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ CĐHA BHYT</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-shield-halved"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisBhytRate"><?= ($ris['total'] ?? 0) > 0 ? round((($ris['bhyt'] ?? 0) / $ris['total']) * 100, 1) : 0 ?>%</div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Đối tượng BHYT</span> chiếm đa số</div>
    </div>

    <!-- [NOTE]: Các thẻ chỉ số nguồn bệnh nhân & kỹ thuật chuyển thành ghi chú theo yêu cầu, hiển thị chi tiết ở biểu đồ tròn và bảng:
    <div class="kpi-card" id="noteKpiRisOutpatient" style="display:none;">
      <div class="kpi-top">
        <span class="kpi-label">CĐHA Ngoại trú</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-hospital-user"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisOutpatient"><?= number_format($ris['outpatient'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiRisOutpatientSub">Chiếm <?= ($ris['total'] ?? 0) > 0 ? round((($ris['outpatient'] ?? 0) / $ris['total']) * 100, 1) : 0 ?>% tổng số ca</div>
    </div>

    <div class="kpi-card" id="noteKpiRisInpatient" style="display:none;">
      <div class="kpi-top">
        <span class="kpi-label">CĐHA Nội trú</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-bed-pulse"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisInpatient"><?= number_format($ris['inpatient'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiRisInpatientSub">Chiếm <?= ($ris['total'] ?? 0) > 0 ? round((($ris['inpatient'] ?? 0) / $ris['total']) * 100, 1) : 0 ?>% tổng số ca</div>
    </div>
    <div class="kpi-card" id="noteKpiRisCt" style="display:none;">
      <div class="kpi-top">
        <span class="kpi-label">Chụp Cắt lớp vi tính CT</span>
        <div class="kpi-icon-wrap" style="color:var(--gold); background:rgba(255,193,7,0.1);"><i class="fa-solid fa-brain"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisCt"><?= number_format($ris['ct_count'] ?? 0) ?></div>
      <div class="kpi-subtext" id="kpiRisCtSub">Máy CT 32 lát cắt kết nối PACS</div>
    </div>

    <div class="kpi-card" id="noteKpiRisPacsRate" style="display:none;">
      <div class="kpi-top">
        <span class="kpi-label">Tỷ lệ Lưu trữ & Đọc PACS</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-cloud-arrow-up"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisPacsRate"><?= ($ris['pacs_rate'] ?? 100) ?>%</div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Không dùng phim</span> (Tiết kiệm chi phí)</div>
    </div>

    <div class="kpi-card" id="noteKpiRisTat" style="display:none;">
      <div class="kpi-top">
        <span class="kpi-label">Thời gian trả KQ hình ảnh</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-stopwatch"></i></div>
      </div>
      <div class="kpi-value" id="kpiRisTat"><?= ($ris['tat_avg'] ?? 21.8) ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">phút</span></div>
      <div class="kpi-subtext"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Nhanh</span> hơn quy định 8.2p</div>
    </div>
    -->
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ CĐHA (100% DỮ LIỆU THỰC TỪ CSDL) -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Donut Chart - Nguồn Chi Trả CĐHA (BHYT vs Viện phí - CSDL ioc_ris_daily) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Nguồn Chi Trả CĐHA</div>
          <div class="chart-subtitle">Tỷ lệ BHYT vs Viện phí</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisPayerDonut"></div>
    </div>

    <!-- Biểu đồ 2: Donut Chart - Cơ Cấu Nguồn Bệnh Nhân (Ngoại trú vs Nội trú - CSDL ioc_ris_daily) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-hospital-user"></i> Cơ Cấu Nguồn Bệnh Nhân</div>
          <div class="chart-subtitle">Ngoại trú vs Nội trú</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisPatientTypeDonut"></div>
    </div>

    <!-- Biểu đồ 3: Multi-Line Chart - Diễn Biến Sản Lượng Theo Thời Gian (X-quang KTS & Siêu âm Doppler) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-line"></i> Diễn Biến Sản Lượng Theo Thời Gian</div>
          <div class="chart-subtitle">Theo danh mục kỹ thuật CĐHA</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisMultiLine"></div>
    </div>

    <!-- [NOTE]: Biểu đồ 1: Funnel Chart (Biểu đồ Phễu: Luồng Tiến Trình Ca CĐHA) đã được chuyển thành ghi chú theo yêu cầu, không xóa
         - Mục tiêu: Giám sát luồng quy trình thực hiện kỹ thuật CĐHA (Chỉ định -> Tiếp nhận -> Chụp -> Lên PACS -> Trả kết quả)
         - Dữ liệu cần: Cần bảng log quy trình chi tiết từng ca chụp theo mốc thời gian thực. CSDL care_ioc hiện tại chỉ lưu trữ sản lượng tổng hợp ngày tại ioc_ris_daily.
         - Khối mã lưu trữ:
    <div class="chart-card col-4" id="noteRisFunnelWrapper" style="display:none;">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-filter"></i> Biểu đồ Phễu: Luồng Tiến Trình Ca CĐHA</div>
          <div class="chart-subtitle">Từ chỉ định &rarr; Tiếp nhận &rarr; Chụp &rarr; Lưu PACS &rarr; Trả kết quả</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisFunnel"></div>
    </div>
    -->

    <!-- [NOTE]: Biểu đồ 2: Cột Chồng 100%: Tỷ Lệ Không Dùng Phim Nhựa (Số Hóa PACS) đã được chuyển thành ghi chú theo yêu cầu, không xóa
         - Mục tiêu: Đánh giá tỷ lệ không in phim nhựa (số hóa PACS 100%) theo từng kỹ thuật để tính toán chi phí tiết kiệm
         - Dữ liệu cần: Thống kê số lượng phim in thực tế vs ca chụp trên từng loại thiết bị. Bảng ioc_ris_daily hiện chỉ có cột ris_total_fim cho nhóm XQ và SA.
         - Khối mã lưu trữ:
    <div class="chart-card col-5" id="noteRisFilmlessWrapper" style="display:none;">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-film"></i> Cột Chồng 100%: Tỷ Lệ Không Dùng Phim Nhựa (Số Hóa PACS)</div>
          <div class="chart-subtitle">Đánh giá hiệu quả kinh tế tiết kiệm chi phí in ấn phim nhựa</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisFilmlessStacked"></div>
    </div>
    -->

    <!-- [NOTE]: Biểu đồ 4: Cột Nhóm: Sản Lượng Chụp Theo Thiết Bị & Đối Tượng đã được chuyển thành ghi chú theo yêu cầu, không xóa
         - Mục tiêu: So sánh ca chụp diện BHYT và Viện phí dịch vụ trên từng chủng loại máy (X-Quang, Siêu âm, CT Scanner, Nội soi, Điện tim)
         - Dữ liệu cần: Bảng danh mục thiết bị CĐHA và sản lượng ca chụp theo thiết bị. Bảng ioc_ris_daily hiện chỉ phân theo nhóm kỹ thuật (X-quang, Siêu âm).
         - Khối mã lưu trữ:
    <div class="chart-card col-6" id="noteRisModalityWrapper" style="display:none;">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-column"></i> Cột Nhóm: Sản Lượng Chụp Theo Thiết Bị & Đối Tượng</div>
          <div class="chart-subtitle">So sánh ca chụp diện BHYT và Viện phí dịch vụ trên từng chủng loại máy</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisModalityClustered"></div>
    </div>
    -->

    <!-- [NOTE]: Biểu đồ 5: Thời Gian Trả KQ (Phút) đã được chuyển thành ghi chú theo yêu cầu, không xóa
         - Mục tiêu: Giám sát thời gian trả kết quả CĐHA bình quân theo từng kỹ thuật
         - Dữ liệu cần: Cần log thời gian từ lúc chụp xong đến khi bác sĩ ký duyệt kết quả. CSDL care_ioc chưa có log thời gian này.
         - Khối mã lưu trữ:
    <div class="chart-card col-3" id="noteRisTatBarsWrapper" style="display:none;">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-clock"></i> Thời Gian Trả KQ (Phút)</div>
          <div class="chart-subtitle">Theo từng kỹ thuật</div>
        </div>
      </div>
      <div class="chart-body" id="chartRisTatBars"></div>
    </div>
    -->

    <!-- [NOTE]: Biểu đồ 6: Trạng Thái Thiết Bị CĐHA đã được chuyển thành ghi chú theo yêu cầu, không xóa
         - Mục tiêu: Giám sát trạng thái hoạt động của từng máy chẩn đoán hình ảnh và kết nối PACS Server
         - Dữ liệu cần: Bảng thiết bị ioc_medical_devices kết nối IoT/DICOM trạng thái online/offline. Hiện CSDL chưa có bảng này.
         - Khối mã lưu trữ:
    <div class="chart-card col-3" id="noteRisDeviceStatusWrapper" style="display:none;">
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
    -->
  </section>

  <!-- [NOTE]: Bộ lọc đa tiêu chí đã được đưa lên đầu trang tại topbar.php -->

  <!-- 3. BẢNG DỮ LIỆU CHI TIẾT (Ở DƯỚI - TỰ ĐỘNG ĐỒNG BỘ THEO BỘ LỌC) -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Giám sát Chi tiết Kỹ thuật Chẩn đoán hình ảnh (RIS/PACS)</span>
        <span id="tableFilterLabel" style="font-size:12px; color:var(--muted); font-weight:400; margin-left:6px;">(Dữ liệu: <?= htmlspecialchars($date ?? date('d/m/Y')) ?>)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm kỹ thuật CĐHA, mã kỳ..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th id="thCode" style="min-width:90px;">Mã kỳ / Mã kỹ thuật</th>
            <th id="thName" style="min-width:220px;">Kỳ báo cáo / Kỹ thuật CĐHA</th>
            <th style="text-align:right;">Tổng ca</th>
            <th style="text-align:right;">BHYT</th>
            <th style="text-align:right;">Thu phí/VP</th>
            <th style="text-align:right;">Ngoại trú</th>
            <th style="text-align:right;">Nội trú</th>
            <th style="text-align:right;">Số phim in</th>
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
// Các biến biểu đồ đang hoạt động
let chartRisPayerDonut = null;
let chartRisPatientTypeDonut = null;
let chartRisMultiLine = null;
let currentRisCats = <?= json_encode($ris['charts']['multiline']['categories'] ?? [], JSON_UNESCAPED_UNICODE) ?>;

// [NOTE]: Biến của các biểu đồ đã chuyển thành ghi chú, không xóa:
// let chartRisFunnel = null;
// let chartRisFilmlessStacked = null;
// let chartRisModalityClustered = null;
// let chartRisTatBars = null;

function initCharts() {
  const isLight = document.documentElement.getAttribute('data-theme') === 'light';
  const labelColor = isLight ? '#334155' : '#85a4c4';
  const legendColor = isLight ? '#1e293b' : '#b2cbe4';
  const gridBorder = isLight ? 'rgba(203, 213, 225, 0.7)' : 'rgba(38, 76, 115, 0.35)';

  const chartTheme = {
    theme: { mode: isLight ? 'light' : 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: gridBorder, strokeDashArray: 3 }
  };

  // 1. Biểu đồ Donut: Nguồn Chi Trả CĐHA (BHYT vs Viện phí - Dữ liệu thực từ CSDL)
  try {
    const payerColors = isLight ? ['#0284c7', '#ea580c'] : ['#00f2fe', '#ff9100'];
    const payerSeries = <?= json_encode($ris['charts']['payer_donut'] ?? [(int)($ris['bhyt'] ?? 0), (int)($ris['self_pay'] ?? 0)]) ?>;
    chartRisPayerDonut = new ApexCharts(document.getElementById('chartRisPayerDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 260 },
      colors: payerColors,
      labels: ['BHYT', 'Viện phí (Thu phí)'],
      series: payerSeries,
      stroke: { width: 0 },
      plotOptions: {
        pie: {
          donut: {
            size: '68%',
            labels: {
              show: true,
              name: { show: true, color: labelColor, fontSize: '11px', offsetY: -3 },
              value: {
                show: true,
                color: isLight ? '#0284c7' : '#00f2fe',
                fontSize: '17px',
                fontWeight: 700,
                offsetY: 3,
                formatter: v => Number(v).toLocaleString('vi-VN') + ' ca'
              },
              total: {
                show: true,
                label: 'Tổng ca',
                color: labelColor,
                fontSize: '11px',
                formatter: function(w) {
                  const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                  return Number(total).toLocaleString('vi-VN') + ' ca';
                }
              }
            }
          }
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function(val) { return val.toFixed(1) + '%'; },
        style: { fontSize: '12px', colors: ['#fff'] }
      },
      legend: { position: 'bottom', labels: { colors: legendColor } },
      tooltip: {
        y: { formatter: v => Number(v).toLocaleString('vi-VN') + ' ca' }
      }
    });
    chartRisPayerDonut.render();
    window.chartRisPayerDonut = chartRisPayerDonut;
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisPayerDonut:', e);
  }

  // 2. Biểu đồ Donut: Cơ Cấu Nguồn Bệnh Nhân (Ngoại trú vs Nội trú - Dữ liệu thực từ CSDL)
  try {
    const patientColors = isLight ? ['#2563eb', '#0d9488'] : ['#39a0ff', '#20c6b7'];
    const patientSeries = <?= json_encode($ris['charts']['patient_donut'] ?? [(int)($ris['outpatient'] ?? 0), (int)($ris['inpatient'] ?? 0)]) ?>;
    chartRisPatientTypeDonut = new ApexCharts(document.getElementById('chartRisPatientTypeDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 260 },
      colors: patientColors,
      labels: ['Ngoại trú', 'Nội trú'],
      series: patientSeries,
      stroke: { width: 0 },
      plotOptions: {
        pie: {
          donut: {
            size: '68%',
            labels: {
              show: true,
              name: { show: true, color: labelColor, fontSize: '11px', offsetY: -3 },
              value: {
                show: true,
                color: isLight ? '#2563eb' : '#39a0ff',
                fontSize: '17px',
                fontWeight: 700,
                offsetY: 3,
                formatter: v => Number(v).toLocaleString('vi-VN') + ' ca'
              },
              total: {
                show: true,
                label: 'Tổng ca',
                color: labelColor,
                fontSize: '11px',
                formatter: function(w) {
                  const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                  return Number(total).toLocaleString('vi-VN') + ' ca';
                }
              }
            }
          }
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function(val) { return val.toFixed(1) + '%'; },
        style: { fontSize: '12px', colors: ['#fff'] }
      },
      legend: { position: 'bottom', labels: { colors: legendColor } },
      tooltip: {
        y: { formatter: v => Number(v).toLocaleString('vi-VN') + ' ca' }
      }
    });
    chartRisPatientTypeDonut.render();
    window.chartRisPatientTypeDonut = chartRisPatientTypeDonut;
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisPatientTypeDonut:', e);
  }

  // Auto-toggle dataLabels: đổi giữa % và chỉ số thực mỗi 4 giây (như ở Khám chữa bệnh Ngoại trú)
  let _risDonutShowPct = true;
  setInterval(function() {
    _risDonutShowPct = !_risDonutShowPct;
    const fmtRis = _risDonutShowPct
      ? function(val, opts) { return val.toFixed(1) + '%'; }
      : function(val, opts) {
          const s = opts.w.globals.series;
          return Number(s[opts.seriesIndex] || 0).toLocaleString('vi-VN') + ' ca';
        };
    if (chartRisPayerDonut) {
      chartRisPayerDonut.updateOptions({ dataLabels: { formatter: fmtRis } }, false, false);
    }
    if (chartRisPatientTypeDonut) {
      chartRisPatientTypeDonut.updateOptions({ dataLabels: { formatter: fmtRis } }, false, false);
    }
  }, 4000);

  ['chartRisPayerDonut', 'chartRisPatientTypeDonut'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.style.cursor = 'pointer';
      el.setAttribute('title', 'Nhấn để chuyển đổi giữa % và Số lượng ca');
      el.addEventListener('click', function() {
        _risDonutShowPct = !_risDonutShowPct;
        const fmtRis = _risDonutShowPct
          ? function(val, opts) { return val.toFixed(1) + '%'; }
          : function(val, opts) {
              const s = opts.w.globals.series;
              return Number(s[opts.seriesIndex] || 0).toLocaleString('vi-VN') + ' ca';
            };
        if (chartRisPayerDonut) chartRisPayerDonut.updateOptions({ dataLabels: { formatter: fmtRis } }, false, false);
        if (chartRisPatientTypeDonut) chartRisPatientTypeDonut.updateOptions({ dataLabels: { formatter: fmtRis } }, false, false);
      });
    }
  });

  // 3. Biểu đồ Multi-Line: Diễn Biến Sản Lượng Theo Thời Gian (X-quang KTS & Siêu âm Doppler)
  try {
    const mlColors = isLight ? ['#0284c7', '#0d9488'] : ['#00f2fe', '#20c6b7'];
    const multiLineSeries = <?= json_encode($ris['charts']['multiline']['series'] ?? [], JSON_UNESCAPED_UNICODE) ?>;
    currentRisCats = <?= json_encode($ris['charts']['multiline']['categories'] ?? [], JSON_UNESCAPED_UNICODE) ?>;
    chartRisMultiLine = new ApexCharts(document.getElementById('chartRisMultiLine'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'line', height: 260 },
      colors: mlColors,
      stroke: { curve: 'smooth', width: 2.5 },
      series: multiLineSeries,
      xaxis: {
        categories: currentRisCats,
        labels: { style: { colors: labelColor, fontSize: '11px' } }
      },
      yaxis: {
        labels: {
          style: { colors: labelColor },
          formatter: v => Math.round(v) + ' ca'
        }
      },
      legend: { position: 'top', labels: { colors: legendColor } },
      tooltip: {
        y: { formatter: v => Number(v).toLocaleString('vi-VN') + ' ca' }
      }
    });
    chartRisMultiLine.render();
    window.chartRisMultiLine = chartRisMultiLine;
  } catch(e) {
    console.error('Lỗi khởi tạo chartRisMultiLine:', e);
  }

  // [NOTE]: Biểu đồ 1: Funnel Chart (Biểu đồ Phễu) đã được lưu trữ dưới dạng ghi chú
  /*
  const risFunnelSteps = ['1. Chỉ định', '2. Tiếp nhận', '3. Chụp xong', '4. Lên PACS', '5. BS đọc & Trả KQ'];
  try {
    chartRisFunnel = new ApexCharts(document.getElementById('chartRisFunnel'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 260 },
      plotOptions: {
        bar: { borderRadius: 4, horizontal: true, barHeight: '75%', isFunnel: true, distributed: true }
      },
      colors: ['#00f2fe', '#20c6b7', '#39a0ff', '#ffc107', '#2ecc71'],
      dataLabels: {
        enabled: true,
        formatter: (val, opt) => (opt && risFunnelSteps[opt.dataPointIndex] ? risFunnelSteps[opt.dataPointIndex] + ': ' : '') + val + ' ca',
        style: { fontSize: '11px', colors: ['#fff'] }
      },
      series: [{ name: 'Số ca', data: [176, 174, 171, 171, 167] }],
      xaxis: { categories: risFunnelSteps, labels: { show: false } },
      legend: { show: false }
    });
    chartRisFunnel.render();
  } catch(e) {}
  */

  // [NOTE]: Biểu đồ 2: 100% Stacked Bar (Tỷ Lệ Không Dùng Phim Nhựa) đã được lưu trữ dưới dạng ghi chú
  /*
  try {
    chartRisFilmlessStacked = new ApexCharts(document.getElementById('chartRisFilmlessStacked'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 260, stacked: true, stackType: '100%' },
      plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 4 } },
      colors: ['#00f2fe', '#ff9100'],
      series: [
        { name: 'Không in phim (Số hóa PACS %)', data: [100, 100, 100, 100, 100] },
        { name: 'Có in phim nhựa (%)', data: [0, 0, 0, 0, 0] }
      ],
      xaxis: { categories: ['X-Quang KTS', 'Siêu âm Doppler', 'CT-Scanner', 'Nội soi', 'Điện tim'] }
    });
    chartRisFilmlessStacked.render();
  } catch(e) {}
  */
}

// Lắng nghe sự kiện chuyển đổi Sáng / Tối để đổi màu chữ trục, màu đường biểu đồ và lưới
window.addEventListener('themeChanged', function(e) {
  const isL = e.detail && e.detail.theme === 'light';
  const labelColor = isL ? '#334155' : '#85a4c4';
  const legendColor = isL ? '#1e293b' : '#b2cbe4';
  const gridBorder = isL ? 'rgba(203, 213, 225, 0.7)' : 'rgba(38, 76, 115, 0.35)';
  const payerColors = isL ? ['#0284c7', '#ea580c'] : ['#00f2fe', '#ff9100'];
  const patientColors = isL ? ['#2563eb', '#0d9488'] : ['#39a0ff', '#20c6b7'];
  const mlColors = isL ? ['#0284c7', '#0d9488'] : ['#00f2fe', '#20c6b7'];

  if (window.chartRisPayerDonut && typeof window.chartRisPayerDonut.updateOptions === 'function') {
    window.chartRisPayerDonut.updateOptions({
      theme: { mode: isL ? 'light' : 'dark' },
      colors: payerColors,
      plotOptions: {
        pie: {
          donut: {
            labels: {
              name: { color: labelColor },
              value: { color: isL ? '#0284c7' : '#00f2fe' },
              total: { color: labelColor }
            }
          }
        }
      },
      legend: { labels: { colors: legendColor } }
    }, false, false);
  }

  if (window.chartRisPatientTypeDonut && typeof window.chartRisPatientTypeDonut.updateOptions === 'function') {
    window.chartRisPatientTypeDonut.updateOptions({
      theme: { mode: isL ? 'light' : 'dark' },
      colors: patientColors,
      plotOptions: {
        pie: {
          donut: {
            labels: {
              name: { color: labelColor },
              value: { color: isL ? '#2563eb' : '#39a0ff' },
              total: { color: labelColor }
            }
          }
        }
      },
      legend: { labels: { colors: legendColor } }
    }, false, false);
  }

  if (window.chartRisMultiLine && typeof window.chartRisMultiLine.updateOptions === 'function') {
    window.chartRisMultiLine.updateOptions({
      theme: { mode: isL ? 'light' : 'dark' },
      colors: mlColors,
      grid: { borderColor: gridBorder },
      xaxis: {
        categories: currentRisCats,
        labels: { style: { colors: labelColor, fontSize: '11px' } }
      },
      yaxis: {
        labels: {
          style: { colors: labelColor },
          formatter: v => Math.round(v) + ' ca'
        }
      },
      legend: { labels: { colors: legendColor } }
    }, false, false);
  }
});

// ================= CẬP NHẬT TOÀN DIỆN KHI LỌC DỮ LIỆU (KPI, 3 BIỂU ĐỒ & BẢNG) =================
window.fetchDashboardData = async function() {
  const p = typeof getFilterParams === 'function' ? getFilterParams() : '';
  const queryStr = p.toString ? p.toString() : p;

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // 1. Cập nhật các thẻ KPI CĐHA theo danh mục ioc_ris_categories (100% dữ liệu thực từ CSDL)
    if (data.ris) {
      const r = data.ris;
      const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.innerHTML = val; };
      setEl('kpiRisTotal', Number(r.total || 0).toLocaleString('vi-VN'));
      setEl('kpiRisBhyt', Number(r.bhyt || 0).toLocaleString('vi-VN'));
      setEl('kpiRisSelfPay', Number(r.self_pay || 0).toLocaleString('vi-VN'));
      setEl('kpiRisOutpatient', Number(r.outpatient || 0).toLocaleString('vi-VN'));
      setEl('kpiRisInpatient', Number(r.inpatient || 0).toLocaleString('vi-VN'));
      setEl('kpiRisBhytRate', `${r.bhyt_rate || (r.total > 0 ? ((r.bhyt / r.total) * 100).toFixed(1) : '0')}%`);

      const bhytPct = r.total > 0 ? ((r.bhyt / r.total) * 100).toFixed(1) : '0';
      setEl('kpiRisBhytSub', `Chiếm ${bhytPct}% tổng số ca`);
      const vpPct = r.total > 0 ? ((r.self_pay / r.total) * 100).toFixed(1) : '0';
      setEl('kpiRisSelfPaySub', `Chiếm ${vpPct}% tổng số ca`);
      const outPct = r.total > 0 ? ((r.outpatient / r.total) * 100).toFixed(1) : '0';
      setEl('kpiRisOutpatientSub', `Chiếm ${outPct}% tổng số ca`);
      const inPct = r.total > 0 ? ((r.inpatient / r.total) * 100).toFixed(1) : '0';
      setEl('kpiRisInpatientSub', `Chiếm ${inPct}% tổng số ca`);

      if (r.categories && Array.isArray(r.categories)) {
        r.categories.forEach(cat => {
          const valEl = document.getElementById(`kpiRisCatVal_${cat.id}`);
          if (valEl) valEl.textContent = Number(cat.total_scans || 0).toLocaleString('vi-VN');

          const lblEl = document.getElementById(`kpiRisCatLabel_${cat.id}`);
          if (lblEl && cat.ris_category_name) lblEl.textContent = cat.ris_category_name;

          const pctEl = document.getElementById(`kpiRisCatPct_${cat.id}`);
          const pct = r.total > 0 ? ((cat.total_scans / r.total) * 100).toFixed(1) : '0';
          if (pctEl) pctEl.textContent = `${pct}%`;
        });
      }

      const elBadge = document.getElementById('kpiRisTrendBadge');
      if (elBadge && r.growth_label) {
        const isUp = !String(r.growth_label).startsWith('-');
        elBadge.className = isUp ? 'trend-up' : 'trend-down';
        elBadge.innerHTML = `<i class="fa-solid fa-arrow-trend-${isUp ? 'up' : 'down'}"></i> ${r.growth_label}`;
      }
      const elSubDesc = document.getElementById('kpiRisSubtextDesc');
      if (elSubDesc && r.growth_subtext) elSubDesc.textContent = r.growth_subtext;

      // 2. Cập nhật 3 biểu đồ dữ liệu thực
      if (r.charts) {
        const rc = r.charts;
        if (chartRisPayerDonut && rc.payer_donut) {
          chartRisPayerDonut.updateSeries(rc.payer_donut);
        }
        if (chartRisPatientTypeDonut && rc.patient_donut) {
          chartRisPatientTypeDonut.updateSeries(rc.patient_donut);
        }
        if (chartRisMultiLine && rc.multiline) {
          currentRisCats = rc.multiline.categories || [];
          const isL = document.documentElement.getAttribute('data-theme') === 'light';
          const labelColor = isL ? '#334155' : '#85a4c4';
          chartRisMultiLine.updateOptions({
            xaxis: {
              categories: currentRisCats,
              labels: { style: { colors: labelColor, fontSize: '11px' } }
            }
          }, false, false);
          chartRisMultiLine.updateSeries(rc.multiline.series);
        }
      }
    }

    const tblLbl = document.getElementById('tableFilterLabel');
    if (tblLbl && data.date) {
      tblLbl.textContent = `(Dữ liệu: ${data.date})`;
    }

    // 3. Cập nhật Bảng Dữ liệu CĐHA (Bộ lọc cập nhật dữ liệu bảng chi tiết)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && data.tables && data.tables.ris) {
      tbody.innerHTML = '';
      let sTot = 0, sBh = 0, sVp = 0, sOut = 0, sIn = 0, sFilms = 0;

      data.tables.ris.forEach(row => {
        const cTot = parseInt(row.col1) || 0;
        const cBh = parseInt(row.col2) || 0;
        const cVp = parseInt(row.col3) || 0;
        const cOut = parseInt(row.col4) || 0;
        const cIn = parseInt(row.col5) || 0;
        const cFim = parseInt(row.col6) || 0;

        sTot += cTot;
        sBh += cBh;
        sVp += cVp;
        sOut += cOut;
        sIn += cIn;
        sFilms += cFim;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right; font-weight:700;">${cTot.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:var(--teal); font-weight:700;">${cBh.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:var(--warn); font-weight:700;">${cVp.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; font-weight:600;">${cOut.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; font-weight:600;">${cIn.toLocaleString('vi-VN')}</td>
          <td style="text-align:right; color:var(--gold); font-weight:700;">${row.col6}</td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        const filmsTotal = (data.ris && typeof data.ris.total_films !== 'undefined' && data.ris.total_films !== null) ? Number(data.ris.total_films) : sFilms;
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Ca CĐHA Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sTot.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--teal); font-weight:800;">${sBh.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--warn); font-weight:800;">${sVp.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:700;">${sOut.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:700;">${sIn.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--gold); font-weight:800;">${filmsTotal.toLocaleString('vi-VN')} tấm</td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load RIS/PACS:", e);
  }
};

// Tìm kiếm nhanh trên bảng kỹ thuật CĐHA
const searchInp = document.getElementById('tableSearchInput');
if (searchInp) {
  searchInp.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#iocTableBody tr');
    rows.forEach(r => {
      const txt = r.textContent.toLowerCase();
      r.style.display = txt.includes(q) ? '' : 'none';
    });
  });
}

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
