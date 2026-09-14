      <!-- ==================== TAB: DANH MỤC KHOA PHÒNG ==================== -->
      <section id="tab-category-departments" class="tab-content hidden space-y-5">
        <!-- Control bar -->
        <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
              <input type="text" placeholder="Tìm theo mã hoặc tên khoa..." class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-brand-500">
              <i class="ph ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
            </div>
            <select class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none">
              <option value="">Tất cả loại khoa</option>
              <option value="inpatient">Nội trú</option>
              <option value="outpatient">Ngoại trú</option>
              <option value="paraclinical">Cận lâm sàng</option>
            </select>
          </div>

          <button onclick="handleAction('Thêm mới Khoa Phòng')" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-brand-500/20 transition">
            <i class="ph-bold ph-plus"></i> Thêm Khoa Phòng
          </button>
        </div>

        <!-- Data Table Khoa Phòng -->
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-blue-50/50 text-slate-500 border-b border-blue-100 font-semibold uppercase text-[11px] tracking-wider">
                  <th class="py-3.5 px-4">Mã Khoa</th>
                  <th class="py-3.5 px-4">Tên Khoa / Phòng</th>
                  <th class="py-3.5 px-4">Phân Loại</th>
                  <th class="py-3.5 px-4 text-center">Giường Kế Hoạch</th>
                  <th class="py-3.5 px-4 text-center">Giường Thực Kê</th>
                  <th class="py-3.5 px-4 text-center">Trạng Thái</th>
                  <th class="py-3.5 px-4 text-center w-28">Thao Tác</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">KKB-01</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Khám Bệnh Đa Khoa</td>
                  <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-full text-[11px] bg-slate-100 text-slate-600">Ngoại trú</span></td>
                  <td class="py-3.5 px-4 text-center">0</td>
                  <td class="py-3.5 px-4 text-center">0</td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold">Hoạt động</span></td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Khoa: KKB-01')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Khoa: KKB-01')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Khoa: KKB-01')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">KNT-TM</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Nội Tim Mạch Can Thiệp</td>
                  <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-full text-[11px] bg-blue-100 text-brand-700 font-semibold">Nội trú</span></td>
                  <td class="py-3.5 px-4 text-center font-bold">50</td>
                  <td class="py-3.5 px-4 text-center font-bold text-brand-700">62</td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold">Hoạt động</span></td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Khoa: KNT-TM')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Khoa: KNT-TM')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Khoa: KNT-TM')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">KHSCC</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Hồi Sức Cấp Cứu & Chống Độc</td>
                  <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-full text-[11px] bg-blue-100 text-brand-700 font-semibold">Nội trú</span></td>
                  <td class="py-3.5 px-4 text-center font-bold">30</td>
                  <td class="py-3.5 px-4 text-center font-bold text-brand-700">35</td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold">Hoạt động</span></td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Khoa: KHSCC')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Khoa: KHSCC')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Khoa: KHSCC')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr class="hover:bg-blue-50/30 transition">
                  <td class="py-3.5 px-4 font-bold text-brand-700">CLS-HA</td>
                  <td class="py-3.5 px-4 font-semibold text-slate-800">Khoa Chẩn Đoán Hình Ảnh (PACS/RIS)</td>
                  <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-full text-[11px] bg-purple-100 text-purple-700">Cận lâm sàng</span></td>
                  <td class="py-3.5 px-4 text-center">0</td>
                  <td class="py-3.5 px-4 text-center">0</td>
                  <td class="py-3.5 px-4 text-center"><span class="px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-600 font-semibold">Hoạt động</span></td>
                  <td class="py-3.5 px-4">
                    <div class="flex items-center justify-center gap-1.5 text-base">
                      <button onclick="handleAction('Xem chi tiết Khoa: CLS-HA')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Xem chi tiết"><i class="ph ph-eye"></i></button>
                      <button onclick="handleAction('Chỉnh sửa Khoa: CLS-HA')" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Sửa"><i class="ph ph-pencil-simple"></i></button>
                      <button onclick="handleAction('Xóa Khoa: CLS-HA')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Xóa"><i class="ph ph-trash"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- Pagination -->
          <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Hiển thị 1 - 4 trên tổng số 18 khoa phòng</span>
            <div class="flex gap-1">
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50 disabled:opacity-50" disabled>Trước</button>
              <button class="px-2.5 py-1 border rounded-lg bg-brand-600 text-white font-bold">1</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">2</button>
              <button class="px-2.5 py-1 border rounded-lg hover:bg-slate-50">Sau</button>
            </div>
          </div>
        </div>
      </section>
