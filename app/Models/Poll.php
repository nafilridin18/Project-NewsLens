<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Poll {
    public static function getActive(): ?array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `polls` WHERE `is_active` = 1 ORDER BY `id` DESC LIMIT 1");
            $poll = $stmt->fetch();
            if (!$poll) return null;

            $stmtOpt = $db->prepare("SELECT * FROM `poll_options` WHERE `poll_id` = :id ORDER BY `id` ASC");
            $stmtOpt->execute([':id' => $poll['id']]);
            $options = $stmtOpt->fetchAll();

            $totalVotes = 0;
            foreach ($options as $opt) {
                $totalVotes += (int) $opt['votes'];
            }

            $poll['options'] = $options;
            $poll['total_votes'] = $totalVotes;
            return $poll;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function vote(int $pollId, int $optionId, string $ip): array {
        try {
            $db = Database::getConnection();

            // Check if already voted from this IP
            $stmtCheck = $db->prepare("SELECT id FROM `poll_votes` WHERE `poll_id` = :pollId AND `ip` = :ip LIMIT 1");
            $stmtCheck->execute([':pollId' => $pollId, ':ip' => $ip]);
            if ($stmtCheck->fetch()) {
                return ['success' => false, 'message' => 'আপনি ইতোমধ্যে এই জরিপে আপনার ভোট দিয়েছেন!'];
            }

            // Insert vote
            $stmtVote = $db->prepare("INSERT INTO `poll_votes` (`poll_id`, `option_id`, `ip`, `created_at`) VALUES (:pollId, :optionId, :ip, NOW())");
            $stmtVote->execute([':pollId' => $pollId, ':optionId' => $optionId, ':ip' => $ip]);

            // Increment count in option
            $stmtInc = $db->prepare("UPDATE `poll_options` SET `votes` = `votes` + 1 WHERE `id` = :optId AND `poll_id` = :pollId");
            $stmtInc->execute([':optId' => $optionId, ':pollId' => $pollId]);

            return ['success' => true, 'message' => 'আপনার মূল্যবান ভোট সফলভাবে গ্রহণ করা হয়েছে! ধন্যবাদ।'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'ভোট গ্রহণে ত্রুটি হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।'];
        }
    }

    public static function getAllWithStats(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `polls` ORDER BY `id` DESC");
            $polls = $stmt->fetchAll();

            foreach ($polls as &$poll) {
                $stmtOpt = $db->prepare("SELECT * FROM `poll_options` WHERE `poll_id` = :id ORDER BY `id` ASC");
                $stmtOpt->execute([':id' => $poll['id']]);
                $options = $stmtOpt->fetchAll();

                $total = 0;
                foreach ($options as $o) {
                    $total += (int) $o['votes'];
                }

                foreach ($options as &$o) {
                    $pct = $total > 0 ? round(($o['votes'] / $total) * 100, 1) : 0;
                    $o['percentage'] = $pct;
                }

                $poll['options'] = $options;
                $poll['total_votes'] = $total;
            }
            return $polls;
        } catch (Exception $e) {
            return [];
        }
    }
}
