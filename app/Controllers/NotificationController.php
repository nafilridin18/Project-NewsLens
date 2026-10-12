<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Notification;
use App\Models\PushSubscription;

class NotificationController {
    /**
     * Get JSON notifications list & unread count for bell popup
     */
    public function getNotifications(): void {
        Auth::init();
        header('Content-Type: application/json; charset=utf-8');

        $userId = Auth::id();
        $list = Notification::getRecentForUser($userId, 12);
        $unreadCount = Notification::getUnreadCount($userId);

        echo json_encode([
            'success'     => true,
            'unreadCount' => $unreadCount,
            'list'        => $list
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Mark all notifications as read
     */
    public function markRead(): void {
        Auth::init();
        header('Content-Type: application/json; charset=utf-8');

        $userId = Auth::id();
        Notification::markAllAsRead($userId);

        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Store Web Push subscription from service worker
     */
    public function subscribe(): void {
        Auth::init();
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $endpoint = trim($input['endpoint'] ?? '');
        $keys = $input['keys'] ?? [];
        $p256dh = $keys['p256dh'] ?? ($input['public_key'] ?? null);
        $auth = $keys['auth'] ?? ($input['auth_token'] ?? null);
        $userId = Auth::id();

        if (empty($endpoint)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Endpoint missing'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $saved = PushSubscription::save($endpoint, $p256dh, $auth, $userId);

        echo json_encode(['success' => $saved], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Return VAPID public key for frontend subscription
     */
    public function getPublicKey(): void {
        header('Content-Type: application/json; charset=utf-8');
        $config = require CONFIG_PATH . '/config.php';
        $pubKey = $config['push']['vapid_public_key'] ?? $config['push']['public_key'] ?? '';

        echo json_encode([
            'publicKey' => $pubKey
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
