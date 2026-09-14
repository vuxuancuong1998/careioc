<?php require_once 'header.php'; ?>
<main class="content-body">
  <section class="page-section">
    <div class="section-title-bar">
      <div class="section-title"><i class="fa-solid fa-flask-vial"></i> Đồng bộ dữ liệu LIS</div>
    </div>
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fa-solid fa-plug-circle-check"></i> Kiểm tra kết nối hệ thống LIS</div>
      </div>
      <div class="card-body">
        <p style="margin-top:0;color:var(--text-muted)">Hệ thống kiểm tra phiên đăng nhập trước. Khi phiên hết hạn, nút đồng bộ sẽ tự đăng nhập bằng dòng <code>lis_api_code = login</code> rồi lấy dữ liệu từ các URL LIS đang hoạt động.</p>
        <div class="form-grid" style="margin-bottom:16px">
          <div class="form-group">
            <label class="form-label" for="lisFromDate">Từ ngày *</label>
            <input type="date" class="form-control" id="lisFromDate" value="<?php echo htmlspecialchars($fromDate, ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="lisToDate">Đến ngày *</label>
            <input type="date" class="form-control" id="lisToDate" value="<?php echo htmlspecialchars($toDate, ENT_QUOTES, 'UTF-8'); ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Mã đơn vị</label>
            <input type="text" class="form-control" value="<?php echo (int) $hospitalCode; ?>" readonly aria-label="Mã đơn vị lấy từ ioc_config">
          </div>
        </div>
        <div class="form-actions" style="justify-content:flex-start">
          <button type="button" class="btn btn-outline" id="checkLisStatus"><i class="fa-solid fa-heart-pulse"></i> Kiểm tra / đăng nhập LIS</button>
          <button type="button" class="btn btn-primary" id="syncLis"><i class="fa-solid fa-arrows-rotate"></i> Đồng bộ LIS</button>
        </div>
        <div id="lisMessage" role="status" aria-live="polite" style="display:none;margin-top:16px;padding:12px 14px;border-radius:8px"></div>
        <div class="form-group" style="margin-top:18px">
          <label class="form-label" for="lisResult">Dữ liệu LIS nhận được (JSON)</label>
          <textarea id="lisResult" class="form-control" rows="18" readonly placeholder="Dữ liệu nhận được sẽ hiển thị ở đây để kiểm tra."></textarea>
        </div>
      </div>
    </div>
  </section>
</main>
<script src="<?php echo XC_URL; ?>/template/eoffice/assets/js/jquery-3.6.0.min.js"></script>
<script>
// Luu token CSRF do backend tao de moi AJAX request duoc xac thuc.
var lisCsrf = <?php echo json_encode($csrf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
// Hien thi thong bao ngan gon voi mau phu hop ket qua xu ly.
function showLisMessage(message, success) {
  // Lay phan tu thong bao mot lan cho moi lan cap nhat.
  var box = $('#lisMessage');
  // Gan noi dung bang text de du lieu tu API khong the chen HTML.
  box.text(message);
  // Hien thi va dung mau thanh cong/that bai cua giao dien hien tai.
  box.css({ display: 'block', background: success ? 'rgba(16,185,129,.12)' : 'rgba(239,68,68,.12)', color: success ? 'var(--success)' : 'var(--danger)', border: '1px solid ' + (success ? 'var(--success)' : 'var(--danger)') });
}
// Khoa nut trong luc request de tranh nguoi dung bam dong bo trung lap.
function setLisLoading(loading) {
  // Cap nhat ca hai nut de chi mot request LIS duoc chay tai mot thoi diem.
  $('#checkLisStatus, #syncLis').prop('disabled', loading);
}
// Kiem tra phien LIS va tu dang nhap neu phien chua ton tai hoac da het han.
function checkLisStatus() {
  // Khoa thao tac trong luc AJAX dang chay.
  setLisLoading(true);
  // Gui request jQuery POST dung luong apiController, kem CSRF va yeu cau tu dang nhap khi can.
  $.ajax({ url: '<?php echo XC_URL; ?>/api/externalSystemStatus', type: 'POST', dataType: 'json', data: { system: 'lis', csrf: lisCsrf, login_if_needed: 1 } })
    .done(function (response) {
      // Hien thi tong quan de nguoi dung biet phien da duoc khoi tao thanh cong.
      showLisMessage(response.message, response.success && response.data && response.data.logged_in);
      // Hien thi chi tiet cau hinh/phien trong textarea de de kiem tra.
      $('#lisResult').val(JSON.stringify(response.data || {}, null, 2));
    })
    .fail(function (xhr) {
      // Khong hien thi loi ky thuat chi tiet; uu tien thong bao co the xu ly.
      showLisMessage((xhr.responseJSON && xhr.responseJSON.message) || 'Không thể kiểm tra trạng thái LIS.', false);
    })
    .always(function () {
      // Mo lai nut sau khi request ket thuc bat ke thanh cong hay that bai.
      setLisLoading(false);
    });
}
// Dong bo: client tu kiem tra token va tu dang nhap lai khi token da het han.
function syncLis() {
  // Lay gia tri ngay tu form de thay vao tham so tuNgay va denNgay cua lis_url_api.
  var fromDate = $('#lisFromDate').val();
  // Lay gia tri ngay ket thuc tu form de thay vao tham so denNgay cua lis_url_api.
  var toDate = $('#lisToDate').val();
  // Chan khoang ngay nguoc ngay tai trinh duyet truoc khi goi server.
  if (!fromDate || !toDate || fromDate > toDate) {
    showLisMessage('Vui lòng chọn khoảng từ ngày đến ngày hợp lệ.', false);
    return;
  }
  // Khoa thao tac trong luc API ngoai dang duoc goi.
  setLisLoading(true);
  // Gui request AJAX den apiController, khong tai lai trang.
  $.ajax({ url: '<?php echo XC_URL; ?>/api/syncExternalData', type: 'POST', dataType: 'json', data: { system: 'lis', csrf: lisCsrf, from_date: fromDate, to_date: toDate } })
    .done(function (response) {
      // Dua payload da loc thong tin dang nhap vao textarea de nguoi dung doi chieu.
      $('#lisResult').val(JSON.stringify(response.data || {}, null, 2));
      // Thong bao ket qua dong bo de nguoi dung biet endpoint nao can xu ly them.
      showLisMessage(response.message, response.success);
    })
    .fail(function (xhr) {
      // Hien thi loi nghiep vu an toan neu request bi tu choi hoac mat ket noi.
      showLisMessage((xhr.responseJSON && xhr.responseJSON.message) || 'Không thể đồng bộ dữ liệu LIS.', false);
    })
    .always(function () {
      // Mo lai nut cho lan thao tac tiep theo.
      setLisLoading(false);
    });
}
// Gan handler sau khi template da render day du.
$('#checkLisStatus').on('click', checkLisStatus);
// Gan handler dong bo cho nut chinh.
$('#syncLis').on('click', syncLis);
// Tu kiem tra trang thai ngay khi vao man hinh.
checkLisStatus();
</script>
<?php require_once 'footer.php'; ?>
