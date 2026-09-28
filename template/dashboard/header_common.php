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
      var bgOp = localStorage.getItem('ioc_bg_opacity');
      if (bgOp !== null) {
        var val = Math.max(0, Math.min(100, parseInt(bgOp, 10) || 85));
        var opVal = val / 100;
        var panelAlpha = (0.98 - (opVal * 0.20)).toFixed(2);
        var sidebarAlpha = (0.96 - (opVal * 0.26)).toFixed(2);
        var cardBlur = Math.round(6 + (opVal * 12)) + 'px';
        document.documentElement.style.setProperty('--bg-gradient-opacity', opVal);
        document.documentElement.style.setProperty('--bg-gradient-brightness', (0.5 + opVal * 0.7).toFixed(2));
        document.documentElement.style.setProperty('--panel-alpha', panelAlpha);
        document.documentElement.style.setProperty('--sidebar-alpha', sidebarAlpha);
        document.documentElement.style.setProperty('--card-blur', cardBlur);
      }
    } catch(e) {}
  })();
</script>

<style>
:root {
  --bg: #071322;
  --bg-gradient: radial-gradient(circle at 50% 0%, #153e6b 0%, #0d2744 40%, #071322 85%);
  --bg-gradient-opacity: 0.85;
  --bg-gradient-brightness: 1;
  --panel-alpha: 0.88;
  --sidebar-alpha: 0.76;
  --card-blur: 14px;
  --sidebar-bg: rgba(9, 26, 48, var(--sidebar-alpha, 0.76));
  --sidebar-border: rgba(38, 76, 115, 0.45);
  --panel: rgba(13, 29, 50, var(--panel-alpha, 0.88));
  --panel-card: rgba(16, 36, 62, var(--panel-alpha, 0.90));
  --panel-border: rgba(38, 76, 115, 0.45);
  --panel-hover: rgba(48, 96, 145, 0.65);
  --accent: #00f2fe;
  --teal: #20c6b7;
  --blue: #39a0ff;
  --purple: #9b7cff;
  --ok: #2ecc71;
  --warn: #f1c40f;
  --bad: #ff5c5c;
  --text: #ffffff;
  --muted: #ffffff;
  --sub: #ffffff;
  --gold: #ffc107;
  --sidebar-w: 260px;
  --sidebar-w-collapsed: 72px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
  width: 100%; min-height: 100vh;
  background-color: var(--bg);
  color: var(--text); font-family: 'Plus Jakarta Sans', Segoe UI, system-ui, sans-serif;
  overflow-x: hidden; -webkit-font-smoothing: antialiased;
  position: relative;
}

/* Lớp hình nền Gradient phủ tràn Full 100% toàn bộ màn hình (kể cả Sidebar & Content) */
body::before {
  content: "";
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  width: 100vw; height: 100vh;
  background-image: var(--bg-gradient);
  background-attachment: fixed;
  background-size: cover;
  background-position: center top;
  opacity: var(--bg-gradient-opacity, 0.85);
  filter: brightness(var(--bg-gradient-brightness, 1));
  pointer-events: none;
  z-index: 0;
  transition: opacity 0.25s ease, filter 0.25s ease;
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

/* LEFT SIDEBAR (KÍNH MỜ TRONG SUỐT LIỀN MẠCH VỚI HÌNH NỀN TOÀN BỘ) */
.ioc-sidebar {
  width: var(--sidebar-w);
  min-height: 100vh;
  background: var(--sidebar-bg);
  backdrop-filter: blur(var(--card-blur, 14px));
  -webkit-backdrop-filter: blur(var(--card-blur, 14px));
  border-right: 1px solid var(--sidebar-border);
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1), background 0.25s ease;
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
  padding: 16px 42px 16px 14px;
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
  min-width: 0;
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

.ioc-sidebar.collapsed .sidebar-header {
  flex-direction: column;
  padding: 12px 6px 10px;
  gap: 8px;
  align-items: center;
  justify-content: center;
}

.ioc-sidebar.collapsed .sidebar-logo {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  font-size: 18px;
  margin: 0 auto;
  cursor: pointer;
}

.ioc-sidebar.collapsed .sidebar-title {
  display: none;
}

.ioc-sidebar.collapsed .sidebar-toggle-btn {
  position: static;
  right: auto;
  top: auto;
  width: 36px;
  height: 26px;
  margin: 0 auto;
  border-radius: 6px;
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
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.9px;
  color: var(--muted);
  font-weight: 800;
  padding: 12px 10px 4px;
  white-space: nowrap;
  opacity: 0.9;
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

/* NÚT & MENU ĐIỀU CHỈNH ĐỘ HIỂN THỊ MÀU NỀN */
.bg-adjust-wrapper {
  position: relative;
  display: inline-block;
}

.bg-adjust-popover {
  position: absolute;
  top: calc(100% + 8px);
  right: 0;
  width: 290px;
  background: rgba(13, 29, 50, 0.98);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(0, 242, 254, 0.45);
  border-radius: 12px;
  box-shadow: 0 12px 36px rgba(0, 0, 0, 0.65), 0 0 18px rgba(0, 242, 254, 0.2);
  padding: 14px 16px;
  z-index: 1050;
  display: none;
  animation: bgFadeIn 0.18s ease-out;
}

.bg-adjust-popover.show {
  display: block;
}

@keyframes bgFadeIn {
  from { opacity: 0; transform: translateY(-6px); }
  to { opacity: 1; transform: translateY(0); }
}

.bg-adjust-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
  padding-bottom: 8px;
  border-bottom: 1px solid rgba(38, 76, 115, 0.5);
}

.bg-adjust-header h4 {
  font-size: 11.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--accent);
  display: flex;
  align-items: center;
  gap: 6px;
  margin: 0;
}

.bg-adjust-badge {
  font-size: 12px;
  font-weight: 800;
  color: #fff;
  background: rgba(0, 242, 254, 0.2);
  border: 1px solid rgba(0, 242, 254, 0.45);
  padding: 2px 8px;
  border-radius: 6px;
  min-width: 44px;
  text-align: center;
}

.bg-slider-row {
  margin-bottom: 12px;
}

.bg-slider {
  -webkit-appearance: none;
  appearance: none;
  width: 100%;
  height: 6px;
  border-radius: 6px;
  background: linear-gradient(90deg, #071322 0%, #153e6b 50%, #00f2fe 100%);
  outline: none;
  cursor: pointer;
  margin: 8px 0 6px;
}

.bg-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #ffffff;
  border: 2px solid var(--accent);
  box-shadow: 0 0 10px rgba(0, 242, 254, 0.85);
  cursor: pointer;
  transition: transform 0.12s ease;
}

.bg-slider::-webkit-slider-thumb:hover {
  transform: scale(1.22);
}

.bg-slider-ticks {
  display: flex;
  justify-content: space-between;
  font-size: 9.5px;
  color: var(--muted);
  font-weight: 600;
}

.bg-presets-label {
  font-size: 10.5px;
  color: var(--muted);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  margin-bottom: 7px;
}

.bg-presets-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 6px;
}

.bg-preset-btn {
  padding: 7px 8px;
  font-size: 11px;
  font-weight: 600;
  background: rgba(16, 36, 62, 0.85);
  border: 1px solid rgba(38, 76, 115, 0.6);
  border-radius: 8px;
  color: var(--sub);
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  justify-content: center;
  transition: all 0.18s ease;
  font-family: inherit;
}

.bg-preset-btn:hover {
  background: rgba(0, 242, 254, 0.15);
  border-color: var(--accent);
  color: #fff;
}

.bg-preset-btn.active {
  background: rgba(0, 242, 254, 0.22);
  border-color: var(--accent);
  color: var(--accent);
  font-weight: 700;
  box-shadow: inset 0 0 8px rgba(0, 242, 254, 0.2);
}

/* Light mode support cho popup điều chỉnh nền */
[data-theme="light"] .bg-adjust-popover {
  background: rgba(255, 255, 255, 0.98);
  border-color: #cbd5e1;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 0 12px rgba(2, 132, 199, 0.15);
}

[data-theme="light"] .bg-adjust-header {
  border-bottom-color: #e2e8f0;
}

[data-theme="light"] .bg-adjust-header h4 {
  color: #0284c7;
}

[data-theme="light"] .bg-adjust-badge {
  background: rgba(2, 132, 199, 0.12);
  border-color: rgba(2, 132, 199, 0.35);
  color: #0284c7;
}

[data-theme="light"] .bg-slider {
  background: linear-gradient(90deg, #f1f5f9 0%, #94a3b8 50%, #0284c7 100%);
}

[data-theme="light"] .bg-slider::-webkit-slider-thumb {
  background: #ffffff;
  border-color: #0284c7;
  box-shadow: 0 0 6px rgba(2, 132, 199, 0.5);
}

[data-theme="light"] .bg-preset-btn {
  background: #f8fafc;
  border-color: #cbd5e1;
  color: #334155;
}

[data-theme="light"] .bg-preset-btn:hover {
  background: rgba(2, 132, 199, 0.08);
  border-color: #0284c7;
  color: #0284c7;
}

[data-theme="light"] .bg-preset-btn.active {
  background: rgba(2, 132, 199, 0.16);
  border-color: #0284c7;
  color: #0284c7;
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
  backdrop-filter: blur(var(--card-blur, 14px));
  -webkit-backdrop-filter: blur(var(--card-blur, 14px));
  transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
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
  transition: all 0.2s ease;
}
.btn-filter-reset:hover {
  color: #fff;
  border-color: var(--accent);
  background: rgba(0, 242, 254, 0.1);
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
  backdrop-filter: blur(var(--card-blur, 14px));
  -webkit-backdrop-filter: blur(var(--card-blur, 14px));
  transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s, background 0.25s ease;
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
  font-weight: 700;
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
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.45);
}
.kpi-subtext {
  font-size: 11px;
  color: var(--sub);
  display: flex;
  align-items: center;
  gap: 4px;
  font-weight: 500;
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
  backdrop-filter: blur(var(--card-blur, 14px));
  -webkit-backdrop-filter: blur(var(--card-blur, 14px));
  transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
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
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
}
.chart-title i {
  color: var(--accent);
}
.chart-subtitle {
  font-size: 11px;
  color: var(--muted);
  font-weight: 500;
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
  backdrop-filter: blur(var(--card-blur, 14px));
  -webkit-backdrop-filter: blur(var(--card-blur, 14px));
  transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
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

/* ================= DARK MODE PURE CONTRAST (NỀN TỐI -> CHỮ TRẮNG TINH KHIẾT #FFFFFF) ================= */
.nav-section-label {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.nav-link {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.nav-link i {
  color: #ffffff !important;
}
.sidebar-title .app-name {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}
.sidebar-title .app-sub {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.sidebar-footer {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.sidebar-footer span {
  color: #ffffff !important;
}
.sidebar-footer span:last-child {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.topbar-page-info h1 {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.topbar-page-info .hospital-badge {
  color: #ffffff !important;
  font-weight: 700 !important;
}
.live-clock-badge span, #clockTime, #clockDate {
  color: #ffffff !important;
}
.topbar-btn {
  color: #ffffff !important;
}
.filter-group label {
  color: #ffffff !important;
  font-weight: 700 !important;
}
.filter-select, .filter-input {
  color: #ffffff !important;
  background: #071322 !important;
  border-color: rgba(255, 255, 255, 0.4) !important;
}
.filter-select option {
  color: #ffffff !important;
  background: #071322 !important;
}
.btn-filter-reset {
  color: #ffffff !important;
  border-color: rgba(255, 255, 255, 0.5) !important;
}
.btn-filter-reset:hover {
  color: #071322 !important;
  background: #ffffff !important;
  border-color: #ffffff !important;
}
.kpi-label {
  color: #ffffff !important;
  font-weight: 700 !important;
}
.kpi-value {
  color: #ffffff !important;
  font-weight: 800 !important;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6) !important;
}
.kpi-subtext {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.kpi-subtext span:not([class*="ok"]):not([class*="bad"]):not([class*="warn"]) {
  color: #ffffff !important;
}
.chart-title {
  color: #ffffff !important;
  font-weight: 700 !important;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5) !important;
}
.chart-subtitle {
  color: #ffffff !important;
  font-weight: 500 !important;
}
.table-title {
  color: #ffffff !important;
  font-weight: 800 !important;
}
#tableFilterLabel {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.table-search-input {
  color: #ffffff !important;
  background: #071322 !important;
  border-color: rgba(255, 255, 255, 0.4) !important;
}
.ioc-table thead th {
  color: #ffffff !important;
  background: #071322 !important;
  font-weight: 800 !important;
  border-bottom: 2px solid #ffffff !important;
}
.ioc-table tbody td {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.ioc-table tbody td a {
  color: #ffffff !important;
}
.ioc-table tfoot td {
  color: #ffffff !important;
  background: #071322 !important;
  font-weight: 800 !important;
  border-top: 2px solid #ffffff !important;
}
.status-matrix-header {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.status-matrix-header span:first-child {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.status-matrix-name {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.status-matrix-val {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.funnel-step-label {
  color: #ffffff !important;
  font-weight: 600 !important;
}
.funnel-step-pct {
  color: #ffffff !important;
  font-weight: 800 !important;
}
.waffle-container, .waffle-legend {
  color: #ffffff !important;
}
.ioc-empty-state, [style*="color:#85a4c4"] {
  color: #ffffff !important;
}

/* ApexCharts Typography (Toàn bộ chữ biểu đồ trắng ở Chế độ Tối) */
.apexcharts-canvas text:not(.apexcharts-datalabels text):not(.apexcharts-pie-label text),
.apexcharts-text,
.apexcharts-text tspan,
text.apexcharts-text,
.apexcharts-xaxis-label,
.apexcharts-yaxis-label,
.apexcharts-xaxis-label text,
.apexcharts-yaxis-label text,
.apexcharts-radar-series text,
.apexcharts-title-text,
.apexcharts-subtitle-text,
.apexcharts-xaxis-title-text,
.apexcharts-yaxis-title-text,
.apexcharts-legend-text,
.apexcharts-legend-series,
.apexcharts-legend-series span,
.apexcharts-datalabel-value,
.apexcharts-datalabel-label {
  fill: #ffffff !important;
  color: #ffffff !important;
  font-weight: 700 !important;
}
.apexcharts-tooltip {
  background: #071322 !important;
  border: 1.5px solid #ffffff !important;
  color: #ffffff !important;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.7) !important;
}
.apexcharts-tooltip-title {
  background: #0d2744 !important;
  border-bottom: 1px solid rgba(255, 255, 255, 0.4) !important;
  color: #ffffff !important;
  font-weight: 800 !important;
}
.apexcharts-tooltip-text,
.apexcharts-tooltip-series-group,
.apexcharts-tooltip-y-group span {
  color: #ffffff !important;
}
.bg-presets-label {
  color: #ffffff !important;
}
.bg-slider-ticks {
  color: #ffffff !important;
}
.bg-preset-btn {
  color: #ffffff !important;
}

/* ================= LIGHT MODE PURE CONTRAST (NỀN SÁNG -> CHỮ ĐEN TUYỀN #000000) ================= */
[data-theme="light"] {
  --bg: #f8fafc;
  --bg-gradient: radial-gradient(circle at 50% 0%, #cbd5e1 0%, #e2e8f0 40%, #f8fafc 85%);
  --sidebar-bg: rgba(255, 255, 255, var(--sidebar-alpha, 0.88));
  --sidebar-border: #94a3b8;
  --panel: rgba(255, 255, 255, var(--panel-alpha, 0.92));
  --panel-card: rgba(255, 255, 255, var(--panel-alpha, 0.96));
  --panel-border: #cbd5e1;
  --panel-hover: rgba(0, 0, 0, 0.08);
  --accent: #0284c7;
  --teal: #0d9488;
  --blue: #2563eb;
  --purple: #7c3aed;
  --ok: #15803d;
  --warn: #c2410c;
  --bad: #dc2626;
  --text: #000000;
  --muted: #000000;
  --sub: #000000;
  --gold: #b45309;
}

[data-theme="light"] html,
[data-theme="light"] body {
  background-color: var(--bg);
  color: #000000;
}
[data-theme="light"] body::before {
  background-image: var(--bg-gradient);
}

[data-theme="light"] ::-webkit-scrollbar-track { background: #e2e8f0; }
[data-theme="light"] ::-webkit-scrollbar-thumb { background: #64748b; }

[data-theme="light"] .topbar-page-info h1 {
  color: #000000 !important;
  font-weight: 900 !important;
}
[data-theme="light"] .topbar-page-info .hospital-badge {
  color: #000000 !important;
  font-weight: 800 !important;
}

[data-theme="light"] .live-clock-badge {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
[data-theme="light"] .live-clock-badge span,
[data-theme="light"] #clockTime,
[data-theme="light"] #clockDate {
  color: #000000 !important;
  font-weight: 800 !important;
}

[data-theme="light"] .topbar-btn {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
  font-weight: 700 !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
[data-theme="light"] .topbar-btn:hover {
  background: #f1f5f9 !important;
  border-color: #000000 !important;
  color: #000000 !important;
}
[data-theme="light"] .topbar-btn.active {
  background: #000000 !important;
  color: #ffffff !important;
  border-color: #000000 !important;
}

[data-theme="light"] .ioc-filter-bar {
  background: var(--panel);
  border: 1.5px solid #94a3b8;
  box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}

[data-theme="light"] .filter-group label {
  color: #000000 !important;
  font-weight: 900 !important;
}

[data-theme="light"] .filter-select,
[data-theme="light"] .filter-input {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .filter-select:focus,
[data-theme="light"] .filter-input:focus {
  border-color: #000000 !important;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.2) !important;
}
[data-theme="light"] .filter-select option {
  background: #ffffff !important;
  color: #000000 !important;
}

[data-theme="light"] .btn-filter-reset {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
  font-weight: 800 !important;
}
[data-theme="light"] .btn-filter-reset:hover {
  background: #000000 !important;
  color: #ffffff !important;
}

[data-theme="light"] .kpi-card {
  background: var(--panel-card);
  border: 1.5px solid #cbd5e1 !important;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
[data-theme="light"] .kpi-card:hover {
  border-color: #000000 !important;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
}
[data-theme="light"] .kpi-label {
  color: #000000 !important;
  font-weight: 800 !important;
}
[data-theme="light"] .kpi-value {
  color: #000000 !important;
  font-weight: 900 !important;
  text-shadow: none !important;
}
[data-theme="light"] .kpi-subtext {
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .kpi-subtext span:not([class*="ok"]):not([class*="bad"]):not([class*="warn"]) {
  color: #000000 !important;
}

[data-theme="light"] .chart-card {
  background: var(--panel-card);
  border: 1.5px solid #cbd5e1 !important;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
}
[data-theme="light"] .chart-header {
  border-bottom-color: #cbd5e1;
}
[data-theme="light"] .chart-title {
  color: #000000 !important;
  font-weight: 900 !important;
  text-shadow: none !important;
}
[data-theme="light"] .chart-subtitle {
  color: #000000 !important;
  font-weight: 700 !important;
}

[data-theme="light"] .table-section {
  background: var(--panel-card);
  border: 1.5px solid #cbd5e1 !important;
  box-shadow: 0 2px 12px rgba(0,0,0,0.05);
}
[data-theme="light"] .table-title {
  color: #000000 !important;
  font-weight: 900 !important;
}
[data-theme="light"] #tableFilterLabel {
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .table-header {
  border-bottom-color: #cbd5e1;
}
[data-theme="light"] .table-search-input {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .table-search-input:focus {
  border-color: #000000 !important;
}

[data-theme="light"] .ioc-table thead th {
  background: #f1f5f9 !important;
  color: #000000 !important;
  font-weight: 900 !important;
  border-bottom: 2.5px solid #000000 !important;
  border-top-color: #cbd5e1;
}
[data-theme="light"] .ioc-table tbody tr:nth-child(even) {
  background: #f8fafc !important;
}
[data-theme="light"] .ioc-table tbody tr:hover {
  background: #e2e8f0 !important;
}
[data-theme="light"] .ioc-table tbody td {
  border-bottom: 1px solid #cbd5e1 !important;
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .ioc-table tbody td a,
[data-theme="light"] .ioc-table tbody td span:not([class*="status-pill"]):not([style*="color: #2ecc71"]):not([style*="color: rgb(46, 204, 113)"]):not([style*="color: #ff5c5c"]):not([style*="color: rgb(255, 92, 92)"]) {
  color: #000000 !important;
}
[data-theme="light"] .ioc-table tfoot td {
  background: #f1f5f9 !important;
  color: #000000 !important;
  font-weight: 900 !important;
  border-top: 2.5px solid #000000 !important;
}

/* Status Matrix trong chế độ Sáng (Chữ đen) */
[data-theme="light"] .status-matrix-item {
  background: #ffffff !important;
  border: 1.5px solid #cbd5e1 !important;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
}
[data-theme="light"] .status-matrix-item:hover {
  background: #f8fafc !important;
  border-color: #000000 !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12) !important;
}
[data-theme="light"] .status-matrix-header {
  color: #000000 !important;
  font-weight: 900 !important;
}
[data-theme="light"] .status-matrix-header span:first-child {
  color: #000000 !important;
  font-weight: 900 !important;
}
[data-theme="light"] .status-matrix-name {
  color: #000000 !important;
  font-weight: 800 !important;
}
[data-theme="light"] .status-matrix-val {
  color: #000000 !important;
  font-weight: 700 !important;
}

/* Funnel Steps trong chế độ Sáng (Chữ đen) */
[data-theme="light"] .funnel-step-bar-wrap {
  background: #f1f5f9 !important;
  border: 1px solid #cbd5e1 !important;
}
[data-theme="light"] .funnel-step-label {
  color: #000000 !important;
  font-weight: 800 !important;
}
[data-theme="light"] .funnel-step-pct {
  color: #000000 !important;
  font-weight: 900 !important;
}
[data-theme="light"] .waffle-container, [data-theme="light"] .waffle-legend {
  color: #000000 !important;
}
[data-theme="light"] .ioc-empty-state, [data-theme="light"] [style*="color:#85a4c4"] {
  color: #000000 !important;
}

/* Sidebar trong chế độ Sáng (Chữ đen) */
[data-theme="light"] .nav-section-label {
  color: #000000 !important;
  font-weight: 900 !important;
  opacity: 1 !important;
}
[data-theme="light"] .nav-link {
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .nav-link i {
  color: #000000 !important;
}
[data-theme="light"] .nav-link:hover {
  background: #e2e8f0 !important;
  color: #000000 !important;
}
[data-theme="light"] .nav-link.active {
  background: #e2e8f0 !important;
  border-left: 4px solid #000000 !important;
  color: #000000 !important;
  font-weight: 900 !important;
}
[data-theme="light"] .nav-link.active i {
  color: #000000 !important;
  text-shadow: none !important;
}

[data-theme="light"] .sidebar-header {
  border-bottom-color: #cbd5e1;
}
[data-theme="light"] .sidebar-title .app-name {
  color: #000000 !important;
  -webkit-text-fill-color: #000000 !important;
}
[data-theme="light"] .sidebar-title .app-sub {
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .sidebar-toggle-btn {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
}
[data-theme="light"] .sidebar-footer {
  color: #000000 !important;
  border-top-color: #cbd5e1 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .sidebar-footer span:last-child {
  color: #000000 !important;
  font-weight: 800 !important;
}

/* ApexCharts Typography (Chữ đen ở chế độ Sáng) */
[data-theme="light"] .apexcharts-canvas text:not(.apexcharts-datalabels text):not(.apexcharts-pie-label text),
[data-theme="light"] .apexcharts-text,
[data-theme="light"] .apexcharts-text tspan,
[data-theme="light"] text.apexcharts-text,
[data-theme="light"] .apexcharts-xaxis-label,
[data-theme="light"] .apexcharts-yaxis-label,
[data-theme="light"] .apexcharts-xaxis-label text,
[data-theme="light"] .apexcharts-yaxis-label text,
[data-theme="light"] .apexcharts-radar-series text,
[data-theme="light"] .apexcharts-title-text,
[data-theme="light"] .apexcharts-subtitle-text,
[data-theme="light"] .apexcharts-xaxis-title-text,
[data-theme="light"] .apexcharts-yaxis-title-text,
[data-theme="light"] .apexcharts-legend-text,
[data-theme="light"] .apexcharts-legend-series,
[data-theme="light"] .apexcharts-legend-series span,
[data-theme="light"] .apexcharts-datalabel-value,
[data-theme="light"] .apexcharts-datalabel-label {
  fill: #000000 !important;
  color: #000000 !important;
  font-weight: 700 !important;
}
[data-theme="light"] .apexcharts-datalabels text {
  fill: #ffffff !important; /* Bên trong cột/lát cắt màu vẫn giữ trắng để tương phản với màu khối */
}
[data-theme="light"] .apexcharts-gridline {
  stroke: rgba(0, 0, 0, 0.15) !important;
}
[data-theme="light"] .apexcharts-tooltip {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
  color: #000000 !important;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15) !important;
}
[data-theme="light"] .apexcharts-tooltip-title {
  background: #f1f5f9 !important;
  border-bottom: 1.5px solid #000000 !important;
  color: #000000 !important;
  font-weight: 800 !important;
}
[data-theme="light"] .apexcharts-tooltip-text,
[data-theme="light"] .apexcharts-tooltip-series-group,
[data-theme="light"] .apexcharts-tooltip-y-group span {
  color: #000000 !important;
}

/* Popup điều chỉnh nền ở chế độ Sáng */
[data-theme="light"] .bg-adjust-popover {
  background: #ffffff !important;
  border: 1.5px solid #000000 !important;
}
[data-theme="light"] .bg-adjust-header h4 {
  color: #000000 !important;
}
[data-theme="light"] .bg-adjust-badge {
  background: #f1f5f9 !important;
  border-color: #000000 !important;
  color: #000000 !important;
}
[data-theme="light"] .bg-presets-label {
  color: #000000 !important;
  font-weight: 800;
}
[data-theme="light"] .bg-slider-ticks {
  color: #000000 !important;
  font-weight: 700;
}
[data-theme="light"] .bg-preset-btn {
  color: #000000 !important;
  border: 1.5px solid #cbd5e1 !important;
  background: #ffffff !important;
  font-weight: 700;
}
[data-theme="light"] .bg-preset-btn:hover {
  border-color: #000000 !important;
  background: #f1f5f9 !important;
}
[data-theme="light"] .bg-preset-btn.active {
  background: #000000 !important;
  color: #ffffff !important;
  border-color: #000000 !important;
}
</style>
</head>
<body>
<div class="ioc-container">
