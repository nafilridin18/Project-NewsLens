<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\View;

class Mailer {
    /**
     * Send an email via PHPMailer SMTP with fallback to file logging in dev
     */
    public static function send(string $to, string $subject, string $htmlBody, string $altBody = ''): bool {
        $config = require dirname(__DIR__, 2) . '/config/config.php';
        $mailCfg = $config['mail'] ?? [];

        $host = $mailCfg['host'] ?? '';
        $user = $mailCfg['username'] ?? '';
        $pass = $mailCfg['password'] ?? '';
        $port = (int) ($mailCfg['port'] ?? 587);
        $fromEmail = $mailCfg['from_email'] ?? 'no-reply@newslensbd.com';
        $fromName = $mailCfg['from_name'] ?? 'Newslensbd (নিউজলেন্সবিডি)';
        $encryption = $mailCfg['encryption'] ?? 'tls';

        // Check if PHPMailer is available and SMTP credentials exist
        if (class_exists(PHPMailer::class) && !empty($user) && !empty($pass)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $user;
                $mail->Password   = $pass;
                $mail->SMTPSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $port;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom($fromEmail, $fromName);
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $htmlBody;
                $mail->AltBody = !empty($altBody) ? $altBody : strip_tags($htmlBody);

                $mail->send();
                self::logMail("SUCCESS to {$to}: {$subject}");
                return true;
            } catch (Exception $e) {
                self::logMail("PHPMailer ERROR to {$to}: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
            }
        }

        // Development/fallback: Log the email to storage/logs/mail.log so OTP can be inspected even without SMTP
        $logEntry = sprintf(
            "[%s] SIMULATED EMAIL\nTO: %s\nSUBJECT: %s\nBODY: %s\n%s\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            strip_tags($htmlBody),
            str_repeat('-', 60)
        );
        self::logMail($logEntry);
        return true;
    }

    /**
     * Send OTP Verification Email
     */
    public static function sendOtp(string $to, string $code, string $purpose = 'register', string $name = 'সম্মানিত পাঠক'): bool {
        $purposeLabel = ($purpose === 'forgot_password') ? 'পাসওয়ার্ড রিসেট' : 'অ্যাকাউন্ট ভেরিফিকেশন';
        $subject = "🔒 Newslensbd {$purposeLabel} কোড: {$code}";

        $html = self::renderTemplate('otp_mail', [
            'name'         => $name,
            'code'         => $code,
            'purposeLabel' => $purposeLabel,
            'purpose'      => $purpose,
            'expiry'       => '১০ মিনিট'
        ]);

        return self::send($to, $subject, $html);
    }

    /**
     * Send Writer Request Status Email (Approve / Reject / Suspend / Permission Change)
     */
    public static function sendWriterStatus(string $to, string $name, string $status, ?string $permission = null, ?string $reason = null): bool {
        $subject = "📢 Newslensbd লেখক অ্যাকাউন্ট আপডেট";
        if ($status === 'approved') {
            $permLabel = ($permission === 'direct_publish') ? 'সরাসরি প্রকাশ (Direct Publish)' : 'সম্পাদকীয় পর্যালোচনা (Editor Review)';
            $subject = "🎉 অভিনন্দন! আপনার Newslensbd লেখক অ্যাকাউন্ট অনুমোদিত হয়েছে";
        } elseif ($status === 'rejected') {
            $subject = "⚠️ আপনার Newslensbd লেখক আবেদন সংক্রান্ত তথ্য";
        } elseif ($status === 'suspended') {
            $subject = "🚫 আপনার Newslensbd লেখক অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে";
        }

        $html = self::renderTemplate('writer_status_mail', [
            'name'       => $name,
            'status'     => $status,
            'permission' => $permission,
            'reason'     => $reason
        ]);

        return self::send($to, $subject, $html);
    }

    /**
     * Send Breaking News Alert Email
     */
    public static function sendBreakingNews(string $to, string $title, string $excerpt, string $url): bool {
        $subject = "🚨 [ব্রেকিং নিউজ] " . $title;
        $html = self::renderTemplate('breaking_news_mail', [
            'title'   => $title,
            'excerpt' => $excerpt,
            'url'     => $url
        ]);
        return self::send($to, $subject, $html);
    }

    private static function renderTemplate(string $template, array $data): string {
        $file = dirname(__DIR__) . "/Views/emails/{$template}.php";
        if (file_exists($file)) {
            extract($data);
            ob_start();
            require $file;
            return ob_get_clean();
        }

        // Simple default fallback HTML
        $content = "<h2>Newslensbd (নিউজলেন্সবিডি)</h2>";
        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $content .= "<p><strong>" . htmlspecialchars($k) . ":</strong> " . nl2br(htmlspecialchars($v)) . "</p>";
            }
        }
        return $content;
    }

    private static function logMail(string $msg): void {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        @file_put_contents($logDir . '/mail.log', date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
    }
}
