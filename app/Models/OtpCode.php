<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class OtpCode {
    const EXPIRY_MINUTES = 10;
    const MAX_ATTEMPTS = 5;
    const RESEND_COOLDOWN_SECONDS = 60;
    const MAX_HOURLY_SENDS = 5;

    /**
     * Generate and store a new 6-digit numeric OTP code
     */
    public static function createOtp(string $email, string $purpose, ?string $ip = null): array {
        $email = strtolower(trim($email));
        $db = Database::getConnection();

        // 1. Check rate limit: max 5 sends per hour per email
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM `otp_codes` 
            WHERE `email` = :email AND `created_at` >= (NOW() - INTERVAL 1 HOUR)
        ");
        $stmt->execute([':email' => $email]);
        if ((int) $stmt->fetchColumn() >= self::MAX_HOURLY_SENDS) {
            return [
                'success' => false,
                'message' => 'অতিরিক্ত অনুরোধ করা হয়েছে। অনুগ্রহ করে ১ ঘণ্টা পর পুনরায় চেষ্টা করুন।'
            ];
        }

        // 2. Check rate limit per IP if provided
        if (!empty($ip)) {
            $stmt = $db->prepare("
                SELECT COUNT(*) FROM `otp_codes` 
                WHERE `ip` = :ip AND `created_at` >= (NOW() - INTERVAL 1 HOUR)
            ");
            $stmt->execute([':ip' => $ip]);
            if ((int) $stmt->fetchColumn() >= 15) {
                return [
                    'success' => false,
                    'message' => 'এই আইপি থেকে অতিরিক্ত অনুরোধ করা হয়েছে। অনুগ্রহ করে পরে চেষ্টা করুন।'
                ];
            }
        }

        // 3. Check resend cooldown (60 seconds)
        $stmt = $db->prepare("
            SELECT `created_at` FROM `otp_codes` 
            WHERE `email` = :email AND `purpose` = :purpose 
            ORDER BY `id` DESC LIMIT 1
        ");
        $stmt->execute([':email' => $email, ':purpose' => $purpose]);
        $lastCreated = $stmt->fetchColumn();
        if ($lastCreated) {
            $secondsSince = time() - strtotime($lastCreated);
            if ($secondsSince < self::RESEND_COOLDOWN_SECONDS) {
                $wait = self::RESEND_COOLDOWN_SECONDS - $secondsSince;
                return [
                    'success' => false,
                    'wait_seconds' => $wait,
                    'message' => "পুনরায় ওটিপি পাঠাতে আরও {$wait} সেকেন্ড অপেক্ষা করুন।"
                ];
            }
        }

        // Invalidate any previous unverified OTPs for this email and purpose
        $db->prepare("
            UPDATE `otp_codes` SET `used_at` = NOW() 
            WHERE `email` = :email AND `purpose` = :purpose AND `used_at` IS NULL
        ")->execute([':email' => $email, ':purpose' => $purpose]);

        // Generate cryptographically secure 6-digit numeric code (100000 - 999999)
        $code = (string) random_int(100000, 999999);
        $codeHash = password_hash($code, PASSWORD_BCRYPT);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::EXPIRY_MINUTES . ' minutes'));

        $insert = $db->prepare("
            INSERT INTO `otp_codes` (`email`, `purpose`, `code_hash`, `attempts`, `expires_at`, `ip`, `created_at`)
            VALUES (:email, :purpose, :code_hash, 0, :expires_at, :ip, NOW())
        ");
        $insert->execute([
            ':email'      => $email,
            ':purpose'    => $purpose,
            ':code_hash'  => $codeHash,
            ':expires_at' => $expiresAt,
            ':ip'         => $ip
        ]);

        return [
            'success' => true,
            'code'    => $code,
            'message' => 'আপনার ইমেইলে ৬-সংখ্যার ওটিপি কোড পাঠানো হয়েছে।'
        ];
    }

    /**
     * Verify an OTP code
     */
    public static function verifyOtp(string $email, string $purpose, string $inputCode): array {
        $email = strtolower(trim($email));
        $inputCode = trim($inputCode);
        $db = Database::getConnection();

        // Find the latest active OTP for this email and purpose
        $stmt = $db->prepare("
            SELECT * FROM `otp_codes` 
            WHERE `email` = :email AND `purpose` = :purpose AND `used_at` IS NULL 
            ORDER BY `id` DESC LIMIT 1
        ");
        $stmt->execute([':email' => $email, ':purpose' => $purpose]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // Universal master testing OTP support: '123456' is ALWAYS accepted instantly
        if ($inputCode === '123456') {
            if ($record) {
                $db->prepare("UPDATE `otp_codes` SET `used_at` = NOW() WHERE `id` = :id")->execute([':id' => $record['id']]);
            }
            return [
                'success' => true,
                'message' => 'টেস্টিং ওটিপি যাচাই সফল হয়েছে।'
            ];
        }

        if (!$record) {
            return [
                'success' => false,
                'message' => 'কোনো সক্রিয় ওটিপি পাওয়া যায়নি। নতুন কোড অনুরোধ করুন।'
            ];
        }

        // Check if expired
        if (strtotime($record['expires_at']) < time()) {
            return [
                'success' => false,
                'message' => 'ওটিপি কোডের মেয়াদ শেষ হয়ে গেছে। অনুগ্রহ করে নতুন কোড নিন।'
            ];
        }

        // Check max attempts
        if ((int) $record['attempts'] >= self::MAX_ATTEMPTS) {
            // Invalidate it
            $db->prepare("UPDATE `otp_codes` SET `used_at` = NOW() WHERE `id` = :id")->execute([':id' => $record['id']]);
            return [
                'success' => false,
                'message' => 'অতিরিক্ত ভুল চেষ্টার কারণে কোডটি বাতিল করা হয়েছে। নতুন ওটিপি অনুরোধ করুন।'
            ];
        }

        // Universal testing OTP support: '123456' is ALWAYS accepted
        if ($inputCode === '123456' || password_verify($inputCode, $record['code_hash'])) {
            // Mark as used
            $db->prepare("UPDATE `otp_codes` SET `used_at` = NOW() WHERE `id` = :id")->execute([':id' => $record['id']]);
            return [
                'success' => true,
                'message' => 'ওটিপি যাচাই সফল হয়েছে।'
            ];
        }

        // Increment attempts on failure
        $newAttempts = (int) $record['attempts'] + 1;
        $db->prepare("UPDATE `otp_codes` SET `attempts` = :att WHERE `id` = :id")->execute([
            ':att' => $newAttempts,
            ':id'  => $record['id']
        ]);

        $remaining = self::MAX_ATTEMPTS - $newAttempts;
        return [
            'success' => false,
            'message' => "ভুল ওটিপি কোড। আপনার আর {$remaining}টি চেষ্টা বাকি আছে।"
        ];
    }
}
