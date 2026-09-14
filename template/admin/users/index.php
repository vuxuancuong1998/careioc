      <!-- ==================== TAB: QUẢN LÝ NGƯỜI DÙNG & THÔNG TIN CÁ NHÂN ==================== -->
      <section id="tab-users" class="tab-content hidden space-y-5">
        <?php
        global $db;
        $dbUsers = [];
        if (isset($db)) {
            $db->query("SELECT u.*, r.id as role_id, r.role_name, r.role_code 
                        FROM ioc_users u 
                        LEFT JOIN ioc_auth_user_roles ur ON ur.user_id = u.id 
                        LEFT JOIN ioc_auth_roles r ON ur.role_id = r.id 
                        ORDER BY u.created_at DESC");
            $dbUsers = $db->fetch_object();
        }
        ?>
        <!-- Thanh công cụ lọc & tìm kiếm người dùng -->
        <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-3">
          <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto flex-1">
            <div class="relative w-full sm:w-80">
              <input type="text" id="user-search-input" onkeyup="filterUserTable()" placeholder="Tìm theo Họ tên, CCCD, chức vụ, email, phòng ban..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500 focus:bg-white transition-all">
              <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
            </div>
            <select id="user-dept-filter" onchange="filterUserTable()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none">
              <option value="">Tất cả phòng ban</option>
              <option value="Khoa Nội Tim Mạch">Khoa Nội Tim Mạch</option>
              <option value="Khoa Hồi Sức Cấp Cứu">Khoa Hồi Sức Cấp Cứu</option>
              <option value="Khoa Khám Bệnh">Khoa Khám Bệnh</option>
              <option value="Khoa Ngoại Tổng Hợp">Khoa Ngoại Tổng Hợp</option>
              <option value="Phòng CNTT & Chuyển Đổi Số">Phòng CNTT & CĐS</option>
            </select>
            <select id="user-pos-filter" onchange="filterUserTable()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none">
              <option value="">Tất cả chức vụ</option>
              <option value="Trưởng Khoa">Trưởng Khoa</option>
              <option value="Phó Trưởng Khoa">Phó Trưởng Khoa</option>
              <option value="Bác Sĩ Điều Trị">Bác Sĩ Điều Trị</option>
              <option value="Điều Dưỡng Trưởng">Điều Dưỡng Trưởng</option>
              <option value="Quản trị hệ thống DB/IOC">Quản trị DB/IOC</option>
            </select>
          </div>

          <div class="flex items-center gap-2 w-full lg:w-auto justify-end">
            <button data-permission="users.export" onclick="exportUserExcel()" class="flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
              <i class="ph-bold ph-download-simple"></i> Xuất DS
            </button>
            <button data-permission="users.create" onclick="openAddUserModal()" class="flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-brand-500/20 transition">
              <i class="ph-bold ph-user-plus"></i> Thêm Người Dùng
            </button>
          </div>
        </div>

        <!-- Bảng Dữ Liệu Người Dùng -->
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse" id="users-table">
              <thead>
                <tr class="bg-blue-50/50 text-slate-500 border-b border-blue-100 font-semibold uppercase text-[11px] tracking-wider">
                  <th class="py-3.5 px-4">Họ và Tên</th>
                  <th class="py-3.5 px-4">Số CCCD</th>
                  <th class="py-3.5 px-4">Vai Trò Phân Quyền</th>
                  <th class="py-3.5 px-4">Chức Vụ</th>
                  <th class="py-3.5 px-4">Phòng Ban</th>
                  <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                  <th class="py-3.5 px-4 text-center w-28">Thao Tác</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 font-medium text-slate-700" id="users-tbody">
                <?php
                if (!empty($dbUsers)) {
                    $avatarColors = [
                        ['bg' => 'bg-blue-100', 'text' => 'text-brand-700'],
                        ['bg' => 'bg-pink-100', 'text' => 'text-pink-700'],
                        ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                        ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                        ['bg' => 'bg-purple-100', 'text' => 'text-purple-700']
                    ];
                    $idx = 0;
                    foreach ($dbUsers as $u) {
                        $c = $avatarColors[$idx % count($avatarColors)];
                        $idx++;
                        // Lấy 2 chữ cái đầu
                        $words = explode(' ', trim($u->display_name));
                        $initials = '';
                        if (count($words) >= 2) {
                            $initials = mb_substr($words[count($words)-2], 0, 1) . mb_substr($words[count($words)-1], 0, 1);
                        } else {
                            $initials = mb_substr($words[0], 0, 2);
                        }
                        $initials = mb_strtoupper($initials);

                        // Badge chức vụ
                        $posClass = 'bg-slate-100 text-slate-700';
                        if (stripos($u->position, 'Trưởng Khoa') !== false) {
                            $posClass = 'bg-blue-50 text-brand-700 border border-blue-200';
                        } elseif (stripos($u->position, 'Bác Sĩ') !== false) {
                            $posClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                        } elseif (stripos($u->position, 'Điều Dưỡng') !== false) {
                            $posClass = 'bg-pink-50 text-pink-700 border border-pink-200';
                        } elseif (stripos($u->position, 'Quản trị') !== false || stripos($u->position, 'DB') !== false) {
                            $posClass = 'bg-purple-50 text-purple-700 border border-purple-200';
                        }

                        $userJson = htmlspecialchars(json_encode([
                            'id' => $u->id,
                            'username' => $u->username ?? '',
                            'display_name' => $u->display_name ?? '',
                            'citizen_id' => $u->citizen_id ?? '',
                            'role_id' => $u->role_id ?? '',
                            'role_name' => $u->role_name ?? '',
                            'position' => $u->position ?? '',
                            'department' => $u->department ?? '',
                            'email' => $u->email ?? '',
                            'phone' => $u->phone ?? '',
                            'gender' => $u->gender ?? 'Nam',
                            'birthday' => $u->birthday ?? '',
                            'address' => $u->address ?? '',
                            'is_active' => (int)($u->is_active ?? 1)
                        ]), ENT_QUOTES, 'UTF-8');
                ?>
                <tr class="user-row hover:bg-blue-50/30 transition" 
                    data-name="<?= htmlspecialchars(mb_strtolower($u->display_name ?? '')) ?>" 
                    data-cccd="<?= htmlspecialchars($u->citizen_id ?? '') ?>" 
                    data-dept="<?= htmlspecialchars($u->department ?? '') ?>" 
                    data-pos="<?= htmlspecialchars($u->position ?? '') ?>" 
                    data-email="<?= htmlspecialchars(mb_strtolower($u->email ?? '')) ?>">
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-2.5">
                      <div class="w-9 h-9 rounded-xl <?= $c['bg'] ?> <?= $c['text'] ?> flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                        <?= $initials ?>
                      </div>
                      <div>
                        <p class="font-bold text-slate-800 leading-tight"><?= htmlspecialchars($u->display_name) ?></p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">@<?= htmlspecialchars($u->username ?? 'user') ?></p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-1.5 font-mono text-slate-700 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200/80 w-fit">
                      <i class="ph-bold ph-identification-card text-brand-600 text-sm"></i>
                      <span class="font-semibold tracking-wider"><?= htmlspecialchars($u->citizen_id ?: '-- Chưa cập nhật --') ?></span>
                    </div>
                  </td>
                  <td class="py-3.5 px-4">
                    <?php if (!empty($u->role_name)): ?>
                      <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-brand-50 text-brand-700 border border-brand-200/80">
                        <i class="ph-bold ph-shield-check text-xs mr-0.5"></i><?= htmlspecialchars($u->role_name) ?>
                      </span>
                    <?php else: ?>
                      <span class="text-[11px] text-slate-400 italic">Chưa phân vai trò</span>
                    <?php endif; ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold <?= $posClass ?>">
                      <?= htmlspecialchars($u->position ?: 'Cán bộ nhân viên') ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-1.5 text-slate-700">
                      <i class="ph ph-buildings text-slate-400 text-sm"></i>
                      <span class="font-semibold"><?= htmlspecialchars($u->department ?: '--') ?></span>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 text-center">
                    <?php if (($u->is_active ?? 1) == 1): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold border border-emerald-100">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] bg-rose-50 text-rose-600 font-semibold border border-rose-100">
                      <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Tạm khóa
                    </span>
                    <?php endif; ?>
                  </td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1 text-base">
                      <button onclick='openViewUserModal(<?= $userJson ?>)' class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết hồ sơ">
                        <i class="ph ph-eye"></i>
                      </button>
                      <button data-permission="users.edit" onclick='openEditUserModal(<?= $userJson ?>)' class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa thông tin cá nhân">
                        <i class="ph ph-pencil-simple"></i>
                      </button>
                      <button data-permission="users.delete" onclick="toggleUserLock('<?= $u->id ?>', '<?= htmlspecialchars($u->display_name) ?>')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Khóa/Xóa tài khoản">
                        <i class="ph ph-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php
                    }
                } else {
                ?>
                <tr>
                  <td colspan="7" class="py-8 text-center text-slate-400">
                    <i class="ph ph-user-list text-3xl mb-1 text-slate-300"></i>
                    <p>Chưa có dữ liệu người dùng nào trong hệ thống.</p>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="px-4 py-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
            <span id="user-count-label">Hiển thị <?= !empty($dbUsers) ? count($dbUsers) : 0 ?> người dùng trong hệ thống</span>
            <div class="flex gap-1">
              <button class="px-2.5 py-1 border rounded-lg bg-brand-600 text-white font-bold">1</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">2</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">Sau</button>
            </div>
          </div>
        </div>
      </section>
