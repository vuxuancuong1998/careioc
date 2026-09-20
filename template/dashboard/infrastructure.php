<?php
include __DIR__ . '/header_common.php';
include __DIR__ . '/sidebar.php';
?>

<main class="ioc-main">
  <?php include __DIR__ . '/topbar.php'; ?>

  <!-- 1. KPI STATS ROW (TRÊN CÙNG) -->
  <?php $inf = $infrastructure ?? []; ?>
  <section class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Hệ thống Máy chủ Server</span>
        <div class="kpi-icon-wrap"><i class="fa-solid fa-server"></i></div>
      </div>
      <div class="kpi-value" id="kpiInfraServers" style="color:var(--ok);"><?= $inf['servers_online'] ?? '6/6 Online' ?></div>
      <div class="kpi-subtext" id="kpiInfraServersSub"><span style="color:var(--ok);"><i class="fa-solid fa-circle-check"></i> Hoạt động 100%</span> bình thường</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Thời gian Uptime liên tục</span>
        <div class="kpi-icon-wrap" style="color:var(--teal); background:rgba(32,198,183,0.1);"><i class="fa-solid fa-clock"></i></div>
      </div>
      <div class="kpi-value" id="kpiInfraUptime"><?= ($inf['uptime_pct'] ?? 99.98) ?>%</div>
      <div class="kpi-subtext" id="kpiInfraUptimeSub">48 ngày liên tục không downtime</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Đường truyền Internet Cáp quang</span>
        <div class="kpi-icon-wrap" style="color:var(--blue); background:rgba(57,160,255,0.1);"><i class="fa-solid fa-network-wired"></i></div>
      </div>
      <div class="kpi-value" id="kpiInfraNetwork"><?= $inf['network_speed'] ?? '300 Mbps' ?></div>
      <div class="kpi-subtext" id="kpiInfraNetworkSub">Độ trễ Ping: <?= $inf['network']['latency_ms'] ?? 12 ?>ms &bull; Mất gói: <?= $inf['network']['packet_loss_pct'] ?? 0 ?>%</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Cụm Lưu trữ Sao lưu NAS</span>
        <div class="kpi-icon-wrap" style="color:var(--purple); background:rgba(155,124,255,0.1);"><i class="fa-solid fa-hard-drive"></i></div>
      </div>
      <div class="kpi-value" id="kpiInfraBackup"><?= $inf['backup_status'] ?? '16 TB' ?></div>
      <div class="kpi-subtext" id="kpiInfraBackupSub">Đã sao lưu CSDL lúc 02:00:15 sáng</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Nhiệt độ Phòng máy chủ</span>
        <div class="kpi-icon-wrap" style="color:var(--accent); background:rgba(0,242,254,0.1);"><i class="fa-solid fa-temperature-half"></i></div>
      </div>
      <div class="kpi-value" id="kpiInfraTemp"><?= $inf['room_temp'] ?? '24.5 °C' ?></div>
      <div class="kpi-subtext" id="kpiInfraTempSub"><span style="color:var(--ok);"><i class="fa-solid fa-check"></i> Tiêu chuẩn</span> (Cho phép 20-26°C)</div>
    </div>

    <div class="kpi-card">
      <div class="kpi-top">
        <span class="kpi-label">Cảnh báo An toàn thông tin</span>
        <div class="kpi-icon-wrap" style="color:var(--ok); background:rgba(46,204,113,0.1);"><i class="fa-solid fa-shield-halved"></i></div>
      </div>
      <div class="kpi-value" id="kpiInfraSecurity" style="color:var(--ok);"><?= $inf['security_alerts'] ?? 0 ?> <span style="font-size:14px; font-weight:600; color:var(--muted);">sự cố</span></div>
      <div class="kpi-subtext" id="kpiInfraSecuritySub">Tường lửa Firewall Fortinet bảo vệ</div>
    </div>
  </section>

  <!-- 2. HỆ THỐNG BIỂU ĐỒ HẠ TẦNG CHO MÀN HÌNH LỚN -->
  <section class="charts-grid">
    <!-- Biểu đồ 1: Realtime Line (Tải CPU / RAM) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-microchip"></i> Đường Thời Gian Thực (Real-Time): Tải CPU & RAM Cụm Máy Chủ Core (%)</div>
          <div class="chart-subtitle">Giám sát tài nguyên máy chủ HIS Core và Database Master theo từng giây</div>
        </div>
      </div>
      <div class="chart-body" id="chartServerRealtime"></div>
    </div>

    <!-- Biểu đồ 2: Stacked Area (Băng thông Internet WAN & LAN) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-wifi"></i> Miền Chồng: Lưu Lượng Băng Thông Mạng Bệnh Viện (Mbps)</div>
          <div class="chart-subtitle">Tốc độ truyền tải Download, Upload qua đường truyền VNPT cáp quang</div>
        </div>
      </div>
      <div class="chart-body" id="chartNetworkStackedArea"></div>
    </div>

    <!-- Biểu đồ 3: Donut (Dung lượng Storage ổ cứng) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-hard-drive"></i> Dung Lượng Lưu Trữ</div>
          <div class="chart-subtitle">16 TB Cụm SAN/NAS</div>
        </div>
      </div>
      <div class="chart-body" id="chartStorageDonutNew"></div>
    </div>

    <!-- Biểu đồ 4: Clustered Bar (Nhiệt độ & Tải điện tủ Rack) -->
    <div class="chart-card col-6">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-temperature-arrow-up"></i> Cột Nhóm: Môi Trường Phòng Server Room (Nhiệt Độ & Công Suất Tải)</div>
          <div class="chart-subtitle">Giám sát thông số môi trường tủ Rack 1 (Core HIS) và Rack 2 (Storage PACS/EMR)</div>
        </div>
      </div>
      <div class="chart-body" id="chartRackEnvironment"></div>
    </div>

    <!-- Biểu đồ 5: Gauge (Ắc quy nguồn UPS) -->
    <div class="chart-card col-3">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-battery-full"></i> Nguồn Điện UPS</div>
          <div class="chart-subtitle">Ắc quy cấp điện dự phòng</div>
        </div>
      </div>
      <div class="chart-body" id="chartUpsRadial"></div>
    </div>

    <!-- Biểu đồ 6: Lưới Trạng thái Topology Mạng & ATTT -->
    <div class="chart-card col-12">
      <div class="chart-header">
        <div>
          <div class="chart-title"><i class="fa-solid fa-network-wired"></i> Sơ Đồ Ma Trận Trạng Thái (Status Matrix): Hạ Tầng CNTT & An Toàn An Ninh Mạng</div>
          <div class="chart-subtitle">Tình trạng sẵn sàng 24/7 của toàn bộ mắt xích trung tâm dữ liệu Data Center BVĐK KV Đăk Tô</div>
        </div>
      </div>
      <div class="chart-body" style="min-height:100px;">
        <div class="status-matrix-grid" style="grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));">
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>FIREWALL FORTINET</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Tường lửa thế hệ mới</div>
            <div class="status-matrix-val">IP: 192.168.1.1 &bull; 0 Sự cố</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>CISCO SWITCH CORE</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Đường trục 10Gbps</div>
            <div class="status-matrix-val">Uptime: 100% &bull; Loss: 0%</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>HIS CORE SERVER</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Xeon 32 Cores / 64GB</div>
            <div class="status-matrix-val">CPU: 42% &bull; RAM: 68%</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>DATABASE MASTER</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Xeon 64 Cores / 128GB</div>
            <div class="status-matrix-val">CPU: 58% &bull; RAM: 74%</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>PACS STORAGE</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Lưu trữ ảnh DICOM</div>
            <div class="status-matrix-val">Dung lượng: 5.2/16 TB</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>EMR SERVER</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Ký số Bệnh án ĐT</div>
            <div class="status-matrix-val">CPU: 36% &bull; RAM: 61%</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>NAS BACKUP AUTO</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Sao lưu định kỳ 02h00</div>
            <div class="status-matrix-val">Bản snapshot: Hoàn tất</div>
          </div>
          <div class="status-matrix-item">
            <div class="status-matrix-header"><span>UPS APC 10KVA</span> <span class="status-pill ok">ONLINE</span></div>
            <div class="status-matrix-name">Nguồn điện dự phòng</div>
            <div class="status-matrix-val">100% Pin &bull; Duy trì 4h</div>
          </div>
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
        <span>Bảng Giám sát Chi tiết Hạ tầng CNTT, Máy chủ & Thiết bị Mạng (Cập nhật theo bộ lọc)</span>
      </div>
      <div class="table-actions">
        <input type="text" class="table-search-input" id="tableSearchInput" placeholder="Tìm tên máy chủ, IP..."/>
      </div>
    </div>

    <div class="table-responsive">
      <table class="ioc-table">
        <thead>
          <tr>
            <th style="width:45px; text-align:center;">STT</th>
            <th>Mã Thiết Bị</th>
            <th>Tên Máy Chủ / Thiết Bị Trung Tâm</th>
            <th>Địa chỉ IP / Cổng</th>
            <th>Cấu hình phần cứng</th>
            <th style="text-align:right;">CPU %</th>
            <th style="text-align:right;">RAM %</th>
            <th style="text-align:right;">Disk %</th>
            <th style="text-align:right;">Nhiệt độ</th>
            <th style="text-align:right;">Thời gian Uptime</th>
            <th style="text-align:center;">Mạng kết nối</th>
            <th>Quản trị viên</th>
            <th style="text-align:center;">Tình trạng</th>
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
let chartServerRealtime, chartNetworkStackedArea, chartStorageDonutNew, chartRackEnvironment, chartUpsRadial;

function initCharts() {
  const chartTheme = {
    theme: { mode: 'dark' },
    chart: { background: 'transparent', toolbar: { show: false }, fontFamily: 'inherit' },
    grid: { borderColor: 'rgba(38, 76, 115, 0.35)', strokeDashArray: 3 }
  };

  // 1. Realtime Server Load (Cập nhật liên tục)
  <?php
  $serversKpi = $infrastructure['servers'] ?? $infra_alerts['servers'] ?? [];
  $s1 = $serversKpi[0] ?? ['cpu' => 42, 'ram' => 68];
  $s2 = $serversKpi[1] ?? ['cpu' => 58, 'ram' => 74];
  $cpu1 = (int)($s1['cpu'] ?? 42);
  $cpu2 = (int)($s2['cpu'] ?? 58);
  $ramAvg = (int)round((($s1['ram'] ?? 68) + ($s2['ram'] ?? 74)) / 2);
  ?>
  chartServerRealtime = new ApexCharts(document.getElementById('chartServerRealtime'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'line', height: 260 },
    colors: ['#00f2fe', '#ff9100', '#20c6b7'],
    stroke: { curve: 'smooth', width: 2.5 },
    series: [
      { name: 'Tải CPU HIS Core (%)', data: [<?= max(10, $cpu1 - 4) ?>, <?= max(10, $cpu1 - 2) ?>, <?= $cpu1 + 3 ?>, <?= $cpu1 - 1 ?>, <?= $cpu1 + 2 ?>, <?= $cpu1 + 6 ?>, <?= $cpu1 ?>] },
      { name: 'Tải CPU Database Master (%)', data: [<?= max(10, $cpu2 - 6) ?>, <?= max(10, $cpu2 - 2) ?>, <?= $cpu2 + 3 ?>, <?= $cpu2 ?>, <?= $cpu2 + 1 ?>, <?= $cpu2 + 6 ?>, <?= $cpu2 ?>] },
      { name: 'Dung lượng RAM dùng chung (%)', data: [<?= max(10, $ramAvg - 3) ?>, <?= $ramAvg ?>, <?= $ramAvg - 1 ?>, <?= $ramAvg + 3 ?>, <?= $ramAvg + 1 ?>, <?= $ramAvg + 4 ?>, <?= $ramAvg ?>] }
    ],
    xaxis: {
      categories: ['11:25', '11:26', '11:27', '11:28', '11:29', '11:30', '11:31'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: { max: 100, labels: { style: { colors: '#85a4c4' } } },
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartServerRealtime.render();

  // 2. Stacked Area Network Traffic
  chartNetworkStackedArea = new ApexCharts(document.getElementById('chartNetworkStackedArea'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'area', height: 260, stacked: true },
    colors: ['#00f2fe', '#39a0ff'],
    series: [
      { name: 'Download Internet (Mbps)', data: [110, 140, 180, 240, 210, 160, 130] },
      { name: 'Upload / Giám định (Mbps)', data: [45, 65, 85, 110, 95, 70, 55] }
    ],
    xaxis: {
      categories: ['08:00', '09:00', '10:00', '11:00', '13:30', '14:30', '15:30'],
      labels: { style: { colors: '#85a4c4', fontSize: '11px' } }
    },
    yaxis: { labels: { style: { colors: '#85a4c4' } } },
    stroke: { curve: 'smooth', width: 2 },
    fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.05 } },
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartNetworkStackedArea.render();

  // 3. Storage Donut
  chartStorageDonutNew = new ApexCharts(document.getElementById('chartStorageDonutNew'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'donut', height: 250 },
    colors: ['#00f2fe', '#39a0ff', '#20c6b7', 'rgba(38, 76, 115, 0.45)'],
    labels: ['PACS Ảnh DICOM', 'HIS Database', 'EMR Ký số', 'Dung lượng Trống'],
    series: [5.2, 2.1, 1.8, 6.9],
    legend: { position: 'bottom', labels: { colors: '#b2cbe4' } }
  });
  chartStorageDonutNew.render();

  // 4. Clustered Bar: Nhiệt độ vs Tải điện Rack
  chartRackEnvironment = new ApexCharts(document.getElementById('chartRackEnvironment'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'bar', height: 250 },
    colors: ['#00f2fe', '#ff9100'],
    plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 4 } },
    series: [
      { name: 'Nhiệt độ (°C)', data: [24.2, 25.1, 23.8, 24.5] },
      { name: 'Công suất tiêu thụ (kW)', data: [3.4, 4.2, 2.8, 3.1] }
    ],
    xaxis: {
      categories: ['Tủ Rack 1 (HIS Core)', 'Tủ Rack 2 (Database)', 'Tủ Rack 3 (PACS/EMR)', 'Tủ Rack 4 (Network WAN)'],
      labels: { style: { colors: '#85a4c4', fontSize: '10px' } }
    },
    yaxis: { labels: { style: { colors: '#85a4c4' } } },
    legend: { position: 'top', labels: { colors: '#b2cbe4' } }
  });
  chartRackEnvironment.render();

  // 5. Dual Radial Gauge UPS
  chartUpsRadial = new ApexCharts(document.getElementById('chartUpsRadial'), {
    ...chartTheme,
    chart: { ...chartTheme.chart, type: 'radialBar', height: 250 },
    plotOptions: {
      radialBar: {
        hollow: { size: '60%' },
        dataLabels: {
          name: { fontSize: '11px', color: '#85a4c4', offsetY: -6 },
          value: { fontSize: '22px', fontWeight: 800, color: '#2ecc71', offsetY: 6, formatter: v => v + '%' }
        }
      }
    },
    colors: ['#2ecc71'],
    series: [100],
    labels: ['Ắc quy sẵn sàng']
  });
  chartUpsRadial.render();
}

// ================= CẬP NHẬT BẢNG & BIỂU ĐỒ KHI LỌC DỮ LIỆU =================
window.fetchDashboardData = async function() {
  const queryStr = typeof window.getFilterParams === 'function' ? window.getFilterParams() : 'year=2026&quarter=3&month=8';

  try {
    const res = await fetch(`<?= APP_URL ?>/dashboard/api_data?${queryStr}`);
    const data = await res.json();
    if (!data.success) return;

    const infra = data.infrastructure || data.infra_alerts || {};
    const rows = data.tables?.infra || [];

    // Cập nhật bảng dữ liệu chi tiết (Bộ lọc chỉ thay đổi bảng, không thay đổi biểu đồ)
    const tbody = document.getElementById('iocTableBody');
    const tfoot = document.getElementById('iocTableFoot');

    if (tbody && rows.length > 0) {
      tbody.innerHTML = '';
      let sumCpu = 0, sumRam = 0, sumDisk = 0, sumTemp = 0;
      let onlineServers = 0, totalServers = rows.length;

      rows.forEach(row => {
        const cpu = parseInt(String(row.col3).replace(/\D/g, '')) || 0;
        const ram = parseInt(String(row.col4).replace(/\D/g, '')) || 0;
        const disk = parseInt(String(row.col5).replace(/\D/g, '')) || 0;
        const temp = parseFloat(String(row.col6).replace(/[^\d.]/g, '')) || 0;
        sumCpu += cpu;
        sumRam += ram;
        sumDisk += disk;
        sumTemp += temp;
        if ((row.col8 && row.col8.toString().toLowerCase().includes('online')) || (row.status && row.status.toString().toLowerCase().includes('tốt'))) {
          onlineServers++;
        }

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td style="text-align:center;">${row.stt}</td>
          <td style="font-weight:700; color:var(--accent);">${row.code}</td>
          <td style="font-weight:600;">${row.name}</td>
          <td><span style="color:#39a0ff;">${row.col1}</span></td>
          <td>${row.col2}</td>
          <td style="text-align:right; font-weight:700; color:var(--teal);">${row.col3}</td>
          <td style="text-align:right; font-weight:700; color:var(--accent);">${row.col4}</td>
          <td style="text-align:right;">${row.col5}</td>
          <td style="text-align:right; color:var(--gold);">${row.col6}</td>
          <td style="text-align:right;">${row.col7}</td>
          <td style="text-align:center;"><span class="status-pill ok"><i class="fa-solid fa-link"></i> ${row.col8}</span></td>
          <td>${row.col11}</td>
          <td style="text-align:center;"><span class="status-pill ${row.status_type}">${row.status}</span></td>
        `;
        tbody.appendChild(tr);
      });

      if (onlineServers === 0) onlineServers = totalServers;
      const count = rows.length;
      const avgCpu = count > 0 ? Math.round(sumCpu / count) : 46;
      const avgRam = count > 0 ? Math.round(sumRam / count) : 67;
      const avgDisk = count > 0 ? Math.round(sumDisk / count) : 49;
      const avgTemp = count > 0 ? (sumTemp / count).toFixed(1) : '24.5';

      if (tfoot) {
        tfoot.innerHTML = `
          <tr>
            <td colspan="5" style="text-align:center; font-weight:800; text-transform:uppercase;">Tổng Thể Cụm Hạ Tầng Trung Tâm Dữ Liệu:</td>
            <td style="text-align:right; color:var(--teal); font-weight:700;">${avgCpu}%</td>
            <td style="text-align:right; color:var(--accent); font-weight:700;">${avgRam}%</td>
            <td style="text-align:right; font-weight:700;">${avgDisk}%</td>
            <td style="text-align:right; color:var(--gold); font-weight:700;">${avgTemp}°C</td>
            <td style="text-align:right; font-weight:700;">99.98%</td>
            <td style="text-align:center;"><span class="status-pill ok">${Math.round((onlineServers / totalServers) * 100)}% Online</span></td>
            <td colspan="2" style="text-align:center;"><span class="status-pill ok">An toàn tuyệt đối</span></td>
          </tr>
        `;
      }
    }
  } catch(e) {
    console.warn("Lỗi load Hạ tầng:", e);
  }
};

document.addEventListener('DOMContentLoaded', function() {
  initCharts();
  window.fetchDashboardData();
});
</script>
