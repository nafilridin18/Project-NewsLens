<?php
namespace App\Helpers;

/**
 * File-based Rate Limiter for Login Endpoints
 * Protects against brute-force attacks across IP addresses and accounts.
 */
class RateLimiter {
    private static function getStoragePath(string $key): string {
        $dir = dirname(__DIR__, 2) . '/storage/cache/ratelimit';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $safeKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);
        return $dir . '/' . md5($safeKey) . '.json';
    }

    /**
     * Check if key has exceeded max attempts
     */
    public static function tooManyAttempts(string $key, int $maxAttempts = 5, int $decaySeconds = 900): bool {
        $file = self::getStoragePath($key);
        if (!file_exists($file)) {
            return false;
        }

        $data = json_decode(@file_get_contents($file), true);
        if (!$data || !is_array($data)) {
            return false;
        }

        $now = time();
        // Expired record
        if ($now > ($data['reset_at'] ?? 0)) {
            @unlink($file);
            return false;
        }

        return ($data['attempts'] ?? 0) >= $maxAttempts;
    }

    /**
     * Increment attempts count
     */
    public static function hit(string $key, int $decaySeconds = 900): int {
        $file = self::getStoragePath($key);
        $now = time();
        $data = ['attempts' => 1, 'reset_at' => $now + $decaySeconds];

        if (file_exists($file)) {
            $existing = json_decode(@file_get_contents($file), true);
            if ($existing && ($now <= ($existing['reset_at'] ?? 0))) {
                $data['attempts'] = (int) ($existing['attempts'] ?? 0) + 1;
                $data['reset_at'] = (int) $existing['reset_at'];
            }
        }

        @file_put_contents($file, json_encode($data), LOCK_EX);
        return $data['attempts'];
    }

    /**
     * Clear attempts after successful login
     */
    public static function clear(string $key): void {
        $file = self::getStoragePath($key);
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    /**
     * Return remaining lockout seconds
     */
    public static function availableIn(string $key): int {
        $file = self::getStoragePath($key);
        if (!file_exists($file)) {
            return 0;
        }
        $data = json_decode(@file_get_contents($file), true);
        if (!$data || !isset($data['reset_at'])) {
            return 0;
        }
        $diff = (int) $data['reset_at'] - time();
        return max(0, $diff);
    }
}
