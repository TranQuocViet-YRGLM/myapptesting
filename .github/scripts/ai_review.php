<?php

// ===== CONFIG =====
$apiKey = getenv('OPENAI_API_KEY');

if (!$apiKey) {
    echo "Missing OPENAI_API_KEY\n";
    exit(1);
}

// ===== READ FILES =====
$commonRulesPath = ".github/workflows/common_rules.md";
$phpstanRulesPath = ".github/workflows/phpstan_rules.md";

$commonRules = file_exists($commonRulesPath) ? file_get_contents($commonRulesPath) : "";
$phpstanRules = file_exists($phpstanRulesPath) ? file_get_contents($phpstanRulesPath) : "";

$diff = file_get_contents("diff.txt");

// ===== CHECK DIFF =====
if (!$diff || trim($diff) === "") {
    echo "No diff to review\n";
    file_put_contents("review.txt", "No PHP changes detected.");
    exit(0);
}

// ===== LIMIT DIFF =====
$diff = substr($diff, 0, 12000);

// ===== RULE SELECTOR =====
$rules = $commonRules;

// Detect PHP file change chuẩn hơn
if (preg_match('/\.php\b/', $diff)) {
    $rules .= "\n\n===== PHPSTAN RULE =====\n";
    $rules .= $phpstanRules;
}

// ===== PROMPT =====
$prompt = "
Bạn là senior PHP developer rất khó tính.

Hãy review code theo rule sau:
$rules

===== CODE DIFF =====
$diff


===== OUTPUT YÊU CẦU =====
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

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
]);

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
