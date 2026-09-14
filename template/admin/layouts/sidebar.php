  <!-- ==================== SIDEBAR TRÁI ==================== -->
  <aside id="sidebar" class="sidebar-transition bg-white border-r border-blue-100 flex flex-col z-40 fixed lg:static h-full w-64 shadow-[2px_0_15px_-3px_rgba(0,0,0,0.04)]">
    
    <!-- Header Logo -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-blue-50">
      <div class="flex items-center gap-3 overflow-hidden">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-700 to-blue-500 flex items-center justify-center text-white text-xl font-bold shadow-md shadow-brand-500/20 shrink-0">
          <i class="ph-bold ph-heartbeat"></i>
        </div>
        <div class="sidebar-text flex flex-col">
          <span class="font-bold text-slate-800 leading-tight tracking-tight text-base">IOC HOSPITAL</span>
          <span class="text-[11px] font-medium text-brand-600 tracking-wider">HỆ THỐNG ĐIỀU HÀNH</span>
        </div>
      </div>
      <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
        <i class="ph ph-x text-lg"></i>
      </button>
    </div>

    <!-- Danh sách Menu Động Theo Vai Trò -->
    <div class="flex-1 overflow-y-auto no-scrollbar py-4 px-3 space-y-1">
      <?php
        $visibleGroups = Auth::getVisibleMenus();
        $firstItem = true;
      ?>
      <?php if (empty($visibleGroups)): ?>
        <div class="p-4 text-center text-xs text-slate-400">
          <i class="ph ph-warning-circle text-2xl text-amber-500 mb-1"></i>
          <p>Tài khoản chưa được phân quyền truy cập menu nào.</p>
        </div>
      <?php else: ?>
        <?php foreach ($visibleGroups as $group): ?>
          <div class="sidebar-text px-3 pt-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">
            <?php echo htmlspecialchars($group['group_name']); ?>
          </div>

          <?php foreach ($group['menus'] as $menu): ?>
            <?php 
              $isActive = ($firstItem && $menu['id'] === 'dashboard'); 
              if ($isActive) $firstItem = false;
            ?>
            <a href="javascript:void(0)" onclick="switchTab('<?php echo $menu['route']; ?>', this)" 
               class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-sm transition-all duration-200 <?php echo $isActive ? 'active bg-brand-50 text-brand-700 font-semibold' : 'text-slate-600 hover:bg-blue-50/60 hover:text-brand-700'; ?>">
              <i class="<?php echo $menu['icon']; ?> text-xl <?php echo $isActive ? 'text-brand-600' : 'text-slate-400'; ?> shrink-0"></i>
              <span class="sidebar-text truncate"><?php echo htmlspecialchars($menu['name']); ?></span>
            </a>
          <?php endforeach; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Sync status -->
    <div class="p-3 border-t border-blue-50">
      <div class="sidebar-text flex items-center gap-2 p-2 rounded-xl bg-blue-50/60 text-xs text-brand-700 border border-blue-100">
        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
        <span class="font-medium">Data Sync: Realtime API</span>
      </div>
    </div>
  </aside>
