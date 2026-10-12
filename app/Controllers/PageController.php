<?php
namespace App\Controllers;

use App\Core\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Helpers\BanglaDate;

class PageController {
    /**
     * Show static pages (about, contact, advertise, privacy, terms, corrections)
     */
    public function show(array $params = []): void {
        $slug = trim($params['slug'] ?? $_GET['slug'] ?? '');
        $categories   = Category::getMenuCategories();
        $breakingNews = Post::getBreakingNews(6);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        $pages = [
            'about' => [
                'title_bn' => 'আমাদের সম্পর্কে (About Us)',
                'title_en' => 'About Us',
                'badge'    => 'পরিচিতি',
                'content'  => '
                    <p><strong>নিউজলেন্সবিডি (Newslensbd)</strong> বাংলাদেশের একটি অগ্রণী, বস্তুনিষ্ঠ ও দায়িত্বশীল ডিজিটাল সংবাদ মাধ্যম। আমাদের মূল লক্ষ্য—"সাধারণের বাইরে, সত্যের খোঁজে"—পাঠকের নিকট নিরপেক্ষ ও যাচাইকৃত সংবাদ পরিবেশন করা।</p>
                    <p>আমরা জাতীয়, আন্তর্জাতিক, রাজনীতি, অর্থনীতি, খেলাধুলা, বিজ্ঞান ও প্রযুক্তিসহ প্রতিটি ক্ষেত্রে নির্ভরযোগ্য সংবাদ প্রবাহ নিশ্চিত করতে নিরলসভাবে কাজ করে যাচ্ছি। আমাদের সাংবাদিক ও লেখকরা সত্যের পক্ষে অবিচল এবং অপতথ্য ও গুজবের বিরুদ্ধে সদা সতর্ক।</p>
                    <h3>আমাদের মূল্যবোধ</h3>
                    <ul>
                        <li><strong>বস্তুনিষ্ঠতা:</strong> তথ্যের সত্যতা যাচাই ছাড়া কোনো সংবাদ প্রকাশ না করা।</li>
                        <li><strong>নিরপেক্ষতা:</strong> কোনো পক্ষপাতিত্ব বা চাপের কাছে নতি স্বীকার না করা।</li>
                        <li><strong>দায়িত্বশীলতা:</strong> সমাজ ও রাষ্ট্রের প্রতি সর্বোচ্চ সংবেদনশীলতা বজায় রাখা।</li>
                    </ul>
                '
            ],
            'contact' => [
                'title_bn' => 'যোগাযোগ (Contact Us)',
                'title_en' => 'Contact Us',
                'badge'    => 'বার্তা পাঠান',
                'content'  => '
                    <p>নিউজলেন্সবিডির সাথে যেকোনো তথ্যের আদান-প্রদান, সংবাদ টিপস বা মতামতের জন্য নিচের মাধ্যমে আমাদের সাথে যোগাযোগ করতে পারেন:</p>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 20px; margin: 20px 0;">
                        <p><strong>📧 বার্তা ও সম্পাদকীয় বিভাগ:</strong> editor@newslensbd.com</p>
                        <p><strong>📰 সংবাদ বিজ্ঞপ্তি:</strong> newsdesk@newslensbd.com</p>
                        <p><strong>📢 বিজ্ঞাপন ও স্পন্সরশিপ:</strong> ads@newslensbd.com</p>
                        <p><strong>📍 প্রধান কার্যালয়:</strong> ঢাকা, বাংলাদেশ</p>
                    </div>
                '
            ],
            'advertise' => [
                'title_bn' => 'বিজ্ঞাপন ও বিপণন (Advertise with Us)',
                'title_en' => 'Advertise with Us',
                'badge'    => 'প্রচার ও প্রসার',
                'content'  => '
                    <p>নিউজলেন্সবিডিতে বিজ্ঞাপন দিয়ে আপনার ব্র্যান্ড ও পণ্যের প্রচার পৌঁছে দিন প্রতিদিনের লাখো সচেতন পাঠকের কাছে।</p>
                    <h3>বিজ্ঞাপনের নির্ধারিত স্লটসমূহ:</h3>
                    <ul>
                        <li><strong>হেডার ব্যানার (Header Banner):</strong> ৭২৮ × ৯০ পিক্সেল</li>
                        <li><strong>সাইডবার ব্যানার (Sidebar Ad):</strong> ৩০০ × ২৫০ পিক্সেল</li>
                        <li><strong>আর্টিকেলের ভেতরে (In-Article Ad):</strong> ৭২৮ × ৯০ পিক্সেল</li>
                        <li><strong>ফুটার ব্যানার (Footer Banner):</strong> ৯৭০ × ৯০ পিক্সেল</li>
                        <li><strong>স্পন্সরড কনটেন্ট ও ভিডিও বিজ্ঞাপন:</strong> বিস্তারিত আলোচনার সাপেক্ষে</li>
                    </ul>
                    <p>বিজ্ঞাপনের মূল্য তালিকা ও বুকিংয়ের জন্য ইমেইল করুন: <strong>ads@newslensbd.com</strong></p>
                '
            ],
            'privacy' => [
                'title_bn' => 'গোপনীয়তা নীতি (Privacy Policy)',
                'title_en' => 'Privacy Policy',
                'badge'    => 'নিরাপত্তা',
                'content'  => '
                    <p>নিউজলেন্সবিডি পাঠকদের তথ্যের সর্বোচ্চ সুরক্ষা ও গোপনীয়তা বজায় রাখতে প্রতিশ্রুতিবদ্ধ। আমাদের ওয়েবসাইট ব্রাউজ করার সময় ব্যবহারকারীর কোনো ব্যক্তিগত তথ্য অনুমতি ব্যতীত সংগ্রহ করা হয় না।</p>
                    <h3>তথ্য সংগ্রহ ও ব্যবহার:</h3>
                    <p>ওয়েবসাইটের পারফরম্যান্স এবং ব্যবহারকারীর অভিজ্ঞতা উন্নতির লক্ষ্যে কুকিজ (Cookies) ও ব্রাউজার ক্যাশ ব্যবহার করা হতে পারে। পাঠকদের ইমেইল শুধুমাত্র নিউজলেটার ও ওটিপি যাচাইকরণের উদ্দেশ্যে ব্যবহার করা হয় এবং তা কখনো তৃতীয় পক্ষের কাছে হস্তান্তর করা হয় না।</p>
                '
            ],
            'terms' => [
                'title_bn' => 'ব্যবহারের শর্তাবলী (Terms of Service)',
                'title_en' => 'Terms of Service',
                'badge'    => 'শর্তাবলী',
                'content'  => '
                    <p>নিউজলেন্সবিডি ওয়েবসাইটে প্রকাশিত সকল সংবাদ, নিবন্ধ, ছবি ও ভিডিও কনটেন্ট কপিরাইট আইনের আওতাভুক্ত। পূর্বানুমতি ও যথাযথ কৃতজ্ঞতা প্রকাশ ছাড়া কোনো কনটেন্ট হুবহু কপি বা অননুমোদিতভাবে পুনর্প্রকাশ করা আইনত দণ্ডনীয়।</p>
                    <p>মন্তব্য বিভাগে কোনো আক্রমণাত্মক, অশ্লীল বা আইন পরিপন্থী মন্তব্য প্রকাশ করা নিষিদ্ধ।</p>
                '
            ],
            'corrections' => [
                'title_bn' => 'সংশোধনী নীতি (Corrections Policy)',
                'title_en' => 'Corrections Policy',
                'badge'    => 'স্বচ্ছতা',
                'content'  => '
                    <p>নিউজলেন্সবিডি সঠিক ও বস্তুনিষ্ঠ সংবাদ পরিবেশনে সর্বদা সচেষ্ট। তবে কোনো সংবাদে অনিচ্ছাকৃত তথ্যগত ভুল বা মুদ্রণত্রুটি পরিলক্ষিত হলে আমরা তা তাৎক্ষণিকভাবে যাচাই করে দ্রুত সংশোধন করি এবং পাঠকদের উদ্দেশ্যে সংশোধনী নোট সংযোজন করি।</p>
                    <p>কোনো সংবাদে তথ্যের অসঙ্গতি চোখে পড়লে অনুগ্রহ করে আমাদের ইমেইল করুন: <strong>corrections@newslensbd.com</strong></p>
                '
            ]
        ];

        if (!isset($pages[$slug])) {
            http_response_code(404);
            View::render('errors.404', [
                'pageTitle'    => 'পাতাটি পাওয়া যায়নি | Newslensbd',
                'categories'   => $categories,
                'breakingNews' => $breakingNews,
                'settings'     => $settings,
                'todayBn'      => $todayBn
            ]);
            return;
        }

        $page = $pages[$slug];

        View::render('pages.static_page', [
            'pageTitle'    => $page['title_bn'] . ' | Newslensbd',
            'slug'         => $slug,
            'page'         => $page,
            'categories'   => $categories,
            'breakingNews' => $breakingNews,
            'settings'     => $settings,
            'todayBn'      => $todayBn
        ]);
    }
}
