# Hướng dẫn các review code bằng GPT

## 1.Cấu trúc

Đặt các files và folder theo cấu trúc sau:

```
root
    |
    |-- review
        |
        |-- checklist.md
        |-- review-kaizen-file.py
        |-- report.md
```

## 2.Giải thích

- **checklist.md**: chứa nội dung review checklist

- **review-kaizen-file.py**: file thực thi review code và trả kết quả ra file review.md

- **report.md**: chứa kết quả review

## 3.Cách sử dụng

1. Đặt folder review vào thư mục root dự án.

2. Vào môi trường Linux thông qua Docker:
```bash
    docker exec -it ten_container bash
```

3. Tiến hành cài đặt Python3 như hướng dẫn trên (Bước 1,2,3).

4. Kích hoạt môi trường ảo Python:
```bash
	source review/python-virtual-env/bin/activate
```

5. Bắt đầu chạy lệnh AI Review bằng Python
```bash
    python review/review-kaizen-file.py --base develop --feature feature/20250719_something
```
- `--base develop` : nhánh chuẩn
- `--feature feature/20250719_something` : nhánh cần review so sánh 
- Lưu ý: 
    - Phải pull source mới nhất cho nhánh chuẩn.
    - Xem lại Model ChatGPT để tiết kiệm chi phí.

7. Giải thích tiến trình chạy lệnh review
- Bash sẽ tự động lấy diff file của 2 nhánh.
- Tiến hành chạy lên PHPCS để lấy nội dung lỗi style coding.
- Tiến hành chạy PHPStan level 6 để lấy nội dung lỗi analytic.
- Lấy nội dung Checklist review từ file được chỉ định.
- Tạo Prompt và gửi lên A.I nhờ review.
- Tiến trình sẽ chạy loop từng file, và sẽ có 3 options để lựa chọn trước khi thực hiện:
    - Option 1: Yes,review file
    - Option 2: Skip (bỏ qua file này) - không review file này
    - Option 3: Quit (dừng tiến trình review)
- Kết quả sẽ trả về file report.md

8. Thoát khỏi môi trường ảo của Python() - optional:
```bash
    deactivate
```

## 4.Hướng dẫn cài đặt Python3 trong Ubutun (WSL)

0. Nâng cấp apt:

```bash
   apt-get update
```

1. Cài đặt Python3:

```bash
    sudo apt install python3-full  hoặc apt install python3-full 
```

2. Install thư viện Pip3:
```bash
	apt install python3-pip
```

3. Vào thư mục review tạo môi trường ảo cho Python3 (chỉ dành cho Ubutun và Debian) với tên **python-virtual-env**:
```bash
	python3 -m venv review/python-virtual-env
```

4. Kích hoạt môi trường ảo (trong folder review):
```bash
	source review/python-virtual-env/bin/activate
```

5. Install thư viện openai (trong môi trường ảo) - optional:

```bash
    pip install openai==0.28
```

6. Thoát môi trường ảo (trong folder review):
```bash
    deactivate
```

