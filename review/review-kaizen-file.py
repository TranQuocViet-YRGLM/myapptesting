# python review/review.py --base develop --feature feature/20250719_something


# Script review code tự động sử dụng OpenAI Codex và các tool kiểm tra chất lượng code
#!/usr/bin/env python3
import os  # Thư viện thao tác với hệ điều hành (đường dẫn, biến môi trường, ...)
import sys  # Thư viện hệ thống (dùng cho cài package, thoát chương trình, ...)
import subprocess  # Chạy lệnh shell từ Python
import argparse  # Xử lý tham số dòng lệnh
import json  # Xử lý dữ liệu JSON
import time  # Đo thời gian thực thi
import threading  # Tạo luồng cho spinner loading
from datetime import datetime  # Thư viện làm việc với thời gian
# ========== Check & Install Dependencies ==========



# Hàm kiểm tra và cài đặt package Python nếu thiếu
def ensure_package(pkg, import_name=None):
    """Check & install missing python packages."""
    try:
        __import__(import_name or pkg)
    except ImportError:
        print(f"📦 Thiếu thư viện {pkg}, tiến hành cài đặt...")
        subprocess.check_call([sys.executable, "-m", "pip", "install", pkg])
        print(f"✅ Cài đặt {pkg} thành công.")




# Kiểm tra và cài đặt các package cần thiết
ensure_package("requests")
ensure_package("PyYAML", "yaml")
ensure_package("tiktoken")


import requests  # Thư viện gọi API HTTP
import yaml  # Đọc file rules.yml
import tiktoken  # Thư viện tính toán token cho OpenAI API

# ========== Config ==========
# Đường dẫn các file sử dụng trong quá trình review
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))  # Thư mục chứa script
REVIEW_DIR = SCRIPT_DIR  # Thư mục review
DIFF_FILE = os.path.join(REVIEW_DIR, "diff.txt")  # File lưu diff
LINT_FILE = os.path.join(REVIEW_DIR, "lint.txt")  # File lưu kết quả lint
# rename file
# report.html => report-<branches>-<timestamp>.html
timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
REPORT_FILE = os.path.join(REVIEW_DIR, f"report-{timestamp}.html")  # File lưu report cuối cùng
# RULES_FILE = os.path.join(SCRIPT_DIR, "../.codex/rules.yml")  # File rule checklist
RULES_FILE = os.path.join(SCRIPT_DIR, "checklist.md")  # File rule checklist

OPENAI_API_KEY = os.getenv("OPENAI_API_KEY")  # API key
OPENAI_MODEL = os.getenv("OPENAI_MODEL", "gpt-5-mini")  # Model sử dụng
OPENAI_ENDPOINT = "https://api.openai.com/v1/chat/completions"  # Endpoint API

# ========== Utils ==========

# Hàm log ra màn hình
def log(msg):
    print(f"👉 {msg}")


# Hàm chạy lệnh shell và trả về output dạng chuỗi
def run_cmd(cmd):
    result = subprocess.run(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    return result.stdout.decode("utf-8", errors="ignore")

# Hàm tạo hiệu ứng loading spinner khi chờ API
def spinner(stop_event):
    while not stop_event.is_set():
        for char in "|/-\\":
            sys.stdout.write(f"\r⏳ Loading... {char}")
            sys.stdout.flush()
            time.sleep(0.1)
    sys.stdout.write("\r✅ Done!         \n")
    sys.stdout.flush()

# ========== Main ==========

# ========== Batch Review Functions ==========

# Hàm lấy diff và kết quả lint cho một file cụ thể


# Hàm lấy diff và kết quả lint cho một file cụ thể
def get_file_diff_and_lint(file_path):
    # Lấy toàn bộ nội dung file
    with open(file_path, "r", encoding="utf-8") as f:
        file_content = f.read()
    # Chạy phpcs kiểm tra chuẩn PSR-12
    # phpcs = run_cmd(f"./vendor/bin/phpcs --standard=PSR12 '{file_path}' || true")
    phpcs = run_cmd(f"./vendor/bin/phpcs -sq --standard=phpcs.xml '{file_path}' || true")
    # Chạy phpstan kiểm tra static analysis
    phpstan = run_cmd(f"./vendor/bin/phpstan analyse --level=6 --memory-limit=1024M '{file_path}' || true")
    # Gộp kết quả lint lại
    lint = f"## phpcs (PSR-12)\n{phpcs}\n## phpstan (Level 6)\n{phpstan}\n"
    return file_content, lint


def build_openai_prompt(file_path, diff, lint, rules_text):
        return f"""
Bạn là 1 Techlead hơn 10 năm kinh nghiệm.

Dựa vào nội dung **Diff**, **Lint** sau đây, hãy review code theo tiêu chuẩn **Rules checklist** và chuẩn SonarLint. Xuất kết quả theo yêu cầu **Output**:

**Rules checklist**
{rules_text}

File: {file_path}
**Diff**
{diff}

**Lint**
{lint}

**Output**

### 1. Issues theo rules
- Phân loại theo mức độ: `Critical | Major | Minor`.
- Phân biệt rõ issues từ **Diff** (code thay đổi) và từ **Lint**.
- Mapping mỗi issue với rule tương ứng trong **Rules checklist**.
- Đề xuất cách fix ngắn gọn + cung cấp ví dụ code đúng chuẩn.
- Nếu không có issue thì trả về: "✅ No issues found".
- Nhóm theo từng file, sắp xếp theo thứ tự dòng code.

**Output bảng:**

| # | Severity | Source (Diff/Lint) | Line | Rule in Checklist | Issue | Suggest Fix |

Nội dung Suggest Fix phải là tiếng Việt, các phần coding phải đưa vào code block.

### 2. Summary
- Tổng hợp số lượng issues theo `Critical/Major/Minor`.
- Chỉ liệt kê các rules vi phạm trong **Rules checklist**, thống kê số bug trong từng rule vi phạm.
- Không cần nhắc lại rules không vi phạm.

### 3. Suggest Code Improvements
- Suggest lại toàn bộ file sau khi đã fix các issues và optimize.
- Phần coding phải đưa vào code block.

Toàn bộ output sẽ xuất ra HTML với Bootstrap CSS. 
Nội dung chỉ cần được định dạng lại cho phù hợp với HTML và được wrapper trong <div class="review-result"></div>.
Hãy đặt thêm 'class="php language-php hljs"' vào tag <code>.
Tag <code> phải đặt nằm trong thẻ <pre><code>...</code></pre>
Nội dung code không cần bắt đầu bằng '<!--?php'.
"""

#         return f"""
# Bạn là 1 Techlead hơn 10 năm kinh nghiệm.

# Dựa vào nội dung **Diff**, **Lint** sau đây, hãy review code theo tiêu chuẩn **Rules checklist** và chuẩn SonarLint. Xuất kết quả theo yêu cầu **Output**:

# **Rules checklist**
# {rules_text}

# File: {file_path}
# **Diff**
# {diff}

# **Lint**
# {lint}

# **Output**

# ### 1. Issues theo rules
# - Phân loại theo mức độ: `Critical | Major | Minor`.
# - Phân biệt rõ issues từ **Diff** (code thay đổi) và từ **Lint**.
# - Mapping mỗi issue với rule tương ứng trong **Rules checklist**.
# - Đề xuất cách fix ngắn gọn + cung cấp ví dụ code đúng chuẩn.
# - Nếu không có issue thì trả về: "✅ No issues found".
# - Nhóm theo từng file, sắp xếp theo thứ tự dòng code.

# **Output bảng:**

# | # | Severity | Source (Diff/Lint) | Line | Rule in Checklist | Issue | Suggest Fix |

# Nội dung Suggest Fix phải là tiếng Việt

# ### 2. Refactor Suggestions
# - Đưa ra đề xuất refactor cho toàn bộ file (không chỉ phần diff).
# - Gợi ý các cải tiến về:
#     - Tách hàm / class để giảm duplication & tăng readability.
#     - Giảm độ phức tạp cyclomatic.
#     - Đặt lại tên biến/hàm/class dễ hiểu hơn.
#     - Áp dụng pattern hoặc best practice (SOLID, DRY, KISS, Clean Code).
#     - Cải thiện hiệu năng (nếu có).
# - Format bằng bullet points, có ví dụ minh họa nếu cần.

# ### 3. Summary
# - Tổng hợp số lượng issues theo `Critical/Major/Minor`.
# - Chỉ liệt kê các rules vi phạm trong **Rules checklist**, không cần nhắc lại rules không vi phạm.

# ### 4. Suggest Code Improvements
# - Suggest lại toàn bộ file sau khi đã fix các issues và optimize.
# - phần coding phải đưa vào code block trong markdown
# """

def estimate_tokens(prompt, encoding):
    return len(encoding.encode(prompt))


# Hàm xác nhận lựa chọn của người dùng trước khi review file
def confirm_review_option(file_path, input_tokens):
    print(f"\n\033[92mBạn muốn review file: {file_path}?\033[0m")
    print(f"\033[90mƯớc lượng số input token cho file này: {input_tokens}\033[0m")
    print("1. Yes (review file)")
    print("2. Skip (bỏ qua file này)")
    print("3. Quit (dừng tiến trình review)")
    return input("\033[90mChọn (1/2/3 hoặc Yes/Skip/Quit): \033[0m").strip().lower()

# Hàm gọi OpenAI API
def call_openai_api(prompt, model, api_key, endpoint):
    # print(f"\n\n {prompt}\n\n")
    payload = {
        "model": model,
        "messages": [
            {"role": "system", "content": "Bạn là 1 Techlead hơn 10 năm kinh nghiệm."},
            {"role": "user", "content": prompt}
        ]
    }
    stop_event = threading.Event()
    t = threading.Thread(target=spinner, args=(stop_event,))
    t.start()
    start_openai = time.time()
    try:
        response = requests.post(
            endpoint,
            headers={"Authorization": f"Bearer {api_key}", "Content-Type": "application/json"},
            data=json.dumps(payload),
            timeout=600
        )
        if response.status_code != 200:
            print("\n❌ API Error:", response.status_code, response.text)
            review_result = ""
            usage = {"prompt_tokens": 0, "completion_tokens": 0, "total_tokens": 0}
        else:
            resp_json = response.json()
            review_result = resp_json["choices"][0]["message"]["content"]
            usage = resp_json.get("usage", {})
            prompt_tokens = usage.get("prompt_tokens", 0)
            completion_tokens = usage.get("completion_tokens", 0)
            total_tokens_file = usage.get("total_tokens", 0)
            print(f"\n🔢 OpenAI tokens used: prompt={prompt_tokens}, completion={completion_tokens}, total={total_tokens_file}")
    finally:
        stop_event.set()
        t.join()
    end_openai = time.time()
    log(f"⏱️ Thời gian gọi OpenAI: {end_openai - start_openai:.2f}s")
    return review_result, usage


# Hàm ghi report tổng hợp kết quả review và thống kê
def write_report(report_path, all_reviews, total_exec_time, total_tokens, total_prompt_tokens, total_completion_tokens, base_branch, feature_branch):
    with open(report_path, "w", encoding="utf-8") as f:
        ## add thẻ head vào report.html
        f.write(f"<!DOCTYPE html>\n<html lang=\"vi\">\n<head>\n<meta charset=\"UTF-8\">\n<title>Code Review Report</title>\n<link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\">")
        # add <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs2015.min.css"> vào report.html
        f.write(f"<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs2015.min.css\">")
        # add <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlightjs-line-numbers.js@2.8.0/dist/highlightjs-line-numbers.min.css"> vào report.html
        f.write(f"<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/highlightjs-line-numbers.js@2.8.0/dist/highlightjs-line-numbers.min.css\">\n")
        # add <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"> vào report.html
        f.write(f"<script src=\"https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js\"></script>\n")
        f.write(f"<script src=\"https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/languages/php.min.js\"></script>\n")
        f.write(f"<script src=\"https://cdn.jsdelivr.net/npm/highlightjs-line-numbers.js@2.8.0/dist/highlightjs-line-numbers.min.js\"></script>\n")
        f.write(f"<script src=\"https://cdn.jsdelivr.net/npm/highlightjs-copy@1.0.3/dist/highlightjs-copy.min.js\"></script>\n")
        f.write("""
        <style>
        .container {
            max-width: 2140px !important;
        }
        pre {
            position: relative;
            /* Đảm bảo nút sao chép nằm đúng vị trí */
        }
        .hljs-copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #1e1e1e;
            /* Phù hợp với theme vs2015 */
            color: #d4d4d4;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 12px;
            z-index: 10;
            /* Đảm bảo nút không bị che khuất */
        }
        .hljs-copy-button:hover {
            background: #3c3c3c;
        }
        .hljs-ln-line.hljs-ln-numbers {
            padding-right: 10px !important;
            /* Đảm bảo khoảng cách giữa số dòng và mã */
        }
        </style>
        """)
        # Thêm đoạn mã JavaScript để khởi tạo plugin sao chép
        f.write("""
        <script>        
        document.addEventListener('DOMContentLoaded', function() { 
            // Đăng ký plugin sao chép
            hljs.addPlugin(new CopyButtonPlugin({
                callback: (text, el) => {
                    navigator.clipboard.writeText(text).then(() => {
                        el.textContent = 'Đã sao chép!';
                        setTimeout(() => {
                            el.textContent = 'Sao chép';
                        }, 2000);
                    });
                }
            }));
            // Khởi tạo Highlight.js
            hljs.highlightAll();
            // Khởi tạo Line Numbers
            hljs.initLineNumbersOnLoad({
            singleLine: true,
            startFrom: 1
            });
        });
        </script>
        """)
        f.write(f"\n</head>\n<body>\n")
        f.write(f"<h1 class=\"text-center\"># Code Review Report ({base_branch}..{feature_branch})</h1>\n\n")
        for review in all_reviews:
            f.write(review + "\n\n<hr>\n\n")
        f.write(f"\n\n<hr>\n<p>Tổng thời gian thực thi: {total_exec_time:.2f} giây</p>\n")
        f.write(f"<p>Tổng tokens đã sử dụng: {total_tokens} (prompt: {total_prompt_tokens}, completion: {total_completion_tokens})</p>\n")
        # add close tag cho report.html
        f.write(f"</div>\n</body>\n</html>")




# Hàm main xử lý toàn bộ quy trình review

def main():
    import tiktoken
    encoding = tiktoken.encoding_for_model(OPENAI_MODEL)
    parser = argparse.ArgumentParser(description="Review code using OpenAI + rules.yml")
    parser.add_argument("--base", required=True, help="Base branch (e.g., develop)")
    parser.add_argument("--feature", required=True, help="Feature branch (e.g., feature/login)")
    args = parser.parse_args()

    base_branch = args.base
    feature_branch = args.feature

    for f in [DIFF_FILE, LINT_FILE, REPORT_FILE]:
        if os.path.exists(f):
            os.remove(f)
        

    start_diff = time.time()
    log(f"Lấy diff giữa {base_branch}..{feature_branch}")
    changed_files = run_cmd(f"git diff --name-only {base_branch}..{feature_branch} -- '*.php'").strip().split("\n")
    end_diff = time.time()
    log(f"⏱️ Thời gian lấy diff: {end_diff - start_diff:.2f}s")

    rules_text = ""
    if os.path.exists(RULES_FILE):
        with open(RULES_FILE, "r", encoding="utf-8") as rf:
            rules_text = rf.read()

    total_prompt_tokens = 0
    total_completion_tokens = 0
    total_tokens = 0
    start_exec = time.time()
    all_reviews = []
    
    # show tổng số files
    print(f"\n\n🎉 Tổng số file đã thay đổi: {len(changed_files)}")
    # show all changed_files in terminal
    for i, file_path in enumerate(changed_files, 1):
        # in tên file và số thứ tự
        print(f"{i}. {file_path}")

    # Nếu không có file nào thay đổi, kết thúc sớm
    if not changed_files:
        print("Không có file nào thay đổi.")
        return

    # Lặp qua từng file đã thay đổi
    for i, file_path in enumerate(changed_files, 1):
        if not file_path.strip():
            continue
        while True:
            diff, lint = get_file_diff_and_lint(file_path)
            prompt = build_openai_prompt(file_path, diff, lint, rules_text)
            input_tokens = estimate_tokens(prompt, encoding)
            user_input = confirm_review_option(file_path, input_tokens)
            if user_input in ["1", "yes", "y"]:
                log(f"Đang review file: {file_path}")
                review_result, usage = call_openai_api(prompt, OPENAI_MODEL, OPENAI_API_KEY, OPENAI_ENDPOINT)
                prompt_tokens = usage.get("prompt_tokens", 0)
                completion_tokens = usage.get("completion_tokens", 0)
                total_tokens_file = usage.get("total_tokens", 0)
                total_prompt_tokens += prompt_tokens
                total_completion_tokens += completion_tokens
                total_tokens += total_tokens_file
                all_reviews.append(f"<div class=\"container\">\n<h3>{i}. File: {file_path}</h3>\n<p>{review_result}</p>\n</div>")
                break
            elif user_input in ["2", "skip"]:
                log(f"Skip file: {file_path}")
                all_reviews.append(f"<div class=\"container\">\n<h5>{i}. File: {file_path}</h5>\n<p>Ghi chú: Đã skip file này, không review.</p>\n</div>")
                break
            elif user_input in ["3", "quit"]:
                log("Dừng tiến trình review theo yêu cầu người dùng.")
                all_reviews.append(f"<div class=\"container\">\n<h5>{i}. File: {file_path}</h5>\n<p>Ghi chú: Đã dừng tiến trình review tại file này.</p>\n</div>")
                total_exec_time = time.time() - start_exec
                write_report(REPORT_FILE, all_reviews, total_exec_time, total_tokens, total_prompt_tokens, total_completion_tokens, base_branch, feature_branch)
                log(f"🎉 Review đã dừng. Kết quả: {REPORT_FILE}")
                print(f"\n---\nTổng thời gian thực thi: {total_exec_time:.2f} giây\n")
                print(f"Tổng tokens đã sử dụng: {total_tokens} (prompt: {total_prompt_tokens}, completion: {total_completion_tokens})\n\n")
                return
            else:
                print("Vui lòng nhập 1, 2, 3 hoặc Yes, Skip, Quit!")

    total_exec_time = time.time() - start_exec
    write_report(REPORT_FILE, all_reviews, total_exec_time, total_tokens, total_prompt_tokens, total_completion_tokens, base_branch, feature_branch)
    log(f"🎉 Review hoàn tất. Kết quả: {REPORT_FILE}")
    print(f"\n---\nTổng thời gian thực thi: {total_exec_time:.2f} giây\n")
    print(f"Tổng tokens đã sử dụng: {total_tokens} (prompt: {total_prompt_tokens}, completion: {total_completion_tokens})\n\n")
        

# Chạy hàm main khi thực thi script
if __name__ == "__main__":
    main()
