<?php
require_once __DIR__ . '/../../config.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== 1. Thêm cột avg_exam_minutes vào ioc_outpatient_daily ===\n";
    $cols = $pdo->query("SHOW COLUMNS FROM ioc_outpatient_daily LIKE 'avg_exam_minutes'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE ioc_outpatient_daily ADD COLUMN avg_exam_minutes DECIMAL(10,2) DEFAULT 12.50 AFTER avg_wait_minutes");
        echo "-> Đã thêm cột avg_exam_minutes\n";
    } else {
        echo "-> Cột avg_exam_minutes đã tồn tại\n";
    }

    // Cập nhật avg_exam_minutes theo chuyên khoa
    $pdo->exec("UPDATE ioc_outpatient_daily SET avg_exam_minutes = 14.5 WHERE department_id = 2"); // Nội
    $pdo->exec("UPDATE ioc_outpatient_daily SET avg_exam_minutes = 16.0 WHERE department_id = 4"); // Ngoại
    $pdo->exec("UPDATE ioc_outpatient_daily SET avg_exam_minutes = 12.0 WHERE department_id = 5"); // Nhi
    $pdo->exec("UPDATE ioc_outpatient_daily SET avg_exam_minutes = 13.5 WHERE department_id = 6"); // Sản
    $pdo->exec("UPDATE ioc_outpatient_daily SET avg_exam_minutes = 10.5 WHERE department_id = 7"); // Mắt-TMH
    $pdo->exec("UPDATE ioc_outpatient_daily SET avg_exam_minutes = 15.0 WHERE department_id = 8"); // YHCT

    echo "=== 2. Kiểm tra & Seed bảng ioc_quality_kpi_daily (nếu chưa có) ===\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ioc_quality_kpi_daily (
            id INT AUTO_INCREMENT PRIMARY KEY,
            report_date INT NOT NULL,
            outpatient_target_pct DECIMAL(5,2) DEFAULT 98.0,
            inpatient_bed_pct DECIMAL(5,2) DEFAULT 92.0,
            lis_tat_pct DECIMAL(5,2) DEFAULT 99.0,
            ris_pacs_pct DECIMAL(5,2) DEFAULT 96.0,
            pharmacy_safety_pct DECIMAL(5,2) DEFAULT 95.0,
            emr_signing_pct DECIMAL(5,2) DEFAULT 94.0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY idx_rep (report_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $chkKpi = $pdo->query("SELECT COUNT(*) FROM ioc_quality_kpi_daily")->fetchColumn();
    if ($chkKpi == 0) {
        $dates = $pdo->query("SELECT id FROM ioc_date WHERE year_number IN (2025, 2026) AND day_of_month = 1")->fetchAll(PDO::FETCH_COLUMN);
        $stmtKpi = $pdo->prepare("INSERT INTO ioc_quality_kpi_daily (report_date, outpatient_target_pct, inpatient_bed_pct, lis_tat_pct, ris_pacs_pct, pharmacy_safety_pct, emr_signing_pct) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($dates as $dId) {
            $stmtKpi->execute([$dId, 98.2, 92.5, 99.1, 96.4, 95.8, 94.2]);
        }
        echo "-> Đã seed " . count($dates) . " dòng ioc_quality_kpi_daily\n";
    } else {
        echo "-> Bảng ioc_quality_kpi_daily đã có {$chkKpi} dòng\n";
    }

    echo "=== 3. Đồng bộ dữ liệu LIS theo 12 tháng năm 2026 và 2025 ===\n";
    $dates2026 = $pdo->query("SELECT id, month_number FROM ioc_date WHERE year_number = 2026 AND day_of_month = 1 ORDER BY month_number ASC")->fetchAll(PDO::FETCH_KEY_PAIR);
    $stmtLisInsert = $pdo->prepare("INSERT INTO ioc_lis_daily (report_date, lis_group_code, lis_bhyt_count, inpatient_lis_count, outpatient_lis_count, lis_self_pay_count, note) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($dates2026 as $dId => $mNum) {
        $chk = $pdo->query("SELECT COUNT(*) FROM ioc_lis_daily WHERE report_date = {$dId}")->fetchColumn();
        if ($chk == 0) {
            // Hóa sinh
            $stmtLisInsert->execute([$dId, 2, 135, 45, 90, 30, 'Sinh hóa - Miễn dịch Cobas']);
            // Huyết học
            $stmtLisInsert->execute([$dId, 1, 115, 40, 75, 25, 'Huyết học Laser Sysmex']);
            // Vi sinh / Nước tiểu
            $stmtLisInsert->execute([$dId, 3, 74, 35, 39, 17, 'Vi sinh - Nước tiểu']);
        }
    }
    echo "-> ioc_lis_daily hiện có " . $pdo->query("SELECT COUNT(*) FROM ioc_lis_daily")->fetchColumn() . " dòng\n";

    echo "=== 4. Đồng bộ dữ liệu RIS theo 12 tháng năm 2026 và 2025 ===\n";
    $stmtRisInsert = $pdo->prepare("INSERT INTO ioc_ris_daily (report_date, ris_group_code, ris_bhyt_bn_count, inpatient_ris_bn_count, outpatient_ris_bn_count, ris_bn_self_pay_count, ris_total_fim, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($dates2026 as $dId => $mNum) {
        $chk = $pdo->query("SELECT COUNT(*) FROM ioc_ris_daily WHERE report_date = {$dId}")->fetchColumn();
        if ($chk == 0) {
            // X-Quang
            $stmtRisInsert->execute([$dId, 1, 85, 30, 55, 20, 12, 'X-Quang KTS Shimadzu']);
            // Siêu âm & Thăm dò
            $stmtRisInsert->execute([$dId, 2, 59, 22, 37, 12, 0, 'Siêu âm Doppler màu']);
        }
    }
    echo "-> ioc_ris_daily hiện có " . $pdo->query("SELECT COUNT(*) FROM ioc_ris_daily")->fetchColumn() . " dòng\n";

    echo "=== 5. Đồng bộ dữ liệu Tài chính 12 tháng năm 2026 ===\n";
    $stmtFinInsert = $pdo->prepare("INSERT INTO ioc_finance_daily (report_date, outpatient_revenue, inpatient_revenue, lis_revenue, ris_revenue, pharmacy_revenue, total_revenue, bhyt_revenue, self_revenue, cashless_revenue, cashless_rate, receipt_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtPayInsert = $pdo->prepare("INSERT INTO ioc_finance_payment_methods (report_date, method_code, method_name, transaction_count, total_amount) VALUES (?, ?, ?, ?, ?)");

    foreach ($dates2026 as $dId => $mNum) {
        $chk = $pdo->query("SELECT COUNT(*) FROM ioc_finance_daily WHERE report_date = {$dId}")->fetchColumn();
        if ($chk == 0) {
            $baseRev = 100000000 + ($mNum * 3500000);
            $outRev = round($baseRev * 0.10);
            $inRev = round($baseRev * 0.35);
            $lisRev = round($baseRev * 0.16);
            $risRev = round($baseRev * 0.13);
            $pharmRev = round($baseRev * 0.26);
            $totRev = $outRev + $inRev + $lisRev + $risRev + $pharmRev;
            $bhytRev = round($totRev * 0.82);
            $selfRev = $totRev - $bhytRev;
            $cashRate = 72.0 + ($mNum * 0.5);
            $cashlessRev = round($totRev * ($cashRate / 100));
            $rcpt = 110 + ($mNum * 5);

            $stmtFinInsert->execute([$dId, $outRev, $inRev, $lisRev, $risRev, $pharmRev, $totRev, $bhytRev, $selfRev, $cashlessRev, $cashRate, $rcpt]);

            $stmtPayInsert->execute([$dId, 'VIETQR', 'Quét mã VietQR', round($rcpt * 0.45), round($totRev * 0.45)]);
            $stmtPayInsert->execute([$dId, 'POS', 'Thẻ ngân hàng (POS)', round($rcpt * 0.18), round($totRev * 0.18)]);
            $stmtPayInsert->execute([$dId, 'TRANSFER', 'Chuyển khoản viện phí', round($rcpt * 0.10), round($totRev * 0.10)]);
            $stmtPayInsert->execute([$dId, 'CASH', 'Tiền mặt tại quầy', round($rcpt * 0.27), round($totRev * 0.27)]);
        }
    }
    echo "-> ioc_finance_daily hiện có " . $pdo->query("SELECT COUNT(*) FROM ioc_finance_daily")->fetchColumn() . " dòng\n";

    echo "=== 6. Đồng bộ dữ liệu Dược 12 tháng năm 2026 ===\n";
    $stmtPharmDaily = $pdo->prepare("INSERT INTO ioc_pharmacy_daily (report_date, total_import_value, total_export_value, total_stock_value, bhyt_spend, self_spend, low_stock_count, near_expiry_count, generic_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($dates2026 as $dId => $mNum) {
        $chk = $pdo->query("SELECT COUNT(*) FROM ioc_pharmacy_daily WHERE report_date = {$dId}")->fetchColumn();
        if ($chk == 0) {
            $imp = 100000000 + ($mNum * 4000000);
            $exp = 92000000 + ($mNum * 3800000);
            $stk = 480000000 + ($mNum * 5000000);
            $bhytSp = round($exp * 0.83);
            $selfSp = $exp - $bhytSp;
            $stmtPharmDaily->execute([$dId, $imp, $exp, $stk, $bhytSp, $selfSp, 2, 2, 85.5]);
        }
    }
    echo "-> ioc_pharmacy_daily hiện có " . $pdo->query("SELECT COUNT(*) FROM ioc_pharmacy_daily")->fetchColumn() . " dòng\n";

    echo "=== 7. Đồng bộ dữ liệu EMR 12 tháng năm 2026 ===\n";
    $stmtEmrDaily = $pdo->prepare("INSERT INTO ioc_emr_daily (report_date, emr_sent_count, emr_signed_count, emr_signed_unarchived_count, emr_archived_count, emr_archive_due_count, emr_archive_on_time_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($dates2026 as $dId => $mNum) {
        $chk = $pdo->query("SELECT COUNT(*) FROM ioc_emr_daily WHERE report_date = {$dId}")->fetchColumn();
        if ($chk == 0) {
            $sent = 90 + ($mNum * 3);
            $signed = round($sent * 0.94);
            $unarch = round($sent * 0.04);
            $arch = $signed - $unarch;
            $due = $sent;
            $onTime = round($sent * 0.96);
            $stmtEmrDaily->execute([$dId, $sent, $signed, $unarch, $arch, $due, $onTime]);
        }
    }
    echo "-> ioc_emr_daily hiện có " . $pdo->query("SELECT COUNT(*) FROM ioc_emr_daily")->fetchColumn() . " dòng\n";

    echo "\n=== HOÀN TẤT ĐỒNG BỘ DỮ LIỆU CSDL CARE IOC! ===\n";
} catch (Exception $e) {
    echo "LỖI: " . $e->getMessage() . "\n";
}
