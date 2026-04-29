<?php

// ===== CONFIG =====
$apiKey = getenv('OPENAI_API_KEY');

if (!$apiKey) {
    echo "Missing OPENAI_API_KEY\n";
    exit(1);
}

// ===== READ FILES =====
$rules = file_get_contents(".github/workflows/review_rules.md");
$diff  = file_get_contents("diff.txt");

if (!$diff || trim($diff) === "") {
    echo "No diff to review\n";
    file_put_contents("review.txt", "No PHP changes detected.");
    exit(0);
}

// ===== LIMIT DIFF (tránh AI quá tải) =====
$diff = substr($diff, 0, 12000);

// ===== PROMPT =====
$prompt = "
Bạn là senior PHP developer rất khó tính.

Hãy review code theo rule sau:
$rules

===== CODE DIFF =====
$diff


===== OUTPUT YÊU CẦU =====
- KHÔNG dùng bảng Markdown
- Format như sau:

🔹 File: <file>
   Dòng: <line>
   Vấn đề: <desc>
   Gợi ý: <fix>
   Mức độ: 🔴Critical /🟠Important/🟢Suggestion

- Mỗi issue cách nhau 1 dòng
- Trả lời bằng tiếng Việt
- Chỉ ra file + dòng (nếu có thể)
- Không nói chung chung
- Nếu không có lỗi → ghi: No issue found
";

// ===== CALL OPENAI =====
$data = [
    "model" => "gpt-4.1",
    "messages" => [
        ["role" => "user", "content" => $prompt]
    ],
    "temperature" => 0.2
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Curl error: " . curl_error($ch);
    exit(1);
}

curl_close($ch);

// ===== PARSE RESULT =====
$result = json_decode($response, true);

$content = $result['choices'][0]['message']['content'] ?? "AI không trả kết quả";

// ===== SAVE =====
file_put_contents("review.txt", $content);

echo "Review generated\n";
