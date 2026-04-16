<?php
// إعداد ترويسات الاستجابة لضمان التوافق مع الصفحة
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// 1. ضع مفتاح API الخاص بك هنا (سيكون آمناً ومخفياً عن الزوار)
$apiKey = "ضع_مفتاح_API_الخاص_بك_هنا";

// 2. استلام البيانات القادمة من صفحة HTML
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true);

if (!$inputData) {
    echo json_encode(["error" => "No data provided"]);
    exit;
}

// 3. إعداد رابط الاتصال بـ Google Gemini
$geminiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=" . $apiKey;

// 4. فتح اتصال (cURL) للتواصل مع الذكاء الاصطناعي
$ch = curl_init($geminiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON); // نرسل نفس البيانات التي وصلتنا
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json'
));

// 5. تنفيذ الاتصال واستلام النتيجة
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if(curl_errno($ch)){
    echo json_encode(["error" => "cURL Error: " . curl_error($ch)]);
} else if ($httpCode !== 200) {
    echo json_encode(["error" => "API Error: HTTP " . $httpCode, "details" => json_decode($response)]);
} else {
    // إرجاع النتيجة كما هي لصفحة HTML
    echo $response;
}

curl_close($ch);
?>