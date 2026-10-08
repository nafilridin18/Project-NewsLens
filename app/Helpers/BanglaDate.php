<?php
namespace App\Helpers;

use DateTime;
use DateTimeZone;

class BanglaDate {
    private static array $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    private static array $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    private static array $bnMonths = [
        1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
        5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
        9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
    ];

    private static array $bnDays = [
        'Saturday' => 'শনিবার', 'Sunday' => 'রবিবার', 'Monday' => 'সোমবার',
        'Tuesday' => 'মঙ্গলবার', 'Wednesday' => 'বুধবার', 'Thursday' => 'বৃহস্পতিবার',
        'Friday' => 'শুক্রবার'
    ];

    /**
     * Convert English numbers to Bangla numerals
     */
    public static function bnNum($number): string {
        return str_replace(self::$enDigits, self::$bnDigits, (string) $number);
    }

    /**
     * Format a timestamp into Bangla date format (e.g., ৮ অক্টোবর ২০২৬, বৃহস্পতিবার)
     */
    public static function formatBnDate(?string $datetime = null, bool $withDay = true): string {
        $dt = $datetime ? new DateTime($datetime, new DateTimeZone('Asia/Dhaka')) : new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        $dayName = self::$bnDays[$dt->format('l')] ?? '';
        $dayNum = self::bnNum($dt->format('j'));
        $monthName = self::$bnMonths[(int) $dt->format('n')] ?? '';
        $year = self::bnNum($dt->format('Y'));

        if ($withDay) {
            return "{$dayName}, {$dayNum} {$monthName} {$year}";
        }
        return "{$dayNum} {$monthName} {$year}";
    }

    /**
     * Relative human-readable time in Bangla (e.g. ৫ মিনিট আগে, ১ ঘণ্টা আগে)
     */
    public static function timeAgo(string $datetime): string {
        $dt = new DateTime($datetime, new DateTimeZone('Asia/Dhaka'));
        $now = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
        $diff = $now->diff($dt);

        if ($diff->y > 0) {
            return self::bnNum($diff->y) . ' বছর আগে';
        }
        if ($diff->m > 0) {
            return self::bnNum($diff->m) . ' মাস আগে';
        }
        if ($diff->d >= 7) {
            $weeks = (int) floor($diff->d / 7);
            return self::bnNum($weeks) . ' সপ্তাহ আগে';
        }
        if ($diff->d > 0) {
            return self::bnNum($diff->d) . ' দিন আগে';
        }
        if ($diff->h > 0) {
            return self::bnNum($diff->h) . ' ঘণ্টা আগে';
        }
        if ($diff->i > 0) {
            return self::bnNum($diff->i) . ' মিনিট আগে';
        }
        return 'এইমাত্র';
    }
}
