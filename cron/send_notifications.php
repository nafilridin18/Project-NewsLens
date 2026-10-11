<?php
/**
 * CLI Background Worker for NewsLens Notification Queue
 * Run periodically via crontab:
 * * * * * * php /home/lcxdiarj/public_html/cron/send_notifications.php > /dev/null 2>&1
 */

if (php_sapi_name() !== 'cli' && (!isset($_GET['key']) || $_GET['key'] !== 'newslens_cron_2026')) {
    http_response_code(403);
    die("Access denied: CLI only.");
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\Database;
use App\Helpers\Mailer;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$db = Database::getConnection();
$config = require CONFIG_PATH . '/config.php';

// Fetch up to 50 pending notifications
$stmt = $db->query("
    SELECT * FROM `notification_queue` 
    WHERE `status` = 'pending' AND `attempts` < 5 
    ORDER BY `id` ASC 
    LIMIT 50
");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($jobs)) {
    echo "No pending jobs.\n";
    exit(0);
}

echo "Processing " . count($jobs) . " notification jobs...\n";

foreach ($jobs as $job) {
    $jobId = (int) $job['id'];
    $db->prepare("UPDATE `notification_queue` SET `status` = 'processing', `attempts` = `attempts` + 1 WHERE `id` = :id")
       ->execute([':id' => $jobId]);

    $payload = json_decode($job['payload'], true) ?: [];

    try {
        if ($job['type'] === 'email') {
            $to = $job['recipient'];
            $subject = $payload['subject'] ?? 'Newslensbd Notification';
            $body = $payload['body'] ?? '';
            $sent = Mailer::send($to, $subject, $body);

            if ($sent) {
                $db->prepare("UPDATE `notification_queue` SET `status` = 'sent', `updated_at` = NOW() WHERE `id` = :id")
                   ->execute([':id' => $jobId]);
            } else {
                throw new \Exception("Mailer::send failed");
            }
        } elseif ($job['type'] === 'webpush') {
            $endpoint = $job['recipient'];
            $vapidSubject = $config['push']['vapid_subject'] ?? 'mailto:admin@newslensbd.com';
            $publicKey = $config['push']['vapid_public_key'] ?? '';
            $privateKey = $config['push']['vapid_private_key'] ?? '';

            if (empty($publicKey) || empty($privateKey)) {
                // Skip if no VAPID keys
                $db->prepare("UPDATE `notification_queue` SET `status` = 'pending', `last_error` = 'VAPID keys not configured' WHERE `id` = :id")
                   ->execute([':id' => $jobId]);
                continue;
            }

            // Find key and auth token for endpoint
            $subStmt = $db->prepare("SELECT public_key, auth_token FROM `push_subscriptions` WHERE endpoint = :ep LIMIT 1");
            $subStmt->execute([':ep' => $endpoint]);
            $subData = $subStmt->fetch(PDO::FETCH_ASSOC);

            if ($subData && !empty($subData['public_key']) && !empty($subData['auth_token'])) {
                $auth = [
                    'VAPID' => [
                        'subject'    => $vapidSubject,
                        'publicKey'  => $publicKey,
                        'privateKey' => $privateKey,
                    ],
                ];
                $webPush = new WebPush($auth);
                $subscription = Subscription::create([
                    'endpoint'        => $endpoint,
                    'publicKey'       => $subData['public_key'],
                    'authToken'       => $subData['auth_token'],
                    'contentEncoding' => 'aesgcm',
                ]);

                $report = $webPush->sendOneNotification($subscription, json_encode($payload, JSON_UNESCAPED_UNICODE));
                if ($report->isSuccess()) {
                    $db->prepare("UPDATE `notification_queue` SET `status` = 'sent', `updated_at` = NOW() WHERE `id` = :id")
                       ->execute([':id' => $jobId]);
                } else {
                    throw new \Exception($report->getReason());
                }
            } else {
                // Stale endpoint
                $db->prepare("UPDATE `notification_queue` SET `status` = 'failed', `last_error` = 'Missing keys or expired endpoint' WHERE `id` = :id")
                   ->execute([':id' => $jobId]);
            }
        }
    } catch (\Throwable $e) {
        $db->prepare("UPDATE `notification_queue` SET `status` = 'failed', `last_error` = :err, `updated_at` = NOW() WHERE `id` = :id")
           ->execute([
               ':err' => mb_substr($e->getMessage(), 0, 500),
               ':id'  => $jobId
           ]);
    }
}

echo "Batch processing finished.\n";
