<?php
/**
 * Migration & Seeder: Hoàn thiện toàn bộ CSDL và nạp dữ liệu mẫu 12 tháng
 * Phục vụ Trung tâm Điều hành Thông minh IOC - BVĐK KV Đăk Tô
 */

require_once __DIR__ . '/../../config.php';

$host = explode(':', DB_HOST)[0];
$port = defined('DB_PORT') ? DB_PORT : '3306';
$dsn = "mysql:host={$host};port={$port};dbname=" . DB_NAME . ";charset=utf8mb4";
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== BẮT ĐẦU HOÀN THIỆN CSDL & SEED DỮ LIỆU IOC BVĐK KV ĐĂK TÔ ===\n\n";

// -------------------------------------------------------------
// 1. TẠO CÁC BẢNG CSDL MỚI CHO DƯỢC, TÀI CHÍNH, HẠ TẦNG
// -------------------------------------------------------------
echo "[1/4] Tạo các bảng CSDL mới...\n";

$sqlTables = [
    // 1.1 Dược - Nhóm danh mục
    "CREATE TABLE IF NOT EXISTS `ioc_pharmacy_categories` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `category_code` varchar(32) NOT NULL,
      `category_name` varchar(128) NOT NULL,
      `category_status` tinyint(1) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_pharmacy_cat_code` (`category_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 1.2 Dược - Danh mục tồn kho & FEFO
    "CREATE TABLE IF NOT EXISTS `ioc_pharmacy_inventory` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `item_code` varchar(64) NOT NULL,
      `item_name` varchar(255) NOT NULL,
      `category_id` int(11) NOT NULL,
      `unit_name` varchar(32) NOT NULL,
      `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
      `stock_quantity` int(11) NOT NULL DEFAULT 0,
      `safety_stock` int(11) NOT NULL DEFAULT 0,
      `expiry_date` date NOT NULL,
      `warehouse_location` varchar(128) NOT NULL DEFAULT 'Kho Chẵn',
      `is_emergency_kit` tinyint(1) NOT NULL DEFAULT 0,
      `drug_type` varchar(64) NOT NULL DEFAULT 'Generic',
      `status` tinyint(1) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_pharmacy_item_code` (`item_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 1.3 Dược - Biến động hàng ngày
    "CREATE TABLE IF NOT EXISTS `ioc_pharmacy_daily` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `report_date` int(11) NOT NULL,
      `total_import_value` decimal(15,2) NOT NULL DEFAULT 0.00,
      `total_export_value` decimal(15,2) NOT NULL DEFAULT 0.00,
      `total_stock_value` decimal(15,2) NOT NULL DEFAULT 0.00,
      `bhyt_spend` decimal(15,2) NOT NULL DEFAULT 0.00,
      `self_spend` decimal(15,2) NOT NULL DEFAULT 0.00,
      `low_stock_count` int(11) NOT NULL DEFAULT 0,
      `near_expiry_count` int(11) NOT NULL DEFAULT 0,
      `emergency_kit_status` varchar(64) NOT NULL DEFAULT 'Đầy đủ',
      `generic_rate` decimal(5,2) NOT NULL DEFAULT 82.50,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_pharmacy_daily_date` (`report_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 1.4 Tài chính - Doanh thu viện phí hàng ngày
    "CREATE TABLE IF NOT EXISTS `ioc_finance_daily` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `report_date` int(11) NOT NULL,
      `outpatient_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `inpatient_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `lis_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `ris_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `pharmacy_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `total_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `bhyt_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `self_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `cashless_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
      `cashless_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
      `receipt_count` int(11) NOT NULL DEFAULT 0,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_finance_daily_date` (`report_date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 1.5 Tài chính - Kênh thanh toán
    "CREATE TABLE IF NOT EXISTS `ioc_finance_payment_methods` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `report_date` int(11) NOT NULL,
      `method_code` varchar(32) NOT NULL,
      `method_name` varchar(64) NOT NULL,
      `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
      `transaction_count` int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_fin_method_date` (`report_date`, `method_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 1.6 Hạ tầng - Máy chủ lõi
    "CREATE TABLE IF NOT EXISTS `ioc_infra_servers` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `server_code` varchar(32) NOT NULL,
      `server_name` varchar(128) NOT NULL,
      `ip_address` varchar(45) NOT NULL,
      `server_role` varchar(128) NOT NULL,
      `cpu_cores` int(11) NOT NULL DEFAULT 16,
      `ram_gb` int(11) NOT NULL DEFAULT 64,
      `storage_gb` int(11) NOT NULL DEFAULT 2048,
      `server_status` varchar(32) NOT NULL DEFAULT 'online',
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_server_code` (`server_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // 1.7 Hạ tầng - Logs giám sát
    "CREATE TABLE IF NOT EXISTS `ioc_infra_logs` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `report_date` int(11) NOT NULL,
      `server_id` int(11) NOT NULL,
      `avg_cpu_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
      `avg_ram_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
      `avg_disk_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
      `temp_celsius` decimal(5,2) NOT NULL DEFAULT 24.00,
      `uptime_hours` int(11) NOT NULL DEFAULT 720,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_infra_log_date_srv` (`report_date`, `server_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($sqlTables as $sql) {
    $pdo->exec($sql);
}
echo " -> Đã tạo/kiểm tra 7 bảng CSDL mới thành công!\n\n";

// -------------------------------------------------------------
// 2. BỔ SUNG ĐẦY ĐỦ 11 KHOA/PHÒNG CHUẨN CỦA BV ĐĂK TÔ
// -------------------------------------------------------------
echo "[2/4] Cập nhật danh mục 11 khoa/phòng chuẩn vào ioc_departments...\n";

$departments = [
    ['id' => 1, 'code' => 'HSCC', 'name' => 'Khoa Hồi sức cấp cứu & Chống độc', 'bed' => 30],
    ['id' => 2, 'code' => 'NT', 'name' => 'Khoa Nội tổng hợp', 'bed' => 45],
    ['id' => 3, 'code' => 'KB', 'name' => 'Khoa Khám bệnh', 'bed' => 0],
    ['id' => 4, 'code' => 'NGOAI', 'name' => 'Khoa Ngoại tổng hợp', 'bed' => 35],
    ['id' => 5, 'code' => 'NHI', 'name' => 'Khoa Nhi đồng', 'bed' => 25],
    ['id' => 6, 'code' => 'SAN', 'name' => 'Khoa Phụ sản', 'bed' => 20],
    ['id' => 7, 'code' => 'LMC', 'name' => 'Khoa Liên chuyên khoa (Mắt - TMH - RHM)', 'bed' => 15],
    ['id' => 8, 'code' => 'YHCT', 'name' => 'Khoa Y học Cổ truyền & PHCN', 'bed' => 20],
    ['id' => 9, 'code' => 'XN', 'name' => 'Khoa Xét nghiệm (LIS)', 'bed' => 0],
    ['id' => 10, 'code' => 'CDHA', 'name' => 'Khoa Chẩn đoán hình ảnh (RIS/PACS)', 'bed' => 0],
    ['id' => 11, 'code' => 'DUOC', 'name' => 'Khoa Dược & Vật tư y tế', 'bed' => 0]
];

$stmtDept = $pdo->prepare("INSERT INTO `ioc_departments` (`id`, `department_code`, `department_name`, `department_bed`, `department_status`, `department_created_at`, `department_updated_at`)
VALUES (:id, :code, :name, :bed, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `department_code` = VALUES(`department_code`), `department_name` = VALUES(`department_name`), `department_bed` = VALUES(`department_bed`), `department_status` = 1");

foreach ($departments as $d) {
    $stmtDept->execute([
        ':id' => $d['id'],
        ':code' => $d['code'],
        ':name' => $d['name'],
        ':bed' => $d['bed']
    ]);
}
echo " -> Đã cập nhật 11 khoa/phòng chuẩn BVĐK KV Đăk Tô (Tổng giường kế hoạch: 190 giường).\n\n";

// -------------------------------------------------------------
// 3. NẠP DANH MỤC DƯỢC, NHÓM XÉT NGHIỆM, CĐHA, MÁY CHỦ
// -------------------------------------------------------------
echo "[3/4] Nạp danh mục Dược, CĐHA, Máy chủ...\n";

// Nhóm Dược
$pharmacyCats = [
    [1, 'KS', 'Thuốc Kháng sinh & Kháng khuẩn'],
    [2, 'DT', 'Dịch truyền & Bù điện giải'],
    [3, 'GD', 'Giảm đau, Hạ sốt, Kháng viêm'],
    [4, 'TM', 'Thuốc Tim mạch & Huyết áp'],
    [5, 'VTYT', 'Vật tư y tế tiêu hao & Bảo hộ'],
    [6, 'HC', 'Hóa chất xét nghiệm & Sát trùng']
];
$stmtPCat = $pdo->prepare("INSERT INTO `ioc_pharmacy_categories` (`id`, `category_code`, `category_name`, `category_status`)
VALUES (?, ?, ?, 1) ON DUPLICATE KEY UPDATE `category_name` = VALUES(`category_name`)");
foreach ($pharmacyCats as $c) { $stmtPCat->execute($c); }

// Tồn kho Dược mẫu (Top thuốc)
$inventory = [
    ['MED-001', 'Paracetamol 500mg (Viên nén)', 3, 'Viên', 450, 18400, 5000, '2027-11-24', 'Kho Chẵn', 0, 'Generic'],
    ['MED-002', 'Cefuroxim 500mg (Viên bao phim)', 1, 'Viên', 4200, 3200, 1000, '2027-08-15', 'Kho Ngoại trú', 0, 'Kháng sinh'],
    ['MED-003', 'Natri Clorid 0.9% 500ml (Chai dịch truyền)', 2, 'Chai', 12500, 1450, 500, '2027-05-10', 'Kho Nội trú', 1, 'Dịch truyền'],
    ['MED-004', 'Augmentin 1g (Amoxicillin/Clavulanate)', 1, 'Lọ', 45000, 210, 300, '2026-12-18', 'Kho Nội trú', 1, 'Biệt dược'],
    ['MED-005', 'Amlodipin 5mg (Viên nén tim mạch)', 4, 'Viên', 1100, 4800, 1500, '2027-09-30', 'Kho Ngoại trú', 0, 'Generic'],
    ['MED-006', 'Omeprazol 20mg (Viên nang dạ dày)', 3, 'Viên', 850, 6200, 2000, '2027-10-15', 'Kho Ngoại trú', 0, 'Generic'],
    ['VTYT-001', 'Bơm tiêm dùng 1 lần 5ml (Kèm kim)', 5, 'Cái', 1200, 12000, 3000, '2028-12-30', 'Kho VTYT', 0, 'VTYT tiêu hao'],
    ['VTYT-002', 'Găng tay y tế khám bệnh (Hộp 100 cái)', 5, 'Hộp', 65000, 520, 150, '2028-06-20', 'Kho VTYT', 0, 'VTYT bảo hộ'],
    ['VTYT-003', 'Dây truyền dịch có bầu đếm giọt', 5, 'Dây', 6800, 2400, 800, '2028-04-15', 'Kho Nội trú', 1, 'VTYT tiêu hao'],
    ['HC-001', 'Cồn y tế sát trùng 70 độ 500ml', 6, 'Chai', 18000, 680, 200, '2027-03-20', 'Kho Chẵn', 1, 'Hóa chất']
];
$stmtInv = $pdo->prepare("INSERT INTO `ioc_pharmacy_inventory` (`item_code`, `item_name`, `category_id`, `unit_name`, `unit_price`, `stock_quantity`, `safety_stock`, `expiry_date`, `warehouse_location`, `is_emergency_kit`, `drug_type`, `status`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
ON DUPLICATE KEY UPDATE `stock_quantity` = VALUES(`stock_quantity`), `safety_stock` = VALUES(`safety_stock`), `unit_price` = VALUES(`unit_price`)");
foreach ($inventory as $inv) { $stmtInv->execute($inv); }

// Danh sách máy chủ lõi
$servers = [
    [1, 'SRV-01', 'Máy chủ HIS Core (Khám & Điều trị)', '192.168.1.10', 'HIS Core Service', 32, 128, 4096, 'online'],
    [2, 'SRV-02', 'Máy chủ CSDL (Database Master)', '192.168.1.12', 'MySQL Master Enterprise', 64, 256, 8192, 'online'],
    [3, 'SRV-03', 'Máy chủ EMR & Ký số Bệnh án điện tử', '192.168.1.15', 'EMR & HSM Digital Sign', 32, 128, 4096, 'online'],
    [4, 'SRV-04', 'Máy chủ Lưu trữ Hình ảnh PACS/RIS', '192.168.1.18', 'PACS DICOM Storage', 32, 128, 16384, 'online'],
    [5, 'NET-01', 'Firewall An ninh mạng Fortinet', '192.168.1.1', 'Security Gateway', 8, 32, 512, 'online'],
    [6, 'NET-02', 'Switch Mạng lõi Cisco Core 10Gbps', '192.168.1.2', 'Core Backbone Switching', 4, 16, 128, 'online'],
    [7, 'UPS-01', 'Bộ Lưu điện APC Smart-UPS 10kVA (Phòng Server)', '192.168.1.250', 'Power Backup Core', 2, 4, 16, 'online'],
    [8, 'UPS-02', 'Bộ Lưu điện Santak 6kVA (Mạng & Dự phòng)', '192.168.1.251', 'Power Backup Secondary', 2, 4, 16, 'online']
];
$stmtSrv = $pdo->prepare("INSERT INTO `ioc_infra_servers` (`id`, `server_code`, `server_name`, `ip_address`, `server_role`, `cpu_cores`, `ram_gb`, `storage_gb`, `server_status`)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE `server_name` = VALUES(`server_name`), `ip_address` = VALUES(`ip_address`), `server_status` = 'online'");
foreach ($servers as $srv) { $stmtSrv->execute($srv); }

echo " -> Đã nạp danh mục Dược phẩm, Tủ trực cấp cứu, 8 Máy chủ & Hạ tầng mạng.\n\n";

// -------------------------------------------------------------
// 4. NẠP DỮ LIỆU CHUẨN XÁC 12 THÁNG NĂM 2026 CHO TOÀN BỘ CÁC BẢNG DAILY
// -------------------------------------------------------------
echo "[4/4] Nạp dữ liệu hoạt động 12 tháng năm 2026 (Khớp chính xác: T7: 100 BN, T8: 120 BN, Tăng trưởng +20%)...\n";

// Mapping Ngày 01 của 12 tháng trong ioc_date:
$monthDateMap = [
    1 => 366, // 2026-01-01
    2 => 397, // 2026-02-01
    3 => 425, // 2026-03-01
    4 => 456, // 2026-04-01
    5 => 486, // 2026-05-01
    6 => 517, // 2026-06-01
    7 => 547, // 2026-07-01: Đúng 100 lượt
    8 => 578, // 2026-08-01: Đúng 120 lượt -> Tăng trưởng +20.0%!
    9 => 609, // 2026-09-01: 135 lượt
    10 => 639, // 2026-10-01: 128 lượt
    11 => 670, // 2026-11-01: 142 lượt
    12 => 700  // 2026-12-01: 150 lượt
];

$monthlyTotals = [
    1 => 95, 2 => 88, 3 => 104, 4 => 98, 5 => 112, 6 => 105,
    7 => 100, // Tháng 7 = 100 BN
    8 => 120, // Tháng 8 = 120 BN -> Tăng trưởng đúng +20.0%!
    9 => 135, 10 => 128, 11 => 142, 12 => 150
];

// Chuẩn bị statements
$stmtOutDaily = $pdo->prepare("INSERT INTO `ioc_outpatient_daily`
(`report_date`, `department_id`, `payer_type_id`, `visit_count`, `revisit_count`, `waiting_count`, `examining_count`, `completed_count`, `avg_wait_minutes`, `referral_count`, `created_at`, `updated_at`)
VALUES (:date_id, :dept_id, :payer_id, :v_cnt, :rev_cnt, :w_cnt, :ex_cnt, :comp_cnt, :avg_w, :ref_cnt, NOW(), NOW())
ON DUPLICATE KEY UPDATE `visit_count` = VALUES(`visit_count`), `completed_count` = VALUES(`completed_count`), `waiting_count` = VALUES(`waiting_count`)");

$stmtInDaily = $pdo->prepare("INSERT INTO `ioc_inpatient_daily`
(`inpatient_report_date`, `inpatient_department_id`, `payer_type_id`, `inpatient_opening_patient_count`, `inpatient_admission_count`, `inpatient_inpatient_daily_discharge_count`, `inpatient_transfer_count`, `inpatient_hospital_transfer_count`, `inpatient_death_count`, `inpatient_closing_patient_count`, `inpatient_treatment_days`, `inpatient_occupied_beds`, `inpatient_actual_beds`, `bed_occupancy_percent`, `avg_length_of_stay`, `inpatient_daily_status`)
VALUES (:date_id, :dept_id, :payer_id, :open_p, :adm, :dis, :trans, :hosp_trans, 0, :close_p, :days, :occ_beds, :act_beds, :occ_rate, :stay, 1)
ON DUPLICATE KEY UPDATE `inpatient_admission_count` = VALUES(`inpatient_admission_count`), `inpatient_occupied_beds` = VALUES(`inpatient_occupied_beds`), `bed_occupancy_percent` = VALUES(`bed_occupancy_percent`)");

$stmtLisDaily = $pdo->prepare("INSERT INTO `ioc_lis_daily`
(`report_date`, `lis_group_code`, `lis_bhyt_count`, `inpatient_lis_count`, `outpatient_lis_count`, `lis_self_pay_count`, `note`, `created_at`, `updated_at`)
VALUES (:date_id, :grp, :bhyt, :inp, :outp, :self, '', NOW(), NOW())
ON DUPLICATE KEY UPDATE `lis_bhyt_count` = VALUES(`lis_bhyt_count`), `inpatient_lis_count` = VALUES(`inpatient_lis_count`), `outpatient_lis_count` = VALUES(`outpatient_lis_count`), `lis_self_pay_count` = VALUES(`lis_self_pay_count`)");

$stmtRisDaily = $pdo->prepare("INSERT INTO `ioc_ris_daily`
(`report_date`, `ris_group_code`, `ris_bhyt_bn_count`, `inpatient_ris_bn_count`, `outpatient_ris_bn_count`, `ris_bn_self_pay_count`, `ris_total_fim`, `note`, `created_at`, `updated_at`)
VALUES (:date_id, :grp, :bhyt, :inp, :outp, :self, :fim, '', NOW(), NOW())
ON DUPLICATE KEY UPDATE `ris_bhyt_bn_count` = VALUES(`ris_bhyt_bn_count`), `inpatient_ris_bn_count` = VALUES(`inpatient_ris_bn_count`), `outpatient_ris_bn_count` = VALUES(`outpatient_ris_bn_count`), `ris_bn_self_pay_count` = VALUES(`ris_bn_self_pay_count`), `ris_total_fim` = VALUES(`ris_total_fim`)");

$stmtEmrDaily = $pdo->prepare("INSERT INTO `ioc_emr_daily`
(`report_date`, `emr_sent_count`, `emr_signed_count`, `emr_signed_unarchived_count`, `emr_archived_count`, `emr_archive_due_count`, `emr_archive_on_time_count`, `note`, `created_at`, `updated_at`)
VALUES (:date_id, :sent, :signed, :unarch, :arch, :due, :ontime, 'Bệnh án điện tử liên thông', NOW(), NOW())
ON DUPLICATE KEY UPDATE `emr_sent_count` = VALUES(`emr_sent_count`), `emr_signed_count` = VALUES(`emr_signed_count`), `emr_archived_count` = VALUES(`emr_archived_count`)");

$stmtPharmDaily = $pdo->prepare("INSERT INTO `ioc_pharmacy_daily`
(`report_date`, `total_import_value`, `total_export_value`, `total_stock_value`, `bhyt_spend`, `self_spend`, `low_stock_count`, `near_expiry_count`, `emergency_kit_status`, `generic_rate`)
VALUES (:date_id, :imp, :exp, :stock, :bhyt, :self, :low, :near, '100% Sẵn sàng', :gen)
ON DUPLICATE KEY UPDATE `total_import_value` = VALUES(`total_import_value`), `total_export_value` = VALUES(`total_export_value`), `total_stock_value` = VALUES(`total_stock_value`)");

$stmtFinDaily = $pdo->prepare("INSERT INTO `ioc_finance_daily`
(`report_date`, `outpatient_revenue`, `inpatient_revenue`, `lis_revenue`, `ris_revenue`, `pharmacy_revenue`, `total_revenue`, `bhyt_revenue`, `self_revenue`, `cashless_revenue`, `cashless_rate`, `receipt_count`)
VALUES (:date_id, :out_r, :in_r, :lis_r, :ris_r, :pharm_r, :tot_r, :bhyt_r, :self_r, :cashless_r, :cashless_rate, :receipts)
ON DUPLICATE KEY UPDATE `total_revenue` = VALUES(`total_revenue`), `cashless_revenue` = VALUES(`cashless_revenue`), `cashless_rate` = VALUES(`cashless_rate`)");

$stmtPayMethod = $pdo->prepare("INSERT INTO `ioc_finance_payment_methods`
(`report_date`, `method_code`, `method_name`, `total_amount`, `transaction_count`)
VALUES (:date_id, :code, :name, :amount, :tx_cnt)
ON DUPLICATE KEY UPDATE `total_amount` = VALUES(`total_amount`), `transaction_count` = VALUES(`transaction_count`)");

$stmtInfraLog = $pdo->prepare("INSERT INTO `ioc_infra_logs`
(`report_date`, `server_id`, `avg_cpu_percent`, `avg_ram_percent`, `avg_disk_percent`, `temp_celsius`, `uptime_hours`)
VALUES (:date_id, :srv_id, :cpu, :ram, :disk, :temp, :uptime)
ON DUPLICATE KEY UPDATE `avg_cpu_percent` = VALUES(`avg_cpu_percent`), `avg_ram_percent` = VALUES(`avg_ram_percent`)");

// Loop qua 12 tháng năm 2026
foreach ($monthlyTotals as $m => $totV) {
    $dateId = $monthDateMap[$m];

    // 4.1 Ngoại trú: BHYT (~85%), Viện phí (~15%)
    // Phân bổ chính vào Khoa Khám bệnh (id=3) và Cấp cứu (id=1)
    $bhytV = (int)round($totV * 0.85);
    $vpV = $totV - $bhytV;

    // Khoa Khám bệnh (id=3)
    $kbBhyt = (int)round($bhytV * 0.70);
    $kbVp = (int)round($vpV * 0.70);
    $stmtOutDaily->execute([':date_id' => $dateId, ':dept_id' => 3, ':payer_id' => 1, ':v_cnt' => $kbBhyt, ':rev_cnt' => (int)round($kbBhyt*0.2), ':w_cnt' => 8, ':ex_cnt' => 5, ':comp_cnt' => $kbBhyt - 13, ':avg_w' => 18.2, ':ref_cnt' => 4]);
    $stmtOutDaily->execute([':date_id' => $dateId, ':dept_id' => 3, ':payer_id' => 2, ':v_cnt' => $kbVp, ':rev_cnt' => (int)round($kbVp*0.1), ':w_cnt' => 2, ':ex_cnt' => 1, ':comp_cnt' => $kbVp - 3, ':avg_w' => 14.5, ':ref_cnt' => 1]);

    // Khoa Cấp cứu HSCC (id=1)
    $hsccBhyt = $bhytV - $kbBhyt;
    $hsccVp = $vpV - $kbVp;
    $stmtOutDaily->execute([':date_id' => $dateId, ':dept_id' => 1, ':payer_id' => 1, ':v_cnt' => $hsccBhyt, ':rev_cnt' => 2, ':w_cnt' => 3, ':ex_cnt' => 2, ':comp_cnt' => $hsccBhyt - 5, ':avg_w' => 5.0, ':ref_cnt' => 8]);
    $stmtOutDaily->execute([':date_id' => $dateId, ':dept_id' => 1, ':payer_id' => 2, ':v_cnt' => $hsccVp, ':rev_cnt' => 0, ':w_cnt' => 1, ':ex_cnt' => 0, ':comp_cnt' => $hsccVp - 1, ':avg_w' => 5.0, ':ref_cnt' => 2]);

    // 4.2 Nội trú & Giường bệnh (Khoa Nội id=2, Khoa Ngoại id=4, Cấp cứu id=1)
    // Khoa Nội (bed=45)
    $stmtInDaily->execute([':date_id' => $dateId, ':dept_id' => 2, ':payer_id' => 1, ':open_p' => 32, ':adm' => 8, ':dis' => 7, ':trans' => 1, ':hosp_trans' => 1, ':close_p' => 33, ':days' => 210, ':occ_beds' => 35, ':act_beds' => 45, ':occ_rate' => 77.8, ':stay' => 6.2]);
    // Khoa Ngoại (bed=35)
    $stmtInDaily->execute([':date_id' => $dateId, ':dept_id' => 4, ':payer_id' => 1, ':open_p' => 24, ':adm' => 6, ':dis' => 5, ':trans' => 0, ':hosp_trans' => 0, ':close_p' => 25, ':days' => 160, ':occ_beds' => 27, ':act_beds' => 35, ':occ_rate' => 77.1, ':stay' => 5.8]);
    // Khoa HSCC (bed=30)
    $stmtInDaily->execute([':date_id' => $dateId, ':dept_id' => 1, ':payer_id' => 1, ':open_p' => 18, ':adm' => 5, ':dis' => 4, ':trans' => 1, ':hosp_trans' => 1, ':close_p' => 18, ':days' => 95, ':occ_beds' => 20, ':act_beds' => 30, ':occ_rate' => 66.7, ':stay' => 4.5]);

    // 4.3 Xét nghiệm (LIS)
    $stmtLisDaily->execute([':date_id' => $dateId, ':grp' => 1, ':bhyt' => (int)round($totV * 1.2), ':inp' => (int)round($totV * 0.4), ':outp' => (int)round($totV * 0.8), ':self' => (int)round($totV * 0.25)]); // Huyết học
    $stmtLisDaily->execute([':date_id' => $dateId, ':grp' => 2, ':bhyt' => (int)round($totV * 1.5), ':inp' => (int)round($totV * 0.6), ':outp' => (int)round($totV * 0.9), ':self' => (int)round($totV * 0.35)]); // Sinh hóa

    // 4.4 Chẩn đoán hình ảnh (RIS/PACS)
    $stmtRisDaily->execute([':date_id' => $dateId, ':grp' => 1, ':bhyt' => (int)round($totV * 0.7), ':inp' => (int)round($totV * 0.25), ':outp' => (int)round($totV * 0.45), ':self' => (int)round($totV * 0.15), ':fim' => (int)round($totV * 0.08)]); // X-quang
    $stmtRisDaily->execute([':date_id' => $dateId, ':grp' => 2, ':bhyt' => (int)round($totV * 0.5), ':inp' => (int)round($totV * 0.18), ':outp' => (int)round($totV * 0.32), ':self' => (int)round($totV * 0.12), ':fim' => 0]); // Siêu âm (Không in phim)

    // 4.5 Bệnh án điện tử EMR
    $emrSent = (int)round($totV * 0.85);
    $emrSigned = (int)round($emrSent * 0.94);
    $stmtEmrDaily->execute([':date_id' => $dateId, ':sent' => $emrSent, ':signed' => $emrSigned, ':unarch' => $emrSent - $emrSigned, ':arch' => (int)round($emrSigned * 0.92), ':due' => $emrSent, ':ontime' => (int)round($emrSent * 0.96)]);

    // 4.6 Dược - VTYT hàng tháng
    $impVal = (float)round(80000000 + ($totV * 250000));
    $expVal = (float)round(75000000 + ($totV * 240000));
    $stockVal = (float)round(450000000 + ($m * 5000000));
    $stmtPharmDaily->execute([':date_id' => $dateId, ':imp' => $impVal, ':exp' => $expVal, ':stock' => $stockVal, ':bhyt' => $expVal * 0.82, ':self' => $expVal * 0.18, ':low' => max(1, 6 - (int)($m / 2)), ':near' => 2, ':gen' => 84.50]);

    // 4.7 Tài chính - Viện phí & Dòng tiền
    $outR = $totV * 85000;
    $inR = 35 * 220000 * 6; // Ngày giường nội trú
    $lisR = (int)round($totV * 180000);
    $risR = (int)round($totV * 140000);
    $pharmR = $expVal * 0.35;
    $totR = $outR + $inR + $lisR + $risR + $pharmR;
    $bhytR = $totR * 0.82;
    $selfR = $totR * 0.18;
    $cashlessRate = 60.0 + ($m * 1.5); // Tăng dần từ 61.5% đến 78%
    $cashlessR = $totR * ($cashlessRate / 100);

    $stmtFinDaily->execute([
        ':date_id' => $dateId,
        ':out_r' => $outR,
        ':in_r' => $inR,
        ':lis_r' => $lisR,
        ':ris_r' => $risR,
        ':pharm_r' => $pharmR,
        ':tot_r' => $totR,
        ':bhyt_r' => $bhytR,
        ':self_r' => $selfR,
        ':cashless_r' => $cashlessR,
        ':cashless_rate' => $cashlessRate,
        ':receipts' => (int)round($totV * 4.2)
    ]);

    // 4.8 Kênh thanh toán (VietQR 45%, POS 18%, Chuyển khoản 10%, Tiền mặt 27%)
    $stmtPayMethod->execute([':date_id' => $dateId, ':code' => 'QR', ':name' => 'Thanh toán Quét mã VietQR', ':amount' => $totR * 0.45, ':tx_cnt' => (int)round($totV * 2.1)]);
    $stmtPayMethod->execute([':date_id' => $dateId, ':code' => 'POS', ':name' => 'Quẹt thẻ máy POS ngân hàng', ':amount' => $totR * 0.18, ':tx_cnt' => (int)round($totV * 0.6)]);
    $stmtPayMethod->execute([':date_id' => $dateId, ':code' => 'TRANSFER', ':name' => 'Chuyển khoản Internet Banking', ':amount' => $totR * 0.10, ':tx_cnt' => (int)round($totV * 0.3)]);
    $stmtPayMethod->execute([':date_id' => $dateId, ':code' => 'CASH', ':name' => 'Tiền mặt thu tại quầy viện phí', ':amount' => $totR * 0.27, ':tx_cnt' => (int)round($totV * 1.2)]);

    // 4.9 Giám sát 8 máy chủ & hạ tầng
    for ($srvId = 1; $srvId <= 8; $srvId++) {
        $cpu = ($srvId == 2) ? 58.0 : (($srvId == 1) ? 42.0 : 35.0);
        $ram = ($srvId == 2) ? 74.0 : (($srvId == 1) ? 68.0 : 55.0);
        $disk = 45.0 + ($m * 0.8);
        $stmtInfraLog->execute([
            ':date_id' => $dateId,
            ':srv_id' => $srvId,
            ':cpu' => $cpu + rand(-3, 3),
            ':ram' => $ram + rand(-2, 2),
            ':disk' => $disk,
            ':temp' => 24.0 + rand(-1, 1),
            ':uptime' => 720
        ]);
    }
}

echo " -> Đã nạp thành công dữ liệu mẫu 12 tháng năm 2026 cho toàn bộ các bảng!\n\n";

echo "=== HOÀN TẤT THÀNH CÔNG! ===\n";
