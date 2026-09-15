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
			$db->query("SELECT id FROM ioc_inpatient_daily WHERE id = $id AND status = 1 LIMIT 1");
			$record = $db->fetch_object(true);
			if (!$record) {
				$result['message'] = 'Bản ghi không tồn tại hoặc đã được xóa.';
				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				return;
			}
			$db->query("UPDATE ioc_inpatient_daily SET status = 99 WHERE id = $id AND status = 1");
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
		foreach (array('full_date', 'inpatient_department_id') as $required) {
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
		if ($dateId < 1 || $departmentId < 1) {
			$result['message'] = 'Vui lòng chọn ngày ghi nhận và khoa điều trị.';
			if ($input !== $_POST || $isImport) return $result;
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
			return $result;
		}
		$db->query("SELECT full_date FROM ioc_date WHERE id = $dateId LIMIT 1");
		$date = $db->fetch_object(true);
		$db->query("SELECT id FROM ioc_departments WHERE id = $departmentId AND department_status = 1 LIMIT 1");
		$department = $db->fetch_object(true);
		if (!$date || !$department) {
			$result['message'] = 'Ngày ghi nhận hoặc khoa điều trị không còn hợp lệ.';
		} else {
			$fields = array('inpatient_opening_patient_count','inpatient_admission_count','inpatient_inpatient_daily_discharge_count','inpatient_transfer_count','inpatient_hospital_transfer_count','inpatient_death_count','inpatient_closing_patient_count','inpatient_treatment_days','inpatient_occupied_beds');
			$set = array("inpatient_report_date = $dateId", "inpatient_department_id = $departmentId", 'status = 1');
			$valid = true;
			foreach ($fields as $field) {
				$value = isset($input[$field]) && $input[$field] !== '' ? filter_var($input[$field], FILTER_VALIDATE_INT) : 0;
				if ($value === false || $value < 0 || $value > 4294967295) { $valid = false; break; }
				$set[] = "$field = " . (int) $value;
			}
			foreach (array('bed_occupancy_percent' => 100000, 'avg_length_of_stay' => 99999999.99) as $field => $max) {
				$value = isset($input[$field]) && $input[$field] !== '' ? filter_var($input[$field], FILTER_VALIDATE_FLOAT) : null;
				if ($value !== null && ($value === false || !is_finite($value) || $value < 0 || $value > $max)) { $valid = false; break; }
				$set[] = "$field = " . ($value === null ? 'NULL' : number_format((float) $value, 2, '.', ''));
			}
			if (!$valid) {
				$result['message'] = 'Các chỉ tiêu phải là số không âm và nằm trong giới hạn cho phép.';
			} else {
				$id = isset($input['id']) ? (int) $input['id'] : 0;
				if ($isEdit && $id < 1) $result['message'] = 'Không xác định được bản ghi cần sửa.';
				else {
					if ($id) { $db->query("SELECT id FROM ioc_inpatient_daily WHERE id = $id AND status = 1 LIMIT 1"); if (!$db->fetch_object(true)) $result['message'] = 'Bản ghi không tồn tại hoặc đã bị xóa.'; }
					$db->query("SELECT id, status FROM ioc_inpatient_daily WHERE inpatient_report_date = $dateId AND inpatient_department_id = $departmentId AND id <> $id LIMIT 1");
					$duplicate = $db->fetch_object(true);
					if ($duplicate && (int) $duplicate->status === 1) $result['message'] = 'Đã có số liệu cho ngày và khoa điều trị này.';
					elseif ($duplicate && (int) $duplicate->status === 99) $id = (int) $duplicate->id;
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
		$map = array('ngay'=>'full_date','report_date'=>'full_date','inpatient_report_date'=>'full_date','khoa'=>'inpatient_department_id','khoa_dieu_tri'=>'inpatient_department_id','department'=>'inpatient_department_id');
		return isset($map[$text]) ? $map[$text] : $text;
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
	//end điều trị nội trú
}



