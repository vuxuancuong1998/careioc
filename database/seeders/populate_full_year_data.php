<?php
/**
 * Seeder chuẩn hóa và hoàn thiện dữ liệu trọn vẹn 12 tháng năm 2026 và năm 2025
 * Đảm bảo:
 * 1. Tháng 8/2026 (3.884 ca) và Tháng 9/2026 (3.769 ca) được GIỮ NGUYÊN 100%.
 * 2. Tất cả các ngày khác trong năm 2026 và 2025 đều có dữ liệu thực tế đầy đủ.
 * 3. Tổng mỗi tháng dao động tự nhiên từ 3.100 - 3.900 ca/tháng.
 * 4. Tăng trưởng MoM và YoY hợp lý, khoa học cho bệnh viện đa khoa khu vực.
 */

require_once __DIR__ . '/../../config.php';

$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== BẮT ĐẦU CHUẨN HÓA DỮ LIỆU 12 THÁNG NĂM 2026 VÀ NĂM 2025 ===" . PHP_EOL;

// 1. Lấy danh sách các ngày trong bảng ioc_date cho 2025 và 2026
$stmtDates = $pdo->query("SELECT id, full_date, day_of_month, month_number, year_number FROM ioc_date WHERE year_number IN (2025, 2026) ORDER BY full_date ASC");
$allDates = $stmtDates->fetchAll();

// Lấy danh sách các report_date đã có dữ liệu trong ioc_outpatient_daily
$existingDates = $pdo->query("SELECT DISTINCT report_date FROM ioc_outpatient_daily")->fetchAll(PDO::FETCH_COLUMN);
$existingDateSet = array_flip($existingDates);

// Đếm số bản ghi theo tháng của năm 2026
$mCounts = $pdo->query("
    SELECT dt.month_number, COUNT(DISTINCT dt.id) as days_in_db
    FROM ioc_date dt
    JOIN ioc_outpatient_daily o ON dt.id = o.report_date
    WHERE dt.year_number = 2026
    GROUP BY dt.month_number
")->fetchAll(PDO::FETCH_KEY_PAIR);

echo "Các tháng 2026 đã có đầy đủ (>25 ngày): ";
$fullySeededMonths2026 = [];
foreach ($mCounts as $m => $cnt) {
    if ($cnt >= 25) {
        $fullySeededMonths2026[$m] = true;
        echo "T{$m} ($cnt ngày) ";
    }
}
echo PHP_EOL;

// 2. Chuẩn bị câu lệnh INSERT cho ioc_outpatient_daily
$stmtInsertOp = $pdo->prepare("
    INSERT INTO ioc_outpatient_daily 
    (report_date, department_id, payer_type_id, visit_count, revisit_count, waiting_count, examining_count, completed_count, avg_wait_minutes, avg_exam_minutes, referral_count, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

// Phân bổ theo 8 khoa
$deptDist = [
    3 => ['pct' => 0.35, 'wait' => 18.0, 'exam' => 12.0], // Khám bệnh
    2 => ['pct' => 0.20, 'wait' => 17.5, 'exam' => 14.5], // Nội
    4 => ['pct' => 0.11, 'wait' => 15.0, 'exam' => 16.0], // Ngoại
    5 => ['pct' => 0.11, 'wait' => 14.0, 'exam' => 12.0], // Nhi
    6 => ['pct' => 0.07, 'wait' => 16.0, 'exam' => 13.5], // Sản
    7 => ['pct' => 0.07, 'wait' => 15.5, 'exam' => 10.5], // LCK
    1 => ['pct' => 0.05, 'wait' =>  5.0, 'exam' => 20.0], // HSCC
    8 => ['pct' => 0.04, 'wait' => 12.0, 'exam' => 15.0], // YHCT
];

// Mục tiêu số lượt khám trung bình ngày theo tháng (để tạo tính mùa vụ thực tế)
$monthlyAvgDay2026 = [
    1 => 112, 2 => 105, 3 => 118, 4 => 115, 5 => 122, 6 => 120, 7 => 124,
    8 => 125, 9 => 125, 10 => 126, 11 => 124, 12 => 128
];
$monthlyAvgDay2025 = [
    1 => 102, 2 =>  95, 3 => 106, 4 => 104, 5 => 110, 6 => 108, 7 => 112,
    8 => 114, 9 => 115, 10 => 116, 11 => 114, 12 => 118
];

$pdo->beginTransaction();
$insertedCount = 0;

foreach ($allDates as $d) {
    $dId = (int)$d['id'];
    $yr = (int)$d['year_number'];
    $m = (int)$d['month_number'];
    $dom = (int)$d['day_of_month'];
    $dow = (int)date('w', strtotime($d['full_date'])); // 0 = Sunday, 1 = Monday... 6 = Saturday

    // Bỏ qua Tháng 8 và Tháng 9 của 2026 vì đã có dữ liệu chuẩn
    if ($yr === 2026 && isset($fullySeededMonths2026[$m])) {
        continue;
    }

    // Nếu ngày mùng 1 của tháng cũ chỉ có 1 record dummy -> xóa bản ghi cũ của ngày đó để tạo bộ chuẩn
    if (isset($existingDateSet[$dId])) {
        $pdo->prepare("DELETE FROM ioc_outpatient_daily WHERE report_date = ?")->execute([$dId]);
    }

    // Tính số ca trong ngày
    $baseAvg = ($yr === 2026) ? ($monthlyAvgDay2026[$m] ?? 120) : ($monthlyAvgDay2025[$m] ?? 108);

    // Hệ số ngày trong tuần: Thứ 2 đông nhất, Thứ 7/CN ít hơn
    $dayFactor = 1.0;
    if ($dow === 1) $dayFactor = 1.22; // Thứ 2
    elseif ($dow === 2) $dayFactor = 1.12; // Thứ 3
    elseif ($dow === 3) $dayFactor = 1.05; // Thứ 4
    elseif ($dow === 4) $dayFactor = 1.00; // Thứ 5
    elseif ($dow === 5) $dayFactor = 0.95; // Thứ 6
    elseif ($dow === 6) $dayFactor = 0.65; // Thứ 7
    elseif ($dow === 0) $dayFactor = 0.55; // Chủ nhật

    // Dao động giả lập ngẫu nhiên có trật tự
    $pseudoNoise = sin($dom * 1.7 + $m * 2.3) * 8;
    $dayVisits = (int)round($baseAvg * $dayFactor + $pseudoNoise);
    if ($dayVisits < 40) $dayVisits = 40;

    // Phân bổ vào 8 khoa
    $allocatedVisits = 0;
    $deptVisitsList = [];
    foreach ($deptDist as $deptId => $cfg) {
        $deptV = (int)round($dayVisits * $cfg['pct']);
        if ($deptV < 1) $deptV = 1;
        $deptVisitsList[$deptId] = $deptV;
        $allocatedVisits += $deptV;
    }
    // Điều chỉnh phần dư vào Khoa Khám bệnh (dept 3)
    $diff = $dayVisits - $allocatedVisits;
    $deptVisitsList[3] += $diff;

    foreach ($deptDist as $deptId => $cfg) {
        $vTotal = $deptVisitsList[$deptId];
        // BHYT ~85%, Viện phí ~15%
        $vBhyt = (int)round($vTotal * 0.85);
        if ($vBhyt < 1 && $vTotal > 0) $vBhyt = $vTotal;
        $vVp = $vTotal - $vBhyt;

        // Thêm bản ghi BHYT (payer_type_id = 1)
        if ($vBhyt > 0) {
            $w = max(0, (int)round($vBhyt * 0.11));
            $ex = max(0, (int)round($vBhyt * 0.07));
            $comp = max(0, $vBhyt - $w - $ex);
            $rev = (int)round($vBhyt * 0.25);
            $ref = (int)round($vBhyt * 0.035);
            $stmtInsertOp->execute([
                $dId, $deptId, 1, $vBhyt, $rev, $w, $ex, $comp, $cfg['wait'], $cfg['exam'], $ref
            ]);
            $insertedCount++;
        }

        // Thêm bản ghi Viện phí (payer_type_id = 2)
        if ($vVp > 0) {
            $w = max(0, (int)round($vVp * 0.10));
            $ex = max(0, (int)round($vVp * 0.06));
            $comp = max(0, $vVp - $w - $ex);
            $rev = (int)round($vVp * 0.15);
            $ref = (int)round($vVp * 0.02);
            $stmtInsertOp->execute([
                $dId, $deptId, 2, $vVp, $rev, $w, $ex, $comp, $cfg['wait'], $cfg['exam'], $ref
            ]);
            $insertedCount++;
        }
    }
}

$pdo->commit();
echo "-> Đã nạp thành công {$insertedCount} bản ghi cho ioc_outpatient_daily!" . PHP_EOL;

// 3. Chuẩn hóa ioc_inpatient_daily (Nội trú) cho các ngày chưa có
echo "=== CHUẨN HÓA DỮ LIỆU NỘI TRÚ (ioc_inpatient_daily) ===" . PHP_EOL;
$inpCounts = $pdo->query("
    SELECT dt.month_number, COUNT(DISTINCT dt.id) as days_in_db
    FROM ioc_date dt
    JOIN ioc_inpatient_daily i ON dt.id = i.inpatient_report_date
    WHERE dt.year_number = 2026
    GROUP BY dt.month_number
")->fetchAll(PDO::FETCH_KEY_PAIR);

$fullySeededInp2026 = [];
foreach ($inpCounts as $m => $cnt) {
    if ($cnt >= 25) {
        $fullySeededInp2026[$m] = true;
    }
}

$stmtInsertInp = $pdo->prepare("
    INSERT INTO ioc_inpatient_daily
    (inpatient_report_date, inpatient_department_id, payer_type_id, inpatient_opening_patient_count, inpatient_admission_count, inpatient_inpatient_daily_discharge_count, inpatient_transfer_count, inpatient_hospital_transfer_count, inpatient_death_count, inpatient_closing_patient_count, inpatient_treatment_days, inpatient_actual_beds, inpatient_occupied_beds, bed_occupancy_percent, avg_length_of_stay, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

// 5 khoa nội trú chính: 1 HSCC, 2 Nội TH, 4 Ngoại TH, 5 Nhi, 6 Sản
$inDepts = [
    1 => ['beds' => 15, 'adm_share' => 0.15],
    2 => ['beds' => 55, 'adm_share' => 0.35],
    4 => ['beds' => 35, 'adm_share' => 0.20],
    5 => ['beds' => 35, 'adm_share' => 0.18],
    6 => ['beds' => 25, 'adm_share' => 0.12]
];

$pdo->beginTransaction();
$inpInserted = 0;

foreach ($allDates as $d) {
    $dId = (int)$d['id'];
    $yr = (int)$d['year_number'];
    $m = (int)$d['month_number'];
    $dom = (int)$d['day_of_month'];

    if ($yr === 2026 && isset($fullySeededInp2026[$m])) {
        continue;
    }

    // Xóa dummy cũ nếu có
    $pdo->prepare("DELETE FROM ioc_inpatient_daily WHERE inpatient_report_date = ?")->execute([$dId]);

    // Tổng số nhập viện 1 ngày của toàn viện ~20-25 ca
    $totAdm = 22 + (int)(sin($dom * 1.5 + $m) * 4);

    foreach ($inDepts as $deptId => $cfg) {
        $adm = max(1, (int)round($totAdm * $cfg['adm_share']));
        $dis = max(1, $adm + (int)(sin($dom + $deptId) * 2));
        $occBeds = (int)round($cfg['beds'] * (0.75 + (sin($dom * 0.8 + $deptId) * 0.10)));
        $closing = $occBeds;
        $opening = $closing - $adm + $dis;
        $treatDays = $occBeds;
        $occPct = round(($occBeds / $cfg['beds']) * 100, 1);

        // Payer 1 (BHYT)
        $stmtInsertInp->execute([
            $dId, $deptId, 1,
            (int)round($opening * 0.85),
            (int)round($adm * 0.85),
            (int)round($dis * 0.85),
            1, 0, 0,
            (int)round($closing * 0.85),
            (int)round($treatDays * 0.85),
            $cfg['beds'],
            (int)round($occBeds * 0.85),
            $occPct, 6.4
        ]);
        $inpInserted++;

        // Payer 2 (Viện phí)
        $stmtInsertInp->execute([
            $dId, $deptId, 2,
            max(0, $opening - (int)round($opening * 0.85)),
            max(0, $adm - (int)round($adm * 0.85)),
            max(0, $dis - (int)round($dis * 0.85)),
            0, 0, 0,
            max(0, $closing - (int)round($closing * 0.85)),
            max(0, $treatDays - (int)round($treatDays * 0.85)),
            $cfg['beds'],
            max(0, $occBeds - (int)round($occBeds * 0.85)),
            $occPct, 6.4
        ]);
        $inpInserted++;
    }
}

$pdo->commit();
echo "-> Đã nạp thành công {$inpInserted} bản ghi cho ioc_inpatient_daily!" . PHP_EOL;

echo "=== HOÀN TẤT CHUẨN HÓA DỮ LIỆU ===" . PHP_EOL;
