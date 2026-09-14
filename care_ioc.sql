-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 10, 2026 lúc 05:22 PM
-- Phiên bản máy phục vụ: 10.4.21-MariaDB
-- Phiên bản PHP: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `care_ioc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_alert_events`
--

CREATE TABLE `ioc_alert_events` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `rule_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Luật sinh cảnh báo. FK -> alerting.ioc_alert_rules.id.',
  `metric_value_id` bigint(20) DEFAULT NULL COMMENT 'Giá trị KPI kích hoạt. FK -> analytics.ioc_metric_values.id.',
  `measurement_id` bigint(20) DEFAULT NULL COMMENT 'Quan trắc kích hoạt. FK -> ops.ioc_monitoring_measurements.id.',
  `organization_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tổ chức. FK -> core.ioc_organizations.id.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa/phòng. FK -> core.ioc_org_units.id.',
  `asset_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tài sản. FK -> ops.ioc_monitoring_assets.id.',
  `opened_at` datetime(6) NOT NULL COMMENT 'Thời điểm mở.',
  `acknowledged_at` datetime(6) DEFAULT NULL COMMENT 'Thời điểm xác nhận.',
  `resolved_at` datetime(6) DEFAULT NULL COMMENT 'Thời điểm đóng.',
  `severity_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mức độ. FK -> core.ioc_catalog_items.id.',
  `status_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'OPEN/ACK/RESOLVED/SUPPRESSED; ALERT_STATUS. FK -> core.ioc_catalog_items.id.',
  `observed_value` decimal(24,6) DEFAULT NULL COMMENT 'Giá trị thực tế.',
  `threshold_snapshot_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Snapshot ngưỡng để audit.' CHECK (json_valid(`threshold_snapshot_json`)),
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung.',
  `ack_by_user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người xác nhận. FK -> iam.ioc_users.id.',
  `resolved_by_user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người đóng. FK -> iam.ioc_users.id.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[alerting] Sự kiện cảnh báo, xác nhận và đóng cảnh báo.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_alert_rules`
--

CREATE TABLE `ioc_alert_rules` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã luật.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên luật.',
  `metric_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'KPI nghiệp vụ nếu có. FK -> analytics.ioc_metrics.id.',
  `monitoring_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Item hạ tầng nếu có. FK -> ops.ioc_monitoring_items.id.',
  `rule_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'THRESHOLD/STATUS/MISSING_DATA/JOB_FAILURE; ALERT_RULE_TYPE. FK -> core.ioc_catalog_items.id.',
  `operator_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '>, >=, <, <=, =, !=; COMPARISON_OPERATOR. FK -> core.ioc_catalog_items.id.',
  `threshold_value` decimal(24,6) DEFAULT NULL COMMENT 'Ngưỡng chính.',
  `threshold_value_2` decimal(24,6) DEFAULT NULL COMMENT 'Ngưỡng thứ hai nếu cần.',
  `duration_seconds` int(11) NOT NULL DEFAULT 0 COMMENT 'Điều kiện duy trì bao lâu trước khi mở cảnh báo.',
  `severity_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mức độ; ALERT_SEVERITY. FK -> core.ioc_catalog_items.id.',
  `recovery_threshold_value` decimal(24,6) DEFAULT NULL COMMENT 'Ngưỡng phục hồi/hysteresis.',
  `cooldown_seconds` int(11) NOT NULL DEFAULT 300 COMMENT 'Khoảng chống gửi lặp.',
  `effective_from` datetime(6) DEFAULT NULL COMMENT 'Hiệu lực từ.',
  `effective_to` datetime(6) DEFAULT NULL COMMENT 'Hiệu lực đến.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[alerting] Luật cảnh báo linh hoạt cho KPI nghiệp vụ và metric hạ tầng.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_audit_logs`
--

CREATE TABLE `ioc_audit_logs` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `occurred_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm hành động.',
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người thực hiện; null cho system. FK -> iam.ioc_users.id.',
  `action` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CREATE/UPDATE/DELETE/LOGIN/ACK/EXPORT...',
  `entity_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại đối tượng.',
  `entity_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID đối tượng.',
  `before_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Giá trị trước, đã mask dữ liệu nhạy cảm.' CHECK (json_valid(`before_json`)),
  `after_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Giá trị sau, đã mask dữ liệu nhạy cảm.' CHECK (json_valid(`after_json`)),
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP nguồn.',
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Client/user-agent.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[audit] Theo dõi thay đổi cấu hình, phân quyền, xác nhận cảnh báo.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_backup_jobs`
--

CREATE TABLE `ioc_backup_jobs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `asset_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Máy chủ/CSDL được backup. FK -> ops.ioc_monitoring_assets.id.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã job.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên job.',
  `schedule_cron` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lịch kỳ vọng.',
  `expected_duration_minutes` int(11) DEFAULT NULL COMMENT 'Thời lượng kỳ vọng.',
  `retention_days` int(11) DEFAULT NULL COMMENT 'Số ngày lưu.',
  `last_success_at` datetime(6) DEFAULT NULL COMMENT 'Lần thành công gần nhất.',
  `next_expected_at` datetime(6) DEFAULT NULL COMMENT 'Lần chạy kỳ vọng tiếp theo.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[ops] Cấu hình lịch backup phục vụ cảnh báo thất bại/trễ lịch.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_backup_runs`
--

CREATE TABLE `ioc_backup_runs` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `backup_job_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Job backup. FK -> ops.ioc_backup_jobs.id.',
  `started_at` datetime(6) NOT NULL COMMENT 'Bắt đầu.',
  `finished_at` datetime(6) DEFAULT NULL COMMENT 'Kết thúc.',
  `status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'RUNNING/SUCCESS/FAILED; BACKUP_STATUS. FK -> core.ioc_catalog_items.id.',
  `size_bytes` bigint(20) DEFAULT NULL COMMENT 'Dung lượng.',
  `backup_location` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vị trí logic; không chứa credential.',
  `error_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lỗi nếu có.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[ops] Nhật ký thực thi backup.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_bed_snapshots`
--

CREATE TABLE `ioc_bed_snapshots` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `captured_at` datetime(6) NOT NULL COMMENT 'Thời điểm snapshot.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khoa nội trú. FK -> core.ioc_org_units.id.',
  `planned_beds` int(11) NOT NULL DEFAULT 0 COMMENT 'Giường kế hoạch.',
  `actual_beds` int(11) NOT NULL DEFAULT 0 COMMENT 'Giường thực kê.',
  `occupied_beds` int(11) NOT NULL DEFAULT 0 COMMENT 'Giường đang sử dụng.',
  `inpatient_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Người bệnh đang điều trị nội trú.',
  `admissions_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Vào viện trong kỳ.',
  `discharges_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Ra viện trong kỳ.',
  `transfer_in_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Chuyển khoa vào.',
  `transfer_out_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Chuyển khoa ra.',
  `total_treatment_days` decimal(14,2) DEFAULT NULL COMMENT 'Tổng ngày điều trị.',
  `avg_length_of_stay` decimal(10,2) DEFAULT NULL COMMENT 'Số ngày điều trị trung bình.',
  `occupancy_percent` decimal(7,2) DEFAULT NULL COMMENT 'Công suất giường tính sẵn để đối soát.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn HIS. FK -> integration.ioc_source_systems.id.',
  `sync_run_id` bigint(20) DEFAULT NULL COMMENT 'Lần đồng bộ. FK -> integration.ioc_sync_runs.id.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[clinical] Snapshot giường kế hoạch/thực kê/đang sử dụng và người bệnh nội trú.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_care_flow_snapshots`
--

CREATE TABLE `ioc_care_flow_snapshots` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `captured_at` datetime(6) NOT NULL COMMENT 'Thời điểm snapshot.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa/phòng. FK -> core.ioc_org_units.id.',
  `visit_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngoại trú/nội trú; VISIT_TYPE. FK -> core.ioc_catalog_items.id.',
  `payer_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'BHYT/dịch vụ/khác; PAYER_TYPE. FK -> core.ioc_catalog_items.id.',
  `waiting_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số người bệnh đang chờ.',
  `examining_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số đang khám.',
  `completed_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lượt hoàn thành.',
  `admission_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số vào viện.',
  `discharge_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số ra viện.',
  `transfer_in_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số chuyển vào khoa.',
  `transfer_out_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số chuyển ra khoa.',
  `referral_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số chuyển tuyến/chuyển viện.',
  `avg_wait_minutes` decimal(10,2) DEFAULT NULL COMMENT 'Thời gian chờ trung bình nếu nguồn hỗ trợ.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn HIS. FK -> integration.ioc_source_systems.id.',
  `sync_run_id` bigint(20) DEFAULT NULL COMMENT 'Lần đồng bộ. FK -> integration.ioc_sync_runs.id.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[clinical] Snapshot luồng KCB: chờ/đang khám/hoàn thành, vào/ra/chuyển.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_catalogs`
--

CREATE TABLE `ioc_catalogs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã danh mục.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên danh mục.',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả.',
  `is_system` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Danh mục hệ thống.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[core] Đầu mục danh mục dùng chung, tránh hard-code.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_catalog_items`
--

CREATE TABLE `ioc_catalog_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `catalog_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Danh mục cha. FK -> core.ioc_catalogs.id.',
  `parent_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị cha nếu phân cấp. FK -> core.ioc_catalog_items.id.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã giá trị, duy nhất trong catalog.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hiển thị.',
  `value_text` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị text bổ sung.',
  `value_number` decimal(18,4) DEFAULT NULL COMMENT 'Giá trị số bổ sung.',
  `metadata_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Metadata mở rộng.' CHECK (json_valid(`metadata_json`)),
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự.',
  `effective_from` datetime(6) DEFAULT NULL COMMENT 'Hiệu lực từ.',
  `effective_to` datetime(6) DEFAULT NULL COMMENT 'Hiệu lực đến.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[core] Giá trị của từng danh mục, hỗ trợ phân cấp và metadata mở rộng.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_data_sources`
--

CREATE TABLE `ioc_data_sources` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hệ thống nguồn. FK -> integration.ioc_source_systems.id.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã nguồn dữ liệu.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nguồn dữ liệu.',
  `source_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DB_TABLE/DB_VIEW/API/FILE/ZABBIX_ITEM; DATA_SOURCE_TYPE. FK -> core.ioc_catalog_items.id.',
  `database_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên CSDL nếu nguồn DB.',
  `schema_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Schema nguồn.',
  `object_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Table/View/collection.',
  `endpoint_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn API tương đối.',
  `http_method` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'GET/POST...',
  `connection_profile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Thông tin không bí mật: host, port, timeout, params.' CHECK (json_valid(`connection_profile`)),
  `secret_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tham chiếu secret trong Vault/Secret Manager.',
  `expected_interval_seconds` int(11) DEFAULT NULL COMMENT 'Chu kỳ cập nhật kỳ vọng.',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Ho_Chi_Minh' COMMENT 'Múi giờ nguồn.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[integration] Khai báo CSDL/schema/table/view/API dùng để lấy dữ liệu.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_data_source_fields`
--

CREATE TABLE `ioc_data_source_fields` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `data_source_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn dữ liệu. FK -> integration.ioc_data_sources.id.',
  `field_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên trường gốc.',
  `field_type` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kiểu dữ liệu gốc.',
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên nghiệp vụ.',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ý nghĩa trường.',
  `is_key` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Có phải khóa nguồn.',
  `is_nullable` tinyint(1) DEFAULT NULL COMMENT 'Khả năng null.',
  `sample_masked` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ví dụ đã mask; không lưu dữ liệu nhạy cảm thật.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[integration] Từ điển trường nguồn phục vụ mẫu Trường/Bảng/API.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_employees`
--

CREATE TABLE `ioc_employees` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã nhân viên.',
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ tên.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa/phòng hiện tại. FK -> core.ioc_org_units.id.',
  `position_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức danh. FK -> core.ioc_positions.id.',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email công vụ.',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại công vụ.',
  `employment_status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái nhân sự; EMPLOYMENT_STATUS. FK -> core.ioc_catalog_items.id.',
  `joined_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu.',
  `left_date` date DEFAULT NULL COMMENT 'Ngày nghỉ/chuyển nếu có.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Được phép dùng trong IOC.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[core] Nhân viên/đầu mối xác nhận, nhận cảnh báo và quản trị IOC.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_metrics`
--

CREATE TABLE `ioc_metrics` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `metric_group_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nhóm chỉ số. FK -> analytics.ioc_metric_groups.id.',
  `code` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã KPI ổn định.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên KPI.',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Định nghĩa nghiệp vụ.',
  `unit_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị đo; UNIT. FK -> core.ioc_catalog_items.id.',
  `value_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NUMBER/PERCENT/DURATION/STATUS; METRIC_VALUE_TYPE. FK -> core.ioc_catalog_items.id.',
  `aggregation_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SUM/AVG/MAX/LAST/RATIO; AGGREGATION_TYPE. FK -> core.ioc_catalog_items.id.',
  `formula_expression` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Công thức logic được thống nhất.',
  `default_period_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chu kỳ mặc định; PERIOD_TYPE. FK -> core.ioc_catalog_items.id.',
  `supports_realtime` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Có thể hiển thị gần realtime.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[analytics] Danh mục định nghĩa KPI: công thức, đơn vị, chu kỳ, realtime.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_metric_dimensions`
--

CREATE TABLE `ioc_metric_dimensions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `metric_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'KPI. FK -> analytics.ioc_metrics.id.',
  `dimension_code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chiều.',
  `dimension_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chiều.',
  `source_expression` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trường/biểu thức lấy chiều.',
  `dimension_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại chiều; DIMENSION_TYPE. FK -> core.ioc_catalog_items.id.',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự.',
  `is_required` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Bắt buộc.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[analytics] Định nghĩa chiều phân tích động: khoa/phòng, đối tượng, trạng thái...';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_metric_groups`
--

CREATE TABLE `ioc_metric_groups` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `parent_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhóm cha. FK -> analytics.ioc_metric_groups.id.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã nhóm.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm.',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[analytics] Nhóm chỉ số Dashboard.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_metric_sources`
--

CREATE TABLE `ioc_metric_sources` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `metric_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'KPI. FK -> analytics.ioc_metrics.id.',
  `data_source_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn dữ liệu. FK -> integration.ioc_data_sources.id.',
  `source_expression` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trường/biểu thức nguồn.',
  `filter_expression` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Điều kiện lọc nghiệp vụ.',
  `refresh_interval_seconds` int(11) DEFAULT NULL COMMENT 'Chu kỳ thực tế.',
  `validation_owner_employee_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đầu mối xác nhận. FK -> core.ioc_employees.id.',
  `priority` int(11) NOT NULL DEFAULT 1 COMMENT 'Ưu tiên nguồn.',
  `is_primary` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Nguồn chính.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[analytics] Ánh xạ KPI với nguồn/trường/bảng/API và đầu mối xác nhận.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_metric_values`
--

CREATE TABLE `ioc_metric_values` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `metric_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'KPI. FK -> analytics.ioc_metrics.id.',
  `organization_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tổ chức. FK -> core.ioc_organizations.id.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa/phòng. FK -> core.ioc_org_units.id.',
  `period_start` datetime(6) NOT NULL COMMENT 'Bắt đầu kỳ.',
  `period_end` datetime(6) NOT NULL COMMENT 'Kết thúc kỳ.',
  `bucket_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'MINUTE/HOUR/DAY/WEEK/MONTH/QUARTER/YEAR; PERIOD_TYPE. FK -> core.ioc_catalog_items.id.',
  `dimension_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hash của dimensions_json.',
  `dimensions_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Chiều động: payer_type, visit_status, order_type...' CHECK (json_valid(`dimensions_json`)),
  `value_number` decimal(24,6) DEFAULT NULL COMMENT 'Giá trị số.',
  `value_text` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị text/status.',
  `numerator_value` decimal(24,6) DEFAULT NULL COMMENT 'Tử số cho KPI tỷ lệ.',
  `denominator_value` decimal(24,6) DEFAULT NULL COMMENT 'Mẫu số cho KPI tỷ lệ.',
  `data_as_of` datetime(6) NOT NULL COMMENT 'Thời điểm dữ liệu nguồn phản ánh.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nguồn. FK -> integration.ioc_source_systems.id.',
  `sync_run_id` bigint(20) DEFAULT NULL COMMENT 'Lần đồng bộ. FK -> integration.ioc_sync_runs.id.',
  `quality_status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'VALID/WARNING/INVALID; DATA_QUALITY_STATUS. FK -> core.ioc_catalog_items.id.',
  `calculated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tính.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[analytics] Kho giá trị KPI time-series tối ưu truy vấn Dashboard.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_monitoring_assets`
--

CREATE TABLE `ioc_monitoring_assets` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `organization_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tổ chức sở hữu. FK -> core.ioc_organizations.id.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị quản lý. FK -> core.ioc_org_units.id.',
  `parent_asset_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tài sản cha, ví dụ VM thuộc host. FK -> ops.ioc_monitoring_assets.id.',
  `asset_type_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SERVER/VM/NETWORK/DB/SERVICE/INTERNET; ASSET_TYPE. FK -> core.ioc_catalog_items.id.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã tài sản.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên tài sản.',
  `hostname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hostname.',
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP quản trị.',
  `vendor` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhà sản xuất.',
  `model` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model.',
  `zabbix_host_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID Host Zabbix.',
  `criticality_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mức quan trọng; ASSET_CRITICALITY. FK -> core.ioc_catalog_items.id.',
  `owner_employee_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đầu mối phụ trách. FK -> core.ioc_employees.id.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái giám sát.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[ops] Tài sản cần giám sát: server, VM, thiết bị mạng, DB, service, Internet link.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_monitoring_items`
--

CREATE TABLE `ioc_monitoring_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `asset_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tài sản. FK -> ops.ioc_monitoring_assets.id.',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã item chuẩn IOC, ví dụ CPU_PERCENT.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên item.',
  `metric_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'GAUGE/COUNTER/STATUS; MONITOR_METRIC_TYPE. FK -> core.ioc_catalog_items.id.',
  `unit_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '%, ms, °C, bytes...; UNIT. FK -> core.ioc_catalog_items.id.',
  `zabbix_item_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Item key của Zabbix.',
  `collection_interval_seconds` int(11) DEFAULT NULL COMMENT 'Chu kỳ thu thập.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[ops] Định nghĩa Item/metric kỹ thuật trên từng tài sản.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_monitoring_measurements`
--

CREATE TABLE `ioc_monitoring_measurements` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Item giám sát. FK -> ops.ioc_monitoring_items.id.',
  `observed_at` datetime(6) NOT NULL COMMENT 'Thời điểm quan trắc.',
  `numeric_value` decimal(24,6) DEFAULT NULL COMMENT 'Giá trị số.',
  `text_value` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giá trị text/status.',
  `status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NORMAL/WARNING/ERROR/UNKNOWN; MEASUREMENT_STATUS. FK -> core.ioc_catalog_items.id.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn Zabbix/monitoring. FK -> integration.ioc_source_systems.id.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[ops] Time-series CPU/RAM/disk/nhiệt độ/latency/loss/availability.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_notification_deliveries`
--

CREATE TABLE `ioc_notification_deliveries` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `alert_event_id` bigint(20) NOT NULL COMMENT 'Cảnh báo. FK -> alerting.ioc_alert_events.id.',
  `group_member_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Người nhận. FK -> alerting.ioc_notification_group_members.id.',
  `channel_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'EMAIL/SMS/ZALO/PUSH; NOTIFICATION_CHANNEL. FK -> core.ioc_catalog_items.id.',
  `sent_at` datetime(6) DEFAULT NULL COMMENT 'Thời điểm gửi.',
  `status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'PENDING/SENT/DELIVERED/FAILED; DELIVERY_STATUS. FK -> core.ioc_catalog_items.id.',
  `provider_message_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID nhà cung cấp.',
  `retry_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lần thử lại.',
  `error_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lỗi.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[alerting] Log gửi thông báo từng thành viên/kênh.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_notification_groups`
--

CREATE TABLE `ioc_notification_groups` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã nhóm.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên nhóm.',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[alerting] Nhóm nhận cảnh báo theo vận hành/CNTT/lãnh đạo.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_notification_group_members`
--

CREATE TABLE `ioc_notification_group_members` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `group_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nhóm. FK -> alerting.ioc_notification_groups.id.',
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhân viên nội bộ nếu có. FK -> core.ioc_employees.id.',
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên đầu mối ngoài danh sách nhân viên.',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email.',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SĐT.',
  `zalo_target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Target tích hợp Zalo.',
  `channel_preferences_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Bật/tắt kênh, quiet hours, ưu tiên.' CHECK (json_valid(`channel_preferences_json`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[alerting] Thành viên/đầu mối và kênh Email/SĐT/Zalo.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_notification_subscriptions`
--

CREATE TABLE `ioc_notification_subscriptions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `alert_rule_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Luật. FK -> alerting.ioc_alert_rules.id.',
  `notification_group_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nhóm nhận. FK -> alerting.ioc_notification_groups.id.',
  `organization_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lọc theo tổ chức. FK -> core.ioc_organizations.id.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lọc theo khoa/phòng. FK -> core.ioc_org_units.id.',
  `notify_on_open` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Gửi khi mở.',
  `notify_on_escalation` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Gửi khi nâng mức.',
  `notify_on_resolve` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Gửi khi phục hồi.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[alerting] Gắn luật cảnh báo với nhóm nhận và phạm vi áp dụng.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_organizations`
--

CREATE TABLE `ioc_organizations` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `parent_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị cấp trên; null nếu là gốc. FK -> core.ioc_organizations.id.',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã tổ chức duy nhất.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên tổ chức/cơ sở y tế.',
  `org_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại tổ chức; thuộc catalog ORG_TYPE. FK -> core.ioc_catalog_items.id.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái sử dụng.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[core] Đơn vị pháp nhân/cơ sở y tế vận hành Care IOC.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_org_units`
--

CREATE TABLE `ioc_org_units` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `organization_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tổ chức sở hữu. FK -> core.ioc_organizations.id.',
  `parent_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị cha. FK -> core.ioc_org_units.id.',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã khoa/phòng, duy nhất trong tổ chức.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên khoa/phòng/chuyên khoa.',
  `unit_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại đơn vị; ORG_UNIT_TYPE. FK -> core.ioc_catalog_items.id.',
  `specialty_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chuyên khoa; SPECIALTY. FK -> core.ioc_catalog_items.id.',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái sử dụng.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[core] Cây khoa/phòng/chuyên khoa/bộ phận; chiều phân tích chính của Dashboard.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_permissions`
--

CREATE TABLE `ioc_permissions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã quyền, ví dụ DASHBOARD.VIEW.',
  `module` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Module.',
  `resource` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tài nguyên.',
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'VIEW/CREATE/UPDATE/DELETE/EXPORT/ACK...',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[iam] Quyền chi tiết theo module/resource/action.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_positions`
--

CREATE TABLE `ioc_positions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã chức danh.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chức danh.',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[core] Danh mục chức danh/vị trí công việc.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_quality_snapshots`
--

CREATE TABLE `ioc_quality_snapshots` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `period_start` datetime(6) NOT NULL COMMENT 'Đầu kỳ.',
  `period_end` datetime(6) NOT NULL COMMENT 'Cuối kỳ.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa/phòng nếu phân tách. FK -> core.ioc_org_units.id.',
  `records_due` int(11) DEFAULT NULL COMMENT 'Số hồ sơ đến hạn.',
  `records_completed_on_time` int(11) DEFAULT NULL COMMENT 'Số hồ sơ hoàn thành đúng hạn.',
  `records_total` int(11) DEFAULT NULL COMMENT 'Tổng hồ sơ.',
  `electronic_records` int(11) DEFAULT NULL COMMENT 'Số BAĐT.',
  `payment_total_amount` decimal(20,2) DEFAULT NULL COMMENT 'Tổng giá trị thanh toán.',
  `cashless_amount` decimal(20,2) DEFAULT NULL COMMENT 'Giá trị không dùng tiền mặt.',
  `satisfaction_responses` int(11) DEFAULT NULL COMMENT 'Số phản hồi hợp lệ.',
  `satisfaction_positive` int(11) DEFAULT NULL COMMENT 'Số phản hồi hài lòng.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn chính. FK -> integration.ioc_source_systems.id.',
  `sync_run_id` bigint(20) DEFAULT NULL COMMENT 'Lần đồng bộ. FK -> integration.ioc_sync_runs.id.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[clinical] Tổng hợp BA đúng hạn, BAĐT, thanh toán không tiền mặt, hài lòng.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_roles`
--

CREATE TABLE `ioc_roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã vai trò.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên vai trò.',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả.',
  `is_system` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Vai trò hệ thống.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[iam] Vai trò nghiệp vụ.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_role_permissions`
--

CREATE TABLE `ioc_role_permissions` (
  `role_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Vai trò. FK -> iam.ioc_roles.id.',
  `permission_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Quyền. FK -> iam.ioc_permissions.id.',
  `grant_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ALLOW' COMMENT 'ALLOW/DENY.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[iam] Ánh xạ vai trò - quyền.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_service_order_snapshots`
--

CREATE TABLE `ioc_service_order_snapshots` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `captured_at` datetime(6) NOT NULL COMMENT 'Thời điểm snapshot.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa chỉ định/thực hiện. FK -> core.ioc_org_units.id.',
  `order_type_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'LAB/IMAGING/CLS_OTHER; ORDER_TYPE. FK -> core.ioc_catalog_items.id.',
  `ordered_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số chỉ định.',
  `completed_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số hoàn thành.',
  `pending_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số đang chờ.',
  `source_system_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn. FK -> integration.ioc_source_systems.id.',
  `sync_run_id` bigint(20) DEFAULT NULL COMMENT 'Lần đồng bộ. FK -> integration.ioc_sync_runs.id.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[clinical] Snapshot số lượt chỉ định xét nghiệm/CĐHA/CLS.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_source_systems`
--

CREATE TABLE `ioc_source_systems` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hệ thống nguồn.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hệ thống.',
  `system_type_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại hệ thống; SOURCE_SYSTEM_TYPE. FK -> core.ioc_catalog_items.id.',
  `vendor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhà cung cấp.',
  `version` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phiên bản.',
  `base_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Base URL kỹ thuật nếu có.',
  `owner_org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị quản lý. FK -> core.ioc_org_units.id.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[integration] Danh mục hệ thống nguồn: HIS/LIS/RIS/PACS/EMR/Zabbix/khác.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_sync_jobs`
--

CREATE TABLE `ioc_sync_jobs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã job.',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên job.',
  `data_source_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nguồn dữ liệu. FK -> integration.ioc_data_sources.id.',
  `target_entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Bảng/luồng đích IOC.',
  `schedule_cron` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cron; null nếu stream/event.',
  `incremental_field` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trường watermark để tải tăng dần.',
  `lookback_minutes` int(11) NOT NULL DEFAULT 0 COMMENT 'Cửa sổ đọc lùi chống bỏ sót cập nhật.',
  `batch_size` int(11) DEFAULT NULL COMMENT 'Kích thước batch.',
  `last_success_at` datetime(6) DEFAULT NULL COMMENT 'Lần thành công gần nhất.',
  `next_run_at` datetime(6) DEFAULT NULL COMMENT 'Lần dự kiến tiếp theo.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[integration] Cấu hình job ETL/ELT/API pull/push.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_sync_runs`
--

CREATE TABLE `ioc_sync_runs` (
  `id` bigint(20) NOT NULL COMMENT 'Khóa chính.',
  `sync_job_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Job thực thi. FK -> integration.ioc_sync_jobs.id.',
  `started_at` datetime(6) NOT NULL COMMENT 'Bắt đầu.',
  `finished_at` datetime(6) DEFAULT NULL COMMENT 'Kết thúc.',
  `status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'RUNNING/SUCCESS/FAILED/PARTIAL; SYNC_STATUS. FK -> core.ioc_catalog_items.id.',
  `rows_read` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Số bản ghi đọc.',
  `rows_written` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Số bản ghi ghi.',
  `rows_rejected` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Số bản ghi bị loại.',
  `watermark_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Watermark đầu.',
  `watermark_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Watermark cuối.',
  `error_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lỗi rút gọn.',
  `run_metadata_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Metadata kỹ thuật.' CHECK (json_valid(`run_metadata_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[integration] Nhật ký từng lần đồng bộ và độ tươi dữ liệu.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_users`
--

CREATE TABLE `ioc_users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Liên kết nhân viên nội bộ. FK -> core.ioc_employees.id.',
  `username` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên đăng nhập.',
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hash mật khẩu nếu dùng local auth; không lưu mật khẩu rõ.',
  `auth_provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LOCAL' COMMENT 'LOCAL/LDAP/OIDC/SSO.',
  `external_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID/subject bên hệ thống xác thực ngoài.',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hiển thị.',
  `citizen_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số CCCD/Định danh cá nhân.',
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức vụ công tác.',
  `department` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khoa phòng / Phòng ban.',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email.',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số điện thoại liên hệ.',
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Giới tính.',
  `birthday` date DEFAULT NULL COMMENT 'Ngày sinh.',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ thường trú.',
  `status_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trạng thái tài khoản; USER_STATUS. FK -> core.ioc_catalog_items.id.',
  `last_login_at` datetime(6) DEFAULT NULL COMMENT 'Lần đăng nhập gần nhất.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Cho phép đăng nhập.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[iam] Tài khoản đăng nhập Care IOC.';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ioc_user_roles`
--

CREATE TABLE `ioc_user_roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Khóa chính.',
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Người dùng. FK -> iam.ioc_users.id.',
  `role_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Vai trò. FK -> iam.ioc_roles.id.',
  `organization_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phạm vi tổ chức; null = không giới hạn bởi tổ chức. FK -> core.ioc_organizations.id.',
  `org_unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phạm vi khoa/phòng; null = toàn tổ chức. FK -> core.ioc_org_units.id.',
  `valid_from` datetime(6) DEFAULT NULL COMMENT 'Hiệu lực từ.',
  `valid_to` datetime(6) DEFAULT NULL COMMENT 'Hiệu lực đến.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái.',
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm tạo bản ghi.',
  `updated_at` datetime(6) NOT NULL DEFAULT current_timestamp(6) COMMENT 'Thời điểm cập nhật gần nhất.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='[iam] Gán vai trò cho người dùng kèm phạm vi tổ chức/khoa phòng.';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ioc_alert_events`
--
ALTER TABLE `ioc_alert_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_ioc_alert_events_status_item_id_opened_at` (`status_item_id`,`opened_at`),
  ADD KEY `ix_ioc_alert_events_asset_id_opened_at` (`asset_id`,`opened_at`),
  ADD KEY `ix_ioc_alert_events_org_unit_id_opened_at` (`org_unit_id`,`opened_at`),
  ADD KEY `fk_ioc_alert_events_rule_id_ioc_alert_rules` (`rule_id`),
  ADD KEY `fk_ioc_alert_events_metric_value_id_ioc_metric_values` (`metric_value_id`),
  ADD KEY `fk_ioc_alert_events_measurement_id_ioc_monitoring_measurements` (`measurement_id`),
  ADD KEY `fk_ioc_alert_events_organization_id_ioc_organizations` (`organization_id`),
  ADD KEY `fk_ioc_alert_events_severity_item_id_ioc_catalog_items` (`severity_item_id`),
  ADD KEY `fk_ioc_alert_events_ack_by_user_id_ioc_users` (`ack_by_user_id`),
  ADD KEY `fk_ioc_alert_events_resolved_by_user_id_ioc_users` (`resolved_by_user_id`);

--
-- Chỉ mục cho bảng `ioc_alert_rules`
--
ALTER TABLE `ioc_alert_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_alert_rules_code` (`code`),
  ADD KEY `fk_ioc_alert_rules_metric_id_ioc_metrics` (`metric_id`),
  ADD KEY `fk_ioc_alert_rules_monitoring_item_id_ioc_monitoring_items` (`monitoring_item_id`),
  ADD KEY `fk_ioc_alert_rules_rule_type_item_id_ioc_catalog_items` (`rule_type_item_id`),
  ADD KEY `fk_ioc_alert_rules_operator_item_id_ioc_catalog_items` (`operator_item_id`),
  ADD KEY `fk_ioc_alert_rules_severity_item_id_ioc_catalog_items` (`severity_item_id`);

--
-- Chỉ mục cho bảng `ioc_audit_logs`
--
ALTER TABLE `ioc_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_ioc_audit_logs_occurred_at` (`occurred_at`),
  ADD KEY `ix_ioc_audit_logs_user_id_occurred_at` (`user_id`,`occurred_at`);

--
-- Chỉ mục cho bảng `ioc_backup_jobs`
--
ALTER TABLE `ioc_backup_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_backup_jobs_code` (`code`),
  ADD KEY `fk_ioc_backup_jobs_asset_id_ioc_monitoring_assets` (`asset_id`);

--
-- Chỉ mục cho bảng `ioc_backup_runs`
--
ALTER TABLE `ioc_backup_runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ioc_backup_runs_backup_job_id_ioc_backup_jobs` (`backup_job_id`),
  ADD KEY `fk_ioc_backup_runs_status_item_id_ioc_catalog_items` (`status_item_id`);

--
-- Chỉ mục cho bảng `ioc_bed_snapshots`
--
ALTER TABLE `ioc_bed_snapshots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_bed_snapshots_captured_at_org_unit_id` (`captured_at`,`org_unit_id`),
  ADD KEY `fk_ioc_bed_snapshots_org_unit_id_ioc_org_units` (`org_unit_id`),
  ADD KEY `fk_ioc_bed_snapshots_source_system_id_ioc_source_systems` (`source_system_id`),
  ADD KEY `fk_ioc_bed_snapshots_sync_run_id_ioc_sync_runs` (`sync_run_id`);

--
-- Chỉ mục cho bảng `ioc_care_flow_snapshots`
--
ALTER TABLE `ioc_care_flow_snapshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_ioc_care_flow_snapshots_captured_at_org_unit_id` (`captured_at`,`org_unit_id`),
  ADD KEY `fk_ioc_care_flow_snapshots_org_unit_id_ioc_org_units` (`org_unit_id`),
  ADD KEY `fk_ioc_care_flow_snapshots_visit_type_item_id_ioc_catalog_items` (`visit_type_item_id`),
  ADD KEY `fk_ioc_care_flow_snapshots_payer_type_item_id_ioc_catalog_items` (`payer_type_item_id`),
  ADD KEY `fk_ioc_care_flow_snapshots_source_system_id_ioc_source_systems` (`source_system_id`),
  ADD KEY `fk_ioc_care_flow_snapshots_sync_run_id_ioc_sync_runs` (`sync_run_id`);

--
-- Chỉ mục cho bảng `ioc_catalogs`
--
ALTER TABLE `ioc_catalogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_catalogs_code` (`code`);

--
-- Chỉ mục cho bảng `ioc_catalog_items`
--
ALTER TABLE `ioc_catalog_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_catalog_items_catalog_id_code` (`catalog_id`,`code`),
  ADD KEY `ix_ioc_catalog_items_parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `ioc_data_sources`
--
ALTER TABLE `ioc_data_sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_data_sources_source_system_id_code` (`source_system_id`,`code`),
  ADD KEY `fk_ioc_data_sources_source_type_item_id_ioc_catalog_items` (`source_type_item_id`);

--
-- Chỉ mục cho bảng `ioc_data_source_fields`
--
ALTER TABLE `ioc_data_source_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_data_source_fields_data_source_id_field_name` (`data_source_id`,`field_name`);

--
-- Chỉ mục cho bảng `ioc_employees`
--
ALTER TABLE `ioc_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_employees_employee_code` (`employee_code`),
  ADD KEY `fk_ioc_employees_org_unit_id_ioc_org_units` (`org_unit_id`),
  ADD KEY `fk_ioc_employees_position_id_ioc_positions` (`position_id`),
  ADD KEY `fk_ioc_employees_employment_status_item_id_ioc_catalog_items` (`employment_status_item_id`);

--
-- Chỉ mục cho bảng `ioc_metrics`
--
ALTER TABLE `ioc_metrics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_metrics_code` (`code`),
  ADD KEY `fk_ioc_metrics_metric_group_id_ioc_metric_groups` (`metric_group_id`),
  ADD KEY `fk_ioc_metrics_unit_item_id_ioc_catalog_items` (`unit_item_id`),
  ADD KEY `fk_ioc_metrics_value_type_item_id_ioc_catalog_items` (`value_type_item_id`),
  ADD KEY `fk_ioc_metrics_aggregation_item_id_ioc_catalog_items` (`aggregation_item_id`),
  ADD KEY `fk_ioc_metrics_default_period_item_id_ioc_catalog_items` (`default_period_item_id`);

--
-- Chỉ mục cho bảng `ioc_metric_dimensions`
--
ALTER TABLE `ioc_metric_dimensions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_metric_dimensions_metric_id_dimension_code` (`metric_id`,`dimension_code`),
  ADD KEY `fk_ioc_metric_dimensions_dimension_type_item_id_ioc_cat_e03993ac` (`dimension_type_item_id`);

--
-- Chỉ mục cho bảng `ioc_metric_groups`
--
ALTER TABLE `ioc_metric_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_metric_groups_code` (`code`),
  ADD KEY `fk_ioc_metric_groups_parent_id_ioc_metric_groups` (`parent_id`);

--
-- Chỉ mục cho bảng `ioc_metric_sources`
--
ALTER TABLE `ioc_metric_sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_ioc_metric_sources_metric_id_data_source_id` (`metric_id`,`data_source_id`),
  ADD KEY `fk_ioc_metric_sources_data_source_id_ioc_data_sources` (`data_source_id`),
  ADD KEY `fk_ioc_metric_sources_validation_owner_employee_id_ioc_employees` (`validation_owner_employee_id`);

--
-- Chỉ mục cho bảng `ioc_metric_values`
--
ALTER TABLE `ioc_metric_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_metric_values_metric_id_organization_id_org_unit_760cb17e` (`metric_id`,`organization_id`,`org_unit_id`,`period_start`,`period_end`,`dimension_hash`),
  ADD KEY `ix_ioc_metric_values_metric_id_period_start` (`metric_id`,`period_start`),
  ADD KEY `ix_ioc_metric_values_org_unit_id_period_start` (`org_unit_id`,`period_start`),
  ADD KEY `ix_ioc_metric_values_data_as_of` (`data_as_of`),
  ADD KEY `fk_ioc_metric_values_organization_id_ioc_organizations` (`organization_id`),
  ADD KEY `fk_ioc_metric_values_bucket_item_id_ioc_catalog_items` (`bucket_item_id`),
  ADD KEY `fk_ioc_metric_values_source_system_id_ioc_source_systems` (`source_system_id`),
  ADD KEY `fk_ioc_metric_values_sync_run_id_ioc_sync_runs` (`sync_run_id`),
  ADD KEY `fk_ioc_metric_values_quality_status_item_id_ioc_catalog_items` (`quality_status_item_id`);

--
-- Chỉ mục cho bảng `ioc_monitoring_assets`
--
ALTER TABLE `ioc_monitoring_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_monitoring_assets_organization_id_code` (`organization_id`,`code`),
  ADD KEY `ix_ioc_monitoring_assets_zabbix_host_id` (`zabbix_host_id`),
  ADD KEY `fk_ioc_monitoring_assets_org_unit_id_ioc_org_units` (`org_unit_id`),
  ADD KEY `fk_ioc_monitoring_assets_parent_asset_id_ioc_monitoring_assets` (`parent_asset_id`),
  ADD KEY `fk_ioc_monitoring_assets_asset_type_item_id_ioc_catalog_items` (`asset_type_item_id`),
  ADD KEY `fk_ioc_monitoring_assets_criticality_item_id_ioc_catalog_items` (`criticality_item_id`),
  ADD KEY `fk_ioc_monitoring_assets_owner_employee_id_ioc_employees` (`owner_employee_id`);

--
-- Chỉ mục cho bảng `ioc_monitoring_items`
--
ALTER TABLE `ioc_monitoring_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_monitoring_items_asset_id_code` (`asset_id`,`code`),
  ADD KEY `fk_ioc_monitoring_items_metric_type_item_id_ioc_catalog_items` (`metric_type_item_id`),
  ADD KEY `fk_ioc_monitoring_items_unit_item_id_ioc_catalog_items` (`unit_item_id`);

--
-- Chỉ mục cho bảng `ioc_monitoring_measurements`
--
ALTER TABLE `ioc_monitoring_measurements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_monitoring_measurements_item_id_observed_at` (`item_id`,`observed_at`),
  ADD KEY `ix_ioc_monitoring_measurements_observed_at` (`observed_at`),
  ADD KEY `fk_ioc_monitoring_measurements_status_item_id_ioc_catalog_items` (`status_item_id`),
  ADD KEY `fk_ioc_monitoring_measurements_source_system_id_ioc_sou_85fd66f7` (`source_system_id`);

--
-- Chỉ mục cho bảng `ioc_notification_deliveries`
--
ALTER TABLE `ioc_notification_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ioc_notification_deliveries_alert_event_id_ioc_alert_events` (`alert_event_id`),
  ADD KEY `fk_ioc_notification_deliveries_group_member_id_ioc_noti_8f2ae98b` (`group_member_id`),
  ADD KEY `fk_ioc_notification_deliveries_channel_item_id_ioc_catalog_items` (`channel_item_id`),
  ADD KEY `fk_ioc_notification_deliveries_status_item_id_ioc_catalog_items` (`status_item_id`);

--
-- Chỉ mục cho bảng `ioc_notification_groups`
--
ALTER TABLE `ioc_notification_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_notification_groups_code` (`code`);

--
-- Chỉ mục cho bảng `ioc_notification_group_members`
--
ALTER TABLE `ioc_notification_group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ioc_notification_group_members_group_id_ioc_notifica_f5aece94` (`group_id`),
  ADD KEY `fk_ioc_notification_group_members_employee_id_ioc_employees` (`employee_id`);

--
-- Chỉ mục cho bảng `ioc_notification_subscriptions`
--
ALTER TABLE `ioc_notification_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_notification_subscriptions_alert_rule_id_notific_bc0413e3` (`alert_rule_id`,`notification_group_id`,`organization_id`,`org_unit_id`),
  ADD KEY `fk_ioc_notification_subscriptions_notification_group_id_b39cdbdb` (`notification_group_id`),
  ADD KEY `fk_ioc_notification_subscriptions_organization_id_ioc_o_d2cfc614` (`organization_id`),
  ADD KEY `fk_ioc_notification_subscriptions_org_unit_id_ioc_org_units` (`org_unit_id`);

--
-- Chỉ mục cho bảng `ioc_organizations`
--
ALTER TABLE `ioc_organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_organizations_code` (`code`),
  ADD KEY `fk_ioc_organizations_parent_id_ioc_organizations` (`parent_id`),
  ADD KEY `fk_ioc_organizations_org_type_item_id_ioc_catalog_items` (`org_type_item_id`);

--
-- Chỉ mục cho bảng `ioc_org_units`
--
ALTER TABLE `ioc_org_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_org_units_organization_id_code` (`organization_id`,`code`),
  ADD KEY `ix_ioc_org_units_parent_id` (`parent_id`),
  ADD KEY `fk_ioc_org_units_unit_type_item_id_ioc_catalog_items` (`unit_type_item_id`),
  ADD KEY `fk_ioc_org_units_specialty_item_id_ioc_catalog_items` (`specialty_item_id`);

--
-- Chỉ mục cho bảng `ioc_permissions`
--
ALTER TABLE `ioc_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_permissions_code` (`code`);

--
-- Chỉ mục cho bảng `ioc_positions`
--
ALTER TABLE `ioc_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_positions_code` (`code`);

--
-- Chỉ mục cho bảng `ioc_quality_snapshots`
--
ALTER TABLE `ioc_quality_snapshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_ioc_quality_snapshots_period_start_period_end_org_unit_id` (`period_start`,`period_end`,`org_unit_id`),
  ADD KEY `fk_ioc_quality_snapshots_org_unit_id_ioc_org_units` (`org_unit_id`),
  ADD KEY `fk_ioc_quality_snapshots_source_system_id_ioc_source_systems` (`source_system_id`),
  ADD KEY `fk_ioc_quality_snapshots_sync_run_id_ioc_sync_runs` (`sync_run_id`);

--
-- Chỉ mục cho bảng `ioc_roles`
--
ALTER TABLE `ioc_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_roles_code` (`code`);

--
-- Chỉ mục cho bảng `ioc_role_permissions`
--
ALTER TABLE `ioc_role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fk_ioc_role_permissions_permission_id_ioc_permissions` (`permission_id`);

--
-- Chỉ mục cho bảng `ioc_service_order_snapshots`
--
ALTER TABLE `ioc_service_order_snapshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_ioc_service_order_snapshots_captured_at_org_unit_id__bd9b0066` (`captured_at`,`org_unit_id`,`order_type_item_id`),
  ADD KEY `fk_ioc_service_order_snapshots_org_unit_id_ioc_org_units` (`org_unit_id`),
  ADD KEY `fk_ioc_service_order_snapshots_order_type_item_id_ioc_c_d71dc460` (`order_type_item_id`),
  ADD KEY `fk_ioc_service_order_snapshots_source_system_id_ioc_sou_ee3d0054` (`source_system_id`),
  ADD KEY `fk_ioc_service_order_snapshots_sync_run_id_ioc_sync_runs` (`sync_run_id`);

--
-- Chỉ mục cho bảng `ioc_source_systems`
--
ALTER TABLE `ioc_source_systems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_source_systems_code` (`code`),
  ADD KEY `fk_ioc_source_systems_system_type_item_id_ioc_catalog_items` (`system_type_item_id`),
  ADD KEY `fk_ioc_source_systems_owner_org_unit_id_ioc_org_units` (`owner_org_unit_id`);

--
-- Chỉ mục cho bảng `ioc_sync_jobs`
--
ALTER TABLE `ioc_sync_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_sync_jobs_code` (`code`),
  ADD KEY `fk_ioc_sync_jobs_data_source_id_ioc_data_sources` (`data_source_id`);

--
-- Chỉ mục cho bảng `ioc_sync_runs`
--
ALTER TABLE `ioc_sync_runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ioc_sync_runs_sync_job_id_ioc_sync_jobs` (`sync_job_id`),
  ADD KEY `fk_ioc_sync_runs_status_item_id_ioc_catalog_items` (`status_item_id`);

--
-- Chỉ mục cho bảng `ioc_users`
--
ALTER TABLE `ioc_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_users_username` (`username`),
  ADD KEY `fk_ioc_users_employee_id_ioc_employees` (`employee_id`),
  ADD KEY `fk_ioc_users_status_item_id_ioc_catalog_items` (`status_item_id`);

--
-- Chỉ mục cho bảng `ioc_user_roles`
--
ALTER TABLE `ioc_user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ioc_user_roles_user_id_role_id_organization_id_org_unit_id` (`user_id`,`role_id`,`organization_id`,`org_unit_id`),
  ADD KEY `fk_ioc_user_roles_role_id_ioc_roles` (`role_id`),
  ADD KEY `fk_ioc_user_roles_organization_id_ioc_organizations` (`organization_id`),
  ADD KEY `fk_ioc_user_roles_org_unit_id_ioc_org_units` (`org_unit_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ioc_alert_events`
--
ALTER TABLE `ioc_alert_events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_audit_logs`
--
ALTER TABLE `ioc_audit_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_backup_runs`
--
ALTER TABLE `ioc_backup_runs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_bed_snapshots`
--
ALTER TABLE `ioc_bed_snapshots`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_care_flow_snapshots`
--
ALTER TABLE `ioc_care_flow_snapshots`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_metric_values`
--
ALTER TABLE `ioc_metric_values`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_monitoring_measurements`
--
ALTER TABLE `ioc_monitoring_measurements`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_notification_deliveries`
--
ALTER TABLE `ioc_notification_deliveries`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_quality_snapshots`
--
ALTER TABLE `ioc_quality_snapshots`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_service_order_snapshots`
--
ALTER TABLE `ioc_service_order_snapshots`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- AUTO_INCREMENT cho bảng `ioc_sync_runs`
--
ALTER TABLE `ioc_sync_runs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính.';

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ioc_alert_events`
--
ALTER TABLE `ioc_alert_events`
  ADD CONSTRAINT `fk_ioc_alert_events_ack_by_user_id_ioc_users` FOREIGN KEY (`ack_by_user_id`) REFERENCES `ioc_users` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_asset_id_ioc_monitoring_assets` FOREIGN KEY (`asset_id`) REFERENCES `ioc_monitoring_assets` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_measurement_id_ioc_monitoring_measurements` FOREIGN KEY (`measurement_id`) REFERENCES `ioc_monitoring_measurements` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_metric_value_id_ioc_metric_values` FOREIGN KEY (`metric_value_id`) REFERENCES `ioc_metric_values` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_organization_id_ioc_organizations` FOREIGN KEY (`organization_id`) REFERENCES `ioc_organizations` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_resolved_by_user_id_ioc_users` FOREIGN KEY (`resolved_by_user_id`) REFERENCES `ioc_users` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_rule_id_ioc_alert_rules` FOREIGN KEY (`rule_id`) REFERENCES `ioc_alert_rules` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_severity_item_id_ioc_catalog_items` FOREIGN KEY (`severity_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_events_status_item_id_ioc_catalog_items` FOREIGN KEY (`status_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_alert_rules`
--
ALTER TABLE `ioc_alert_rules`
  ADD CONSTRAINT `fk_ioc_alert_rules_metric_id_ioc_metrics` FOREIGN KEY (`metric_id`) REFERENCES `ioc_metrics` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_rules_monitoring_item_id_ioc_monitoring_items` FOREIGN KEY (`monitoring_item_id`) REFERENCES `ioc_monitoring_items` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_rules_operator_item_id_ioc_catalog_items` FOREIGN KEY (`operator_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_rules_rule_type_item_id_ioc_catalog_items` FOREIGN KEY (`rule_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_alert_rules_severity_item_id_ioc_catalog_items` FOREIGN KEY (`severity_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_audit_logs`
--
ALTER TABLE `ioc_audit_logs`
  ADD CONSTRAINT `fk_ioc_audit_logs_user_id_ioc_users` FOREIGN KEY (`user_id`) REFERENCES `ioc_users` (`id`);

--
-- Các ràng buộc cho bảng `ioc_backup_jobs`
--
ALTER TABLE `ioc_backup_jobs`
  ADD CONSTRAINT `fk_ioc_backup_jobs_asset_id_ioc_monitoring_assets` FOREIGN KEY (`asset_id`) REFERENCES `ioc_monitoring_assets` (`id`);

--
-- Các ràng buộc cho bảng `ioc_backup_runs`
--
ALTER TABLE `ioc_backup_runs`
  ADD CONSTRAINT `fk_ioc_backup_runs_backup_job_id_ioc_backup_jobs` FOREIGN KEY (`backup_job_id`) REFERENCES `ioc_backup_jobs` (`id`),
  ADD CONSTRAINT `fk_ioc_backup_runs_status_item_id_ioc_catalog_items` FOREIGN KEY (`status_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_bed_snapshots`
--
ALTER TABLE `ioc_bed_snapshots`
  ADD CONSTRAINT `fk_ioc_bed_snapshots_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_bed_snapshots_source_system_id_ioc_source_systems` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_bed_snapshots_sync_run_id_ioc_sync_runs` FOREIGN KEY (`sync_run_id`) REFERENCES `ioc_sync_runs` (`id`);

--
-- Các ràng buộc cho bảng `ioc_care_flow_snapshots`
--
ALTER TABLE `ioc_care_flow_snapshots`
  ADD CONSTRAINT `fk_ioc_care_flow_snapshots_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_care_flow_snapshots_payer_type_item_id_ioc_catalog_items` FOREIGN KEY (`payer_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_care_flow_snapshots_source_system_id_ioc_source_systems` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_care_flow_snapshots_sync_run_id_ioc_sync_runs` FOREIGN KEY (`sync_run_id`) REFERENCES `ioc_sync_runs` (`id`),
  ADD CONSTRAINT `fk_ioc_care_flow_snapshots_visit_type_item_id_ioc_catalog_items` FOREIGN KEY (`visit_type_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_catalog_items`
--
ALTER TABLE `ioc_catalog_items`
  ADD CONSTRAINT `fk_ioc_catalog_items_catalog_id_ioc_catalogs` FOREIGN KEY (`catalog_id`) REFERENCES `ioc_catalogs` (`id`),
  ADD CONSTRAINT `fk_ioc_catalog_items_parent_id_ioc_catalog_items` FOREIGN KEY (`parent_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_data_sources`
--
ALTER TABLE `ioc_data_sources`
  ADD CONSTRAINT `fk_ioc_data_sources_source_system_id_ioc_source_systems` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_data_sources_source_type_item_id_ioc_catalog_items` FOREIGN KEY (`source_type_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_data_source_fields`
--
ALTER TABLE `ioc_data_source_fields`
  ADD CONSTRAINT `fk_ioc_data_source_fields_data_source_id_ioc_data_sources` FOREIGN KEY (`data_source_id`) REFERENCES `ioc_data_sources` (`id`);

--
-- Các ràng buộc cho bảng `ioc_employees`
--
ALTER TABLE `ioc_employees`
  ADD CONSTRAINT `fk_ioc_employees_employment_status_item_id_ioc_catalog_items` FOREIGN KEY (`employment_status_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_employees_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_employees_position_id_ioc_positions` FOREIGN KEY (`position_id`) REFERENCES `ioc_positions` (`id`);

--
-- Các ràng buộc cho bảng `ioc_metrics`
--
ALTER TABLE `ioc_metrics`
  ADD CONSTRAINT `fk_ioc_metrics_aggregation_item_id_ioc_catalog_items` FOREIGN KEY (`aggregation_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_metrics_default_period_item_id_ioc_catalog_items` FOREIGN KEY (`default_period_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_metrics_metric_group_id_ioc_metric_groups` FOREIGN KEY (`metric_group_id`) REFERENCES `ioc_metric_groups` (`id`),
  ADD CONSTRAINT `fk_ioc_metrics_unit_item_id_ioc_catalog_items` FOREIGN KEY (`unit_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_metrics_value_type_item_id_ioc_catalog_items` FOREIGN KEY (`value_type_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_metric_dimensions`
--
ALTER TABLE `ioc_metric_dimensions`
  ADD CONSTRAINT `fk_ioc_metric_dimensions_dimension_type_item_id_ioc_cat_e03993ac` FOREIGN KEY (`dimension_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_dimensions_metric_id_ioc_metrics` FOREIGN KEY (`metric_id`) REFERENCES `ioc_metrics` (`id`);

--
-- Các ràng buộc cho bảng `ioc_metric_groups`
--
ALTER TABLE `ioc_metric_groups`
  ADD CONSTRAINT `fk_ioc_metric_groups_parent_id_ioc_metric_groups` FOREIGN KEY (`parent_id`) REFERENCES `ioc_metric_groups` (`id`);

--
-- Các ràng buộc cho bảng `ioc_metric_sources`
--
ALTER TABLE `ioc_metric_sources`
  ADD CONSTRAINT `fk_ioc_metric_sources_data_source_id_ioc_data_sources` FOREIGN KEY (`data_source_id`) REFERENCES `ioc_data_sources` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_sources_metric_id_ioc_metrics` FOREIGN KEY (`metric_id`) REFERENCES `ioc_metrics` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_sources_validation_owner_employee_id_ioc_employees` FOREIGN KEY (`validation_owner_employee_id`) REFERENCES `ioc_employees` (`id`);

--
-- Các ràng buộc cho bảng `ioc_metric_values`
--
ALTER TABLE `ioc_metric_values`
  ADD CONSTRAINT `fk_ioc_metric_values_bucket_item_id_ioc_catalog_items` FOREIGN KEY (`bucket_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_values_metric_id_ioc_metrics` FOREIGN KEY (`metric_id`) REFERENCES `ioc_metrics` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_values_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_values_organization_id_ioc_organizations` FOREIGN KEY (`organization_id`) REFERENCES `ioc_organizations` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_values_quality_status_item_id_ioc_catalog_items` FOREIGN KEY (`quality_status_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_values_source_system_id_ioc_source_systems` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_metric_values_sync_run_id_ioc_sync_runs` FOREIGN KEY (`sync_run_id`) REFERENCES `ioc_sync_runs` (`id`);

--
-- Các ràng buộc cho bảng `ioc_monitoring_assets`
--
ALTER TABLE `ioc_monitoring_assets`
  ADD CONSTRAINT `fk_ioc_monitoring_assets_asset_type_item_id_ioc_catalog_items` FOREIGN KEY (`asset_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_assets_criticality_item_id_ioc_catalog_items` FOREIGN KEY (`criticality_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_assets_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_assets_organization_id_ioc_organizations` FOREIGN KEY (`organization_id`) REFERENCES `ioc_organizations` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_assets_owner_employee_id_ioc_employees` FOREIGN KEY (`owner_employee_id`) REFERENCES `ioc_employees` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_assets_parent_asset_id_ioc_monitoring_assets` FOREIGN KEY (`parent_asset_id`) REFERENCES `ioc_monitoring_assets` (`id`);

--
-- Các ràng buộc cho bảng `ioc_monitoring_items`
--
ALTER TABLE `ioc_monitoring_items`
  ADD CONSTRAINT `fk_ioc_monitoring_items_asset_id_ioc_monitoring_assets` FOREIGN KEY (`asset_id`) REFERENCES `ioc_monitoring_assets` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_items_metric_type_item_id_ioc_catalog_items` FOREIGN KEY (`metric_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_items_unit_item_id_ioc_catalog_items` FOREIGN KEY (`unit_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_monitoring_measurements`
--
ALTER TABLE `ioc_monitoring_measurements`
  ADD CONSTRAINT `fk_ioc_monitoring_measurements_item_id_ioc_monitoring_items` FOREIGN KEY (`item_id`) REFERENCES `ioc_monitoring_items` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_measurements_source_system_id_ioc_sou_85fd66f7` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_monitoring_measurements_status_item_id_ioc_catalog_items` FOREIGN KEY (`status_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_notification_deliveries`
--
ALTER TABLE `ioc_notification_deliveries`
  ADD CONSTRAINT `fk_ioc_notification_deliveries_alert_event_id_ioc_alert_events` FOREIGN KEY (`alert_event_id`) REFERENCES `ioc_alert_events` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_deliveries_channel_item_id_ioc_catalog_items` FOREIGN KEY (`channel_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_deliveries_group_member_id_ioc_noti_8f2ae98b` FOREIGN KEY (`group_member_id`) REFERENCES `ioc_notification_group_members` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_deliveries_status_item_id_ioc_catalog_items` FOREIGN KEY (`status_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_notification_group_members`
--
ALTER TABLE `ioc_notification_group_members`
  ADD CONSTRAINT `fk_ioc_notification_group_members_employee_id_ioc_employees` FOREIGN KEY (`employee_id`) REFERENCES `ioc_employees` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_group_members_group_id_ioc_notifica_f5aece94` FOREIGN KEY (`group_id`) REFERENCES `ioc_notification_groups` (`id`);

--
-- Các ràng buộc cho bảng `ioc_notification_subscriptions`
--
ALTER TABLE `ioc_notification_subscriptions`
  ADD CONSTRAINT `fk_ioc_notification_subscriptions_alert_rule_id_ioc_alert_rules` FOREIGN KEY (`alert_rule_id`) REFERENCES `ioc_alert_rules` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_subscriptions_notification_group_id_b39cdbdb` FOREIGN KEY (`notification_group_id`) REFERENCES `ioc_notification_groups` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_subscriptions_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_notification_subscriptions_organization_id_ioc_o_d2cfc614` FOREIGN KEY (`organization_id`) REFERENCES `ioc_organizations` (`id`);

--
-- Các ràng buộc cho bảng `ioc_organizations`
--
ALTER TABLE `ioc_organizations`
  ADD CONSTRAINT `fk_ioc_organizations_org_type_item_id_ioc_catalog_items` FOREIGN KEY (`org_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_organizations_parent_id_ioc_organizations` FOREIGN KEY (`parent_id`) REFERENCES `ioc_organizations` (`id`);

--
-- Các ràng buộc cho bảng `ioc_org_units`
--
ALTER TABLE `ioc_org_units`
  ADD CONSTRAINT `fk_ioc_org_units_organization_id_ioc_organizations` FOREIGN KEY (`organization_id`) REFERENCES `ioc_organizations` (`id`),
  ADD CONSTRAINT `fk_ioc_org_units_parent_id_ioc_org_units` FOREIGN KEY (`parent_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_org_units_specialty_item_id_ioc_catalog_items` FOREIGN KEY (`specialty_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_org_units_unit_type_item_id_ioc_catalog_items` FOREIGN KEY (`unit_type_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_quality_snapshots`
--
ALTER TABLE `ioc_quality_snapshots`
  ADD CONSTRAINT `fk_ioc_quality_snapshots_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_quality_snapshots_source_system_id_ioc_source_systems` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_quality_snapshots_sync_run_id_ioc_sync_runs` FOREIGN KEY (`sync_run_id`) REFERENCES `ioc_sync_runs` (`id`);

--
-- Các ràng buộc cho bảng `ioc_role_permissions`
--
ALTER TABLE `ioc_role_permissions`
  ADD CONSTRAINT `fk_ioc_role_permissions_permission_id_ioc_permissions` FOREIGN KEY (`permission_id`) REFERENCES `ioc_permissions` (`id`),
  ADD CONSTRAINT `fk_ioc_role_permissions_role_id_ioc_roles` FOREIGN KEY (`role_id`) REFERENCES `ioc_roles` (`id`);

--
-- Các ràng buộc cho bảng `ioc_service_order_snapshots`
--
ALTER TABLE `ioc_service_order_snapshots`
  ADD CONSTRAINT `fk_ioc_service_order_snapshots_order_type_item_id_ioc_c_d71dc460` FOREIGN KEY (`order_type_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_service_order_snapshots_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_service_order_snapshots_source_system_id_ioc_sou_ee3d0054` FOREIGN KEY (`source_system_id`) REFERENCES `ioc_source_systems` (`id`),
  ADD CONSTRAINT `fk_ioc_service_order_snapshots_sync_run_id_ioc_sync_runs` FOREIGN KEY (`sync_run_id`) REFERENCES `ioc_sync_runs` (`id`);

--
-- Các ràng buộc cho bảng `ioc_source_systems`
--
ALTER TABLE `ioc_source_systems`
  ADD CONSTRAINT `fk_ioc_source_systems_owner_org_unit_id_ioc_org_units` FOREIGN KEY (`owner_org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_source_systems_system_type_item_id_ioc_catalog_items` FOREIGN KEY (`system_type_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_sync_jobs`
--
ALTER TABLE `ioc_sync_jobs`
  ADD CONSTRAINT `fk_ioc_sync_jobs_data_source_id_ioc_data_sources` FOREIGN KEY (`data_source_id`) REFERENCES `ioc_data_sources` (`id`);

--
-- Các ràng buộc cho bảng `ioc_sync_runs`
--
ALTER TABLE `ioc_sync_runs`
  ADD CONSTRAINT `fk_ioc_sync_runs_status_item_id_ioc_catalog_items` FOREIGN KEY (`status_item_id`) REFERENCES `ioc_catalog_items` (`id`),
  ADD CONSTRAINT `fk_ioc_sync_runs_sync_job_id_ioc_sync_jobs` FOREIGN KEY (`sync_job_id`) REFERENCES `ioc_sync_jobs` (`id`);

--
-- Các ràng buộc cho bảng `ioc_users`
--
ALTER TABLE `ioc_users`
  ADD CONSTRAINT `fk_ioc_users_employee_id_ioc_employees` FOREIGN KEY (`employee_id`) REFERENCES `ioc_employees` (`id`),
  ADD CONSTRAINT `fk_ioc_users_status_item_id_ioc_catalog_items` FOREIGN KEY (`status_item_id`) REFERENCES `ioc_catalog_items` (`id`);

--
-- Các ràng buộc cho bảng `ioc_user_roles`
--
ALTER TABLE `ioc_user_roles`
  ADD CONSTRAINT `fk_ioc_user_roles_org_unit_id_ioc_org_units` FOREIGN KEY (`org_unit_id`) REFERENCES `ioc_org_units` (`id`),
  ADD CONSTRAINT `fk_ioc_user_roles_organization_id_ioc_organizations` FOREIGN KEY (`organization_id`) REFERENCES `ioc_organizations` (`id`),
  ADD CONSTRAINT `fk_ioc_user_roles_role_id_ioc_roles` FOREIGN KEY (`role_id`) REFERENCES `ioc_roles` (`id`),
  ADD CONSTRAINT `fk_ioc_user_roles_user_id_ioc_users` FOREIGN KEY (`user_id`) REFERENCES `ioc_users` (`id`);

-- --------------------------------------------------------
-- Cấu trúc các bảng Phân Quyền Động mới (Auth & Dynamic RBAC)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `ioc_auth_menu_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version_tag` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã phiên bản, vd: v1.0',
  `config_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung JSON đầy đủ',
  `content_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hash kiểm tra thay đổi',
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'SYSTEM' COMMENT 'Người chỉnh sửa',
  `change_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú lý do thay đổi',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Bản đang dùng, 0: Bản cũ',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký phiên bản cấu hình JSON menu & quyền';

CREATE TABLE IF NOT EXISTS `ioc_auth_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã vai trò',
  `role_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên hiển thị vai trò',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả vai trò',
  `menu_access` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mảng JSON menu được nhìn thấy',
  `permissions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mảng JSON quyền chi tiết (thêm/sửa/cấm xóa)',
  `is_system` tinyint(1) DEFAULT 0 COMMENT '1: Vai trò hệ thống',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Kích hoạt, 0: Tạm khóa',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_code` (`role_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh mục vai trò phân quyền';

CREATE TABLE IF NOT EXISTS `ioc_auth_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tham chiếu ioc_users.id',
  `role_id` int(11) NOT NULL COMMENT 'Tham chiếu ioc_auth_roles.id',
  `assigned_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Người gán vai trò',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_role` (`user_id`,`role_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Gán vai trò cho cán bộ y tế';

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
