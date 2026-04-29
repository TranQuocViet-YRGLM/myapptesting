## 9. PHPStan Level 6 Rules (Static Analysis Inspired)

### 9.1 Type Safety
- Tất cả function/method phải khai báo **type cho parameter và return**.
- Không sử dụng `mixed` nếu có thể xác định type cụ thể.
- Không truyền sai type vào function (ví dụ: string vào int).

---

### 9.2 Null Safety
- Luôn kiểm tra null trước khi gọi method hoặc truy cập property.
- Sử dụng toán tử null-safe (`?->`) khi cần.
- Không giả định biến luôn có giá trị.

---

### 9.3 Undefined Variable
- Không sử dụng biến chưa được khởi tạo.
- Không rely vào biến global hoặc biến ngoài scope.

---

### 9.4 Method & Property Access
- Không gọi method không tồn tại.
- Không truy cập property không tồn tại.
- Đảm bảo object đúng type trước khi gọi method.

---

### 9.5 Return Type Consistency
- Function phải luôn return đúng type đã khai báo.
- Không return nhiều kiểu dữ liệu khác nhau (trừ khi dùng union type rõ ràng).

---

### 9.6 Array Handling
- Tránh sử dụng array không rõ structure.
- Nếu có thể, mô tả rõ kiểu array (array<string, int>, array<User>).
- Không truy cập index không tồn tại.

---

### 9.7 Strict Comparison
- Ưu tiên dùng `===` thay vì `==`.
- Tránh so sánh lỏng gây lỗi logic.

---

### 9.8 Dead Code
- Không để code không bao giờ chạy.
- Không để branch không thể xảy ra.

---

### 9.9 Exception Handling
- Không bỏ qua exception.
- Nếu function có thể throw → phải xử lý hoặc document rõ.

---

### 9.10 Casting & Type Conversion
- Tránh ép kiểu không rõ ràng.
- Kiểm tra dữ liệu trước khi cast.
