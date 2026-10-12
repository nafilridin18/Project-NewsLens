<?php
namespace App\Helpers;

use App\Core\Database;
use App\Models\Notification;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotifier {
    /**
     * Notify Breaking News across in-app bell, notification queue, and Web Push
     */
    public static function notifyBreakingNews(array $post): void {
        $title = '🚨 ব্রেকিং নিউজ: ' . ($post['title'] ?? '');
        $url = '/news/' . ($post['slug'] ?? '');
        $body = !empty($post['excerpt']) ? $post['excerpt'] : 'সর্বশেষ ব্রেকিং আপডেট পড়ুন Newslensbd-এ।';

        // 1. Create Broadcast In-App Notification (user_id = null)
        Notification::create([
            'user_id' => null,
            'title'   => $title,
            'message' => $body,
            'url'     => $url,
            'type'    => 'breaking'
        ]);

        // 2. Queue & Dispatch Web Push
        $subscriptions = PushSubscription::all();
        if (empty($subscriptions)) return;

        $db = Database::getConnection();
        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
            'icon'  => '/assets/img/icon-192.png',
            'badge' => '/assets/img/badge.png'
        ], JSON_UNESCAPED_UNICODE);

        // Insert into notification_queue
        $qStmt = $db->prepare("
            INSERT INTO `notification_queue` (`type`, `recipient`, `payload`, `status`, `created_at`)
            VALUES ('webpush', :rcp, :pay, 'pending', NOW())
        ");

        foreach ($subscriptions as $sub) {
            $qStmt->execute([
                ':rcp' => $sub['endpoint'],
                ':pay' => $payload
            ]);
        }

        // Try immediate push dispatch if VAPID configured
        self::dispatchQueueDirectly($payload, $subscriptions);
    }

    /**
     * Dispatch push notifications directly if VAPID keys exist
     */
    public static function dispatchQueueDirectly(string $payload, array $subscriptions): void {
        $vapidSubject = $config['push']['vapid_subject'] ?? $config['push']['subject'] ?? 'mailto:admin@newslensbd.com';
        $publicKey = $config['push']['vapid_public_key'] ?? $config['push']['public_key'] ?? '';
        $privateKey = $config['push']['vapid_private_key'] ?? $config['push']['private_key'] ?? '';

        if (empty($publicKey) || empty($privateKey)) {
            // VAPID keys not configured yet; stays in queue for worker or when configured
            return;
        }

        try {
            $auth = [
                'VAPID' => [
                    'subject'    => $vapidSubject,
                    'publicKey'  => $publicKey,
                    'privateKey' => $privateKey,
                ],
            ];

            $webPush = new WebPush($auth);
            $webPush->setDefaultOptions(['TTL' => 3600]);

            foreach ($subscriptions as $sub) {
                if (empty($sub['public_key']) || empty($sub['auth_token'])) continue;

                $subscription = Subscription::create([
                    'endpoint'        => $sub['endpoint'],
                    'publicKey'       => $sub['public_key'],
                    'authToken'       => $sub['auth_token'],
                    'contentEncoding' => 'aesgcm',
                ]);

                $webPush->queueNotification($subscription, $payload);
            }

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();
                if (!$report->isSuccess() && $report->isSubscriptionExpired()) {
                    PushSubscription::deleteByEndpoint($endpoint);
                }
            }
        } catch (\Throwable $e) {
            error_log("Push dispatch error: " . $e->getMessage());
        }
    }
}
