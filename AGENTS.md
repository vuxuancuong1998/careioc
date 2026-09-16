# Quy ước phát triển CARE IOC

Hệ thống phục vụ **Điều hành thông minh cho Bệnh viện Đa khoa Khu vực Đăk Tô**. Áp dụng các quy ước này cho mọi hạng mục mới hoặc thay đổi liên quan.

## Giao diện

- Thiết kế responsive cho điện thoại, máy tính bảng và máy tính; ưu tiên luồng thao tác rõ ràng, ít bước và dễ sử dụng trong môi trường bệnh viện.
- Form phải kiểm tra dữ liệu cả phía trình duyệt lẫn máy chủ: trường bắt buộc, định dạng, miền giá trị và dữ liệu tham chiếu trước khi lưu.
- Sau mọi tác vụ (thêm, sửa, xóa, import, gọi API), hiển thị thông báo nhỏ, dễ đọc về thành công hoặc thất bại. Không sử dụng hộp thoại `alert()` cho thông báo nghiệp vụ thông thường.
- Thông báo lỗi phải nêu nguyên nhân có thể xử lý; không trả về lỗi kỹ thuật hoặc thông tin nhạy cảm cho người dùng.

## Luồng frontend và backend

- Điều hướng trang, lấy dữ liệu hiển thị ban đầu và xử lý logic trang đặt tại `backendController`.
- Mỗi action hiển thị trang tại `backendController` gọi `$this->view->backendtmp('tên-file-view')`, trong đó view tương ứng nằm tại thư mục `template/backend`.
- Tác vụ thay đổi dữ liệu từ frontend sử dụng AJAX bằng jQuery gửi đến `apiController`; API phản hồi JSON nhất quán, tối thiểu gồm `success`, `message` và `data` khi cần.
- Frontend chịu trách nhiệm hiển thị, bắt sự kiện và khai báo jQuery AJAX; `apiController` chịu trách nhiệm xác thực, query database và xử lý nghiệp vụ rồi trả kết quả JSON cho frontend.
- API phải xác thực yêu cầu, kiểm tra CSRF/quyền truy cập theo cơ chế hiện có, validate lại toàn bộ dữ liệu ở máy chủ và chỉ trả về dữ liệu cần thiết.
- Frontend cập nhật bảng/danh sách sau phản hồi AJAX, đồng thời dùng SweetAlert (`Swal.fire`) để hiển thị thông báo thành công/thất bại; không dùng `alert()` cho thông báo nghiệp vụ.
- Ưu tiên code sạch, trực tiếp và dễ đọc; chỉ tách hàm khi có trách nhiệm rõ ràng, tránh gọi hoặc tạo quá nhiều hàm làm rối luồng xử lý.

## Dữ liệu và tích hợp

- Hệ thống hỗ trợ hai nguồn nhập dữ liệu: nhập thủ công có kiểm soát và đồng bộ/call API từ các phần mềm LIS, RIS, EMR.
- Thiết kế bảng dữ liệu cần lưu được nguồn dữ liệu, thời điểm đồng bộ/nhập, trạng thái xử lý và khóa định danh để chống trùng lặp khi đồng bộ.
- Không ghi đè dữ liệu tích hợp bằng dữ liệu thủ công mà không có quy tắc nghiệp vụ rõ ràng; lưu dấu vết thay đổi khi hạng mục có yêu cầu kiểm toán.
- Tích hợp ngoài cần có xử lý lỗi, timeout, ghi log và cơ chế chạy lại an toàn.

## Quy trình nhận công việc từ Google Sheet

- Google Sheet theo dõi công việc: `Bảng tổng hợp công việc nhóm - dev` (tab `Công việc`, kèm `Danh mục` và `Dashboard`).
- Khi người dùng gửi đúng lệnh `procces in google sheet`, đọc mã việc và mô tả chi tiết đã được cung cấp trong Sheet, sau đó đối chiếu với mã nguồn hiện tại trước khi thay đổi.
- Triển khai giao diện/điều hướng và logic hiển thị trong `backendController`; triển khai tác vụ AJAX, kiểm tra dữ liệu và phản hồi JSON trong `apiController`.
- Sau khi thực hiện, kiểm tra cú pháp và luồng liên quan, rồi báo cáo chính xác các tệp/chức năng đã thay đổi.
