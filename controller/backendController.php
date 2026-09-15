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
        if (empty($_SESSION['user']['id']) && empty($_SESSION['staff']['id'])) {
            header('Location: ' . XC_URL . '/admin/login');
            return;
        }
        require_once __DIR__ . '/libs/LisDailySync.php';
        $from = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
        $to = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $saved = array('rows'=>array(),'page'=>1,'total'=>0);
        $loadError = '';
        try {
            $lis = new LisDailySync();
            $saved = $lis->records($from,$to,$page);
            if (isset($_GET['details'])) $saved = $lis->details($_GET['details']);
        } catch (Throwable $e) {
            $loadError = $e instanceof InvalidArgumentException ? $e->getMessage() : 'Không tải được dữ liệu LIS. Vui lòng kiểm tra cơ sở dữ liệu và cài đặt tác vụ đồng bộ.';
        }
        if (isset($_GET['format']) && $_GET['format']==='json') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('success'=>$loadError==='','message'=>$loadError,'data'=>$saved),JSON_UNESCAPED_UNICODE);
            return;
        }
        $this->view->data['saved'] = $saved;
        $this->view->data['loadError'] = $loadError;
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
        $this->view->data['fromDate'] = LisDailySync::validDate($from) ? $from : date('Y-m-01');
        // Gan ngay ket thuc mac dinh la ngay hien tai.
        $this->view->data['toDate'] = LisDailySync::validDate($to) ? $to : date('Y-m-d');
        // Chi dua ma don vi ra giao dien, khong dua thong tin dang nhap LIS.
        $this->view->data['hospitalCode'] = $config ? (int) $config->hospital_code : 0;
        // Render trong layout backend de nguoi dung co thong bao va thao tac nhat quan.
        $this->view->backendtmp('lis-integration');
    }

    public function outpatient($para = array())
    {
        global $db;
        if (empty($_SESSION['user']['id']) && empty($_SESSION['staff']['id'])) {
            header('Location: ' . XC_URL . '/backend/login');
            return;
        }
        if (isset($para[1]) && $para[1] === 'template') {
            $this->downloadOutpatientTemplate();
            return;
        }
        if (isset($para[1]) && $para[1] === 'add') {
            $this->outpatientForm(null);
            return;
        }
        if (isset($para[1]) && $para[1] === 'edit') {
            $this->outpatientForm(isset($para[2]) ? (int) $para[2] : 0);
            return;
        }
        if (empty($_SESSION['outpatient_csrf'])) $_SESSION['outpatient_csrf'] = bin2hex(random_bytes(24));
        $flash = array('type' => '', 'message' => '');
        $where = array('1=1');
        foreach (array('from_date' => '>=', 'to_date' => '<=') as $field => $operator) if (!empty($_GET[$field]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET[$field])) $where[] = "dt.full_date $operator '" . $db->escapestring($_GET[$field]) . "'";
        if (!empty($_GET['department_id'])) $where[] = 'o.department_id=' . (int) $_GET['department_id'];
        if (!empty($_GET['payer_type_id'])) $where[] = 'o.payer_type_id=' . (int) $_GET['payer_type_id'];
        $db->query('SELECT o.*, dt.full_date, d.department_name, p.payer_type_name FROM ioc_outpatient_daily o JOIN ioc_date dt ON dt.id=o.report_date JOIN ioc_departments d ON d.id=o.department_id JOIN ioc_payer_types p ON p.id=o.payer_type_id WHERE ' . implode(' AND ', $where) . ' ORDER BY dt.full_date DESC, d.department_name');
        $records = $db->fetch_object();
        $db->query('SELECT id, department_code, department_name FROM ioc_departments WHERE department_status=1 ORDER BY department_name');
        $departments = $db->fetch_object();
        $db->query('SELECT id, payer_type_code, payer_type_name FROM ioc_payer_types WHERE payer_type_status=1 ORDER BY payer_type_sort_order, payer_type_name');
        $payerTypes = $db->fetch_object();
        $this->view->data = array('records' => is_array($records) ? $records : array(), 'departments' => is_array($departments) ? $departments : array(), 'payerTypes' => is_array($payerTypes) ? $payerTypes : array(), 'flash' => $flash, 'csrf' => $_SESSION['outpatient_csrf']);
        $this->view->backendtmp('outpatient');
    }

    // Hiển thị danh sách điều trị nội trú và xử lý điều hướng thêm/sửa.
    public function inpatient($para = array())
    {
        global $db;
        if (empty($_SESSION['user']['id']) && empty($_SESSION['staff']['id'])) {
            header('Location: ' . XC_URL . '/backend/login');
            return;
        }
        if (isset($para[1]) && $para[1] === 'add') {
            $this->inpatientForm(null);
            return;
        }
        if (isset($para[1]) && $para[1] === 'edit') {
            $this->inpatientForm(isset($para[2]) ? (int) $para[2] : 0);
            return;
        }
        if (empty($_SESSION['inpatient_csrf'])) $_SESSION['inpatient_csrf'] = bin2hex(random_bytes(24));

        // Chỉ hiển thị bản ghi đang hoạt động; bản ghi status = 99 đã xóa mềm.
        $where = array('i.status = 1');
        if (!empty($_GET['from_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from_date'])) $where[] = "dt.full_date >= '" . $db->escapestring($_GET['from_date']) . "'";
        if (!empty($_GET['to_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to_date'])) $where[] = "dt.full_date <= '" . $db->escapestring($_GET['to_date']) . "'";
        if (!empty($_GET['department_id'])) $where[] = 'i.inpatient_department_id = ' . (int) $_GET['department_id'];

        $db->query('SELECT i.*, dt.full_date, d.department_code, d.department_name
            FROM ioc_inpatient_daily i
            INNER JOIN ioc_date dt ON dt.id = i.inpatient_report_date
            INNER JOIN ioc_departments d ON d.id = i.inpatient_department_id
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY dt.full_date DESC, d.department_name');
        $records = $db->fetch_object();
        $db->query('SELECT id, department_code, department_name FROM ioc_departments WHERE department_status = 1 ORDER BY department_name');
        $departments = $db->fetch_object();
        $this->view->data = array(
            'records' => is_array($records) ? $records : array(),
            'departments' => is_array($departments) ? $departments : array(),
            'csrf' => $_SESSION['inpatient_csrf']
        );
        $this->view->backendtmp('inpatient');
    }

    // Chuẩn bị dữ liệu cho form thêm mới hoặc form chỉnh sửa.
    private function inpatientForm($id)
    {
        global $db;
        if (empty($_SESSION['inpatient_csrf'])) $_SESSION['inpatient_csrf'] = bin2hex(random_bytes(24));
        $record = null;
        if ($id) {
            $db->query("SELECT i.*, dt.full_date FROM ioc_inpatient_daily i INNER JOIN ioc_date dt ON dt.id = i.inpatient_report_date WHERE i.id = $id AND i.status = 1");
            $record = $db->fetch_object(true);
            if (!$record) {
                header('Location: ' . XC_URL . '/backend/inpatient');
                return;
            }
        }
        // Lấy danh mục ngày để form lưu bằng ioc_date.id.
        $db->query('SELECT id, full_date FROM ioc_date ORDER BY full_date DESC LIMIT 370');
        $reportDates = $db->fetch_object();
        $db->query('SELECT id, department_code, department_name FROM ioc_departments WHERE department_status = 1 ORDER BY department_name');
        $departments = $db->fetch_object();
        $this->view->data = array(
            'record' => $record,
            'reportDates' => is_array($reportDates) ? $reportDates : array(),
            'departments' => is_array($departments) ? $departments : array(),
            'csrf' => $_SESSION['inpatient_csrf']
        );
        $this->view->backendtmp('inpatient-form');
    }

    private function outpatientForm($id)
    {
        global $db;
        if (empty($_SESSION['outpatient_csrf'])) $_SESSION['outpatient_csrf'] = bin2hex(random_bytes(24));
        $flash = array('type' => '', 'message' => '');
        $record = null;
        if ($id) {
            $db->query("SELECT * FROM ioc_outpatient_daily WHERE id=$id");
            $record = $db->fetch_object(true);
            if (!$record) {
                header('Location: ' . XC_URL . '/backend/outpatient');
                exit;
            }
        }
        $db->query('SELECT id, department_name FROM ioc_departments WHERE department_status=1 ORDER BY department_name');
        $departments = $db->fetch_object();
        $db->query('SELECT id, payer_type_name FROM ioc_payer_types WHERE payer_type_status=1 ORDER BY payer_type_sort_order, payer_type_name');
        $payerTypes = $db->fetch_object();
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $db->query("SELECT id, full_date FROM ioc_date WHERE full_date BETWEEN '$monthStart' AND '$monthEnd' ORDER BY full_date");
        $reportDates = $db->fetch_object();
        $this->view->data = array('reportDates' => $reportDates ?: array(), 'record' => $record, 'departments' => is_array($departments) ? $departments : array(), 'payerTypes' => is_array($payerTypes) ? $payerTypes : array(), 'csrf' => $_SESSION['outpatient_csrf'], 'flash' => $flash);
        $this->view->backendtmp('outpatient-form');
    }

    public function downloadOutpatientTemplate()
    {
        global $db;
        $db->query('SELECT department_code, department_name FROM ioc_departments WHERE department_status=1 ORDER BY department_name');
        $departments = $db->fetch_object();
        $db->query('SELECT payer_type_code, payer_type_name FROM ioc_payer_types WHERE payer_type_status=1 ORDER BY payer_type_sort_order, payer_type_name');
        $payers = $db->fetch_object();
        if (!class_exists('ZipArchive')) {
            http_response_code(500);
            echo 'Máy chủ chưa hỗ trợ tạo tệp Excel.';
            return;
        }
        $xml = function ($value) {
            return htmlspecialchars((string)$value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        };
        $cell = function ($column, $row, $value) use ($xml) {
            return '<c r="' . $column . $row . '" t="inlineStr"><is><t>' . $xml($value) . '</t></is></c>';
        };
        $inputHeaders = array('full_date', 'Khoa/phòng', 'Đối tượng thanh toán', 'Tổng lượt khám', 'Lượt tái khám', 'Đang chờ khám', 'Đang được khám', 'Đã hoàn thành', 'Thời gian chờ TB (phút)', 'Chuyển tuyến');
        $sheet1 = '<row r="1">';
        foreach ($inputHeaders as $index => $header) $sheet1 .= $cell(chr(65 + $index), 1, $header);
        $sheet1 .= '</row>';
        $sheet2 = '<row r="1">' . $cell('A', 1, 'Mã khoa/phòng') . $cell('B', 1, 'Tên khoa/phòng') . $cell('D', 1, 'Mã đối tượng thanh toán') . $cell('E', 1, 'Tên đối tượng thanh toán') . '</row>';
        $max = max(count($departments ?: array()), count($payers ?: array()));
        for ($i = 0; $i < $max; $i++) {
            $row = $i + 2;
            $sheet2 .= '<row r="' . $row . '">';
            if (isset($departments[$i])) {
                $sheet2 .= $cell('A', $row, $departments[$i]->department_code) . $cell('B', $row, $departments[$i]->department_name);
            }
            if (isset($payers[$i])) {
                $sheet2 .= $cell('D', $row, $payers[$i]->payer_type_code) . $cell('E', $row, $payers[$i]->payer_type_name);
            }
            $sheet2 .= '</row>';
        }
        $book = new ZipArchive();
        $path = tempnam(sys_get_temp_dir(), 'outpatient_template_');
        $book->open($path, ZipArchive::OVERWRITE);
        $book->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $book->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $book->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Nhap lieu" sheetId="1" r:id="rId1"/><sheet name="Danh muc" sheetId="2" r:id="rId2"/></sheets></workbook>');
        $book->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet2.xml"/></Relationships>');
        $book->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $sheet1 . '</sheetData></worksheet>');
        $book->addFromString('xl/worksheets/sheet2.xml', '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $sheet2 . '</sheetData></worksheet>');
        $book->close();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="mau_import_ngoai_tru.xlsx"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        unlink($path);
        exit;
    }

}
