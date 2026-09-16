<?php
/**
 * Project: thuvien.
 * File: tourController.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 09:54 - 07/10/2016
 * Website: www.xiao.vn
 */
Class apiController extends baseController
{ 
    public function index()
    {
		
    }
	// ======================== SỐ LIỆU KHÁM NGOẠI TRÚ ========================
	public function saveOutpatient() { $this->outpatientSave(false); }
	public function editOutpatient() { $this->outpatientSave(true); }

	// Xóa bản ghi ngoại trú bằng SQL trực tiếp.
	public function deleteOutpatient()
	{
		global $db; $result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if (!$this->authorizeOutpatient($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
		if ($id < 1) $result['message'] = 'Bản ghi cần xóa không hợp lệ.';
		else { $db->query("SELECT id FROM ioc_outpatient_daily WHERE id = $id LIMIT 1"); if (!$db->fetch_object(true)) $result['message'] = 'Bản ghi không tồn tại hoặc đã được xóa.'; else { $db->query("DELETE FROM ioc_outpatient_daily WHERE id = $id"); $result['success'] = true; $result['message'] = 'Đã xóa số liệu ngoại trú.'; } }
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	// Import CSV/XLSX, map full_date sang ioc_date.id và báo lỗi theo từng dòng.
	public function importOutpatient()
	{
		global $db; $result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if (!$this->authorizeOutpatient($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$file = isset($_FILES['import_file']) ? $_FILES['import_file'] : null; $extension = $file ? strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) : '';
		if (!$file || $file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name']) || $file['size'] < 1 || $file['size'] > 5 * 1024 * 1024 || !in_array($extension, array('csv', 'xlsx'), true)) { $result['message'] = 'Vui lòng chọn tệp .xlsx hoặc .csv không quá 5 MB.'; echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$rows = $extension === 'csv' ? $this->readOutpatientCsv($file['tmp_name']) : $this->readOutpatientXlsx($file['tmp_name']);
		if (count($rows) < 2) { $result['message'] = 'Tệp chưa có dòng dữ liệu hợp lệ hoặc máy chủ không hỗ trợ XLSX.'; echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$headers = array_map(array($this, 'normalizeOutpatientHeader'), array_shift($rows));
		foreach (array('full_date', 'department_id', 'payer_type_id') as $required) if (!in_array($required, $headers, true)) { $result['message'] = "Thiếu cột bắt buộc: $required."; echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$success = 0; $errors = array();
		foreach ($rows as $index => $row) { $line = $index + 2; if (!array_filter($row, function ($value) { return trim((string) $value) !== ''; })) continue; if (count($row) > count($headers)) { $errors[] = "Dòng $line: số cột vượt quá tiêu đề."; continue; } $input = array_combine($headers, array_pad($row, count($headers), '')); $fullDate = $this->parseOutpatientDate($input['full_date']); if (!$fullDate) { $errors[] = "Dòng $line: full_date không hợp lệ."; continue; } $db->query("SELECT id FROM ioc_date WHERE full_date = '" . $db->escapestring($fullDate) . "' LIMIT 1"); $date = $db->fetch_object(true); if (!$date) { $errors[] = "Dòng $line: full_date $fullDate chưa có trong ioc_date."; continue; } $input['report_date'] = (int) $date->id; $input['department_id'] = $this->resolveOutpatientDepartment($input['department_id']); $input['payer_type_id'] = $this->resolveOutpatientPayer($input['payer_type_id']); $save = $this->outpatientSave(false, $input, true); if ($save['success']) $success++; else $errors[] = "Dòng $line: " . $save['message']; }
		$result['success'] = $success > 0 && !$errors; $result['message'] = "Đã import $success bản ghi." . ($errors ? ' Chi tiết lỗi: ' . implode(' | ', $errors) : ''); $result['data'] = array('imported' => $success, 'errors' => $errors); echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	// Validate dữ liệu và INSERT/UPDATE bằng Database của hệ thống.
	private function outpatientSave($isEdit, $input = null, $isImport = false)
	{
		global $db; $result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if ($input === null) { if (!$this->authorizeOutpatient($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return $result; } $input = $_POST; }
		$dateId = isset($input['report_date']) ? (int) $input['report_date'] : 0; $departmentId = isset($input['department_id']) ? (int) $input['department_id'] : 0; $payerId = isset($input['payer_type_id']) ? (int) $input['payer_type_id'] : 0;
		$db->query("SELECT full_date FROM ioc_date WHERE id = $dateId LIMIT 1"); $date = $db->fetch_object(true); $db->query("SELECT id FROM ioc_departments WHERE id = $departmentId AND department_status = 1 LIMIT 1"); $department = $db->fetch_object(true); $db->query("SELECT id FROM ioc_payer_types WHERE id = $payerId AND payer_type_status = 1 LIMIT 1"); $payer = $db->fetch_object(true);
		if (!$date || !$department || !$payer) $result['message'] = 'Ngày, khoa/phòng hoặc đối tượng thanh toán không hợp lệ.'; elseif (!$isImport && substr($date->full_date, 0, 7) !== date('Y-m')) $result['message'] = 'Nhập thủ công chỉ áp dụng cho ngày trong tháng hiện tại.'; else {
			$set = array("report_date = $dateId", "department_id = $departmentId", "payer_type_id = $payerId"); $valid = true; foreach (array('visit_count','revisit_count','waiting_count','examining_count','completed_count','referral_count') as $field) { $value = isset($input[$field]) && $input[$field] !== '' ? filter_var($input[$field], FILTER_VALIDATE_INT) : 0; if ($value === false || $value < 0 || $value > 4294967295) { $valid = false; break; } $set[] = "$field = " . (int) $value; }
			$wait = isset($input['avg_wait_minutes']) && $input['avg_wait_minutes'] !== '' ? filter_var($input['avg_wait_minutes'], FILTER_VALIDATE_FLOAT) : null; if ($wait !== null && ($wait === false || !is_finite($wait) || $wait < 0 || $wait > 99999999.99)) $valid = false; $set[] = 'avg_wait_minutes = ' . ($wait === null ? 'NULL' : number_format((float) $wait, 2, '.', '')); $id = isset($input['id']) ? (int) $input['id'] : 0;
			if (!$valid) $result['message'] = 'Các chỉ tiêu phải là số không âm và nằm trong giới hạn cho phép.'; else { $db->query("SELECT id FROM ioc_outpatient_daily WHERE report_date = $dateId AND department_id = $departmentId AND payer_type_id = $payerId AND id <> $id LIMIT 1"); $duplicate = $db->fetch_object(true); if ($duplicate) $result['message'] = 'Đã có số liệu cho ngày, khoa/phòng và đối tượng thanh toán này.'; elseif ($isEdit && $id < 1) $result['message'] = 'Không xác định được bản ghi cần sửa.'; else { if ($id) { $db->query("SELECT id FROM ioc_outpatient_daily WHERE id = $id LIMIT 1"); if (!$db->fetch_object(true)) $result['message'] = 'Bản ghi không tồn tại hoặc đã bị xóa.'; } if ($result['message'] === 'Không thể xử lý yêu cầu.') { if ($id) $db->query("UPDATE ioc_outpatient_daily SET " . implode(', ', $set) . " WHERE id = $id"); else $db->query("INSERT INTO ioc_outpatient_daily SET " . implode(', ', $set)); $result['success'] = true; $result['message'] = $isEdit ? 'Cập nhật số liệu ngoại trú thành công.' : 'Thêm số liệu ngoại trú thành công.'; $result['data'] = array('id' => $id, 'full_date' => $date->full_date); } } }
		}
		if ($input !== $_POST || $isImport) return $result; echo json_encode($result, JSON_UNESCAPED_UNICODE); return $result;
	}

	// Kiểm tra đăng nhập, POST và CSRF trước khi thay đổi dữ liệu.
	private function authorizeOutpatient(&$result)
	{
		header('Content-Type: application/json; charset=utf-8'); if (empty($_SESSION['user']['id']) && empty($_SESSION['staff']['id'])) { http_response_code(401); $result['message'] = 'Vui lòng đăng nhập lại để tiếp tục.'; return false; } if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); $result['message'] = 'Chỉ hỗ trợ yêu cầu POST.'; return false; } if (empty($_SESSION['outpatient_csrf']) || !isset($_POST['csrf']) || !hash_equals($_SESSION['outpatient_csrf'], (string) $_POST['csrf'])) { http_response_code(419); $result['message'] = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.'; return false; } return true;
	}

	private function resolveOutpatientDepartment($value) { global $db; $value = trim((string) $value); $db->query("SELECT id FROM ioc_departments WHERE department_status = 1 AND (department_code = '" . $db->escapestring($value) . "' OR department_name = '" . $db->escapestring($value) . "') LIMIT 1"); $row = $db->fetch_object(true); return $row ? (int) $row->id : 0; }
	private function resolveOutpatientPayer($value) { global $db; $value = trim((string) $value); $db->query("SELECT id FROM ioc_payer_types WHERE payer_type_status = 1 AND (payer_type_code = '" . $db->escapestring($value) . "' OR payer_type_name = '" . $db->escapestring($value) . "') LIMIT 1"); $row = $db->fetch_object(true); return $row ? (int) $row->id : 0; }
	private function normalizeOutpatientHeader($header) { $text = strtolower(trim((string) $header)); $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text); $text = trim(preg_replace('/[^a-zA-Z0-9_]+/', '_', $ascii !== false ? $ascii : $text), '_'); $map = array('full_date'=>'full_date','ngay'=>'full_date','report_date'=>'full_date','khoa_phong'=>'department_id','department'=>'department_id','department_id'=>'department_id','doi_tuong_thanh_toan'=>'payer_type_id','payer_type'=>'payer_type_id','payer_type_id'=>'payer_type_id','tong_luot_kham'=>'visit_count','luot_kham'=>'visit_count','luot_tai_kham'=>'revisit_count','tai_kham'=>'revisit_count','dang_cho_kham'=>'waiting_count','dang_cho'=>'waiting_count','dang_duoc_kham'=>'examining_count','dang_kham'=>'examining_count','da_hoan_thanh'=>'completed_count','hoan_thanh'=>'completed_count','thoi_gian_cho_tb_phut'=>'avg_wait_minutes','thoi_gian_cho_tb'=>'avg_wait_minutes','chuyen_tuyen'=>'referral_count'); return isset($map[$text]) ? $map[$text] : $text; }
	private function parseOutpatientDate($value) { $value = trim((string) $value); if (is_numeric($value) && $value > 25569 && $value < 2958466) return gmdate('Y-m-d', ((int) $value - 25569) * 86400); foreach (array('!Y-m-d','!d/m/Y','!d-m-Y') as $format) { $date = DateTimeImmutable::createFromFormat($format, $value); $errors = DateTimeImmutable::getLastErrors(); if ($date && (!$errors || (!$errors['warning_count'] && !$errors['error_count']))) return $date->format('Y-m-d'); } return ''; }
	private function readOutpatientCsv($path) { $handle = fopen($path, 'r'); if (!$handle) return array(); $rows = array(); while (($row = fgetcsv($handle, 0, ',')) !== false) $rows[] = array_map(function ($value) { return preg_replace('/^\xEF\xBB\xBF/', '', trim($value)); }, $row); fclose($handle); return $rows; }
	private function readOutpatientXlsx($path) { if (!class_exists('ZipArchive')) return array(); $zip = new ZipArchive(); if ($zip->open($path) !== true) return array(); $shared = array(); $xml = $zip->getFromName('xl/sharedStrings.xml'); if ($xml) { $doc = simplexml_load_string($xml); foreach ($doc->si as $item) $shared[] = (string) $item->t; } $sheet = $zip->getFromName('xl/worksheets/sheet1.xml'); $zip->close(); if (!$sheet) return array(); $doc = simplexml_load_string($sheet); if (!$doc) return array(); $doc->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'); $rows = array(); foreach ($doc->xpath('//x:sheetData/x:row') as $row) { $values = array(); $position = 0; foreach ($row->c as $cell) { $letters = preg_replace('/\d+/', '', (string) $cell['r']); $column = 0; foreach (str_split($letters) as $letter) $column = $column * 26 + ord($letter) - 64; while ($position < $column - 1) { $values[] = ''; $position++; } $raw = (string) $cell->v; $values[] = (string) $cell['t'] === 's' && isset($shared[(int) $raw]) ? $shared[(int) $raw] : ((string) $cell['t'] === 'inlineStr' ? (string) $cell->is->t : $raw); $position++; } $rows[] = $values; } return $rows; }

	// ======================== SỐ LIỆU ĐIỀU TRỊ NỘI TRÚ ========================
	public function saveInpatient()
	{
		$this->inpatientSave(false);
	}

	public function editInpatient()
	{
		$this->inpatientSave(true);
	}

	public function deleteInpatient()
	{
		global $db;
		$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if (!$this->authorizeInpatient($result)) {
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
		if ($id < 1) {
			$result['message'] = 'Bản ghi cần xóa không hợp lệ.';
		} else {
			$db->query("SELECT id FROM ioc_inpatient_daily WHERE id = $id AND inpatient_daily_status = 1 LIMIT 1");
			$record = $db->fetch_object(true);
			if (!$record) {
				$result['message'] = 'Bản ghi không tồn tại hoặc đã được xóa.';
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				return;
			}
			$db->query("UPDATE ioc_inpatient_daily SET inpatient_daily_status = 99 WHERE id = $id AND inpatient_daily_status = 1");
			$result['success'] = true;
			$result['message'] = 'Đã xóa bản ghi nội trú.';
		}
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function importInpatient()
	{
		global $db;
		$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if (!$this->authorizeInpatient($result)) {
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		$file = isset($_FILES['import_file']) ? $_FILES['import_file'] : null;
		if (!$file || $file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name']) || $file['size'] < 1 || $file['size'] > 5 * 1024 * 1024) {
			$result['message'] = 'Vui lòng chọn tệp .xlsx hoặc .csv không quá 5 MB.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$rows = $extension === 'csv' ? $this->readInpatientCsv($file['tmp_name']) : ($extension === 'xlsx' ? $this->readInpatientXlsx($file['tmp_name']) : array());
		if (count($rows) < 2) {
			$result['message'] = 'Tệp chưa có dòng dữ liệu hợp lệ hoặc máy chủ không hỗ trợ định dạng XLSX.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		$headers = array_map(array($this, 'normalizeInpatientHeader'), array_shift($rows));
		if (count(array_unique($headers)) !== count($headers)) {
			$result['message'] = 'Tệp có tiêu đề cột trùng nhau.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		foreach (array('full_date', 'inpatient_department_id', 'payer_type_id') as $required) {
			if (!in_array($required, $headers, true)) {
				$result['message'] = "Thiếu cột bắt buộc: $required.";
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				return;
			}
		}
		$success = 0;
		$errors = array();
		foreach ($rows as $index => $row) {
			$line = $index + 2;
			if (!array_filter($row, function ($value) { return trim((string) $value) !== ''; })) continue;
			if (count($row) > count($headers)) {
				$errors[] = "Dòng $line: số cột vượt quá tiêu đề.";
				continue;
			}
			$input = array_combine($headers, array_pad($row, count($headers), ''));
			$fullDate = $this->parseInpatientDate(isset($input['full_date']) ? $input['full_date'] : '');
			if (!$fullDate) {
				$errors[] = "Dòng $line: full_date không đúng định dạng YYYY-MM-DD hoặc DD/MM/YYYY.";
				continue;
			}
			$db->query("SELECT id FROM ioc_date WHERE full_date = '" . $db->escapestring($fullDate) . "' LIMIT 1");
			$date = $db->fetch_object(true);
			if (!$date) {
				$errors[] = "Dòng $line: full_date $fullDate chưa có trong danh mục ioc_date.";
				continue;
			}
			$input['inpatient_report_date'] = (int) $date->id;
			$departmentValue = trim((string) $input['inpatient_department_id']);
			$db->query("SELECT id FROM ioc_departments WHERE department_status = 1 AND (department_code = '" . $db->escapestring($departmentValue) . "' OR department_name = '" . $db->escapestring($departmentValue) . "') LIMIT 1");
			$department = $db->fetch_object(true);
			if (!$department) {
				$errors[] = "Dòng $line: khoa điều trị '$departmentValue' không tồn tại hoặc đã ngừng sử dụng.";
				continue;
			}
			$input['inpatient_department_id'] = (int) $department->id;
			$input['payer_type_id'] = $this->resolveInpatientPayer($input['payer_type_id']);
			if (!$input['payer_type_id']) { $errors[] = "Dòng $line: đối tượng khám chữa bệnh không hợp lệ."; continue; }
			$input['import_line'] = $line;
			$save = $this->inpatientSave(false, $input, true);
			if ($save['success']) $success++; else $errors[] = "Dòng $line: " . $save['message'];
		}
		$result['success'] = $success > 0 && !$errors;
		$result['message'] = "Đã import $success bản ghi." . ($errors ? ' Chi tiết lỗi: ' . implode(' | ', $errors) : '');
		$result['data'] = array('imported' => $success, 'errors' => $errors);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	private function inpatientSave($isEdit, $input = null, $isImport = false)
	{
		global $db;
		if ($input === null) {
			$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
			if (!$this->authorizeInpatient($result)) {
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				return $result;
			}
			$input = $_POST;
		} else {
			$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		}
		$dateId = isset($input['inpatient_report_date']) ? (int) $input['inpatient_report_date'] : 0;
		$departmentId = isset($input['inpatient_department_id']) ? (int) $input['inpatient_department_id'] : 0;
		$payerTypeId = isset($input['payer_type_id']) ? (int) $input['payer_type_id'] : 0;
		if ($dateId < 1 || $departmentId < 1 || $payerTypeId < 1) {
			$result['message'] = 'Vui lòng chọn ngày ghi nhận, khoa điều trị và đối tượng khám chữa bệnh.';
			if ($input !== $_POST || $isImport) return $result;
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return $result;
		}
		$db->query("SELECT full_date FROM ioc_date WHERE id = $dateId LIMIT 1");
		$date = $db->fetch_object(true);
			$db->query("SELECT id, department_bed FROM ioc_departments WHERE id = $departmentId AND department_status = 1 LIMIT 1");
			$department = $db->fetch_object(true);
			$db->query("SELECT id FROM ioc_payer_types WHERE id = $payerTypeId AND payer_type_status = 1 LIMIT 1");
			$payer = $db->fetch_object(true);
			if (!$date || !$department || !$payer) {
			$result['message'] = 'Ngày ghi nhận, khoa điều trị hoặc đối tượng khám chữa bệnh không còn hợp lệ.';
		} else {
			$fields = array('inpatient_opening_patient_count','inpatient_admission_count','inpatient_inpatient_daily_discharge_count','inpatient_transfer_count','inpatient_hospital_transfer_count','inpatient_death_count','inpatient_occupied_beds');
			$set = array("inpatient_report_date = $dateId", "inpatient_department_id = $departmentId", "payer_type_id = $payerTypeId", 'inpatient_daily_status = 1');
			$valid = true;
			foreach ($fields as $field) {
				$value = $this->parseInpatientNonNegativeInt(isset($input[$field]) ? $input[$field] : '');
				if ($value === false || $value < 0 || $value > 4294967295) { $valid = false; break; }
				$set[] = "$field = " . (int) $value;
			}
			$opening = isset($input['inpatient_opening_patient_count']) ? (int) $input['inpatient_opening_patient_count'] : 0;
			$admission = isset($input['inpatient_admission_count']) ? (int) $input['inpatient_admission_count'] : 0;
			$discharge = isset($input['inpatient_inpatient_daily_discharge_count']) ? (int) $input['inpatient_inpatient_daily_discharge_count'] : 0;
			$transfer = isset($input['inpatient_transfer_count']) ? (int) $input['inpatient_transfer_count'] : 0;
			$hospitalTransfer = isset($input['inpatient_hospital_transfer_count']) ? (int) $input['inpatient_hospital_transfer_count'] : 0;
			$death = isset($input['inpatient_death_count']) ? (int) $input['inpatient_death_count'] : 0;
			$closing = $opening + $admission + $transfer - $discharge - $hospitalTransfer - $death;
			if ($closing < 0) $valid = false;
			$set[] = 'inpatient_closing_patient_count = ' . max(0, $closing);
			// Tổng ngày điều trị không còn nhập trên form/import; bản ghi mới dùng giá trị mặc định 0.
			$treatmentDays = isset($input['inpatient_treatment_days']) && $input['inpatient_treatment_days'] !== '' ? max(0, (int) $input['inpatient_treatment_days']) : 0;
			$set[] = 'inpatient_treatment_days = ' . $treatmentDays;
			$avgStay = $discharge > 0 ? $treatmentDays / $discharge : null;
			$actualBeds = max(0, (int) $department->department_bed);
			$occupiedBeds = (int) $input['inpatient_occupied_beds'];
			$occupancy = $actualBeds > 0 ? ($occupiedBeds / $actualBeds) * 100 : null;
			if ($occupiedBeds > $actualBeds && $actualBeds > 0) $valid = false;
			$set[] = 'inpatient_actual_beds = ' . $actualBeds;
			foreach (array('bed_occupancy_percent' => 100000, 'avg_length_of_stay' => 99999999.99) as $field => $max) {
				$value = $field === 'bed_occupancy_percent' ? $occupancy : $avgStay;
				if ($value !== null && ($value === false || !is_finite($value) || $value < 0 || $value > $max)) { $valid = false; break; }
				$set[] = "$field = " . ($value === null ? 'NULL' : number_format((float) $value, 2, '.', ''));
			}
			if (!$valid) {
				$result['message'] = 'Các chỉ tiêu phải là số không âm và nằm trong giới hạn cho phép.';
			} else {
				$id = isset($input['id']) ? (int) $input['id'] : 0;
				if ($isEdit && $id < 1) $result['message'] = 'Không xác định được bản ghi cần sửa.';
				else {
					if ($id) { $db->query("SELECT id FROM ioc_inpatient_daily WHERE id = $id AND inpatient_daily_status = 1 LIMIT 1"); if (!$db->fetch_object(true)) $result['message'] = 'Bản ghi không tồn tại hoặc đã bị xóa.'; }
					$db->query("SELECT id, inpatient_daily_status FROM ioc_inpatient_daily WHERE inpatient_report_date = $dateId AND inpatient_department_id = $departmentId AND payer_type_id = $payerTypeId AND id <> $id LIMIT 1");
					$duplicate = $db->fetch_object(true);
					if ($duplicate && (int) $duplicate->inpatient_daily_status === 1) $result['message'] = 'Đã có số liệu cho ngày, khoa và đối tượng này.';
					elseif ($duplicate && (int) $duplicate->inpatient_daily_status === 99) $id = (int) $duplicate->id;
					if (empty($result['message']) || $result['message'] === 'Không thể xử lý yêu cầu.') {
						if ($id) $db->query("UPDATE ioc_inpatient_daily SET " . implode(', ', $set) . " WHERE id = $id");
						else $db->query("INSERT INTO ioc_inpatient_daily SET " . implode(', ', $set));
						$result['success'] = true;
						$result['message'] = $isEdit ? 'Cập nhật số liệu nội trú thành công.' : 'Thêm số liệu nội trú thành công.';
						$result['data'] = array('id' => $id ?: 0, 'full_date' => $date->full_date);
					}
				}
			}
		}
		if ($input !== $_POST || $isImport) return $result;
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		return $result;
	}

	private function parseInpatientNonNegativeInt($value)
	{
		$value = trim((string) $value);
		if ($value === '') return 0;
		if (!preg_match('/^\d+$/', $value)) return false;
		$value = ltrim($value, '0');
		if ($value === '') return 0;
		if (strlen($value) > 10 || (strlen($value) === 10 && strcmp($value, '4294967295') > 0)) return false;
		return (int) $value;
	}

	private function authorizeInpatient(&$result)
	{
		header('Content-Type: application/json; charset=utf-8');
		if (empty($_SESSION['user']['id']) && empty($_SESSION['staff']['id'])) { http_response_code(401); $result['message'] = 'Vui lòng đăng nhập lại để tiếp tục.'; return false; }
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); $result['message'] = 'Chỉ hỗ trợ yêu cầu POST.'; return false; }
		if (empty($_SESSION['inpatient_csrf']) || !isset($_POST['csrf']) || !hash_equals($_SESSION['inpatient_csrf'], (string) $_POST['csrf'])) { http_response_code(419); $result['message'] = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.'; return false; }
		return true;
	}

	private function normalizeInpatientHeader($header)
	{
		$text = strtolower(trim((string) $header));
		$text = strtr($text, array('à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a','đ'=>'d','è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e','ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i','ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o','ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u','ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y'));
		$text = trim(preg_replace('/[^a-z0-9]+/', '_', $text), '_');
		$map = array('ngay'=>'full_date','ngay_ghi_nhan'=>'full_date','report_date'=>'full_date','inpatient_report_date'=>'full_date','khoa'=>'inpatient_department_id','khoa_dieu_tri'=>'inpatient_department_id','department'=>'inpatient_department_id','doi_tuong_kcb'=>'payer_type_id','doi_tuong_kham_chua_benh'=>'payer_type_id','payer_type_id'=>'payer_type_id','so_giuong_thuc_ke'=>'inpatient_actual_beds','giuong_thuc_ke'=>'inpatient_actual_beds','nguoi_benh_dau_ngay'=>'inpatient_opening_patient_count','nhap_vien'=>'inpatient_admission_count','ra_vien'=>'inpatient_inpatient_daily_discharge_count','chuyen_khoa'=>'inpatient_transfer_count','chuyen_vien'=>'inpatient_hospital_transfer_count','tu_vong'=>'inpatient_death_count','giuong_dang_su_dung'=>'inpatient_occupied_beds');
		return isset($map[$text]) ? $map[$text] : $text;
	}

	private function resolveInpatientPayer($value)
	{
		global $db;
		$value = trim((string) $value);
		$db->query("SELECT id FROM ioc_payer_types WHERE payer_type_status = 1 AND (payer_type_code = '" . $db->escapestring($value) . "' OR payer_type_name = '" . $db->escapestring($value) . "') LIMIT 1");
		$row = $db->fetch_object(true);
		return $row ? (int) $row->id : (is_numeric($value) ? (int) $value : 0);
	}

	private function parseInpatientDate($value)
	{
		$value = trim((string) $value);
		if (is_numeric($value) && $value > 25569 && $value < 2958466) return gmdate('Y-m-d', ((int) $value - 25569) * 86400);
		foreach (array('!Y-m-d','!d/m/Y','!d-m-Y') as $format) { $date = DateTimeImmutable::createFromFormat($format, $value); $errors = DateTimeImmutable::getLastErrors(); if ($date && (!$errors || (!$errors['warning_count'] && !$errors['error_count']))) return $date->format('Y-m-d'); }
		return '';
	}

	private function readInpatientCsv($path)
	{
		$handle = fopen($path, 'r'); if (!$handle) return array(); $rows = array(); while (($row = fgetcsv($handle, 0, ',')) !== false) $rows[] = array_map(function ($value) { return preg_replace('/^\xEF\xBB\xBF/', '', trim($value)); }, $row); fclose($handle); return $rows;
	}

	private function readInpatientXlsx($path)
	{
		if (!class_exists('ZipArchive')) return array(); $zip = new ZipArchive(); if ($zip->open($path) !== true) return array(); $shared = array(); $xml = $zip->getFromName('xl/sharedStrings.xml'); if ($xml) { $doc = simplexml_load_string($xml); foreach ($doc->si as $item) $shared[] = (string) $item->t; } $sheet = $zip->getFromName('xl/worksheets/sheet1.xml'); $zip->close(); if (!$sheet) return array(); $doc = simplexml_load_string($sheet); if (!$doc) return array(); $doc->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'); $rows = array(); foreach ($doc->xpath('//x:sheetData/x:row') as $row) { $values = array(); $position = 0; foreach ($row->c as $cell) { $letters = preg_replace('/\d+/', '', (string) $cell['r']); $column = 0; foreach (str_split($letters) as $letter) $column = $column * 26 + ord($letter) - 64; while ($position < $column - 1) { $values[] = ''; $position++; } $raw = (string) $cell->v; $values[] = (string) $cell['t'] === 's' && isset($shared[(int) $raw]) ? $shared[(int) $raw] : ((string) $cell['t'] === 'inlineStr' ? (string) $cell->is->t : $raw); $position++; } $rows[] = $values; } return $rows;
	}

	// ======================== BÁO CÁO SỐ LIỆU XÉT NGHIỆM ========================
	public function saveLisReport() { $this->lisReportSave(false); }
	public function editLisReport() { $this->lisReportSave(true); }

	public function deleteLisReport()
	{
		global $db;
		$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if (!$this->authorizeLisReport($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
		if ($id < 1) {
			$result['message'] = 'Bản ghi cần xóa không hợp lệ.';
		} else {
			$db->query("SELECT id FROM ioc_lis_daily WHERE id = $id LIMIT 1");
			if (!$db->fetch_object(true)) {
				$result['message'] = 'Bản ghi không tồn tại hoặc đã được xóa.';
			} else {
				$db->query("DELETE FROM ioc_lis_daily WHERE id = $id");
				$result['success'] = true;
				$result['message'] = 'Đã xóa số liệu xét nghiệm.';
			}
		}
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function importLisReport()
	{
		global $db;
		$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if (!$this->authorizeLisReport($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$file = isset($_FILES['import_file']) ? $_FILES['import_file'] : null;
		$extension = $file ? strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) : '';
		if (!$file || $file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name']) || $file['size'] < 1 || $file['size'] > 5 * 1024 * 1024 || !in_array($extension, array('csv', 'xlsx'), true)) {
			$result['message'] = 'Vui lòng chọn tệp .xlsx hoặc .csv không quá 5 MB.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE); return;
		}
		$rows = $extension === 'csv' ? $this->readInpatientCsv($file['tmp_name']) : $this->readInpatientXlsx($file['tmp_name']);
		if (count($rows) < 2) {
			$result['message'] = 'Tệp chưa có dòng dữ liệu hợp lệ hoặc máy chủ không hỗ trợ XLSX.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE); return;
		}
		$headers = array_map(array($this, 'normalizeLisReportHeader'), array_shift($rows));
		if (count(array_unique($headers)) !== count($headers)) {
			$result['message'] = 'Tệp có tiêu đề cột trùng nhau.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE); return;
		}
		foreach (array('full_date', 'lis_group_code') as $required) {
			if (!in_array($required, $headers, true)) {
				$result['message'] = "Thiếu cột bắt buộc: $required.";
				echo json_encode($result, JSON_UNESCAPED_UNICODE); return;
			}
		}
		$success = 0; $errors = array();
		foreach ($rows as $index => $row) {
			$line = $index + 2;
			if (!array_filter($row, function ($value) { return trim((string) $value) !== ''; })) continue;
			if (count($row) > count($headers)) { $errors[] = "Dòng $line: số cột vượt quá tiêu đề."; continue; }
			$input = array_combine($headers, array_pad($row, count($headers), ''));
			$fullDate = $this->parseInpatientDate(isset($input['full_date']) ? $input['full_date'] : '');
			if (!$fullDate) { $errors[] = "Dòng $line: ngày ghi nhận không đúng định dạng YYYY-MM-DD hoặc DD/MM/YYYY."; continue; }
			$db->query("SELECT id FROM ioc_date WHERE full_date = '" . $db->escapestring($fullDate) . "' LIMIT 1");
			$date = $db->fetch_object(true);
			if (!$date) { $errors[] = "Dòng $line: ngày $fullDate chưa có trong danh mục ngày."; continue; }
			$input['report_date'] = (int) $date->id;
			$groupValue = isset($input['lis_group_code']) ? $input['lis_group_code'] : '';
			$input['lis_group_code'] = $this->resolveLisReportCategory($groupValue);
			if (!$input['lis_group_code']) { $errors[] = "Dòng $line: nhóm xét nghiệm '$groupValue' không tồn tại hoặc đã ngừng hoạt động."; continue; }
			$save = $this->lisReportSave(false, $input, true);
			if ($save['success']) $success++; else $errors[] = "Dòng $line: " . $save['message'];
		}
		$result['success'] = $success > 0 && !$errors;
		$result['message'] = "Đã import $success bản ghi." . ($errors ? ' Chi tiết lỗi: ' . implode(' | ', array_slice($errors, 0, 20)) . (count($errors) > 20 ? ' | ...' : '') : '');
		$result['data'] = array('imported' => $success, 'errors' => $errors);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	private function lisReportSave($isEdit, $input = null, $isImport = false)
	{
		global $db;
		$result = array('success' => false, 'message' => 'Không thể xử lý yêu cầu.', 'data' => array());
		if ($input === null) {
			if (!$this->authorizeLisReport($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return $result; }
			$input = $_POST;
		}
		$dateId = isset($input['report_date']) ? (int) $input['report_date'] : 0;
		$groupCode = isset($input['lis_group_code']) && ctype_digit(trim((string) $input['lis_group_code'])) ? (int) $input['lis_group_code'] : 0;
		if ($dateId < 1 || $groupCode < 1 || $groupCode > 2147483647) {
			$result['message'] = 'Vui lòng chọn ngày và nhập mã nhóm xét nghiệm hợp lệ.';
			if ($isImport) return $result; echo json_encode($result, JSON_UNESCAPED_UNICODE); return $result;
		}
		$db->query("SELECT full_date FROM ioc_date WHERE id = $dateId LIMIT 1");
		$date = $db->fetch_object(true);
		$db->query("SELECT id FROM ioc_lis_categories WHERE id = $groupCode AND lis_category_status = 1 LIMIT 1");
		$category = $db->fetch_object(true);
		if (!$date || !$category) {
			$result['message'] = 'Ngày ghi nhận hoặc nhóm xét nghiệm không còn hợp lệ.';
		} else {
			$set = array("report_date = $dateId", "lis_group_code = $groupCode");
			$valid = true;
			foreach (array('lis_bhyt_count','inpatient_lis_count','outpatient_lis_count','lis_self_pay_count') as $field) {
				$value = $this->parseInpatientNonNegativeInt(isset($input[$field]) ? $input[$field] : '');
				if ($value === false || $value > 4294967295) { $valid = false; break; }
				$set[] = "$field = " . (int) $value;
			}
			$note = isset($input['note']) ? trim((string) $input['note']) : '';
			$noteLength = function_exists('mb_strlen') ? mb_strlen($note, 'UTF-8') : strlen($note);
			if ($noteLength > 5000) $valid = false;
			$set[] = "note = '" . $db->escapestring($note) . "'";
			if (!$valid) {
				$result['message'] = 'Các chỉ tiêu phải là số nguyên không âm; ghi chú tối đa 5.000 ký tự.';
			} else {
				$id = isset($input['id']) ? (int) $input['id'] : 0;
				if ($isEdit && $id < 1) $result['message'] = 'Không xác định được bản ghi cần sửa.';
				else {
					if ($id) { $db->query("SELECT id FROM ioc_lis_daily WHERE id = $id LIMIT 1"); if (!$db->fetch_object(true)) $result['message'] = 'Bản ghi không tồn tại hoặc đã bị xóa.'; }
					$db->query("SELECT id FROM ioc_lis_daily WHERE report_date = $dateId AND lis_group_code = $groupCode AND id <> $id LIMIT 1");
					if ($db->fetch_object(true)) $result['message'] = 'Đã có số liệu cho ngày và nhóm xét nghiệm này.';
					if ($result['message'] === 'Không thể xử lý yêu cầu.') {
						if ($id) $db->query("UPDATE ioc_lis_daily SET " . implode(', ', $set) . " WHERE id = $id");
						else $db->query("INSERT INTO ioc_lis_daily SET " . implode(', ', $set));
						$result['success'] = true;
						$result['message'] = $isEdit ? 'Cập nhật số liệu xét nghiệm thành công.' : 'Thêm số liệu xét nghiệm thành công.';
						$result['data'] = array('id' => $id, 'full_date' => $date->full_date, 'lis_group_code' => $groupCode);
					}
				}
			}
		}
		if ($isImport) return $result;
		echo json_encode($result, JSON_UNESCAPED_UNICODE); return $result;
	}

	private function authorizeLisReport(&$result)
	{
		header('Content-Type: application/json; charset=utf-8');
		if (empty($_SESSION['user']['id']) && empty($_SESSION['staff']['id'])) { http_response_code(401); $result['message'] = 'Vui lòng đăng nhập lại để tiếp tục.'; return false; }
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); $result['message'] = 'Chỉ hỗ trợ yêu cầu POST.'; return false; }
		if (empty($_SESSION['lis_report_csrf']) || !isset($_POST['csrf']) || !hash_equals($_SESSION['lis_report_csrf'], (string) $_POST['csrf'])) { http_response_code(419); $result['message'] = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.'; return false; }
		return true;
	}

	private function resolveLisReportCategory($value)
	{
		global $db;
		$value = trim((string) $value);
		if ($value === '') return 0;
		$escaped = $db->escapestring($value);
		$idCondition = ctype_digit($value) ? 'id = ' . (int) $value . ' OR ' : '';
		$db->query("SELECT id FROM ioc_lis_categories WHERE lis_category_status = 1 AND ($idCondition category_lis_code = '$escaped' OR lis_category_name = '$escaped') ORDER BY id LIMIT 1");
		$category = $db->fetch_object(true);
		return $category ? (int) $category->id : 0;
	}

	private function normalizeLisReportHeader($header)
	{
		$text = trim((string) $header);
		$text = strtr($text, array('à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a','đ'=>'d','è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e','ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i','ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o','ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u','ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y'));
		$text = strtolower($text);
		$text = trim(preg_replace('/[^a-z0-9_]+/', '_', $text), '_');
		$map = array(
			'ngay' => 'full_date', 'ngay_ghi_nhan' => 'full_date', 'report_date' => 'full_date', 'full_date' => 'full_date',
			'ma_nhom_xet_nghiem' => 'lis_group_code', 'nhom_xet_nghiem' => 'lis_group_code', 'lis_group_code' => 'lis_group_code',
			'xet_nghiem_bhyt' => 'lis_bhyt_count', 'bhyt' => 'lis_bhyt_count', 'lis_bhyt_count' => 'lis_bhyt_count',
			'xet_nghiem_noi_tru' => 'inpatient_lis_count', 'noi_tru' => 'inpatient_lis_count', 'inpatient_lis_count' => 'inpatient_lis_count',
			'xet_nghiem_ngoai_tru' => 'outpatient_lis_count', 'ngoai_tru' => 'outpatient_lis_count', 'outpatient_lis_count' => 'outpatient_lis_count',
			'xet_nghiem_vien_phi' => 'lis_self_pay_count', 'vien_phi' => 'lis_self_pay_count', 'lis_self_pay_count' => 'lis_self_pay_count',
			'ghi_chu' => 'note', 'note' => 'note'
		);
		return isset($map[$text]) ? $map[$text] : $text;
	}

	// ======================== BÁO CÁO SỐ LIỆU CĐHA ========================
	public function saveRisReport() { $this->risReportSave(false); }
	public function editRisReport() { $this->risReportSave(true); }

	public function deleteRisReport()
	{
		global $db;
		$result = array('success'=>false,'message'=>'Không thể xử lý yêu cầu.','data'=>array());
		if (!$this->authorizeRisReport($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
		if ($id < 1) $result['message'] = 'Bản ghi cần xóa không hợp lệ.';
		else {
			$db->query("SELECT id FROM ioc_ris_daily WHERE id = $id LIMIT 1");
			if (!$db->fetch_object(true)) $result['message'] = 'Bản ghi không tồn tại hoặc đã được xóa.';
			else { $db->query("DELETE FROM ioc_ris_daily WHERE id = $id"); $result['success'] = true; $result['message'] = 'Đã xóa số liệu CĐHA.'; }
		}
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function importRisReport()
	{
		global $db;
		$result = array('success'=>false,'message'=>'Không thể xử lý yêu cầu.','data'=>array());
		if (!$this->authorizeRisReport($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$file = isset($_FILES['import_file']) ? $_FILES['import_file'] : null;
		$extension = $file ? strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) : '';
		if (!$file || $file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name']) || $file['size'] < 1 || $file['size'] > 5 * 1024 * 1024 || !in_array($extension,array('csv','xlsx'),true)) {
			$result['message'] = 'Vui lòng chọn tệp .xlsx hoặc .csv không quá 5 MB.'; echo json_encode($result, JSON_UNESCAPED_UNICODE); return;
		}
		$rows = $extension === 'csv' ? $this->readInpatientCsv($file['tmp_name']) : $this->readInpatientXlsx($file['tmp_name']);
		if (count($rows) < 2) { $result['message'] = 'Tệp chưa có dòng dữ liệu hợp lệ hoặc máy chủ không hỗ trợ XLSX.'; echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$headers = array_map(array($this,'normalizeRisReportHeader'),array_shift($rows));
		if (count(array_unique($headers)) !== count($headers)) { $result['message'] = 'Tệp có tiêu đề cột trùng nhau.'; echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		foreach (array('full_date','ris_group_code') as $required) if (!in_array($required,$headers,true)) { $result['message'] = "Thiếu cột bắt buộc: $required."; echo json_encode($result, JSON_UNESCAPED_UNICODE); return; }
		$success = 0; $errors = array();
		foreach ($rows as $index=>$row) {
			$line = $index + 2;
			if (!array_filter($row,function($value){return trim((string)$value)!=='';})) continue;
			if (count($row)>count($headers)) { $errors[] = "Dòng $line: số cột vượt quá tiêu đề."; continue; }
			$input = array_combine($headers,array_pad($row,count($headers),''));
			$fullDate = $this->parseInpatientDate(isset($input['full_date'])?$input['full_date']:'');
			if (!$fullDate) { $errors[] = "Dòng $line: ngày ghi nhận không đúng định dạng YYYY-MM-DD hoặc DD/MM/YYYY."; continue; }
			$db->query("SELECT id FROM ioc_date WHERE full_date = '".$db->escapestring($fullDate)."' LIMIT 1"); $date = $db->fetch_object(true);
			if (!$date) { $errors[] = "Dòng $line: ngày $fullDate chưa có trong danh mục ngày."; continue; }
			$input['report_date'] = (int)$date->id;
			$groupValue = isset($input['ris_group_code']) ? $input['ris_group_code'] : '';
			$input['ris_group_code'] = $this->resolveRisReportCategory($groupValue);
			if (!$input['ris_group_code']) { $errors[] = "Dòng $line: nhóm CĐHA '$groupValue' không tồn tại hoặc đã ngừng hoạt động."; continue; }
			$save = $this->risReportSave(false,$input,true);
			if ($save['success']) $success++; else $errors[] = "Dòng $line: ".$save['message'];
		}
		$result['success'] = $success > 0 && !$errors;
		$result['message'] = "Đã import $success bản ghi.".($errors?' Chi tiết lỗi: '.implode(' | ',array_slice($errors,0,20)).(count($errors)>20?' | ...':''):'');
		$result['data'] = array('imported'=>$success,'errors'=>$errors);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	private function risReportSave($isEdit,$input=null,$isImport=false)
	{
		global $db;
		$result = array('success'=>false,'message'=>'Không thể xử lý yêu cầu.','data'=>array());
		if ($input===null) { if (!$this->authorizeRisReport($result)) { echo json_encode($result, JSON_UNESCAPED_UNICODE); return $result; } $input=$_POST; }
		$dateId = isset($input['report_date'])?(int)$input['report_date']:0;
		$groupCode = isset($input['ris_group_code'])&&ctype_digit(trim((string)$input['ris_group_code']))?(int)$input['ris_group_code']:0;
		if ($dateId<1||$groupCode<1) { $result['message']='Vui lòng chọn ngày và nhóm CĐHA hợp lệ.'; if($isImport)return $result; echo json_encode($result,JSON_UNESCAPED_UNICODE); return $result; }
		$db->query("SELECT full_date FROM ioc_date WHERE id = $dateId LIMIT 1"); $date=$db->fetch_object(true);
		$db->query("SELECT id FROM ioc_ris_categories WHERE id = $groupCode AND ris_category_status = '1' LIMIT 1"); $category=$db->fetch_object(true);
		if (!$date||!$category) $result['message']='Ngày ghi nhận hoặc nhóm CĐHA không còn hợp lệ.';
		else {
			$set=array("report_date = $dateId","ris_group_code = $groupCode"); $valid=true;
			foreach(array('ris_bhyt_bn_count','inpatient_ris_bn_count','outpatient_ris_bn_count','ris_bn_self_pay_count','ris_total_fim') as $field){
				$value=$this->parseInpatientNonNegativeInt(isset($input[$field])?$input[$field]:'');
				$max=$field==='ris_total_fim'?2147483647:4294967295;
				if($value===false||$value>$max){$valid=false;break;}
				$set[]="$field = ".(int)$value;
			}
			$note=isset($input['note'])?trim((string)$input['note']):''; $noteLength=function_exists('mb_strlen')?mb_strlen($note,'UTF-8'):strlen($note);
			if($noteLength>5000)$valid=false; $set[]="note = '".$db->escapestring($note)."'";
			if(!$valid)$result['message']='Các chỉ tiêu phải là số nguyên không âm; ghi chú tối đa 5.000 ký tự.';
			else {
				$id=isset($input['id'])?(int)$input['id']:0;
				if($isEdit&&$id<1)$result['message']='Không xác định được bản ghi cần sửa.';
				else {
					if($id){$db->query("SELECT id FROM ioc_ris_daily WHERE id = $id LIMIT 1");if(!$db->fetch_object(true))$result['message']='Bản ghi không tồn tại hoặc đã bị xóa.';}
					$db->query("SELECT id FROM ioc_ris_daily WHERE report_date = $dateId AND ris_group_code = $groupCode AND id <> $id LIMIT 1");
					if($db->fetch_object(true))$result['message']='Đã có số liệu cho ngày và nhóm CĐHA này.';
					if($result['message']==='Không thể xử lý yêu cầu.'){
						if($id)$db->query("UPDATE ioc_ris_daily SET ".implode(', ',$set)." WHERE id = $id");else $db->query("INSERT INTO ioc_ris_daily SET ".implode(', ',$set));
						$result['success']=true;$result['message']=$isEdit?'Cập nhật số liệu CĐHA thành công.':'Thêm số liệu CĐHA thành công.';$result['data']=array('id'=>$id,'full_date'=>$date->full_date,'ris_group_code'=>$groupCode);
					}
				}
			}
		}
		if($isImport)return $result; echo json_encode($result,JSON_UNESCAPED_UNICODE); return $result;
	}

	private function authorizeRisReport(&$result)
	{
		header('Content-Type: application/json; charset=utf-8');
		if(empty($_SESSION['user']['id'])&&empty($_SESSION['staff']['id'])){http_response_code(401);$result['message']='Vui lòng đăng nhập lại để tiếp tục.';return false;}
		if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);$result['message']='Chỉ hỗ trợ yêu cầu POST.';return false;}
		if(empty($_SESSION['ris_report_csrf'])||!isset($_POST['csrf'])||!hash_equals($_SESSION['ris_report_csrf'],(string)$_POST['csrf'])){http_response_code(419);$result['message']='Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';return false;}
		return true;
	}

	private function resolveRisReportCategory($value)
	{
		global $db; $value=trim((string)$value); if($value==='')return 0; $escaped=$db->escapestring($value); $idCondition=ctype_digit($value)?'id = '.(int)$value.' OR ':'';
		$db->query("SELECT id FROM ioc_ris_categories WHERE ris_category_status = '1' AND ($idCondition category_ris_code = '$escaped' OR ris_category_name = '$escaped') ORDER BY id LIMIT 1");
		$category=$db->fetch_object(true); return $category?(int)$category->id:0;
	}

	private function normalizeRisReportHeader($header)
	{
		$text=trim((string)$header);
		$text=strtr($text,array('Đ'=>'D','à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a','đ'=>'d','è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e','ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i','ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o','ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u','ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y'));
		$text=strtolower($text);$text=trim(preg_replace('/[^a-z0-9_]+/','_',$text),'_');
		$map=array(
			'ngay'=>'full_date','ngay_ghi_nhan'=>'full_date','report_date'=>'full_date','full_date'=>'full_date',
			'ma_nhom_cdha'=>'ris_group_code','nhom_cdha'=>'ris_group_code','ris_group_code'=>'ris_group_code',
			'benh_nhan_bhyt'=>'ris_bhyt_bn_count','bhyt'=>'ris_bhyt_bn_count','ris_bhyt_bn_count'=>'ris_bhyt_bn_count',
			'benh_nhan_noi_tru'=>'inpatient_ris_bn_count','noi_tru'=>'inpatient_ris_bn_count','inpatient_ris_bn_count'=>'inpatient_ris_bn_count',
			'benh_nhan_ngoai_tru'=>'outpatient_ris_bn_count','ngoai_tru'=>'outpatient_ris_bn_count','outpatient_ris_bn_count'=>'outpatient_ris_bn_count',
			'benh_nhan_vien_phi'=>'ris_bn_self_pay_count','vien_phi'=>'ris_bn_self_pay_count','ris_bn_self_pay_count'=>'ris_bn_self_pay_count',
			'tong_so_phim_da_chup'=>'ris_total_fim','tong_so_phim'=>'ris_total_fim','ris_total_fim'=>'ris_total_fim','ghi_chu'=>'note','note'=>'note'
		);
		return isset($map[$text])?$map[$text]:$text;
	}

	// ======================== BÁO CÁO SỐ LIỆU EMR ========================
	public function saveEmrReport() { $this->emrReportSave(false); }
	public function editEmrReport() { $this->emrReportSave(true); }

	public function deleteEmrReport()
	{
		global $db; $result=array('success'=>false,'message'=>'Không thể xử lý yêu cầu.','data'=>array());
		if(!$this->authorizeEmrReport($result)){echo json_encode($result,JSON_UNESCAPED_UNICODE);return;}
		$id=isset($_POST['id'])?(int)$_POST['id']:0;
		if($id<1)$result['message']='Bản ghi cần xóa không hợp lệ.';
		else{$db->query("SELECT id FROM ioc_emr_daily WHERE id = $id LIMIT 1");if(!$db->fetch_object(true))$result['message']='Bản ghi không tồn tại hoặc đã được xóa.';else{$db->query("DELETE FROM ioc_emr_daily WHERE id = $id");$result['success']=true;$result['message']='Đã xóa số liệu EMR.';}}
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}

	public function importEmrReport()
	{
		global $db; $result=array('success'=>false,'message'=>'Không thể xử lý yêu cầu.','data'=>array());
		if(!$this->authorizeEmrReport($result)){echo json_encode($result,JSON_UNESCAPED_UNICODE);return;}
		$file=isset($_FILES['import_file'])?$_FILES['import_file']:null;$extension=$file?strtolower(pathinfo($file['name'],PATHINFO_EXTENSION)):'';
		if(!$file||$file['error']!==UPLOAD_ERR_OK||empty($file['tmp_name'])||$file['size']<1||$file['size']>5*1024*1024||!in_array($extension,array('csv','xlsx'),true)){$result['message']='Vui lòng chọn tệp .xlsx hoặc .csv không quá 5 MB.';echo json_encode($result,JSON_UNESCAPED_UNICODE);return;}
		$rows=$extension==='csv'?$this->readInpatientCsv($file['tmp_name']):$this->readInpatientXlsx($file['tmp_name']);
		if(count($rows)<2){$result['message']='Tệp chưa có dòng dữ liệu hợp lệ hoặc máy chủ không hỗ trợ XLSX.';echo json_encode($result,JSON_UNESCAPED_UNICODE);return;}
		$headers=array_map(array($this,'normalizeEmrReportHeader'),array_shift($rows));
		if(count(array_unique($headers))!==count($headers)){$result['message']='Tệp có tiêu đề cột trùng nhau.';echo json_encode($result,JSON_UNESCAPED_UNICODE);return;}
		if(!in_array('full_date',$headers,true)){$result['message']='Thiếu cột bắt buộc: full_date.';echo json_encode($result,JSON_UNESCAPED_UNICODE);return;}
		$success=0;$errors=array();
		foreach($rows as $index=>$row){
			$line=$index+2;if(!array_filter($row,function($value){return trim((string)$value)!=='';}))continue;
			if(count($row)>count($headers)){$errors[]="Dòng $line: số cột vượt quá tiêu đề.";continue;}
			$input=array_combine($headers,array_pad($row,count($headers),''));$fullDate=$this->parseInpatientDate(isset($input['full_date'])?$input['full_date']:'');
			if(!$fullDate){$errors[]="Dòng $line: ngày ghi nhận không đúng định dạng YYYY-MM-DD hoặc DD/MM/YYYY.";continue;}
			$db->query("SELECT id FROM ioc_date WHERE full_date = '".$db->escapestring($fullDate)."' LIMIT 1");$date=$db->fetch_object(true);
			if(!$date){$errors[]="Dòng $line: ngày $fullDate chưa có trong danh mục ngày.";continue;}
			$input['report_date']=(int)$date->id;$save=$this->emrReportSave(false,$input,true);if($save['success'])$success++;else$errors[]="Dòng $line: ".$save['message'];
		}
		$result['success']=$success>0&&!$errors;$result['message']="Đã import $success bản ghi.".($errors?' Chi tiết lỗi: '.implode(' | ',array_slice($errors,0,20)).(count($errors)>20?' | ...':''):'');$result['data']=array('imported'=>$success,'errors'=>$errors);echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}

	private function emrReportSave($isEdit,$input=null,$isImport=false)
	{
		global $db;$result=array('success'=>false,'message'=>'Không thể xử lý yêu cầu.','data'=>array());
		if($input===null){if(!$this->authorizeEmrReport($result)){echo json_encode($result,JSON_UNESCAPED_UNICODE);return $result;}$input=$_POST;}
		$dateId=isset($input['report_date'])?(int)$input['report_date']:0;
		if($dateId<1){$result['message']='Vui lòng chọn ngày ghi nhận hợp lệ.';if($isImport)return $result;echo json_encode($result,JSON_UNESCAPED_UNICODE);return $result;}
		$db->query("SELECT full_date FROM ioc_date WHERE id = $dateId LIMIT 1");$date=$db->fetch_object(true);
		if(!$date)$result['message']='Ngày ghi nhận không còn hợp lệ.';
		else{
			$set=array("report_date = $dateId");$valid=true;
			foreach(array('emr_sent_count','emr_signed_count','emr_signed_unarchived_count','emr_archived_count','emr_archive_due_count','emr_archive_on_time_count') as $field){$value=$this->parseInpatientNonNegativeInt(isset($input[$field])?$input[$field]:'');if($value===false||$value>4294967295){$valid=false;break;}$set[]="$field = ".(int)$value;}
			$note=isset($input['note'])?trim((string)$input['note']):'';$noteLength=function_exists('mb_strlen')?mb_strlen($note,'UTF-8'):strlen($note);if($noteLength>5000)$valid=false;$set[]="note = '".$db->escapestring($note)."'";
			if(!$valid)$result['message']='Các chỉ tiêu phải là số nguyên không âm; ghi chú tối đa 5.000 ký tự.';
			else{
				$id=isset($input['id'])?(int)$input['id']:0;if($isEdit&&$id<1)$result['message']='Không xác định được bản ghi cần sửa.';
				else{
					if($id){$db->query("SELECT id FROM ioc_emr_daily WHERE id = $id LIMIT 1");if(!$db->fetch_object(true))$result['message']='Bản ghi không tồn tại hoặc đã bị xóa.';}
					$db->query("SELECT id FROM ioc_emr_daily WHERE report_date = $dateId AND id <> $id LIMIT 1");if($db->fetch_object(true))$result['message']='Đã có số liệu EMR cho ngày này.';
					if($result['message']==='Không thể xử lý yêu cầu.'){if($id)$db->query("UPDATE ioc_emr_daily SET ".implode(', ',$set)." WHERE id = $id");else$db->query("INSERT INTO ioc_emr_daily SET ".implode(', ',$set));$result['success']=true;$result['message']=$isEdit?'Cập nhật số liệu EMR thành công.':'Thêm số liệu EMR thành công.';$result['data']=array('id'=>$id,'full_date'=>$date->full_date);}
				}
			}
		}
		if($isImport)return $result;echo json_encode($result,JSON_UNESCAPED_UNICODE);return $result;
	}

	private function authorizeEmrReport(&$result)
	{
		header('Content-Type: application/json; charset=utf-8');if(empty($_SESSION['user']['id'])&&empty($_SESSION['staff']['id'])){http_response_code(401);$result['message']='Vui lòng đăng nhập lại để tiếp tục.';return false;}if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);$result['message']='Chỉ hỗ trợ yêu cầu POST.';return false;}if(empty($_SESSION['emr_report_csrf'])||!isset($_POST['csrf'])||!hash_equals($_SESSION['emr_report_csrf'],(string)$_POST['csrf'])){http_response_code(419);$result['message']='Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';return false;}return true;
	}

	private function normalizeEmrReportHeader($header)
	{
		$text=trim((string)$header);$text=strtr($text,array('Đ'=>'D','à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a','đ'=>'d','è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e','ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i','ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o','ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u','ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y'));$text=strtolower($text);$text=trim(preg_replace('/[^a-z0-9_]+/','_',$text),'_');
		$map=array('ngay'=>'full_date','ngay_ghi_nhan'=>'full_date','report_date'=>'full_date','full_date'=>'full_date','benh_an_da_gui'=>'emr_sent_count','da_gui'=>'emr_sent_count','emr_sent_count'=>'emr_sent_count','benh_an_da_ky'=>'emr_signed_count','da_ky'=>'emr_signed_count','emr_signed_count'=>'emr_signed_count','da_ky_chua_luu_tru'=>'emr_signed_unarchived_count','emr_signed_unarchived_count'=>'emr_signed_unarchived_count','benh_an_da_luu_tru'=>'emr_archived_count','da_luu_tru'=>'emr_archived_count','emr_archived_count'=>'emr_archived_count','luu_tru_cham'=>'emr_archive_due_count','emr_archive_due_count'=>'emr_archive_due_count','luu_tru_dung_han'=>'emr_archive_on_time_count','emr_archive_on_time_count'=>'emr_archive_on_time_count','ghi_chu'=>'note','note'=>'note');return isset($map[$text])?$map[$text]:$text;
	}

	public function stafflogin()
	{
		header('Content-Type: application/json; charset=utf-8');
		$result = array('success' => false, 'status' => 401, 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.', 'data' => array());
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			$result['message'] = 'Chỉ hỗ trợ yêu cầu POST.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		$identifier = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
		$password = isset($_POST['password']) ? (string) $_POST['password'] : '';
		if ($identifier === '' || $password === '' || strlen($identifier) > 255 || strlen($password) > 255) {
			http_response_code(422);
			$result['message'] = 'Vui lòng nhập tên đăng nhập và mật khẩu hợp lệ.';
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		global $db;
		$identifier = $db->escapestring($identifier);
		$db->query("SELECT id, username, password, full_name, email FROM ioc_users WHERE (username = '$identifier' OR email = '$identifier') AND is_active = 1 AND user_status = 1 LIMIT 1");
		$user = $db->fetch_object(true);
		// Hỗ trợ dữ liệu hiện hữu đang lưu MD5; password_verify vẫn cho phép nâng cấp sang hash hiện đại sau này.
		$passwordValid = false;
		if ($user && !empty($user->password)) {
			$passwordValid = password_verify($password, $user->password);
			if (!$passwordValid && preg_match('/^[a-f0-9]{32}$/i', $user->password)) {
				$passwordValid = hash_equals(strtolower($user->password), md5($password));
			}
		}
		if (!$user || !$passwordValid) {
			http_response_code(401);
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return;
		}
		session_regenerate_id(true);
		// Một số tài khoản legacy chưa có id; dùng username làm khóa phiên tạm thời để không làm mất phiên hợp lệ.
		$sessionUserId = trim((string) $user->id) !== '' ? $user->id : $user->username;
		$_SESSION['user'] = array('id' => $sessionUserId, 'username' => $user->username, 'email' => $user->email, 'fullname' => $user->full_name);
		$_SESSION['staff'] = array('id' => $sessionUserId, 'fullname' => $user->full_name, 'group' => null);
		$_SESSION['LoggedIn'] = 1;
		$db->query("UPDATE ioc_users SET last_login_at = NOW(6) WHERE username = '" . $db->escapestring($user->username) . "'");
		$result = array('success' => true, 'status' => 200, 'message' => 'Đăng nhập thành công.', 'data' => array('id' => $sessionUserId, 'display_name' => $user->full_name), 'return_url' => XC_URL . '/backend');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	//end điều trị nội trú
}



