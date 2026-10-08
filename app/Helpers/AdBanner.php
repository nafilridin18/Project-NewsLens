<?php
namespace App\Helpers;

use App\Models\Ad;

class AdBanner {
    /**
     * Store recorded impression IDs per request to avoid double-counting
     */
    private static array $recordedImpressions = [];

    /**
     * Render an advertisement slot by position
     *
     * @param string $position header | sidebar | in_article | footer | popup
     * @param array $options [show_placeholder => bool, class => string, label => string]
     * @return string
     */
    public static function render(string $position, array $options = []): string {
        $config = require CONFIG_PATH . '/config.php';
        $appUrl = rtrim($config['app']['url'] ?? '', '/');

        $showPlaceholder = $options['show_placeholder'] ?? true;
        $customClass = htmlspecialchars($options['class'] ?? '');
        $slotLabel = $options['label'] ?? null;

        $ad = Ad::getByPosition($position);

        if ($ad) {
            // Track Impression (deduplicated per request)
            $adId = (int) $ad['id'];
            if (!isset(self::$recordedImpressions[$adId])) {
                self::$recordedImpressions[$adId] = true;
                Ad::incrementImpression($adId);
            }

            $clickUrl = !empty($ad['link']) ? $appUrl . '/ad/click/' . $adId : '#';
            $title = htmlspecialchars($ad['title'] ?? 'বিজ্ঞাপন');
            $type = $ad['type'] ?? 'image';

            $out = "<div class=\"nl-ad-slot nl-ad-{$position} {$customClass}\" data-ad-id=\"{$adId}\" aria-label=\"স্পন্সরড বিজ্ঞাপন\">";
            $out .= "<div class=\"nl-ad-wrapper\">";
            $out .= "<span class=\"nl-ad-badge\" data-bn=\"বিজ্ঞাপন\" data-en=\"ADVERTISEMENT\">বিজ্ঞাপন</span>";

            if ($type === 'code' && !empty($ad['code'])) {
                $out .= "<div class=\"nl-ad-code-box\">" . $ad['code'] . "</div>";
            } elseif ($type === 'video' && !empty($ad['video_url'])) {
                $videoUrl = $ad['video_url'];
                preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $videoUrl, $ytMatch);
                $ytId = $ytMatch[1] ?? '';

                if ($ytId) {
                    $out .= "<div class=\"nl-ad-video-frame-box\">";
                    $out .= "<iframe src=\"https://www.youtube.com/embed/{$ytId}?autoplay=0&rel=0&mute=1\" title=\"{$title}\" frameborder=\"0\" allowfullscreen loading=\"lazy\" class=\"nl-ad-iframe\"></iframe>";
                    $out .= "</div>";
                } else {
                    $escVid = htmlspecialchars($videoUrl);
                    $out .= "<div class=\"nl-ad-video-box\">";
                    $out .= "<video src=\"{$escVid}\" controls playsinline preload=\"metadata\" class=\"nl-ad-video\"></video>";
                    $out .= "</div>";
                }

                if (!empty($ad['link'])) {
                    $out .= "<a href=\"{$clickUrl}\" target=\"_blank\" rel=\"noopener noreferrer nofollow\" class=\"nl-ad-cta-btn\" title=\"{$title}\">";
                    $out .= "<span>বিস্তারিত জানতে ভিজিট করুন</span> <span class=\"nl-cta-arrow\">→</span>";
                    $out .= "</a>";
                }
            } elseif (!empty($ad['image'])) {
                $rawImg = $ad['image'];
                $imgSrc = (strpos($rawImg, 'http://') === 0 || strpos($rawImg, 'https://') === 0) 
                    ? htmlspecialchars($rawImg) 
                    : $appUrl . '/' . ltrim(htmlspecialchars($rawImg), '/');

                $dimensions = match ($position) {
                    'header' => 'width="728" height="90"',
                    'sidebar' => 'width="300" height="250"',
                    'in_article' => 'width="728" height="90"',
                    'footer' => 'width="970" height="90"',
                    default => 'width="300" height="250"'
                };

                $out .= "<a href=\"{$clickUrl}\" target=\"_blank\" rel=\"noopener noreferrer nofollow\" class=\"nl-ad-link\" title=\"{$title} — ক্লিক করে বিস্তারিত দেখুন\">";
                $out .= "<div class=\"nl-ad-image-container\">";
                $out .= "<img src=\"{$imgSrc}\" alt=\"{$title}\" loading=\"lazy\" {$dimensions} class=\"nl-ad-img\">";
                $out .= "<div class=\"nl-ad-shine\"></div>";
                $out .= "</div>";
                $out .= "</a>";

                if ($position === 'sidebar' && !empty($ad['link'])) {
                    $out .= "<a href=\"{$clickUrl}\" target=\"_blank\" rel=\"noopener noreferrer nofollow\" class=\"nl-ad-cta-btn\" title=\"{$title}\">";
                    $out .= "<span>বিস্তারিত জানতে ভিজিট করুন</span> <span class=\"nl-cta-arrow\">→</span>";
                    $out .= "</a>";
                }
            }

            $out .= "</div></div>";
            return $out;
        }

        // Placeholder if no active ad
        if ($showPlaceholder) {
            $dimensionText = match ($position) {
                'header' => '৭২৮ × ৯০',
                'sidebar' => '৩০০ × ২৫০',
                'in_article' => '৭২৮ × ৯০',
                'footer' => '৯৭০ × ৯০',
                default => '৩০০ × ২৫০'
            };
            $dimensionEn = match ($position) {
                'header' => '728 × 90',
                'sidebar' => '300 × 250',
                'in_article' => '728 × 90',
                'footer' => '970 × 90',
                default => '300 × 250'
            };

            $labelBn = $slotLabel ?? "বিজ্ঞাপন দিন ({$dimensionText})";
            $labelEn = $slotLabel ?? "ADVERTISE HERE ({$dimensionEn})";

            return "
            <div class=\"nl-ad-slot nl-ad-placeholder-slot nl-ad-{$position} {$customClass}\" aria-label=\"বিজ্ঞাপন স্লট\">
              <a href=\"{$appUrl}/page/advertise\" class=\"nl-ad-placeholder-box\" title=\"বিজ্ঞাপনের জন্য যোগাযোগ করুন\">
                <div class=\"nl-ad-placeholder-content\">
                  <span class=\"nl-ad-ph-icon\">📢</span>
                  <div class=\"nl-ad-ph-info\">
                    <strong class=\"nl-ad-ph-title\" data-bn=\"{$labelBn}\" data-en=\"{$labelEn}\">{$labelBn}</strong>
                    <span class=\"nl-ad-ph-sub\" data-bn=\"আপনার ব্যবসার প্রচার করুন আমাদের পাঠকদের কাছে\" data-en=\"Reach millions of readers daily\">আপনার ব্যবসার প্রচার করুন আমাদের পাঠকদের কাছে</span>
                  </div>
                  <span class=\"nl-ad-ph-btn\" data-bn=\"যোগাযোগ →\" data-en=\"Contact →\">যোগাযোগ →</span>
                </div>
              </a>
            </div>";
        }

        return '';
    }
}
