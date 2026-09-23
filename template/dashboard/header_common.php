<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= htmlspecialchars($page_title ?? 'Hệ thống Điều hành Thông minh IOC - BVĐK KV Đăk Tô') ?></title>
<base href="/careioc/"/>
<!-- Google Fonts & FontAwesome -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  (function() {
    try {
      if (localStorage.getItem('ioc_theme') === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    } catch(e) {}
  })();
</script>

<style>
:root {
  --bg: #071322;
  --bg-gradient: radial-gradient(circle at 50% 0%, #0d2744 0%, #071322 85%);
  --sidebar-bg: #091a30;
  --sidebar-border: rgba(38, 76, 115, 0.45);
  --panel: rgba(13, 29, 50, 0.94);
  --panel-card: rgba(16, 36, 62, 0.96);
  --panel-border: rgba(38, 76, 115, 0.45);
  --panel-hover: rgba(48, 96, 145, 0.65);
  --accent: #00f2fe;
  --teal: #20c6b7;
  --blue: #39a0ff;
  --purple: #9b7cff;
  --ok: #2ecc71;
  --warn: #f1c40f;
  --bad: #ff5c5c;
  --text: #eaf3ff;
  --muted: #85a4c4;
  --sub: #b2cbe4;
  --gold: #ffc107;
  --sidebar-w: 260px;
  --sidebar-w-collapsed: 72px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
  width: 100%; min-height: 100vh;
  background: var(--bg); background-image: var(--bg-gradient); background-attachment: fixed;
  color: var(--text); font-family: 'Plus Jakarta Sans', Segoe UI, system-ui, sans-serif;
  overflow-x: hidden; -webkit-font-smoothing: antialiased;
}

/* Custom Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #071322; }
::-webkit-scrollbar-thumb { background: #1a3c60; border-radius: 999px; }
::-webkit-scrollbar-thumb:hover { background: var(--teal); }

/* MAIN LAYOUT WRAPPER */
.ioc-container {
  display: flex;
  width: 100%;
  min-height: 100vh;
  position: relative;
}

/* LEFT SIDEBAR */
.ioc-sidebar {
  width: var(--sidebar-w);
  min-height: 100vh;
  background: var(--sidebar-bg);
  border-right: 1px solid var(--sidebar-border);
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1);
  position: fixed;
  left: 0; top: 0; bottom: 0;
  z-index: 100;
  overflow-y: auto;
  overflow-x: hidden;
}

.ioc-sidebar.collapsed {
  width: var(--sidebar-w-collapsed);
}

.sidebar-header {
  padding: 16px 14px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid var(--sidebar-border);
  position: relative;
}

.sidebar-logo {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: linear-gradient(135deg, #00f2fe, #20c6b7 50%, #39a0ff);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #071322;
  box-shadow: 0 0 16px rgba(0, 242, 254, 0.45);
  flex-shrink: 0;
}

.sidebar-title {
  display: flex;
  flex-direction: column;
  overflow: hidden;
  white-space: nowrap;
  transition: opacity 0.2s;
}
.sidebar-title .app-name {
  font-size: 15px;
  font-weight: 800;
  letter-spacing: 0.5px;
  background: linear-gradient(90deg, #fff, var(--accent));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.sidebar-title .app-sub {
  font-size: 10px;
  font-weight: 600;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.6px;
}

.sidebar-toggle-btn {
  position: absolute;
  right: 10px;
  top: 20px;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  background: rgba(38, 76, 115, 0.4);
  border: 1px solid rgba(0, 242, 254, 0.3);
  color: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}
.sidebar-toggle-btn:hover {
  background: var(--accent);
  color: #071322;
  box-shadow: 0 0 10px rgba(0, 242, 254, 0.5);
}

.ioc-sidebar.collapsed .sidebar-title {
  display: none;
}
.ioc-sidebar.collapsed .sidebar-toggle-btn {
  right: 22px;
}

/* SIDEBAR MENU */
.sidebar-nav {
  padding: 14px 8px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  flex: 1;
}

.nav-section-label {
  font-size: 9.5px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #4f7096;
  font-weight: 700;
  padding: 10px 10px 4px;
  white-space: nowrap;
}
.ioc-sidebar.collapsed .nav-section-label {
  display: none;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 10px;
  color: var(--sub);
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  transition: all 0.2s ease;
  position: relative;
  white-space: nowrap;
}

.nav-link i {
  font-size: 16px;
  width: 24px;
  text-align: center;
  flex-shrink: 0;
  color: var(--muted);
  transition: color 0.2s;
}

.nav-link:hover {
  background: rgba(32, 198, 183, 0.12);
  color: #fff;
}
.nav-link:hover i {
  color: var(--accent);
}

.nav-link.active {
  background: linear-gradient(90deg, rgba(0, 242, 254, 0.22), rgba(32, 198, 183, 0.08));
  border-left: 3px solid var(--accent);
  color: #fff;
  font-weight: 700;
  box-shadow: inset 0 0 14px rgba(0, 242, 254, 0.1);
}
.nav-link.active i {
  color: var(--accent);
  text-shadow: 0 0 8px rgba(0, 242, 254, 0.6);
}

.ioc-sidebar.collapsed .nav-link span {
  display: none;
}
.ioc-sidebar.collapsed .nav-link {
  justify-content: center;
  padding: 12px;
}
.ioc-sidebar.collapsed .nav-link i {
  width: auto;
  font-size: 18px;
}

/* Tooltip on collapsed hover */
.ioc-sidebar.collapsed .nav-link:hover::after {
  content: attr(data-tooltip);
  position: absolute;
  left: 100%;
  margin-left: 12px;
  padding: 6px 12px;
  background: #0d2744;
  color: #fff;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
  border-radius: 6px;
  border: 1px solid var(--accent);
  box-shadow: 0 4px 16px rgba(0,0,0,0.5);
  pointer-events: none;
  z-index: 1000;
}

/* SIDEBAR FOOTER */
.sidebar-footer {
  padding: 12px 14px;
  border-top: 1px solid var(--sidebar-border);
  font-size: 11px;
  color: var(--muted);
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.ioc-sidebar.collapsed .sidebar-footer {
  display: none;
}

/* MAIN CONTENT CONTAINER */
.ioc-main {
  flex: 1;
  margin-left: var(--sidebar-w);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
  padding: 16px 24px 30px;
  max-width: calc(100vw - var(--sidebar-w));
}

.ioc-sidebar.collapsed ~ .ioc-main {
  margin-left: var(--sidebar-w-collapsed);
  max-width: calc(100vw - var(--sidebar-w-collapsed));
}

/* TOPBAR */
.ioc-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding-bottom: 14px;
  border-bottom: 1px solid var(--sidebar-border);
  margin-bottom: 14px;
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 14px;
}

.topbar-page-info h1 {
  font-size: 19px;
  font-weight: 800;
  color: #fff;
  letter-spacing: 0.3px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.topbar-page-info .hospital-badge {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--teal);
  display: flex;
  align-items: center;
  gap: 6px;
  margin-top: 2px;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.live-clock-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 14px;
  background: rgba(16, 36, 62, 0.9);
  border: 1px solid rgba(0, 242, 254, 0.35);
  border-radius: 10px;
  font-size: 12.5px;
  font-weight: 700;
  color: var(--accent);
  box-shadow: inset 0 0 10px rgba(0, 242, 254, 0.1);
}
.live-clock-badge i {
  animation: pulseDot 2s infinite ease-in-out;
}
@keyframes pulseDot {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.4; transform: scale(0.85); }
}

.topbar-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 13px;
  background: rgba(20, 48, 80, 0.7);
  border: 1px solid rgba(38, 76, 115, 0.6);
  border-radius: 9px;
  color: var(--text);
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
}
.topbar-btn:hover {
  background: rgba(0, 242, 254, 0.2);
  border-color: var(--accent);
  color: #fff;
}
.topbar-btn.active {
  background: linear-gradient(90deg, #00f2fe, #20c6b7);
  color: #071322;
  font-weight: 700;
  border-color: transparent;
  box-shadow: 0 0 12px rgba(0, 242, 254, 0.5);
}

/* FILTER BAR */
.ioc-filter-bar {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
  padding: 10px 14px;
  background: var(--panel);
  border: 1px solid var(--panel-border);
  border-radius: 12px;
  margin-bottom: 16px;
  box-shadow: 0 4px 18px rgba(0,0,0,0.3);
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 6px;
}
.filter-group label {
  font-size: 11px;
  font-weight: 700;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.4px;
  white-space: nowrap;
}

.filter-select, .filter-input {
  background: #091a30;
  border: 1px solid rgba(38, 76, 115, 0.8);
  border-radius: 8px;
  padding: 6px 10px;
  color: #fff;
  font-size: 12px;
  font-weight: 500;
  font-family: inherit;
  outline: none;
  transition: border-color 0.2s;
}
.filter-select:focus, .filter-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 8px rgba(0, 242, 254, 0.3);
}

.btn-filter-apply {
  background: linear-gradient(135deg, #00f2fe, #20c6b7);
  color: #071322;
  border: none;
  border-radius: 8px;
  padding: 7px 16px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.btn-filter-apply:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(0, 242, 254, 0.4);
}

.btn-filter-reset {
  background: transparent;
  color: var(--muted);
  border: 1px solid rgba(38, 76, 115, 0.8);
  border-radius: 8px;
  padding: 7px 12px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
}
.btn-filter-reset:hover {
  color: #fff;
  border-color: var(--muted);
}

/* KPI STATS ROW */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
  margin-bottom: 16px;
}

.kpi-card {
  background: var(--panel-card);
  border: 1px solid var(--panel-border);
  border-radius: 12px;
  padding: 14px 16px;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 6px;
  transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
}
.kpi-card:hover {
  transform: translateY(-2px);
  border-color: var(--panel-hover);
  box-shadow: 0 6px 20px rgba(0, 242, 254, 0.12);
}
.kpi-card::before {
  content: "";
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--accent), var(--teal));
  opacity: 0.85;
}

.kpi-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.kpi-label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.kpi-icon-wrap {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: rgba(0, 242, 254, 0.1);
  color: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
}
.kpi-value {
  font-size: 24px;
  font-weight: 800;
  color: #fff;
  letter-spacing: -0.5px;
}
.kpi-subtext {
  font-size: 11px;
  color: var(--sub);
  display: flex;
  align-items: center;
  gap: 4px;
}
.kpi-subtext .trend-up { color: var(--ok); font-weight: 700; }
.kpi-subtext .trend-down { color: var(--bad); font-weight: 700; }

/* CHARTS GRID (TOP SECTION) */
.charts-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 14px;
  margin-bottom: 20px;
}
.col-12 { grid-column: span 12; }
.col-9 { grid-column: span 9; }
.col-8 { grid-column: span 8; }
.col-7 { grid-column: span 7; }
.col-6 { grid-column: span 6; }
.col-5 { grid-column: span 5; }
.col-4 { grid-column: span 4; }
.col-3 { grid-column: span 3; }

.chart-card {
  background: var(--panel-card);
  border: 1px solid var(--panel-border);
  border-radius: 12px;
  padding: 14px 16px;
  display: flex;
  flex-direction: column;
  position: relative;
  box-shadow: 0 4px 18px rgba(0,0,0,0.25);
  overflow: hidden;
}
.chart-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
  padding-bottom: 8px;
  border-bottom: 1px solid rgba(38, 76, 115, 0.3);
}
.chart-title {
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 8px;
}
.chart-title i {
  color: var(--accent);
}
.chart-subtitle {
  font-size: 10.5px;
  color: var(--muted);
}
.chart-body {
  flex: 1;
  min-height: 250px;
  position: relative;
}

/* SỐ LƯỢNG BỆNH NHÂN KHÁM THEO TỪNG KHOA (TREEMAP BENTO CARDS: SỐ HIỂN THỊ Ở GÓC, RÕ RÀNG, SANG TRỌNG) */
.ioc-treemap-board {
  position: relative;
  width: 100%;
  height: 275px;
  overflow: hidden;
  border-radius: 8px;
}
.ioc-treemap-tile {
  position: absolute;
  border-radius: 6px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  cursor: pointer;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 2px 6px rgba(0, 0, 0, 0.28);
  overflow: hidden;
  transition: transform 0.16s ease, filter 0.16s ease, box-shadow 0.16s ease;
}
.ioc-treemap-tile:hover {
  transform: translateY(-2px) scale(1.015);
  z-index: 10;
  filter: brightness(1.15);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35), 0 6px 16px rgba(0, 0, 0, 0.45);
}

/* STATUS GRID MATRIX */
.status-matrix-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 10px;
  padding: 6px 0;
}
.status-matrix-item {
  background: rgba(9, 26, 48, 0.75);
  border: 1px solid rgba(38, 76, 115, 0.5);
  border-radius: 10px;
  padding: 10px 12px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  transition: all 0.2s;
}
.status-matrix-item:hover {
  border-color: var(--accent);
  transform: translateY(-2px);
  box-shadow: 0 4px 14px rgba(0,242,254,0.15);
}
.status-matrix-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 10.5px;
  color: var(--muted);
  font-weight: 700;
  text-transform: uppercase;
}
.status-matrix-name {
  font-size: 12px;
  font-weight: 700;
  color: #fff;
}
.status-matrix-val {
  font-size: 11px;
  color: var(--teal);
  font-weight: 600;
}

/* WAFFLE CHART CONTAINER */
.waffle-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  padding: 10px;
}
.waffle-grid {
  display: grid;
  grid-template-columns: repeat(10, 1fr);
  gap: 5px;
  width: 100%;
  max-width: 220px;
  aspect-ratio: 1/1;
}
.waffle-cell {
  background: rgba(38, 76, 115, 0.35);
  border-radius: 3px;
  transition: all 0.2s;
}
.waffle-cell.filled-ok {
  background: #2ecc71;
  box-shadow: 0 0 6px rgba(46,204,113,0.5);
}
.waffle-cell.filled-accent {
  background: #00f2fe;
  box-shadow: 0 0 6px rgba(0,242,254,0.5);
}
.waffle-cell.filled-warn {
  background: #f1c40f;
  box-shadow: 0 0 6px rgba(241,196,15,0.5);
}

/* PROCESS FLOW / FUNNEL STEPS */
.funnel-steps-container {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 8px 0;
  height: 100%;
  justify-content: center;
}
.funnel-step {
  display: flex;
  align-items: center;
  gap: 12px;
}
.funnel-step-bar-wrap {
  flex: 1;
  height: 22px;
  background: rgba(9, 26, 48, 0.8);
  border-radius: 6px;
  overflow: hidden;
  position: relative;
  border: 1px solid rgba(38, 76, 115, 0.4);
}
.funnel-step-bar {
  height: 100%;
  border-radius: 5px;
  transition: width 0.6s ease;
  display: flex;
  align-items: center;
  padding-left: 8px;
  font-size: 10.5px;
  font-weight: 700;
  color: #071322;
}
.funnel-step-label {
  width: 130px;
  font-size: 11px;
  font-weight: 600;
  color: var(--sub);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.funnel-step-pct {
  width: 45px;
  font-size: 11.5px;
  font-weight: 800;
  color: var(--accent);
  text-align: right;
}

/* DATA TABLE CONTAINER (BOTTOM SECTION) */
.table-section {
  background: var(--panel-card);
  border: 1px solid var(--panel-border);
  border-radius: 12px;
  padding: 16px;
  margin-top: 4px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 12px;
  padding-bottom: 10px;
  border-bottom: 1px solid rgba(38, 76, 115, 0.3);
}
.table-title {
  font-size: 14.5px;
  font-weight: 800;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 8px;
}
.table-title i {
  color: var(--teal);
}

.table-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}
.table-search-input {
  background: #091a30;
  border: 1px solid rgba(38, 76, 115, 0.8);
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 12px;
  color: #fff;
  outline: none;
  width: 220px;
}
.table-search-input:focus {
  border-color: var(--teal);
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
  max-height: 480px;
  position: relative;
}

.ioc-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: 12px;
  color: var(--text);
  white-space: nowrap;
}

.ioc-table thead th {
  background: #0c233f;
  color: var(--accent);
  font-weight: 700;
  text-transform: uppercase;
  font-size: 11px;
  letter-spacing: 0.5px;
  padding: 10px 12px;
  border-bottom: 2px solid rgba(0, 242, 254, 0.35);
  border-top: 1px solid rgba(38, 76, 115, 0.4);
  position: sticky;
  top: 0;
  z-index: 10;
}

.ioc-table tbody tr {
  transition: background 0.15s;
}
.ioc-table tbody tr:nth-child(even) {
  background: rgba(9, 26, 48, 0.4);
}
.ioc-table tbody tr:hover {
  background: rgba(0, 242, 254, 0.08);
}

.ioc-table tbody td {
  padding: 9px 12px;
  border-bottom: 1px solid rgba(38, 76, 115, 0.25);
  font-size: 12px;
}

.ioc-table tfoot td {
  background: #0d2744;
  color: var(--gold);
  font-weight: 800;
  padding: 10px 12px;
  border-top: 2px solid rgba(255, 193, 7, 0.4);
  position: sticky;
  bottom: 0;
  z-index: 10;
}

/* BADGES & STATUS */
.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 9px;
  border-radius: 999px;
  font-size: 10.5px;
  font-weight: 700;
}
.status-pill.ok {
  background: rgba(46, 204, 113, 0.15);
  color: #2ecc71;
  border: 1px solid rgba(46, 204, 113, 0.4);
}
.status-pill.warn {
  background: rgba(241, 196, 15, 0.15);
  color: #f1c40f;
  border: 1px solid rgba(241, 196, 15, 0.4);
}
.status-pill.bad {
  background: rgba(255, 92, 92, 0.15);
  color: #ff5c5c;
  border: 1px solid rgba(255, 92, 92, 0.4);
}

/* RESPONSIVE */
@media (max-width: 1200px) {
  .col-8, .col-7, .col-6, .col-5, .col-4 { grid-column: span 12; }
}
@media (max-width: 768px) {
  .ioc-sidebar {
    position: fixed;
    transform: translateX(-100%);
    width: var(--sidebar-w);
  }
  .ioc-sidebar.mobile-open {
    transform: translateX(0);
  }
  .ioc-main {
    margin-left: 0 !important;
    max-width: 100vw !important;
    padding: 12px;
  }
}

/* ================= LIGHT MODE (CHẾ ĐỘ SÁNG) ================= */
[data-theme="light"] {
  --bg: #f1f5f9;
  --bg-gradient: radial-gradient(circle at 50% 0%, #e2e8f0 0%, #f1f5f9 85%);
  --sidebar-bg: #ffffff;
  --sidebar-border: #cbd5e1;
  --panel: rgba(255, 255, 255, 0.96);
  --panel-card: #ffffff;
  --panel-border: #cbd5e1;
  --panel-hover: rgba(2, 132, 199, 0.15);
  --accent: #0284c7;
  --teal: #0d9488;
  --blue: #2563eb;
  --purple: #7c3aed;
  --ok: #16a34a;
  --warn: #d97706;
  --bad: #dc2626;
  --text: #0f172a;
  --muted: #64748b;
  --sub: #475569;
  --gold: #b45309;
}

[data-theme="light"] html,
[data-theme="light"] body {
  background: var(--bg);
  background-image: var(--bg-gradient);
  color: var(--text);
}

[data-theme="light"] ::-webkit-scrollbar-track { background: #e2e8f0; }
[data-theme="light"] ::-webkit-scrollbar-thumb { background: #94a3b8; }

[data-theme="light"] .topbar-page-info h1 {
  color: #0f172a;
}
[data-theme="light"] .topbar-page-info .hospital-badge {
  color: #0369a1;
}

[data-theme="light"] .live-clock-badge {
  background: #ffffff;
  border-color: #cbd5e1;
  color: var(--accent);
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

[data-theme="light"] .topbar-btn {
  background: #ffffff;
  border-color: #cbd5e1;
  color: #334155;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
[data-theme="light"] .topbar-btn:hover {
  background: #f8fafc;
  border-color: var(--accent);
  color: var(--accent);
}
[data-theme="light"] .topbar-btn.active {
  background: linear-gradient(90deg, #0284c7, #0d9488);
  color: #ffffff;
  box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
}

[data-theme="light"] .ioc-filter-bar {
  background: #ffffff;
  border-color: #cbd5e1;
  box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}

[data-theme="light"] .filter-select,
[data-theme="light"] .filter-input {
  background: #f8fafc;
  border-color: #cbd5e1;
  color: #0f172a;
}
[data-theme="light"] .filter-select:focus,
[data-theme="light"] .filter-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 8px rgba(2, 132, 199, 0.2);
}

[data-theme="light"] .kpi-card {
  background: #ffffff;
  border-color: #cbd5e1;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
[data-theme="light"] .kpi-card:hover {
  border-color: var(--accent);
  box-shadow: 0 6px 18px rgba(2, 132, 199, 0.12);
}
[data-theme="light"] .kpi-value {
  color: #0f172a;
}

[data-theme="light"] .chart-card {
  background: #ffffff;
  border-color: #cbd5e1;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
[data-theme="light"] .chart-header {
  border-bottom-color: #e2e8f0;
}
[data-theme="light"] .chart-title {
  color: #0f172a;
}

[data-theme="light"] .table-section {
  background: #ffffff;
  border-color: #cbd5e1;
  box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}
[data-theme="light"] .table-title {
  color: #0f172a;
}
[data-theme="light"] .table-header {
  border-bottom-color: #e2e8f0;
}
[data-theme="light"] .table-search-input {
  background: #f8fafc;
  border-color: #cbd5e1;
  color: #0f172a;
}
[data-theme="light"] .table-search-input:focus {
  border-color: var(--accent);
}

[data-theme="light"] .ioc-table thead th {
  background: #f1f5f9;
  color: #0369a1;
  border-bottom: 2px solid #0284c7;
  border-top-color: #e2e8f0;
}
[data-theme="light"] .ioc-table tbody tr:nth-child(even) {
  background: #f8fafc;
}
[data-theme="light"] .ioc-table tbody tr:hover {
  background: rgba(2, 132, 199, 0.08);
}
[data-theme="light"] .ioc-table tbody td {
  border-bottom-color: #e2e8f0;
  color: #1e293b;
}
[data-theme="light"] .ioc-table tfoot td {
  background: #f1f5f9;
  color: #0f172a;
  border-top: 2px solid #0284c7;
}

/* Màu sắc các chữ số KPI ở chế độ Sáng theo từng chuyên mục y tế */
[data-theme="light"] #kpiLisTotal { color: #0284c7 !important; }
[data-theme="light"] #kpiLisBhyt { color: #0d9488 !important; }
[data-theme="light"] #kpiLisSelfPay { color: #c2410c !important; }
[data-theme="light"] #kpiLisOutpatient { color: #2563eb !important; }
[data-theme="light"] #kpiLisInpatient { color: #7c3aed !important; }
[data-theme="light"] #kpiLisBhytRate { color: #15803d !important; }

[data-theme="light"] .kpi-num-primary { color: #0284c7 !important; }
[data-theme="light"] .kpi-num-teal { color: #0d9488 !important; }
[data-theme="light"] .kpi-num-gold { color: #c2410c !important; }
[data-theme="light"] .kpi-num-blue { color: #2563eb !important; }
[data-theme="light"] .kpi-num-purple { color: #7c3aed !important; }
[data-theme="light"] .kpi-num-green { color: #15803d !important; }

/* Tối ưu màu sắc các chữ số trong bảng dữ liệu khi ở chế độ Sáng */
[data-theme="light"] .ioc-table td[style*="#20c6b7"],
[data-theme="light"] .ioc-table td[style*="20c6b7"],
[data-theme="light"] .ioc-table span[style*="#20c6b7"],
[data-theme="light"] .ioc-table span[style*="20c6b7"] {
  color: #0d9488 !important; /* Xanh mòng két đậm đà, tương phản cao trên nền trắng */
  font-weight: 700;
}
[data-theme="light"] .ioc-table td[style*="#ff9100"],
[data-theme="light"] .ioc-table td[style*="ff9100"],
[data-theme="light"] .ioc-table span[style*="#ff9100"],
[data-theme="light"] .ioc-table span[style*="ff9100"] {
  color: #c2410c !important; /* Cam hổ phách đậm đà, không bị chói nhạt */
  font-weight: 700;
}
[data-theme="light"] .ioc-table td[style*="var(--ok)"],
[data-theme="light"] .ioc-table td[style*="#2ecc71"],
[data-theme="light"] .ioc-table span[style*="var(--ok)"] {
  color: #15803d !important; /* Xanh lục đậm chuẩn y tế */
  font-weight: 700;
}
[data-theme="light"] .ioc-table td[style*="var(--accent)"],
[data-theme="light"] .ioc-table td[style*="#00f2fe"],
[data-theme="light"] .ioc-table span[style*="var(--accent)"] {
  color: #0284c7 !important; /* Xanh dương đậm nét */
}

/* Màu các chỉ số tăng/giảm */
[data-theme="light"] .trend-up,
[data-theme="light"] .kpi-subtext .trend-up {
  color: #15803d !important;
  font-weight: 700;
}
[data-theme="light"] .trend-down,
[data-theme="light"] .kpi-subtext .trend-down {
  color: #dc2626 !important;
  font-weight: 700;
}

[data-theme="light"] .nav-link {
  color: #475569;
}
[data-theme="light"] .nav-link:hover {
  background: rgba(2, 132, 199, 0.08);
  color: var(--accent);
}
[data-theme="light"] .nav-link.active {
  background: linear-gradient(90deg, rgba(2, 132, 199, 0.15), rgba(13, 148, 136, 0.06));
  color: #0284c7;
}
[data-theme="light"] .sidebar-header {
  border-bottom-color: #e2e8f0;
}
[data-theme="light"] .sidebar-title h2 {
  color: #0f172a;
}
[data-theme="light"] .sidebar-toggle-btn {
  background: #f1f5f9;
  border-color: #cbd5e1;
  color: #475569;
}
[data-theme="light"] .sidebar-footer {
  border-top-color: #e2e8f0;
}
[data-theme="light"] .apexcharts-text {
  fill: #475569 !important;
}
[data-theme="light"] .apexcharts-legend-text {
  color: #334155 !important;
}
[data-theme="light"] .apexcharts-gridline {
  stroke: rgba(203, 213, 225, 0.7) !important;
}
</style>
</head>
<body>
<div class="ioc-container">
