      <!-- TAB: HẠ TẦNG CNTT & SERVER -->
      <section id="tab-it-infra" class="tab-content hidden space-y-4">
        <div class="bg-white p-6 rounded-2xl border border-blue-100 shadow-sm">
          <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
              <h2 class="font-bold text-slate-800 text-base">Hạ Tầng Máy Chủ, Cơ Sở Dữ Liệu & Thiết Bị Mạng</h2>
              <p class="text-xs text-slate-500 mt-0.5">Giám sát tải CPU, RAM, ổ cứng và trạng thái kết nối các node cụm Database/HIS</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Tất cả Node Online</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
              <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-slate-500">Database Master (3307)</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              </div>
              <p class="text-lg font-bold text-slate-800 mt-2">MariaDB 10.4</p>
              <div class="mt-2 w-full bg-slate-200 rounded-full h-1.5">
                <div class="bg-brand-600 h-1.5 rounded-full" style="width: 28%"></div>
              </div>
              <p class="text-[11px] text-slate-400 mt-1">CPU: 28% • RAM: 4.2 / 16 GB</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
              <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-slate-500">Web Server Apache</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              </div>
              <p class="text-lg font-bold text-slate-800 mt-2">Apache 2.4 / PHP 8.2</p>
              <div class="mt-2 w-full bg-slate-200 rounded-full h-1.5">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 35%"></div>
              </div>
              <p class="text-[11px] text-slate-400 mt-1">Latency: 14ms • Requests: 340/s</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
              <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-slate-500">Bộ Lưu Trữ PACS/DICOM</span>
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
              </div>
              <p class="text-lg font-bold text-slate-800 mt-2">NAS Storage 12 TB</p>
              <div class="mt-2 w-full bg-slate-200 rounded-full h-1.5">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: 62%"></div>
              </div>
              <p class="text-[11px] text-slate-400 mt-1">Đã dùng: 7.4 TB / 12 TB (62%)</p>
            </div>
          </div>
        </div>
      </section>
