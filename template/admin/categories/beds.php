      <!-- ==================== TAB: DANH MỤC GIƯỜNG BỆNH ==================== -->
      <section id="tab-category-beds" class="tab-content hidden space-y-5">
        <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
              <input type="text" placeholder="Tìm theo mã giường, số phòng..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
              <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
            </div>
            <select class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none">
              <option value="">Tất cả trạng thái</option>
              <option value="occupied">Đang nằm</option>
              <option value="available">Giường trống</option>
              <option value="maintenance">Bảo trì</option>
            </select>
          </div>

          <button onclick="handleAction('Thêm Giường Mới')" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-brand-500/20 transition">
            <i class="ph-bold ph-plus"></i> Thêm Giường Bệnh
          </button>
        </div>

        <!-- Data Table Giường Bệnh -->
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-blue-50/50 text-slate-500 border-b border-blue-100 font-semibold uppercase text-[11px] tracking-wider">
                  <th class="py-3.5 px-4">Mã Giường</th>
                  <th class="py-3.5 px-4">Khoa Trực Thuộc</th>
                  <th class="py-3.5 px-4">Số Phòng</th>
                  <th class="py-3.5 px-4">Loại Giường</th>
                  <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                  <th class="py-3.5 px-4 text-center">Bệnh Nhân Hiện Tại</th>
                  <th class="py-3.5 px-4 text-center w-28">Thao Tác</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">P201-G01</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Nội Tim Mạch</td>
                  <td class="py-3.5 px-4">Phòng 201 (Khu A)</td>
                  <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">Tiêu chuẩn</span></td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-1 rounded-full text-[11px] bg-rose-50 text-rose-600 font-semibold">Đang nằm</span></td>
                  <td class="py-3.5 px-4 text-center font-bold text-slate-800">Nguyễn Văn Bình (PID: 108422)</td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Giường: P201-G01')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Giường: P201-G01')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Giường: P201-G01')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">P201-G02</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Nội Tim Mạch</td>
                  <td class="py-3.5 px-4">Phòng 201 (Khu A)</td>
                  <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium">Tiêu chuẩn</span></td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-1 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold">Giường trống</span></td>
                  <td class="py-3.5 px-4 text-center text-slate-400 font-normal">-- Không có --</td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Giường: P201-G02')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Giường: P201-G02')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Giường: P201-G02')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">ICU-G05</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Hồi Sức Cấp Cứu</td>
                  <td class="py-3.5 px-4">Phòng Hồi sức đặc biệt</td>
                  <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-purple-100 text-purple-700 font-semibold">ICU Đa năng</span></td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-1 rounded-full text-[11px] bg-rose-50 text-rose-600 font-semibold">Đang nằm</span></td>
                  <td class="py-3.5 px-4 text-center font-bold text-slate-800">Trần Thị Mai (PID: 108511)</td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Giường: ICU-G05')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Giường: ICU-G05')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Giường: ICU-G05')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">VIP-G01</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Ngoại Tiêu Hóa</td>
                  <td class="py-3.5 px-4">Phòng Yêu cầu VIP 1</td>
                  <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-700 font-semibold">Theo yêu cầu</span></td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-1 rounded-full text-[11px] bg-amber-50 text-amber-600 font-semibold">Bảo dưỡng</span></td>
                  <td class="py-3.5 px-4 text-center text-slate-400 font-normal">-- Tạm khóa --</td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Giường: VIP-G01')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Giường: VIP-G01')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Giường: VIP-G01')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Hiển thị 1 - 4 trên tổng số 600 giường</span>
            <div class="flex gap-1">
              <button class="px-2.5 py-1 border rounded-lg bg-brand-600 text-white font-bold">1</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">2</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">...</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">150</button>
            </div>
          </div>
        </div>
      </section>
