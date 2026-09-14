<?php require_once 'header.php'; ?>
      <!-- Nội dung Body chia thành từng Section -->
      <main class="content-body">

        <!-- ==========================================
             SECTION 1: TỔNG QUAN CHỈ SỐ NHANH (KPIS)
        =========================================== -->
        <section class="page-section" id="section-metrics">
          <div class="section-title-bar">
            <div class="section-title">
              <i class="fa-solid fa-chart-simple"></i>
              Section 1: Chỉ Số Vận Hành Trong Ngày
            </div>
            <span style="font-size: 12.5px; color: var(--text-muted);"><i class="fa-regular fa-clock"></i> Cập nhật thời gian thực</span>
          </div>

          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-meta">
                <div class="stat-label">Tổng lượt tiếp nhận</div>
                <div class="stat-value">1,248</div>
              </div>
              <div class="stat-icon-badge stat-blue"><i class="fa-solid fa-users"></i></div>
            </div>

            <div class="stat-card">
              <div class="stat-meta">
                <div class="stat-label">Đang khám tại buồng</div>
                <div class="stat-value">342</div>
              </div>
              <div class="stat-icon-badge stat-green"><i class="fa-solid fa-stethoscope"></i></div>
            </div>

            <div class="stat-card">
              <div class="stat-meta">
                <div class="stat-label">Ưu tiên & Cấp cứu</div>
                <div class="stat-value">28</div>
              </div>
              <div class="stat-icon-badge stat-red"><i class="fa-solid fa-truck-medical"></i></div>
            </div>

            <div class="stat-card">
              <div class="stat-meta">
                <div class="stat-label">Đã liên thông BHYT</div>
                <div class="stat-value">99.4%</div>
              </div>
              <div class="stat-icon-badge stat-orange"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            </div>
          </div>
        </section>


        <!-- ==========================================
             SECTION 2: FORM NHẬP LIỆU & TIẾP NHẬN SỐ
        =========================================== -->
        <section class="page-section" id="section-registration">
          <div class="section-title-bar">
            <div class="section-title">
              <i class="fa-solid fa-file-medical"></i>
              Section 2: Tiếp Nhận & Phân Luồng Khám Chữa Bệnh
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <i class="fa-solid fa-hospital-user"></i>
                Phiếu Đăng Ký Hồ Sơ Bệnh Nhân Mới
              </div>
              <span class="badge badge-info"><i class="fa-solid fa-bolt"></i> Tự động kiểm tra thẻ BHYT trực tuyến</span>
            </div>
            <div class="card-body">
              <form id="healthForm" onsubmit="event.preventDefault(); simulateProcess('Đang lưu thông tin bệnh án');">
                
                <!-- Khối A: Thông tin định danh -->
                <div class="form-block">
                  <div class="form-block-title">
                    <i class="fa-regular fa-id-card"></i> Khối A: Định Danh & Thông Tin Hành Chính
                  </div>
                  <div class="form-grid">
                    <div class="form-group">
                      <label class="form-label">Mã thẻ BHYT / Số CCCD <span class="req">*</span></label>
                      <div class="input-icon-wrapper">
                        <i class="fa-solid fa-barcode"></i>
                        <input type="text" class="form-control" placeholder="Nhập 15 ký tự hoặc quét mã..." required value="DN4797921820491">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Họ và tên người bệnh <span class="req">*</span></label>
                      <div class="input-icon-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" class="form-control" placeholder="NGUYỄN VĂN A" required value="TRẦN VĂN MINH">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Ngày sinh <span class="req">*</span></label>
                      <input type="date" class="form-control" required value="1988-08-20">
                    </div>

                    <div class="form-group">
                      <label class="form-label">Giới tính sinh học</label>
                      <div class="radio-group">
                        <label class="check-item"><input type="radio" name="gender" value="nam" checked> Nam</label>
                        <label class="check-item"><input type="radio" name="gender" value="nu"> Nữ</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Khối B: Chỉ định đối tượng & Phòng khám -->
                <div class="form-block">
                  <div class="form-block-title">
                    <i class="fa-solid fa-clinic-medical"></i> Khối B: Đối Tượng & Buồng Khám Chỉ Định
                  </div>
                  <div class="form-grid">
                    <div class="form-group">
                      <label class="form-label">Loại đối tượng khám <span class="req">*</span></label>
                      <select class="form-select">
                        <option value="1" selected>BHYT Đúng Tuyến (100%)</option>
                        <option value="2">BHYT Trái Tuyến</option>
                        <option value="3">Viện Phí (Khám Theo Yêu Cầu)</option>
                        <option value="4">Khám Sức Khỏe Cơ Quan</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="form-label">Buồng khám chỉ định <span class="req">*</span></label>
                      <select class="form-select">
                        <option value="101">Phòng 101 - Khám Cấp Cứu Ban Đầu</option>
                        <option value="102" selected>Phòng 102 - Khoa Khám Nội Tổng Quát</option>
                        <option value="105">Phòng 105 - Khám Chuyên Khoa Nhi</option>
                        <option value="201">Phòng 201 - Chuyên Khoa Tim Mạch</option>
                      </select>
                    </div>

                    <div class="form-group full-width">
                      <label class="form-label">Tiêu chí phân luồng ưu tiên</label>
                      <div class="checkbox-group">
                        <label class="check-item"><input type="checkbox" checked> Cấp cứu khẩn</label>
                        <label class="check-item"><input type="checkbox"> Người cao tuổi (&gt; 75t)</label>
                        <label class="check-item"><input type="checkbox"> Phụ nữ mang thai</label>
                        <label class="check-item"><input type="checkbox"> Khám lại theo hẹn</label>
                      </div>
                    </div>

                    <div class="form-group full-width">
                      <label class="form-label">Triệu chứng lâm sàng ban đầu</label>
                      <textarea class="form-control" rows="2" placeholder="Nhập lý do đến khám, tiền sử dị ứng nếu có..."></textarea>
                    </div>
                  </div>
                </div>

                <!-- Thao tác Form -->
                <div class="form-actions">
                  <button type="reset" class="btn btn-outline">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Làm mới
                  </button>
                  <button type="button" class="btn btn-outline" onclick="simulateProcess('Đang in phiếu chỉ định khám')">
                    <i class="fa-solid fa-print"></i> In phiếu khám
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Lưu & Chuyển buồng khám
                  </button>
                </div>
              </form>
            </div>
          </div>
        </section>


        <!-- ==========================================
             SECTION 3: BỘ LỌC & TÌM KIẾM DỮ LIỆU
        =========================================== -->
        <section class="page-section" id="section-filter">
          <div class="section-title-bar">
            <div class="section-title">
              <i class="fa-solid fa-filter"></i>
              Section 3: Bộ Lọc & Tra Cứu Bệnh Nhân
            </div>
          </div>

          <div class="filter-card">
            <div class="filter-grid">
              <div class="input-icon-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" class="form-control" placeholder="Tìm theo Mã Tiếp Nhận, Họ Tên, hoặc Số BHYT...">
              </div>

              <select class="form-select">
                <option value="">-- Tất cả buồng khám --</option>
                <option value="102" selected>Phòng 102 - Khoa Nội</option>
                <option value="105">Phòng 105 - Khoa Nhi</option>
              </select>

              <select class="form-select">
                <option value="">-- Trạng thái khám --</option>
                <option value="waiting">Chờ khám</option>
                <option value="in_progress">Đang khám</option>
                <option value="completed">Đã hoàn thành</option>
              </select>

              <button class="btn btn-primary" onclick="simulateProcess('Đang áp dụng bộ lọc tra cứu')">
                <i class="fa-solid fa-filter-circle-check"></i> Lọc dữ liệu
              </button>
            </div>
          </div>
        </section>


        <!-- ==========================================
             SECTION 4: DANH SÁCH & ĐIỀU PHỐI (DATA TABLE)
        =========================================== -->
        <section class="page-section" id="section-table">
          <div class="section-title-bar">
            <div class="section-title">
              <i class="fa-solid fa-table-list"></i>
              Section 4: Danh Sách Bệnh Nhân Chờ Khám & Thao Tác
            </div>
            <div style="display: flex; gap: 8px;">
              <button class="btn btn-outline btn-sm" onclick="simulateProcess('Đang đồng bộ dữ liệu HIS')">
                <i class="fa-solid fa-arrows-rotate"></i> Đồng bộ HIS
              </button>
              <button class="btn btn-success btn-sm" onclick="simulateProcess('Đang kết xuất bảng kê Excel')">
                <i class="fa-solid fa-file-excel"></i> Xuất Excel
              </button>
            </div>
          </div>

          <div class="card">
            <div class="card-body" style="padding: 0;">
              <div class="table-container">
                <table class="custom-table">
                  <thead>
                    <tr>
                      <th style="width: 50px;">STT</th>
                      <th>Mã Lượt Khám</th>
                      <th>Họ Và Tên</th>
                      <th>Năm Sinh</th>
                      <th>Phân Loại</th>
                      <th>Thời Gian Tiếp Nhận</th>
                      <th>Trạng Thái</th>
                      <th style="text-align: center; width: 130px;">Hành Động</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>01</td>
                      <td><strong>#TN-89201</strong></td>
                      <td>Trần Văn Minh</td>
                      <td>1985</td>
                      <td><span class="badge badge-info">BHYT 100%</span></td>
                      <td>08:15:22 - Hôm nay</td>
                      <td><span class="badge badge-warning"><i class="fa-regular fa-clock"></i> Đang chờ khám</span></td>
                      <td>
                        <div class="action-btns" style="justify-content: center;">
                          <button class="btn-icon" title="Phát loa gọi số" onclick="simulateProcess('Đang kích hoạt phát loa gọi số')"><i class="fa-solid fa-bullhorn"></i></button>
                          <button class="btn-icon" title="Cập nhật thông tin"><i class="fa-regular fa-pen-to-square"></i></button>
                          <button class="btn-icon delete" title="Hủy lượt tiếp nhận"><i class="fa-regular fa-trash-can"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>02</td>
                      <td><strong>#TN-89202</strong></td>
                      <td>Nguyễn Thị Thanh Hà</td>
                      <td>1972</td>
                      <td><span class="badge badge-info">BHYT 80%</span></td>
                      <td>08:20:10 - Hôm nay</td>
                      <td><span class="badge badge-success"><i class="fa-solid fa-stethoscope"></i> Đang thực hiện</span></td>
                      <td>
                        <div class="action-btns" style="justify-content: center;">
                          <button class="btn-icon" title="Chỉ định CLS"><i class="fa-solid fa-vial-virus"></i></button>
                          <button class="btn-icon" title="Cập nhật thông tin"><i class="fa-regular fa-pen-to-square"></i></button>
                          <button class="btn-icon delete" title="Hủy lượt tiếp nhận"><i class="fa-regular fa-trash-can"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>03</td>
                      <td><strong>#TN-89203</strong></td>
                      <td>Lê Bảo Khang</td>
                      <td>2021</td>
                      <td><span class="badge badge-danger">Ưu tiên cấp cứu</span></td>
                      <td>08:25:40 - Hôm nay</td>
                      <td><span class="badge badge-danger"><i class="fa-solid fa-triangle-exclamation"></i> Chuyển khẩn</span></td>
                      <td>
                        <div class="action-btns" style="justify-content: center;">
                          <button class="btn-icon" title="Phát loa gọi số"><i class="fa-solid fa-bullhorn"></i></button>
                          <button class="btn-icon" title="Cập nhật thông tin"><i class="fa-regular fa-pen-to-square"></i></button>
                          <button class="btn-icon delete" title="Hủy lượt tiếp nhận"><i class="fa-regular fa-trash-can"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Phân trang Table -->
              <div class="pagination-wrapper">
                <div>Hiển thị <strong>1 - 3</strong> trong tổng số <strong>48</strong> lượt khám tại buồng 102</div>
                <div class="pagination-nav">
                  <button class="page-btn"><i class="fa-solid fa-angle-left"></i></button>
                  <button class="page-btn active">1</button>
                  <button class="page-btn">2</button>
                  <button class="page-btn">3</button>
                  <button class="page-btn"><i class="fa-solid fa-angle-right"></i></button>
                </div>
              </div>
            </div>
          </div>
        </section>

      </main>
    </div>
  </div>
<?php require_once 'footer.php'; ?>