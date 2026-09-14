<?php
class backendController extends baseController
{
    public function index()
    {
		$this->view->data['select_title'] = '30 ngày qua';
		$this->view->data['countcus'] = 0;
		$this->view->data['countorder'] = 0;
		$this->view->data['sumdeposite'] = 0;
		$this->view->data['sumorder'] = 0;
		$this->view->data['orders'] = array();
        $this->view->backendtmp('index');
    }

    // Hien thi man hinh kiem tra ket noi va dong bo du lieu LIS.
    public function lisIntegration()
    {
        global $db;
        // Dung token CSRF rieng cho cac thao tac goi he thong ben ngoai.
        if (empty($_SESSION['ioc_external_sync_csrf'])) {
            $_SESSION['ioc_external_sync_csrf'] = bin2hex(random_bytes(24));
        }
        // Lay ma don vi de nguoi dung kiem tra gia tri se duoc dua vao URL LIS.
        $db->query('SELECT hospital_code FROM ioc_config WHERE hospital_code > 0 ORDER BY id ASC LIMIT 1');
        $config = $db->fetch_object(true);
        // Chuyen token cho template de jQuery gui kem moi AJAX request.
        $this->view->data['csrf'] = $_SESSION['ioc_external_sync_csrf'];
        // Gan ngay mac dinh tu dau thang den hom nay de thao tac nhanh nhung van cho phep chinh sua.
        $this->view->data['fromDate'] = date('Y-m-01');
        // Gan ngay ket thuc mac dinh la ngay hien tai.
        $this->view->data['toDate'] = date('Y-m-d');
        // Chi dua ma don vi ra giao dien, khong dua thong tin dang nhap LIS.
        $this->view->data['hospitalCode'] = $config ? (int) $config->hospital_code : 0;
        // Render trong layout backend de nguoi dung co thong bao va thao tac nhat quan.
        $this->view->backendtmp('lis-integration');
    }

    public function outpatient($para = array())
    {
        global $db;
        if (isset($para[1]) && $para[1] === 'template') { $this->downloadOutpatientTemplate(); return; }
        if (isset($para[1]) && $para[1] === 'add') { $this->outpatientForm(null); return; }
        if (isset($para[1]) && $para[1] === 'edit') { $this->outpatientForm(isset($para[2]) ? (int) $para[2] : 0); return; }
        if (empty($_SESSION['outpatient_csrf'])) $_SESSION['outpatient_csrf'] = bin2hex(random_bytes(24));
        $flash = array('type' => '', 'message' => '');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hash_equals($_SESSION['outpatient_csrf'], isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
                $flash = array('type' => 'error', 'message' => 'Phiên làm việc đã hết hạn. Vui lòng thử lại.');
            } elseif ($_POST['action'] === 'save') {
                $result = $this->saveOutpatient($_POST);
                $flash = array('type' => $result['ok'] ? 'success' : 'error', 'message' => $result['message']);
            } elseif ($_POST['action'] === 'delete') {
                $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
                if ($id) { $db->query("DELETE FROM ioc_outpatient_daily WHERE id = $id"); $flash = array('type' => 'success', 'message' => 'Đã xóa bản ghi.'); }
            } elseif ($_POST['action'] === 'import') {
                $result = $this->importOutpatient(isset($_FILES['import_file']) ? $_FILES['import_file'] : null);
                $flash = array('type' => $result['ok'] ? 'success' : 'error', 'message' => $result['message']);
            }
        }
        $where = array('1=1');
        foreach (array('from_date' => '>=', 'to_date' => '<=') as $field => $operator) if (!empty($_GET[$field]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET[$field])) $where[] = "o.report_date $operator '" . $db->escapestring($_GET[$field]) . ($field === 'to_date' ? " 23:59:59'" : " 00:00:00'");
        if (!empty($_GET['department_id'])) $where[] = 'o.department_id=' . (int) $_GET['department_id'];
        if (!empty($_GET['payer_type_id'])) $where[] = 'o.payer_type_id=' . (int) $_GET['payer_type_id'];
        $db->query('SELECT o.*, d.department_name, p.payer_type_name FROM ioc_outpatient_daily o JOIN ioc_departments d ON d.id=o.department_id JOIN ioc_payer_types p ON p.id=o.payer_type_id WHERE ' . implode(' AND ', $where) . ' ORDER BY o.report_date DESC, d.department_name');
        $records = $db->fetch_object();
        $db->query('SELECT id, department_code, department_name FROM ioc_departments WHERE department_status=1 ORDER BY department_name'); $departments = $db->fetch_object();
        $db->query('SELECT id, payer_type_code, payer_type_name FROM ioc_payer_types WHERE payer_type_status=1 ORDER BY payer_type_sort_order, payer_type_name'); $payerTypes = $db->fetch_object();
        $this->view->data = array('records' => is_array($records) ? $records : array(), 'departments' => is_array($departments) ? $departments : array(), 'payerTypes' => is_array($payerTypes) ? $payerTypes : array(), 'flash' => $flash, 'csrf' => $_SESSION['outpatient_csrf']);
        $this->view->backendtmp('outpatient');
    }

    private function outpatientForm($id)
    {
        global $db;
        if (empty($_SESSION['outpatient_csrf'])) $_SESSION['outpatient_csrf'] = bin2hex(random_bytes(24));
        $flash = array('type' => '', 'message' => '');
        if (!$id && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'import') {
            if (!hash_equals($_SESSION['outpatient_csrf'], isset($_POST['csrf']) ? $_POST['csrf'] : '')) $flash = array('type' => 'error', 'message' => 'Phiên làm việc đã hết hạn. Vui lòng thử lại.');
            else { $result = $this->importOutpatient(isset($_FILES['import_file']) ? $_FILES['import_file'] : null); $flash = array('type' => $result['ok'] ? 'success' : 'error', 'message' => $result['message']); }
        }
        $record = null;
        if ($id) { $db->query("SELECT * FROM ioc_outpatient_daily WHERE id=$id"); $record = $db->fetch_object(true); if (!$record) { header('Location: ' . XC_URL . '/backend/outpatient'); exit; } }
        $db->query('SELECT id, department_name FROM ioc_departments WHERE department_status=1 ORDER BY department_name'); $departments = $db->fetch_object();
        $db->query('SELECT id, payer_type_name FROM ioc_payer_types WHERE payer_type_status=1 ORDER BY payer_type_sort_order, payer_type_name'); $payerTypes = $db->fetch_object();
        $this->view->data = array('record' => $record, 'departments' => is_array($departments) ? $departments : array(), 'payerTypes' => is_array($payerTypes) ? $payerTypes : array(), 'csrf' => $_SESSION['outpatient_csrf'], 'flash' => $flash);
        $this->view->backendtmp('outpatient-form');
    }

    public function downloadOutpatientTemplate()
    {
        global $db;
        $db->query('SELECT department_code, department_name FROM ioc_departments WHERE department_status=1 ORDER BY department_name'); $departments = $db->fetch_object();
        $db->query('SELECT payer_type_code, payer_type_name FROM ioc_payer_types WHERE payer_type_status=1 ORDER BY payer_type_sort_order, payer_type_name'); $payers = $db->fetch_object();
        if (!class_exists('ZipArchive')) { http_response_code(500); echo 'Máy chủ chưa hỗ trợ tạo tệp Excel.'; return; }
        $xml = function($value) { return htmlspecialchars((string)$value, ENT_XML1 | ENT_QUOTES, 'UTF-8'); };
        $cell = function($column, $row, $value) use ($xml) { return '<c r="' . $column . $row . '" t="inlineStr"><is><t>' . $xml($value) . '</t></is></c>'; };
        $inputHeaders = array('Ngày báo cáo','Khoa/phòng','Đối tượng thanh toán','Tổng lượt khám','Lượt tái khám','Đang chờ khám','Đang được khám','Đã hoàn thành','Thời gian chờ TB (phút)','Chuyển tuyến');
        $sheet1 = '<row r="1">'; foreach ($inputHeaders as $index => $header) $sheet1 .= $cell(chr(65 + $index), 1, $header); $sheet1 .= '</row>';
        $sheet2 = '<row r="1">' . $cell('A', 1, 'Mã khoa/phòng') . $cell('B', 1, 'Tên khoa/phòng') . $cell('D', 1, 'Mã đối tượng thanh toán') . $cell('E', 1, 'Tên đối tượng thanh toán') . '</row>';
        $max = max(count($departments ?: array()), count($payers ?: array()));
        for ($i = 0; $i < $max; $i++) { $row = $i + 2; $sheet2 .= '<row r="' . $row . '">'; if (isset($departments[$i])) { $sheet2 .= $cell('A', $row, $departments[$i]->department_code) . $cell('B', $row, $departments[$i]->department_name); } if (isset($payers[$i])) { $sheet2 .= $cell('D', $row, $payers[$i]->payer_type_code) . $cell('E', $row, $payers[$i]->payer_type_name); } $sheet2 .= '</row>'; }
        $book = new ZipArchive(); $path = tempnam(sys_get_temp_dir(), 'outpatient_template_'); $book->open($path, ZipArchive::OVERWRITE);
        $book->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $book->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $book->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Nhap lieu" sheetId="1" r:id="rId1"/><sheet name="Danh muc" sheetId="2" r:id="rId2"/></sheets></workbook>');
        $book->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet2.xml"/></Relationships>');
        $book->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $sheet1 . '</sheetData></worksheet>');
        $book->addFromString('xl/worksheets/sheet2.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $sheet2 . '</sheetData></worksheet>'); $book->close();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); header('Content-Disposition: attachment; filename="mau_import_ngoai_tru.xlsx"'); header('Content-Length: ' . filesize($path)); readfile($path); unlink($path); exit;
    }

    private function saveOutpatient($input)
    {
        global $db;
        $date = isset($input['report_date']) ? trim($input['report_date']) : ''; $departmentId = isset($input['department_id']) ? (int) $input['department_id'] : 0; $payerTypeId = isset($input['payer_type_id']) ? (int) $input['payer_type_id'] : 0;
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4)) || !$departmentId || !$payerTypeId) return array('ok' => false, 'message' => 'Vui lòng nhập ngày, khoa/phòng và đối tượng thanh toán hợp lệ.');
        $db->query("SELECT (SELECT COUNT(*) FROM ioc_departments WHERE id=$departmentId AND department_status=1) department_ok, (SELECT COUNT(*) FROM ioc_payer_types WHERE id=$payerTypeId AND payer_type_status=1) payer_ok"); $references = $db->fetch_object(true);
        if (!$references || !$references->department_ok || !$references->payer_ok) return array('ok' => false, 'message' => 'Khoa/phòng hoặc đối tượng thanh toán không hợp lệ.');
        $columns = array('visit_count', 'revisit_count', 'waiting_count', 'examining_count', 'completed_count', 'referral_count'); $set = "report_date='" . $db->escapestring($date . " 00:00:00") . "', department_id=$departmentId, payer_type_id=$payerTypeId";
        foreach ($columns as $column) { $value = isset($input[$column]) ? filter_var($input[$column], FILTER_VALIDATE_INT) : 0; if ($value === false || $value < 0) return array('ok' => false, 'message' => 'Các chỉ tiêu số lượng phải là số nguyên không âm.'); $set .= ", $column=" . (int) $value; }
        $wait = isset($input['avg_wait_minutes']) && $input['avg_wait_minutes'] !== '' ? filter_var($input['avg_wait_minutes'], FILTER_VALIDATE_FLOAT) : null; if ($wait !== null && ($wait === false || $wait < 0)) return array('ok' => false, 'message' => 'Thời gian chờ trung bình phải là số không âm.'); $set .= ', avg_wait_minutes=' . ($wait === null ? 'NULL' : number_format($wait, 2, '.', ''));
        $id = isset($input['id']) ? (int) $input['id'] : 0; $db->query($id ? "UPDATE ioc_outpatient_daily SET $set WHERE id=$id" : "INSERT INTO ioc_outpatient_daily SET $set"); return array('ok' => true, 'message' => $id ? 'Đã cập nhật số liệu.' : 'Đã ghi nhận số liệu.');
    }

    private function importOutpatient($file)
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] < 1 || $file['size'] > 5 * 1024 * 1024) return array('ok' => false, 'message' => 'Chọn tệp .xlsx/.csv không quá 5 MB.');
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); if (!in_array($extension, array('csv', 'xlsx'), true)) return array('ok' => false, 'message' => 'Chỉ hỗ trợ .xlsx và .csv.');
        $rows = $extension === 'csv' ? $this->readCsv($file['tmp_name']) : $this->readXlsx($file['tmp_name']); if (count($rows) < 2) return array('ok' => false, 'message' => 'Tệp không có dữ liệu hợp lệ.');
        $headers = array_map(array($this, 'importHeader'), array_shift($rows)); foreach (array('report_date', 'department_id', 'payer_type_id') as $field) if (!in_array($field, $headers, true)) return array('ok' => false, 'message' => "Thiếu cột bắt buộc: $field.");
        $success = 0; $errors = array(); foreach ($rows as $line => $row) { if (!array_filter($row, function($value) { return trim((string) $value) !== ''; })) continue; $input = array_combine($headers, array_pad($row, count($headers), '')); $input['report_date'] = $this->importDate($input['report_date']); $input['department_id'] = $this->importReference('ioc_departments', 'department_name', 'department_code', $input['department_id']); $input['payer_type_id'] = $this->importReference('ioc_payer_types', 'payer_type_name', 'payer_type_code', $input['payer_type_id']); $result = $this->saveOutpatient($input); if ($result['ok']) $success++; else $errors[] = 'dòng ' . ($line + 2) . ': ' . $result['message']; }
        return array('ok' => $success > 0, 'message' => "Đã import $success bản ghi" . ($errors ? '. Có ' . count($errors) . ' dòng lỗi: ' . implode('; ', array_slice($errors, 0, 10)) : '.') );
    }
    private function readCsv($path) { $handle = fopen($path, 'r'); if (!$handle) return array(); $rows = array(); while (($row = fgetcsv($handle, 0, ',')) !== false) $rows[] = array_map(function($v) { return preg_replace('/^\xEF\xBB\xBF/', '', trim($v)); }, $row); fclose($handle); return $rows; }
    private function readXlsx($path) { if (!class_exists('ZipArchive')) return array(); $zip = new ZipArchive(); if ($zip->open($path) !== true) return array(); $shared = array(); if ($xml = $zip->getFromName('xl/sharedStrings.xml')) { $doc = simplexml_load_string($xml); foreach ($doc->si as $item) $shared[] = (string) $item->t ?: implode('', $item->r->t); } $sheet = $zip->getFromName('xl/worksheets/sheet1.xml'); $zip->close(); if (!$sheet) return array(); $doc = simplexml_load_string($sheet); $doc->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'); $rows = array(); foreach ($doc->xpath('//x:sheetData/x:row') as $row) { $values = array(); $position = 0; foreach ($row->c as $cell) { $letters = preg_replace('/\d+/', '', (string) $cell['r']); $column = 0; foreach (str_split($letters) as $letter) $column = $column * 26 + ord($letter) - 64; while ($position < $column - 1) { $values[] = ''; $position++; } $type = (string) $cell['t']; $raw = (string) $cell->v; if ($type === 's' && isset($shared[(int) $raw])) $value = $shared[(int) $raw]; elseif ($type === 'inlineStr') $value = (string) $cell->is->t; else $value = $raw; $values[] = $value; $position++; } $rows[] = $values; } return $rows; }
    private function importHeader($header) { $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', strtolower(trim((string) $header))); $text = trim(preg_replace('/[^a-z0-9]+/', '_', $text), '_'); $map = array('ngay' => 'report_date', 'ngay_bao_cao' => 'report_date', 'report_date' => 'report_date', 'khoa_phong' => 'department_id', 'department' => 'department_id', 'department_id' => 'department_id', 'doi_tuong_thanh_toan' => 'payer_type_id', 'payer_type' => 'payer_type_id', 'payer_type_id' => 'payer_type_id', 'tong_luot_kham' => 'visit_count', 'luot_kham' => 'visit_count', 'luot_tai_kham' => 'revisit_count', 'tai_kham' => 'revisit_count', 'dang_cho_kham' => 'waiting_count', 'dang_cho' => 'waiting_count', 'dang_duoc_kham' => 'examining_count', 'dang_kham' => 'examining_count', 'da_hoan_thanh' => 'completed_count', 'hoan_thanh' => 'completed_count', 'thoi_gian_cho_tb_phut' => 'avg_wait_minutes', 'thoi_gian_cho_tb' => 'avg_wait_minutes', 'chuyen_tuyen' => 'referral_count'); return isset($map[$text]) ? $map[$text] : $text; }
    private function importDate($value) { if (is_numeric($value) && $value > 25569) return gmdate('Y-m-d', ((int) $value - 25569) * 86400); $time = strtotime(str_replace('/', '-', trim((string) $value))); return $time ? date('Y-m-d', $time) : ''; }
    private function importReference($table, $name, $code, $value) { global $db; if (is_numeric($value)) return (int) $value; $value = $db->escapestring(trim((string) $value)); $db->query("SELECT id FROM $table WHERE $name='$value' OR $code='$value' LIMIT 1"); $row = $db->fetch_object(true); return $row ? (int) $row->id : 0; }
}
