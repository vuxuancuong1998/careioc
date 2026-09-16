-- Một khoa/phòng và đối tượng thanh toán có thể có một bản ghi cho mỗi ngày báo cáo.
ALTER TABLE ioc_outpatient_daily
  DROP INDEX uq_ioc_outpatient_daily,
  ADD UNIQUE KEY uq_ioc_outpatient_daily (report_date, department_id, payer_type_id);
