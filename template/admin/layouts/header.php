    <!-- Top Header Navigation Bar -->
    <?php 
      $currentUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
      $displayName = $currentUser ? ($currentUser['display_name'] ?? $currentUser['fullname'] ?? 'Cán bộ Y tế') : 'Cán bộ Y tế';
      $userEmail = $currentUser ? ($currentUser['email'] ?? 'ioc@hospital.gov.vn') : 'ioc@hospital.gov.vn';
      $userPosition = $currentUser ? ($currentUser['position'] ?? 'Quản trị viên') : 'Quản trị viên';
      $userCitizenId = $currentUser ? ($currentUser['citizen_id'] ?? '') : '';
      $shortWords = explode(' ', trim($displayName));
      $initials = count($shortWords) >= 2 ? mb_strtoupper(mb_substr($shortWords[0], 0, 1) . mb_substr(end($shortWords), 0, 1)) : mb_strtoupper(mb_substr($displayName, 0, 2));
    ?>
    <header class="h-16 bg-white border-b border-blue-100 flex items-center justify-between px-4 sm:px-6 z-20 shrink-0">
      
      <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="p-2 rounded-xl text-slate-500 hover:bg-blue-50 hover:text-brand-600 transition-colors" title="Ẩn/Hiện menu">
          <i class="ph ph-list text-xl font-bold"></i>
        </button>
        <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>
        <h1 id="page-title" class="text-base sm:text-lg font-bold text-slate-800 tracking-tight truncate">Tổng Quan IOC</h1>
      </div>

      <div class="flex items-center gap-3 sm:gap-4">
        <!-- Search bar -->
        <div class="relative hidden md:block w-64">
          <input type="text" placeholder="Tìm kiếm nhanh khoa, bệnh nhân..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-brand-500 focus:bg-white transition-all text-slate-700">
          <i class="ph ph-magnifying-glass absolute left-3 top-2 text-slate-400 text-sm"></i>
        </div>

        <!-- Chuông thông báo -->
        <button class="relative p-2 rounded-xl text-slate-500 hover:bg-blue-50 hover:text-brand-600 transition">
          <i class="ph ph-bell text-xl"></i>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
        </button>

        <!-- Dropdown User -->
        <div class="relative">
          <button onclick="toggleUserDropdown(event)" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-blue-50 transition focus:outline-none">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-700 to-blue-500 flex items-center justify-center text-white font-semibold text-xs shadow-sm ring-2 ring-blue-100">
              <?php echo htmlspecialchars($initials); ?>
            </div>
            <div class="hidden sm:flex flex-col text-left">
              <span class="text-xs font-bold text-slate-800 leading-tight"><?php echo htmlspecialchars($displayName); ?></span>
              <span class="text-[11px] text-brand-600 font-medium"><?php echo htmlspecialchars($userPosition); ?><?php if($userCitizenId): ?> • <?php echo htmlspecialchars($userCitizenId); ?><?php endif; ?></span>
            </div>
            <i class="ph ph-caret-down text-slate-400 text-xs hidden sm:block"></i>
          </button>

          <!-- Dropdown menu -->
          <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-blue-100 py-1.5 z-50">
            <div class="px-4 py-2.5 border-b border-slate-100">
              <p class="text-[11px] text-slate-400 font-medium">Cán bộ đang đăng nhập</p>
              <p class="text-xs font-bold text-slate-800 truncate"><?php echo htmlspecialchars($displayName); ?></p>
              <p class="text-[11px] text-brand-600 truncate mt-0.5"><i class="ph ph-identification-card mr-1"></i>CCCD: <strong class="font-mono"><?php echo htmlspecialchars($userCitizenId ?: '---'); ?></strong></p>
            </div>
            
            <div class="py-1">
              <a href="javascript:void(0)" onclick="openCurrentUserProfile()" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-blue-50 hover:text-brand-600 transition">
                <i class="ph ph-user text-base"></i> Thông tin cá nhân
              </a>
              <a href="javascript:void(0)" onclick="openChangePasswordModal()" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-blue-50 hover:text-brand-600 transition">
                <i class="ph ph-key text-base text-amber-500"></i> Đổi mật khẩu
              </a>
            </div>

            <div class="border-t border-slate-100 pt-1">
              <button onclick="logoutAction()" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition text-left">
                <i class="ph ph-sign-out text-base"></i> Đăng xuất hệ thống
              </button>
            </div>
          </div>
        </div>

      </div>
    </header>
