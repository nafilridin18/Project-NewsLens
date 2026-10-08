<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class District {
    public static function getDivisions(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `divisions` ORDER BY `id` ASC");
            $res = $stmt->fetchAll();
            if (!empty($res)) return $res;
        } catch (Exception $e) {}

        return [
            ['id' => 1, 'name_bn' => 'ঢাকা', 'name_en' => 'Dhaka', 'slug' => 'dhaka'],
            ['id' => 2, 'name_bn' => 'চট্টগ্রাম', 'name_en' => 'Chattogram', 'slug' => 'chattogram'],
            ['id' => 3, 'name_bn' => 'রাজশাহী', 'name_en' => 'Rajshahi', 'slug' => 'rajshahi'],
            ['id' => 4, 'name_bn' => 'খুলনা', 'name_en' => 'Khulna', 'slug' => 'khulna'],
            ['id' => 5, 'name_bn' => 'বরিশাল', 'name_en' => 'Barishal', 'slug' => 'barishal'],
            ['id' => 6, 'name_bn' => 'সিলেট', 'name_en' => 'Sylhet', 'slug' => 'sylhet'],
            ['id' => 7, 'name_bn' => 'রংপুর', 'name_en' => 'Rangpur', 'slug' => 'rangpur'],
            ['id' => 8, 'name_bn' => 'ময়মনসিংহ', 'name_en' => 'Mymensingh', 'slug' => 'mymensingh']
        ];
    }

    public static function getDistrictsByDivision(int $divisionId): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `districts` WHERE `division_id` = :divisionId ORDER BY `name_bn` ASC");
            $stmt->bindValue(':divisionId', $divisionId, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetchAll();
            if (!empty($res)) return $res;
        } catch (Exception $e) {}

        // Mock fallback by division
        $mock = [
            1 => [ // Dhaka
                ['id' => 101, 'division_id' => 1, 'name_bn' => 'ঢাকা', 'slug' => 'dhaka'],
                ['id' => 102, 'division_id' => 1, 'name_bn' => 'গাজীপুর', 'slug' => 'gazipur'],
                ['id' => 103, 'division_id' => 1, 'name_bn' => 'নারায়ণগঞ্জ', 'slug' => 'narayanganj'],
                ['id' => 104, 'division_id' => 1, 'name_bn' => 'নরসিংদী', 'slug' => 'narsingdi'],
                ['id' => 105, 'division_id' => 1, 'name_bn' => 'মুন্সীগঞ্জ', 'slug' => 'munshiganj'],
                ['id' => 106, 'division_id' => 1, 'name_bn' => 'মানিকগঞ্জ', 'slug' => 'manikganj'],
                ['id' => 107, 'division_id' => 1, 'name_bn' => 'টাঙ্গাইল', 'slug' => 'tangail'],
                ['id' => 108, 'division_id' => 1, 'name_bn' => 'কিশোরগঞ্জ', 'slug' => 'kishoreganj'],
                ['id' => 109, 'division_id' => 1, 'name_bn' => 'ফরিদপুর', 'slug' => 'faridpur']
            ],
            2 => [ // Chattogram
                ['id' => 201, 'division_id' => 2, 'name_bn' => 'চট্টগ্রাম', 'slug' => 'chattogram'],
                ['id' => 202, 'division_id' => 2, 'name_bn' => 'কক্সবাজার', 'slug' => 'coxs-bazar'],
                ['id' => 203, 'division_id' => 2, 'name_bn' => 'কুমিল্লা', 'slug' => 'cumilla'],
                ['id' => 204, 'division_id' => 2, 'name_bn' => 'ফেনী', 'slug' => 'feni'],
                ['id' => 205, 'division_id' => 2, 'name_bn' => 'ব্রাহ্মণবাড়িয়া', 'slug' => 'brahmanbaria'],
                ['id' => 206, 'division_id' => 2, 'name_bn' => 'নোয়াখালী', 'slug' => 'noakhali'],
                ['id' => 207, 'division_id' => 2, 'name_bn' => 'চাঁদপুর', 'slug' => 'chandpur']
            ],
            3 => [ // Rajshahi
                ['id' => 301, 'division_id' => 3, 'name_bn' => 'রাজশাহী', 'slug' => 'rajshahi'],
                ['id' => 302, 'division_id' => 3, 'name_bn' => 'বগুড়া', 'slug' => 'bogura'],
                ['id' => 303, 'division_id' => 3, 'name_bn' => 'পাবনা', 'slug' => 'pabna'],
                ['id' => 304, 'division_id' => 3, 'name_bn' => 'সিরাজগঞ্জ', 'slug' => 'sirajganj'],
                ['id' => 305, 'division_id' => 3, 'name_bn' => 'নওগাঁ', 'slug' => 'naogaon']
            ],
            4 => [ // Khulna
                ['id' => 401, 'division_id' => 4, 'name_bn' => 'খুলনা', 'slug' => 'khulna'],
                ['id' => 402, 'division_id' => 4, 'name_bn' => 'যশোর', 'slug' => 'jashore'],
                ['id' => 403, 'division_id' => 4, 'name_bn' => 'সাতক্ষীরা', 'slug' => 'satkhira'],
                ['id' => 404, 'division_id' => 4, 'name_bn' => 'কুষ্টিয়া', 'slug' => 'kushtia'],
                ['id' => 405, 'division_id' => 4, 'name_bn' => 'বাগেরহাট', 'slug' => 'bagerhat']
            ],
            5 => [ // Barishal
                ['id' => 501, 'division_id' => 5, 'name_bn' => 'বরিশাল', 'slug' => 'barishal'],
                ['id' => 502, 'division_id' => 5, 'name_bn' => 'পটুয়াখালী', 'slug' => 'patuakhali'],
                ['id' => 503, 'division_id' => 5, 'name_bn' => 'ভোলা', 'slug' => 'bhola'],
                ['id' => 504, 'division_id' => 5, 'name_bn' => 'পিরোজপুর', 'slug' => 'pirojpur']
            ],
            6 => [ // Sylhet
                ['id' => 601, 'division_id' => 6, 'name_bn' => 'সিলেট', 'slug' => 'sylhet'],
                ['id' => 602, 'division_id' => 6, 'name_bn' => 'মৌলভীবাজার', 'slug' => 'moulvibazar'],
                ['id' => 603, 'division_id' => 6, 'name_bn' => 'হবিগঞ্জ', 'slug' => 'habiganj'],
                ['id' => 604, 'division_id' => 6, 'name_bn' => 'সুনামগঞ্জ', 'slug' => 'sunamganj']
            ],
            7 => [ // Rangpur
                ['id' => 701, 'division_id' => 7, 'name_bn' => 'রংপুর', 'slug' => 'rangpur'],
                ['id' => 702, 'division_id' => 7, 'name_bn' => 'দিনাজপুর', 'slug' => 'dinajpur'],
                ['id' => 703, 'division_id' => 7, 'name_bn' => 'কুড়িগ্রাম', 'slug' => 'kurigram'],
                ['id' => 704, 'division_id' => 7, 'name_bn' => 'গাইবান্ধা', 'slug' => 'gaibandha']
            ],
            8 => [ // Mymensingh
                ['id' => 801, 'division_id' => 8, 'name_bn' => 'ময়মনসিংহ', 'slug' => 'mymensingh'],
                ['id' => 802, 'division_id' => 8, 'name_bn' => 'জামালপুর', 'slug' => 'jamalpur'],
                ['id' => 803, 'division_id' => 8, 'name_bn' => 'নেত্রকোণা', 'slug' => 'netrokona'],
                ['id' => 804, 'division_id' => 8, 'name_bn' => 'শেরপুর', 'slug' => 'sherpur']
            ]
        ];

        return $mock[$divisionId] ?? [];
    }

    public static function getNewsByDistrict(?int $districtId = null, ?int $divisionId = null, int $limit = 12): array {
        try {
            $db = Database::getConnection();
            $query = "
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug, d.name_bn AS district_name, divn.name_bn AS division_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `districts` d ON p.district_id = d.id
                LEFT JOIN `divisions` divn ON d.division_id = divn.id
                WHERE p.status = 'published'
            ";

            if ($districtId) {
                $query .= " AND p.district_id = :districtId";
            } elseif ($divisionId) {
                $query .= " AND d.division_id = :divisionId";
            }

            $query .= " ORDER BY p.published_at DESC LIMIT :limit";
            $stmt = $db->prepare($query);
            if ($districtId) $stmt->bindValue(':districtId', $districtId, PDO::PARAM_INT);
            elseif ($divisionId) $stmt->bindValue(':divisionId', $divisionId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetchAll();
            if (!empty($res)) return $res;
        } catch (Exception $e) {}

        // Mock fallback articles
        return [
            [
                'id' => 401,
                'title' => 'চট্টগ্রাম বন্দর দিয়ে আমদানি পণ্য খালাসে রেকর্ড সময় বাঁচছে ব্যবসায়ীদের',
                'excerpt' => 'ডিজিটাল অটোমেশন ও নতুন স্ক্যানার বসানোর পর কনটেইনার হ্যান্ডলিংয়ে অভূতপূর্ব গতি এসেছে।',
                'category_name' => 'সারাদেশ',
                'category_slug' => 'saradesh',
                'district_name' => 'চট্টগ্রাম',
                'division_name' => 'চট্টগ্রাম',
                'featured_image' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=600&q=80',
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'slug' => 'chattogram-port-cargo-record'
            ],
            [
                'id' => 402,
                'title' => 'রাজশাহীতে এবার রেশম গুটি উৎপাদনে নতুন জাতের রেশম পোকার সাফল্য',
                'excerpt' => 'সিল্ক সিটির ঐতিহ্য পুনরুদ্ধারে কৃষকদের মাঝে আধুনিক কারিগরি সহায়তা বিতরণ।',
                'category_name' => 'সারাদেশ',
                'category_slug' => 'saradesh',
                'district_name' => 'রাজশাহী',
                'division_name' => 'রাজশাহী',
                'featured_image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=600&q=80',
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'slug' => 'rajshahi-silk-production-success'
            ],
            [
                'id' => 403,
                'title' => 'সিলেটের চা বাগানে পর্যটকদের ভিড়, বুকিং শতভাগ পূর্ণ',
                'excerpt' => 'শীতের আগমনী বার্তায় সবুজ চা বাগান ও পাহাড়ি ঝরনায় ভ্রমণপিপাসুদের আনন্দঘন সময়।',
                'category_name' => 'সারাদেশ',
                'category_slug' => 'saradesh',
                'district_name' => 'সিলেট',
                'division_name' => 'সিলেট',
                'featured_image' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=600&q=80',
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'slug' => 'sylhet-tea-garden-tourism'
            ],
            [
                'id' => 404,
                'title' => 'খুলনায় আধুনিক চিংড়ি প্রক্রিয়াজাতকরণ কারখানা স্থাপন',
                'excerpt' => 'ইউরোপ ও আমেরিকার বাজারে রপ্তানি গুণমান নিশ্চিত করতে নতুন ল্যাব উদ্বোধন।',
                'category_name' => 'সারাদেশ',
                'category_slug' => 'saradesh',
                'district_name' => 'খুলনা',
                'division_name' => 'খুলনা',
                'featured_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=80',
                'published_at' => date('Y-m-d H:i:s', strtotime('-7 hours')),
                'slug' => 'khulna-shrimp-processing-lab'
            ]
        ];
    }
}
