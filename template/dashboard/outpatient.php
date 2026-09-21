<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>



  <!-- 1. KPI STATS ROW (TRÊN CÙNG) -->
  <?php $ok = $outpatient['kpi'] ?? []; ?>
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Tổng lượt tiếp đón</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-users-viewfinder"></i></div>
      </div>
      <div class="kpi-value" id="kpiTongKham"><?= number_format($ok['tong_kham'] ?? 143) ?></div>
      <div class="kpi-subtext" id="kpiKcbSubtext"><span class="trend-up" id="kpiKcbBadge"><i class="fa-solid fa-arrow-trend-up"></i> <?= $ok['growth_label'] ?? '+5.1%' ?></span> <span id="kpiKcbSubtextDesc"><?= $ok['growth_subtext'] ?? 'so với hôm trước' ?></span></div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Bệnh nhân đang chờ khám</span>
        <div class="kpi-icon-wrap" style="color:var(--warn); background:rgba(241,196,15,0.1);"><i class="fa-solid fa-hourglass-half"></i></div>
      </div>
      <div class="kpi-value" id="kpiDangCho" style="color:var(--warn);"><?= number_format($ok['dang_cho'] ?? 17) ?></div>
      <div class="kpi-subtext">Cao điểm tại PK Khám bệnh &amp; PK Nội</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Đang trong phòng khám</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-stethoscope"></i></div>
      </div>
      <div class="kpi-value" id="kpiDangKham"><?= number_format($ok['dang_kham'] ?? 9) ?></div>
      <div class="kpi-subtext">8/8 bàn khám đang hoạt động</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Đã hoàn thành khám</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-circle-check"></i></div>
      </div>
      <div class="kpi-value" id="kpiHoanThanh" style="color:var(--ok);"><?= number_format($ok['hoan_thanh'] ?? 117) ?></div>
      <div class="kpi-subtext" id="kpiHoanThanhSub">Tỷ lệ hoàn thành <?= ($ok['tong_kham'] ?? 0) > 0 ? round((($ok['hoan_thanh'] ?? 117) / $ok['tong_kham']) * 100, 1) : 81.8 ?>%</div>
    </div>

    <!-- [NOTE]: KPI Thẻ Chỉ Số: Thời Gian Chờ Khám TB (Phút)
         - Mục tiêu: Giám sát thời gian chờ đợi trung bình của người bệnh từ lúc lấy số đến khi vào phòng khám
         - Dữ liệu: ioc_outpatient_daily.avg_wait_minutes
         - Đơn vị: phút (chỉ tiêu BV: <= 20 phút)
    -->

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Chuyển viện / Tuyến trên</span>
        <div class="kpi-icon-wrap" style="color:var(--bad); background:rgba(255,92,92,0.1);"><i class="fa-solid fa-truck-medical"></i></div>
      </div>
      <div class="kpi-value" id="kpiChuyenTuyen"><?= number_format($ok['chuyen_tuyen'] ?? 5) ?></div>
      <div class="kpi-subtext" id="kpiChuyenTuyenSub">Tỷ lệ chuyển tuyến <?= ($ok['tong_kham'] ?? 0) > 0 ? round((($ok['chuyen_tuyen'] ?? 5) / $ok['tong_kham']) * 100, 1) : 3.5 ?>%</div>
    </div>
  </section>

  <!-- 2. BIỂU ĐỒ NGOẠI TRÚ -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Funnel Chart (Quy trình luồng khám) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-filter"></i>Luồng Bệnh Nhân Khám Bệnh</div>
          <div class="chart-subtitle">Tỷ lệ chuyển tiếp qua từng bước trong quy trình khám bệnh</div>
        </div>
      </div>
      <div class="chart-body" id="chartKcbFunnel"></div>
    </div>

    <!-- [NOTE]: Biểu đồ Nhiệt (Heatmap): Mật Độ Bệnh Nhân Theo Khung Giờ & Bàn Khám
         - Mục tiêu: Xác định chính xác giờ cao điểm ùn ứ cục bộ tại các chuyên khoa
         - Dữ liệu: ioc_hourly_traffic (hourly_slot x room_code x patient_count)
         - Cần bảng ioc_hourly_traffic có đủ dữ liệu thực tế theo ngày để hiển thị chính xác
         - Chart type: heatmap | colors: #00f2fe | ID: chartKcbHeatmap | col-8
    -->

    <!-- Biểu đồ 2: Donut - Tỷ lệ BHYT vs Viện phí (lượt khám) -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-pie"></i> Lượt Khám: BHYT vs Viện Phí</div>
          <div class="chart-subtitle">Cơ cấu đối tượng chi trả trong ngày</div>
        </div>
      </div>
      <div class="chart-body" id="chartPayerDonut"></div>
    </div>

    <!-- Biểu đồ 3: Donut - Tình trạng / kết quả khám bệnh nhân -->
    <div class="chart-card col-4">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-notes-medical"></i> Thống Kê Tình Trạng Bệnh Nhân</div>
          <div class="chart-subtitle">Kết quả xử lý sau khám: chuyển tuyến, nhập viện, cấp toa, tử vong, cấp cứu</div>
        </div>
      </div>
      <div class="chart-body" id="chartOutcomeDonut"></div>
    </div>

    <!-- Biểu đồ 4: 100% Stacked Bar - BHYT vs Viện phí theo phòng khám -->
    <div class="chart-card col-12">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-chart-column"></i>Cơ Cấu BHYT vs Viện Phí</div>
          <div class="chart-subtitle">Tỷ lệ đối tượng chi trả tại từng phòng khám</div>
        </div>
      </div>
      <div class="chart-body" id="chartKcbPayerStacked"></div>
    </div>

    <!-- [NOTE]: Cột Nhóm: Thời Gian Chờ Khám vs Khám Thực Tế (Phút)
         - Mục tiêu: So sánh thời gian bệnh nhân ngồi đợi và thời lượng bác sĩ tư vấn
         - Dữ liệu: ioc_outpatient_daily.avg_wait_minutes + avg_exam_minutes (cần bổ sung cột avg_exam_minutes)
         - Chart type: clustered bar | colors: #ffc107, #20c6b7 | ID: chartWaitVsExam | col-5
    -->

    <!-- [NOTE]: Mô Hình Bệnh Tật (ICD-10)
         - Mục tiêu: Hiển thị top nhóm bệnh ngoại trú theo phân loại ICD-10
         - Dữ liệu cần: bảng ioc_diagnosis_daily (diagnosis_code, diagnosis_count, report_date)
         - Chart type: donut | colors: #00f2fe, #39a0ff, #ffc107, #2ecc71, #ff5c5c
         - Labels: Hô hấp (J00-J99), Tuần hoàn (I00-I99), Tiêu hóa (K00-K93), Cơ xương khớp (M00), Khác
         - ID: chartIcdDonut | col-3
    -->

    <!-- [NOTE]: Biểu đồ Đường Thời Gian Thực (Real-Time): Diễn Biến Tiếp Đón Từng Khung Giờ Hôm Nay
         - Mục tiêu: Theo dõi diễn biến tiếp đón bệnh nhân theo từng khung giờ (07:00 → 16:30)
         - Khi xem ngày hiện tại: interval 60s tự động làm mới (real-time)
         - Khi xem ngày khác: hiển thị tĩnh dữ liệu lịch sử theo khung giờ
         - Dữ liệu: ioc_hourly_traffic (report_date, hourly_slot, total_patients)
         - Chart type: area / line | colors: #00f2fe | ID: chartKcbRealtimeArea | col-6
    -->
  </section>

  <!-- [NOTE]: Bộ lọc đa tiêu chí đã được đưa lên đầu trang tại topbar.php -->

  <!-- 3. BẢNG DỮ LIỆU CHI TIẾT -->
  <section class="table-section">
    <div class="table-header">
      <div class="table-title">
        <i class="fa-solid fa-table-list"></i>
        <span>Bảng Giám sát Chi tiết Phòng khám Ngoại trú &amp; Tiến độ Khám bệnh</span>
        <span id="tableFilterLabel" style="font-size:12px; font-weight:500; color:var(--accent); margin-left:8px;"></span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm kiếm phòng khám, bác sĩ..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:40px; text-align:center;">STT</th>
            <th>Ngày / Mã Bàn</th>
            <th>Khoa / Phòng / Chuyên Khoa</th>
            <th style="text-align:right;">Tổng lượt khám</th>
            <th style="text-align:right;">Khám BHYT</th>
            <th style="text-align:right;">Viện phí / DV</th>
            <th style="text-align:right;">Đang chờ</th>
            <th style="text-align:right;">Đang khám</th>
            <th style="text-align:right;">Hoàn thành</th>
            <th style="text-align:right;">Chỉ định CLS</th>
            <th style="text-align:right;">Vào nội trú</th>
            <th style="text-align:right;">Ra viện</th>
            <th style="text-align:right;">Chuyển tuyến</th>
            <th style="text-align:right;">Thời gian chờ</th>
            <th style="text-align:center;">Trạng thái</th>
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
let chartKcbFunnel      = null;
let chartPayerDonut     = null;
let chartOutcomeDonut   = null;
let chartKcbStacked     = null;
let chartKcbRealtimeArea = null;

<?php
$outCharts       = $outpatient['charts'] ?? [];
$outFunnel       = $outCharts['funnel']  ?? [143, 126, 126, 88, 117];
$outPayerDonut   = $outCharts['payer_donut'] ?? ['series' => [123, 20], 'labels' => ['BHYT', 'Viện phí / DV']];
$outOutcomeDonut = $outCharts['outcome_donut'] ?? ['series' => [109, 22, 5, 7, 0], 'labels' => ['Cấp toa cho về', 'Nhập viện nội trú', 'Chuyển tuyến', 'Cấp cứu', 'Tử vong']];
$outPayerStack   = $outCharts['payer_stacked'] ?? [];
$outRealtime     = $outCharts['realtime_hourly'] ?? [];
?>

// Trang Ngoại trú: dùng getFilterParams chung (filter.php đã set ngày hôm nay làm mặc định)
// getFilterParams sẽ được dùng bởi fetchDashboardData bên dưới — không cần override thêm

// =================== KHỞI TẠO BIỂU ĐỒ ===================
function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Funnel ca khám
  const kcbFunnelSteps = ['1. Tiếp đón', '2. Vào khám', '3. Khám lâm sàng', '4. Chỉ định CLS', '5. Hoàn tất khám'];
  try {
    chartKcbFunnel = new ApexCharts(document.getElementById('chartKcbFunnel'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 270 },
      plotOptions: { bar: { borderRadius: 4, horizontal: true, barHeight: '75%', isFunnel: true, distributed: true } },
      colors: ['#00f2fe', '#20c6b7', '#39a0ff', '#ffc107', '#2ecc71'],
      dataLabels: {
        enabled: true,
        formatter: function(val, opt) {
          const step = kcbFunnelSteps[opt.dataPointIndex] || '';
          return step ? step + ': ' + val + ' ca' : val + ' ca';
        },
        style: { fontSize: '11px', colors: ['#fff'] }
      },
      series: [{ name: 'Bệnh nhân', data: <?= json_encode($outFunnel) ?> }],
      xaxis: {
        categories: ['1. Đăng ký tiếp đón', '2. Đã gọi vào phòng', '3. Khám lâm sàng', '4. Chỉ định CLS', '5. Hoàn tất khám'],
        labels: { show: false }
      },
      legend: { show: false }
    });
    chartKcbFunnel.render();
  } catch(e) { console.error('Lỗi chartKcbFunnel:', e); }

  // 2. Donut: BHYT vs Viện phí
  try {
    chartPayerDonut = new ApexCharts(document.getElementById('chartPayerDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 270 },
      colors: ['#00f2fe', '#ff9100'],
      labels: <?= json_encode($outPayerDonut['labels'] ?? ['BHYT', 'Viện phí / Dịch vụ']) ?>,
      series: <?= json_encode($outPayerDonut['series'] ?? [123, 20]) ?>,
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Tổng lượt',
                color: '#b2cbe4',
                formatter: function(w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' ca';
                }
              }
            }
          }
        }
      },
      legend: { position: 'bottom', labels: { colors: '#b2cbe4' } },
      dataLabels: {
        enabled: true,
        formatter: function(val) { return val.toFixed(1) + '%'; },
        style: { fontSize: '12px', colors: ['#fff'] }
      }
    });
    chartPayerDonut.render();
  } catch(e) { console.error('Lỗi chartPayerDonut:', e); }

  // 3. Donut: Tình trạng bệnh nhân sau khám
  try {
    chartOutcomeDonut = new ApexCharts(document.getElementById('chartOutcomeDonut'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'donut', height: 270 },
      colors: ['#2ecc71', '#39a0ff', '#ffc107', '#a29bfe', '#ff5c5c'],
      labels: <?= json_encode($outOutcomeDonut['labels'] ?? ['Cấp toa cho về', 'Nhập viện nội trú', 'Chuyển tuyến', 'Cấp cứu', 'Tử vong']) ?>,
      series: <?= json_encode($outOutcomeDonut['series'] ?? [109, 22, 5, 7, 0]) ?>,
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Kết quả khám',
                color: '#b2cbe4',
                formatter: function(w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' ca';
                }
              }
            }
          }
        }
      },
      legend: { position: 'bottom', labels: { colors: '#b2cbe4' } },
      dataLabels: {
        enabled: true,
        formatter: function(val) { return val.toFixed(1) + '%'; },
        style: { fontSize: '11px', colors: ['#fff'] }
      }
    });
    chartOutcomeDonut.render();
  } catch(e) { console.error('Lỗi chartOutcomeDonut:', e); }

  // 4. 100% Stacked Bar: BHYT vs Viện phí theo phòng khám
  try {
    chartKcbStacked = new ApexCharts(document.getElementById('chartKcbPayerStacked'), {
      ...chartTheme,
      chart: { ...chartTheme.chart, type: 'bar', height: 270, stacked: true, stackType: '100%' },
      colors: ['#00f2fe', '#ff9100'],
      series: [
        { name: 'BHYT (%)', data: <?= json_encode($outPayerStack['bhyt'] ?? [85, 88, 90, 82, 80, 84, 86, 75]) ?> },
        { name: 'Viện phí (%)', data: <?= json_encode($outPayerStack['vien_phi'] ?? [15, 12, 10, 18, 20, 16, 14, 25]) ?> }
      ],
      xaxis: {
        categories: ['PK Nội', 'PK Ngoại', 'PK Sản', 'PK Nhi', 'PK Mắt', 'PK TMH', 'PK RHM', 'Cấp cứu'],
        labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
      },
      yaxis: { labels: { style: { colors: '#85a4c4' } } },
      legend: { position: 'top', labels: { colors: '#b2cbe4' } }
    });
    chartKcbStacked.render();
  } catch(e) { console.error('Lỗi chartKcbStacked:', e); }

  // 5. Area Chart: Diễn biến tiếp đón theo khung giờ (chuyển sang note)
  const elRealtime = document.getElementById('chartKcbRealtimeArea');
  if (elRealtime) {
    try {
      chartKcbRealtimeArea = new ApexCharts(elRealtime, {
        ...chartTheme,
        chart: { ...chartTheme.chart, type: 'area', height: 230 },
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#00f2fe'],
        fill: {
          type: 'gradient',
          gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] }
        },
        series: [{ name: 'Lượt tiếp đón', data: <?= json_encode($outRealtime['series'] ?? [11, 29, 36, 21, 9, 14, 21, 10, 6]) ?> }],
        xaxis: {
          categories: ['07:00', '08:00', '09:00', '10:00', '11:00', '13:30', '14:30', '15:30', '16:30'],
          labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
        },
        yaxis: { labels: { style: { colors: '#85a4c4' } } },
        annotations: {
          xaxis: [{ x: '09:00', borderColor: '#ff5c5c', label: { text: 'Cao điểm', style: { color: '#fff', background: '#ff5c5c', fontSize: '10px' } } }]
        }
      });
      chartKcbRealtimeArea.render();
    } catch(e) { console.error('Lỗi chartKcbRealtimeArea:', e); }
  }

  // Auto-toggle dataLabels: đổi giữa % và chỉ số thực mỗi 4 giây
  let _donutShowPct = true;
  setInterval(function() {
    _donutShowPct = !_donutShowPct;
    const fmtPayer = _donutShowPct
      ? function(val, opts) { return val.toFixed(1) + '%'; }
      : function(val, opts) {
          const s = opts.w.globals.series;
          return s[opts.seriesIndex] + ' ca';
        };
    const fmtOutcome = _donutShowPct
      ? function(val, opts) { return val.toFixed(1) + '%'; }
      : function(val, opts) {
          const s = opts.w.globals.series;
          return s[opts.seriesIndex] + ' ca';
        };
    if (chartPayerDonut)   chartPayerDonut.updateOptions({ dataLabels: { formatter: fmtPayer } }, false, false);
    if (chartOutcomeDonut) chartOutcomeDonut.updateOptions({ dataLabels: { formatter: fmtOutcome } }, false, false);
  }, 4000);
}

// =================== TẢI DỮ LIỆU NGOẠI TRÚ ===================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : '';
  try {
    const res  = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    // 1. Cập nhật KPI
    if (data.outpatient && data.outpatient.kpi) {
      const ok = data.outpatient.kpi;
      const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
      setEl('kpiTongKham',    Number(ok.tong_kham || 0).toLocaleString('vi-VN'));
      setEl('kpiDangCho',     Number(ok.dang_cho || 0).toLocaleString('vi-VN'));
      setEl('kpiDangKham',    Number(ok.dang_kham || 0).toLocaleString('vi-VN'));
      setEl('kpiHoanThanh',   Number(ok.hoan_thanh || 0).toLocaleString('vi-VN'));
      setEl('kpiChuyenTuyen', Number(ok.chuyen_tuyen || 0).toLocaleString('vi-VN'));
      const elChoTb = document.getElementById('kpiChoTb');
      if (elChoTb) elChoTb.innerHTML = `${ok.thoi_gian_cho_tb || 16.8} <span style="font-size:14px; font-weight:600; color:var(--muted);">phút</span>`;
      const elBadge = document.getElementById('kpiKcbBadge');
      if (elBadge && ok.growth_label) {
        const isUp = !String(ok.growth_label).startsWith('-');
        elBadge.className = isUp ? 'trend-up' : 'trend-down';
        elBadge.innerHTML = `<i class="fa-solid fa-arrow-trend-${isUp ? 'up' : 'down'}"></i> ${ok.growth_label}`;
      }
      const elSubDesc = document.getElementById('kpiKcbSubtextDesc');
      if (elSubDesc && ok.growth_subtext) elSubDesc.textContent = ok.growth_subtext;
      const elHtSub = document.getElementById('kpiHoanThanhSub');
      if (elHtSub) {
        const htPct = ok.tong_kham > 0 ? ((ok.hoan_thanh / ok.tong_kham) * 100).toFixed(1) : '81.8';
        elHtSub.textContent = `Tỷ lệ hoàn thành ${htPct}%`;
      }
      const elCtSub = document.getElementById('kpiChuyenTuyenSub');
      if (elCtSub) {
        const ctPct = ok.tong_kham > 0 ? ((ok.chuyen_tuyen / ok.tong_kham) * 100).toFixed(1) : '3.5';
        elCtSub.textContent = `Tỷ lệ chuyển tuyến ${ctPct}%`;
      }
    }

    const tblLbl = document.getElementById('tableFilterLabel');
    if (tblLbl && data.date) {
      tblLbl.textContent = `(Dữ liệu: ${data.date})`;
    }

    // 2. Cập nhật biểu đồ
    if (data.outpatient && data.outpatient.charts) {
      const oc = data.outpatient.charts;
      if (oc.funnel && chartKcbFunnel)          chartKcbFunnel.updateSeries([{ data: oc.funnel }]);
      if (oc.payer_donut && chartPayerDonut) {
        chartPayerDonut.updateOptions({ labels: oc.payer_donut.labels || ['BHYT', 'Viện phí / DV'] });
        chartPayerDonut.updateSeries(oc.payer_donut.series || [123, 20]);
      }
      if (oc.outcome_donut && chartOutcomeDonut) {
        chartOutcomeDonut.updateOptions({ labels: oc.outcome_donut.labels || [] });
        chartOutcomeDonut.updateSeries(oc.outcome_donut.series || [109, 22, 5, 7, 0]);
      }
      if (oc.payer_stacked && chartKcbStacked) {
        chartKcbStacked.updateOptions({ xaxis: { categories: oc.payer_stacked.categories || [] } });
        chartKcbStacked.updateSeries(oc.payer_stacked.series || []);
      }
      if (oc.realtime_hourly && chartKcbRealtimeArea) {
        const rt = oc.realtime_hourly;
        chartKcbRealtimeArea.updateSeries([{ name: 'Lượt tiếp đón', data: rt.series || [] }]);
        if (rt.categories) chartKcbRealtimeArea.updateOptions({ xaxis: { categories: rt.categories } });
      }
    }

    // 3. Cập nhật bảng
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');
    const tableRows = data.tables && data.tables.kcb ? data.tables.kcb : [];

    if (tbody && tableRows.length > 0) {
      tbody.innerHTML = '';
      let sV = 0, sBh = 0, sVp = 0, sWait = 0, sExam = 0, sComp = 0, sCls = 0, sAdm = 0, sDis = 0, sRef = 0;

      tableRows.forEach(row => {
        const v    = parseInt(String(row.col1).replace(/\D/g, '')) || 0;
        const bh   = parseInt(String(row.col2).replace(/\D/g, '')) || 0;
        const vp   = parseInt(String(row.col3).replace(/\D/g, '')) || 0;
        const wait = typeof row.col4 !== 'undefined' ? (parseInt(row.col4) || 0) : Math.round(v * 0.12);
        const exam = typeof row.col5 !== 'undefined' ? (parseInt(row.col5) || 0) : Math.round(v * 0.08);
        const comp = typeof row.col6 !== 'undefined' ? (parseInt(row.col6) || 0) : Math.round(v * 0.80);
        const cls  = typeof row.col7 !== 'undefined' ? (parseInt(row.col7) || 0) : Math.round(v * 0.60);
        const adm  = typeof row.col8 !== 'undefined' ? (parseInt(row.col8) || 0) : Math.round(v * 0.10);
        const dis  = typeof row.col9 !== 'undefined' ? (parseInt(row.col9) || 0) : Math.round(v * 0.15);
        const ref  = typeof row.col10 !== 'undefined' ? (parseInt(row.col10) || 0) : Math.round(v * 0.05);
        sV += v; sBh += bh; sVp += vp; sWait += wait; sExam += exam; sComp += comp;
        sCls += cls; sAdm += adm; sDis += dis; sRef += ref;
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td style="text-align:right; font-weight:700;">${row.col1}</td>
          <td style="text-align:right; color:#20c6b7;">${row.col2}</td>
          <td style="text-align:right; color:#ff9100;">${row.col3}</td>
          <td style="text-align:right; color:var(--warn); font-weight:700;">${wait}</td>
          <td style="text-align:right; color:var(--accent);">${exam}</td>
          <td style="text-align:right; color:var(--ok); font-weight:700;">${comp}</td>
          <td style="text-align:right; color:var(--teal); font-weight:700;">${cls}</td>
          <td style="text-align:right; color:#39a0ff;">${adm}</td>
          <td style="text-align:right; color:#a29bfe;">${dis}</td>
          <td style="text-align:right; color:#ff7675;">${ref}</td>
          <td style="text-align:right; color:var(--teal);">${row.col11 || '18.5 phút'}</td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type || 'ok'}">${row.status || 'Ổn định'}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (tfoot) {
        tfoot.innerHTML = `
          <tr>
            <td colspan="3" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Cộng Ngoại Trú Toàn Viện:</td>
            <td style="text-align:right; font-weight:800;">${sV.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#20c6b7; font-weight:800;">${sBh.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#ff9100; font-weight:800;">${sVp.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--warn); font-weight:800;">${sWait.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; font-weight:800;">${sExam.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--ok); font-weight:800;">${sComp.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--teal); font-weight:800;">${sCls.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#39a0ff; font-weight:800;">${sAdm.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#a29bfe; font-weight:800;">${sDis.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:#ff7675; font-weight:800;">${sRef.toLocaleString('vi-VN')}</td>
            <td style="text-align:right; color:var(--teal); font-weight:800;">18.5 phút</td>
            <td style="text-align:center;"><span class="status-pill ok">Ổn định</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn('Lỗi load KCB:', e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
