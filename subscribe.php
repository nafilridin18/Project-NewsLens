<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond($ok, $message, $code = 200) {
    http_response_code($code);
    echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'অনুরোধটি সঠিক নয়।', 405);
}

if (!empty($_POST['website'])) {
    respond(true, 'ধন্যবাদ! লঞ্চের দিন আপনাকে জানানো হবে।');
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if ($email === '' || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'সঠিক ইমেইল ঠিকানা লিখুন, যেমন name@example.com', 422);
}

$email = strtolower($email);

$safe = preg_match('/^[=+\-@]/', $email) ? "'" . $email : $email;

$dir  = __DIR__ . '/data';
$file = $dir . '/subscribers.php';

if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
    respond(false, 'এই মুহূর্তে ইমেইল জমা নেওয়া যাচ্ছে না। একটু পরে আবার চেষ্টা করুন।', 500);
}

$isNew = !file_exists($file);
$fp = fopen($file, 'c+');
if (!$fp) {
    respond(false, 'এই মুহূর্তে ইমেইল জমা নেওয়া যাচ্ছে না। একটু পরে আবার চেষ্টা করুন।', 500);
}

flock($fp, LOCK_EX);

if ($isNew || filesize($file) === 0) {
    fwrite($fp, "<?php http_response_code(404); exit; ?>\nemail,subscribed_at\n");
}

$exists = false;
rewind($fp);
while (($line = fgets($fp)) !== false) {
    $parts = explode(',', trim($line));
    if (isset($parts[0]) && $parts[0] === $safe) { $exists = true; break; }
}

if (!$exists) {
    fseek($fp, 0, SEEK_END);
    fwrite($fp, $safe . ',' . date('Y-m-d H:i:s') . "\n");
}

flock($fp, LOCK_UN);
fclose($fp);

respond(true, 'ধন্যবাদ! লঞ্চের দিন আপনাকে জানানো হবে।');
