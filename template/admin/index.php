<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hệ Thống Quản Trị IOC Điều Hành Y Tế Thông Minh</title>
  
  <!-- Tailwind CSS & Google Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <!-- SweetAlert2 (Thông báo xác nhận & trạng thái) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              500: '#3b82f6',
              600: '#2563eb', // Xanh dương chủ đạo
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a',
            }
          }
        }
      }
    }
  </script>

  <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .sidebar-transition {
      transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>

<body class="bg-[#f8fafc] text-slate-700 antialiased font-sans flex h-screen overflow-hidden">

  <!-- OVERLAY CHO MOBILE -->
  <div id="mobile-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 hidden lg:hidden"></div>

  <!-- 1. GIAO DIỆN KHUNG: SIDEBAR ĐIỀU HƯỚNG TRÁI -->
  <?php include __DIR__ . '/layouts/sidebar.php'; ?>

  <!-- VÙNG NỘI DUNG CHÍNH (BÊN PHẢI) -->
  <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
    
    <!-- 2. GIAO DIỆN KHUNG: TOP HEADER NAVIGATION -->
    <?php include __DIR__ . '/layouts/header.php'; ?>

    <!-- 3. KHÔNG GIAN NỘI DUNG CHỨC NĂNG THEO TỪNG MODULE (BẢO VỆ PHÂN QUYỀN SERVER-SIDE) -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 no-scrollbar">
      
      <!-- Nhóm 1: Tổng Quan Điều Hành IOC -->
      <?php if (Auth::canMenu('dashboard')): ?>
        <?php include __DIR__ . '/dashboard/index.php'; ?>
      <?php endif; ?>

      <!-- Nhóm 2: Điều Hành Khám Chữa Bệnh -->
      <?php if (Auth::canMenu('outpatient')): ?>
        <?php include __DIR__ . '/clinical/outpatient.php'; ?>
      <?php endif; ?>

      <?php if (Auth::canMenu('inpatient')): ?>
        <?php include __DIR__ . '/clinical/inpatient.php'; ?>
      <?php endif; ?>

      <!-- Nhóm 3: Quản Lý Người Dùng & Phân Quyền Vai Trò -->
      <?php if (Auth::canMenu('users')): ?>
        <?php include __DIR__ . '/users/index.php'; ?>
      <?php endif; ?>

      <?php if (Auth::canMenu('roles')): ?>
        <?php include __DIR__ . '/roles/index.php'; ?>
      <?php endif; ?>

      <!-- Nhóm 4: Quản Lý Danh Mục (Khoa phòng, Giường bệnh) -->
      <?php if (Auth::canMenu('category-departments')): ?>
        <?php include __DIR__ . '/categories/departments.php'; ?>
      <?php endif; ?>

      <?php if (Auth::canMenu('category-beds')): ?>
        <?php include __DIR__ . '/categories/beds.php'; ?>
      <?php endif; ?>

      <!-- Nhóm 5: Giám Sát & Hạ Tầng (CNTT, Server, ATTT) -->
      <?php if (Auth::canMenu('it-infra')): ?>
        <?php include __DIR__ . '/infrastructure/it_infra.php'; ?>
      <?php endif; ?>

      <?php if (Auth::canMenu('security')): ?>
        <?php include __DIR__ . '/infrastructure/security.php'; ?>
      <?php endif; ?>

      <!-- Thông Báo Dành Cho Cán Bộ Chưa Phân Quyền Hoặc Chưa Có Menu Nào -->
      <?php if (empty($_SESSION['user']['menu_access']) && !Auth::isSuperAdmin()): ?>
        <div id="tab-unassigned" class="tab-content bg-white rounded-3xl p-8 sm:p-12 border border-blue-100 shadow-sm text-center max-w-2xl mx-auto my-8 space-y-6">
          <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-500 border border-amber-200 mx-auto flex items-center justify-center text-4xl shadow-inner">
            <i class="ph-bold ph-shield-warning"></i>
          </div>
          <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
              Tài Khoản Đang Chờ Phân Quyền
            </span>
            <h3 class="text-xl font-bold text-slate-800">
              Xin chào, <?php echo htmlspecialchars($_SESSION['user']['display_name'] ?? 'Cán bộ'); ?>!
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-lg mx-auto">
              Tài khoản của bạn đã được khởi tạo thành công trên Trung tâm Điều hành IOC Y Tế. Tuy nhiên, quản trị viên chưa gán vai trò chức năng hoặc phân hệ làm việc cho bạn.
            </p>
          </div>
          
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-left text-xs text-slate-600 space-y-2 max-w-md mx-auto">
            <div class="flex items-center gap-2 font-semibold text-slate-700">
              <i class="ph-bold ph-info text-brand-600 text-sm"></i>
              <span>Thông tin cán bộ:</span>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-200/60 font-mono text-[11px]">
              <div>Số CCCD: <strong class="text-slate-800"><?php echo htmlspecialchars($_SESSION['user']['citizen_id'] ?? '--'); ?></strong></div>
              <div>Khoa/Phòng: <strong class="text-slate-800"><?php echo htmlspecialchars($_SESSION['user']['department'] ?? '--'); ?></strong></div>
              <div>Chức vụ: <strong class="text-slate-800"><?php echo htmlspecialchars($_SESSION['user']['position'] ?? '--'); ?></strong></div>
              <div>Trạng thái: <strong class="text-amber-600"><?php echo htmlspecialchars($_SESSION['user']['role_name'] ?? 'Chưa phân quyền'); ?></strong></div>
            </div>
          </div>

          <div class="pt-2 flex items-center justify-center gap-3">
            <button onclick="location.reload()" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
              <i class="ph-bold ph-arrows-clockwise text-sm"></i>
              <span>Kiểm Tra Lại Quyền</span>
            </button>
            <button onclick="logoutAction()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
              Đăng Xuất
            </button>
          </div>
        </div>
      <?php endif; ?>

    </main>
  </div>

  <!-- 4. CÁC MODAL NGƯỜI DÙNG & HỒ SƠ CÁ NHÂN -->
  <?php include __DIR__ . '/users/modals.php'; ?>

  <!-- 5. GIAO DIỆN KHUNG: FOOTER & SCRIPTS HỆ THỐNG -->
  <?php include __DIR__ . '/layouts/footer.php'; ?>