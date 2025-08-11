# Quy định Review Code (Rule Set)

## 1. Quy tắc chung
- Code phải **dễ đọc**, có ý nghĩa, tránh viết tắt khó hiểu.
- Comment chỉ khi cần thiết, không lặp lại ý nghĩa code.
- Không để lại **code thừa, code comment** không dùng.
- Tránh **hardcode** giá trị; nên dùng biến, hằng số, hoặc config.
- Tất cả file đều phải **theo chuẩn định dạng** (PSR-1, PSR-12 nếu PHP; PEP8 nếu Python, v.v.).

## 2. Quy tắc đặt tên
- Biến: `camelCase`.
- Hằng số: `UPPER_CASE`.
- Class: `PascalCase`.
- Tên hàm: `camelCase` và mô tả đúng chức năng.

## 3. Cấu trúc & tổ chức code
- Tách code thành các hàm/method nhỏ gọn (dưới 50 dòng).
- Tránh lồng quá 3 cấp `if/else` hoặc `for/while`.
- Đảm bảo code **DRY** (Don't Repeat Yourself) — tránh trùng lặp logic.
- Sử dụng **early return** để giảm nesting.

## 4. Xử lý lỗi & ngoại lệ
- Luôn kiểm tra input (null, empty, type).
- Sử dụng try/catch khi gọi function có khả năng lỗi.
- Không để lỗi crash toàn bộ hệ thống.

## 5. Bảo mật
- Không để lộ thông tin nhạy cảm (API key, mật khẩu, token).
- Escape/validate dữ liệu trước khi render ra UI hoặc truy vấn DB.
- Dùng prepared statement hoặc ORM để tránh SQL Injection.
- Không log thông tin nhạy cảm.

## 6. Performance
- Tránh truy vấn DB trong vòng lặp.
- Chỉ load dữ liệu cần thiết (SELECT field cụ thể, LIMIT).
- Giải phóng bộ nhớ sau khi dùng biến lớn.
- Ưu tiên thuật toán tối ưu hơn thay vì brute force.

## 7. Tiêu chuẩn ngôn ngữ cụ thể (ví dụ PHP PSR)
- PSR-1: Khai báo namespace, class, method rõ ràng.
- PSR-12: Thụt lề 4 spaces, mở ngoặc `{` trên dòng mới với class và function.
- PSR-4: Tự động load class theo namespace.

## 8. Output yêu cầu khi review
Khi review code, hãy trả kết quả theo bảng:

| File | Dòng | Vấn đề | Gợi ý sửa |
|------|------|--------|-----------|
| tên_file.php | 25 | Hàm quá dài (80 dòng) | Tách hàm thành nhiều hàm nhỏ |
| main.py | 12 | Không validate input | Thêm kiểm tra dữ liệu đầu vào |

