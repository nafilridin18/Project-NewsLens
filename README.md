# Newslensbd (নিউজলেন্সবিডি) — Professional Bangla News Portal

> **ট্যাগলাইন (বাংলা):** সাধারণের বাইরে, সত্যের খোঁজে  
> **Tagline (English):** News Beyond the Ordinary  
> **টেকনোলজি পার্টনার:** [Stratifyx Global](https://stratifyxglobal.com)  
> **লাইভ ওয়েবসাইট:** [https://newslensbd.com](https://newslensbd.com)  
> **লোকাল ওয়েবসাইট:** [http://localhost/NewsLens/public](http://localhost/NewsLens/public)

---

## 🔑 দ্রুত অ্যাক্সেস ও লগইন ক্রেডেনশিয়াল (Quick Credentials & Access Links)

সিস্টেমের সকল অ্যাডমিন, লেখক, সাধারণ পাঠক ও ডাটাবেজের সরাসরি অ্যাক্সেস লিঙ্ক ও ক্রেডেনশিয়াল নিচে সুস্পষ্টভাবে প্রদান করা হলো:

### ১. অ্যাডমিন, লেখক ও ইউজার লগইন তথ্য (Authentication Credentials)

| ভূমিকা (Role) | ইমেইল / ইউজার আইডি (Email / ID) | ডিফল্ট পাসওয়ার্ড (Password) | লাইভ পোর্টাল লিঙ্ক (Live URL) | লোকাল পোর্টাল লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- | :--- |
| **সুপার অ্যাডমিন (Super Admin)** | `admin@newslensbd.com` | `password123` | [Live Admin Login](https://newslensbd.com/lens-desk/login) | [Local Admin Login](http://localhost/NewsLens/public/lens-desk/login) |
| **লেখক প্যানেল (Dedicated Writer)** | অ্যাডমিন কর্তৃক তৈরিকৃত লেখকের ইমেইল | লেখকের পাসওয়ার্ড | [Live Writer Login](https://newslensbd.com/writer/login) | [Local Writer Login](http://localhost/NewsLens/public/writer/login) |
| **সাধারণ পাঠক (Public Reader)** | নিবন্ধিত পাঠকের ইমেইল | পাঠকের নিজস্ব পাসওয়ার্ড | [Live Reader Login](https://newslensbd.com/login) | [Local Reader Login](http://localhost/NewsLens/public/login) |

> [!IMPORTANT]
> **⚠️ লেখক সিস্টেম সম্পূর্ণ পৃথকীকৃত (Independent Writer System):**  
> সাধারণ পাবলিক লগইন/রেজিস্ট্রেশন পেজ (`/login`, `/register`) থেকে লেখকদের সকল অপশন বিলুপ্ত করা হয়েছে। সাধারণ পাঠকরা কখনো পাবলিক পেজ থেকে লেখক হতে পারবেন না। লেখকদের জন্য রয়েছে নিজস্ব ডেডিকেটেড লগইন পোর্টাল: [`/writer/login`](https://newslensbd.com/writer/login)। নতুন লেখক অ্যাকাউন্ট শুধুমাত্র অ্যাডমিন প্যানেল ([`Admin Writers Desk`](https://newslensbd.com/lens-desk/writers)) থেকে তৈরি বা পাঠককে পদোন্নতি (Promote) দেওয়ার মাধ্যমে সক্রিয় করা যায়।

> [!TIP]
> **🧪 ইনস্ট্যান্ট টেস্টিং মাস্টার ওটিপি (Master Test OTP):**  
> নতুন পাঠক নিবন্ধন, ইমেইল যাচাই বা পাসওয়ার্ড রিসেটের সময় যেকোনো ইমেইলের জন্য ওটিপি কোড হিসেবে **`123456`** ব্যবহার করুন। কোনো প্রকার ইমেইল ডেলিভারির জন্য অপেক্ষা না করেই তৎক্ষণাৎ অ্যাকাউন্ট ভেরিফাই হয়ে যাবে। এছাড়াও স্ক্রিনে এক ক্লিকেই ওটিপি অটো-ফিল করার সুবিধা সংযুক্ত রয়েছে।

### ২. ডাটাবেজ সংযোগ তথ্য (Database Configurations)

| পরিবেশ (Environment) | হোস্ট (Host) | পোর্ট (Port) | ডাটাবেজ নাম (DB Name) | ব্যবহারকারী (Username) | পাসওয়ার্ড (Password) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **লোকাল (Local XAMPP)** | `127.0.0.1` | `3306` বা `3307` | `newslensbd` | `root` | *(ফাঁকা / কোনো পাসওয়ার্ড নেই)* |
| **লাইভ সার্ভার (cPanel)** | `localhost` | `3306` | `lcxdiarj_newslens` | `lcxdiarj_lensuser` | `Lens#Secure2026!Db` |

---

## 📌 প্রজেক্ট ওভারভিউ (Project Overview)

**Newslensbd (নিউজলেন্সবিডি)** হলো সমগ্র বাংলাদেশের জাতীয়, আন্তর্জাতিক, ৮টি বিভাগ, ৬৪টি জেলা এবং উপজেলা পর্যায়ের সংবাদ প্রকাশের জন্য একটি আধুনিক, বিদ্যুৎগতির (< ২ সেকেন্ড লোড টাইম), মোবাইল-ফার্স্ট ও এসইও-বান্ধব পেশাদার বাংলা নিউজ পোর্টাল ও সিএমএস।

### 🎨 ব্র্যান্ড ও ডিজাইন সিস্টেম (Brand & UI Palette)
* **Primary (নেভি ব্লু - হেডার, শিরোনাম):** `#0b1f44` / `#0B2149`
* **Dark Theme (ডিপ নেভি মোড):** `#071630` / `#0c1e3d` (নো পিওর ব্ল্যাক, আরামদায়ক রিডিং)
* **Category Label (ক্যাটাগরি ব্যাজ):** জাতীয় সবুজ (`#0E7A3A`)
* **Breaking / Live (ব্রেকিং ও লাইভ ব্যাজ):** রক্তিম লাল (`#E31E24`)
* **ফন্ট ও টাইপোগ্রাফি:** Hind Siliguri / Noto Serif Bengali (Self-hosted)
* **দ্বিভাষিক সমর্থন:** সমগ্র পোর্টালে বাংলা এবং ইংরেজি (BN ⇄ EN) ইনস্ট্যান্ট টগল সুবিধা।
* **লাইট ও ডার্ক মোড:** চোখ-বান্ধব থিম টগল যা লোকালস্টোরেজে ব্যবহারকারীর পছন্দ মনে রাখে।

---

## 🚀 প্রধান ফিচার ও মডিউলসমূহ (Core Modules)

### ১. মূল পোর্টাল ও পাবলিক ইন্টারফেস (Public Reader Portal)
* **স্মার্ট শীর্ষবার (Top Bar):** স্বয়ংক্রিয় বাংলা ও হিজরি তারিখ রূপান্তরকারী (`BanglaDate`), লাইভ আবহাওয়া, ফন্ট সাইজার (অ-, অ, অ+), দ্বিভাষিক সুইচ (`[বাংলা | English]`) এবং ডার্ক/লাইট মোড টগল।
* **ব্রেকিং নিউজ টিকার:** লাল পালসিং ডট সহ মসৃণ অটো-স্লাইডার, হোভার পজ ও ম্যানুয়াল নেভিগেশন।
* **সারাদেশ ও ৬৪ জেলার সংবাদ (`/saradesh`):** ৮টি বিভাগ ও ৬৪টি জেলার ক্যাসকেডিং ড্রপডাউন ফিল্টার সহ দ্রুতগতিতে জেলাভিত্তিক সংবাদ পড়ার ব্যবস্থা।
* **সিঙ্গেল আর্টিকেল ও মিডিয়া প্লেয়ার:** ক্যাটাগরি, লেখক, প্রকাশের তারিখ, সোশ্যাল শেয়ারিং (Facebook, WhatsApp, X, Copy Link), সম্পর্কিত সংবাদ এবং পপআপ লাইটবক্সে ভিডিও প্লেব্যাক।
* **মাল্টি-ক্রাইটেরিয়া সার্চ (`/search`):** কীওয়ার্ড, ক্যাটাগরি, সময়সীমা (আজ, ৭ দিন, ৩০ দিন) এবং বিভাগ/জেলা ফিল্টার।
* **প্রতিষ্ঠান ও সম্পাদকীয় পেজসমূহ (`/page/{slug}`):** আমাদের সম্পর্কে, যোগাযোগ, বিজ্ঞাপন, গোপনীয়তা নীতি, ব্যবহারের শর্তাবলী এবং সংশোধনী নীতি সরাসরি ডেটাবেজ ম্যানেজমেন্ট সহ প্রস্তুত।
* **ইন্টারেক্টিভ সাইডবার উইজেট:** ডাটাবেজ-সংযুক্ত অনলাইন পাঠক জরিপ (Poll) লাইভ ভোট ও রিয়েলটাইম ফলাফল, নামাজের সময়সূচি, নিউজলেটার সাবস্ক্রিপশন এবং বিজ্ঞাপন স্লট।
* **PWA ও ওয়েব পুশ নোটিফিকেশন:** সার্ভিস ওয়ার্কার (`/sw.js`) ও ব্যাকগ্রাউন্ড পুশ সাপোর্ট (`/manifest.json`); নতুন ব্রেকিং নিউজ প্রকাশিত হওয়ামাত্রই সক্রিয় পাঠকদের নোটিফিকেশন অ্যালার্ট।
* **নিরাপদ পাঠক নিবন্ধন (`/register`):** শুধুমাত্র পাঠক হিসেবে রেজিস্ট্রেশন কার্যকর (`role = 'user'`); কোনো ধরনের রোল ট্যাম্পারিং বা লেখক হওয়ার সুযোগ নেই।

### ২. অ্যাডমিন কমান্ড ডেস্ক (`/lens-desk`)
* **সুরক্ষিত নন-ডিফল্ট রুট:** সাধারণ `/admin`-এর বদলে সুরক্ষায় `/lens-desk` পাথ ব্যবহৃত।
* **১০০% সার্বজনীন দ্বিভাষিক ইন্টারফেস (BN ⇄ EN):** অ্যাডমিন প্যানেলের প্রতিটি মেনু, বাটন, ফর্ম লেবেল, প্লেসহোল্ডার, মেট্রিক্স ও টেবিল এক ক্লিকে বাংলা বা ইংরেজিতে রূপান্তরযোগ্য।
* **লগইন স্ক্রিনে ইন্টারঅ্যাক্টিভ বাংলাদেশ ভেক্টর ম্যাপ:** ডার্ক মোডে লাল (`#E31E24`) ও লাইট মোডে সবুজ (`#0E7A3A`) রঙে রেন্ডার হওয়া স্কেলযোগ্য SVG ম্যাপ, নদী, সাগর এবং ঢাকার পালসিং বীকন।
* **মাল্টি-বাইলাইন নির্বাচন (Multi-Author Byline):** সংবাদ তৈরির সময় ৩ ধরনের লেখক অপশন:
  1. *অ্যাকাউন্ট নাম:* লগইনকৃত অ্যাডমিনের নাম।
  2. *বেনামী (Anonymous):* পরিচয় গোপন রেখে প্রকাশ।
  3. *কাস্টম বাইলাইন:* বিশেষ প্রতিনিধি, নিজস্ব প্রতিবেদক বা নির্দিষ্ট কোনো নাম।
* **সংবাদ ও মিডিয়া ব্যবস্থাপনা (`/lens-desk/posts`):** ড্রাফট, এডিটর রিভিউ, সরাসরি প্রকাশ, লোকাল ফাইল আপলোড বা সরাসরি ইমেজ URL ও ইউটিউব ভিডিও লিঙ্ক সাপোর্ট।
* **লেখক নিয়ন্ত্রণ ও কেন্দ্রীয় ব্যবস্থাপনা (`/lens-desk/writers`):** 
  - **নতুন লেখক তৈরি (Create Writer Modal):** নাম, ইমেইল, মোবাইল ও সুরক্ষিত পাসওয়ার্ড দিয়ে সরাসরি নতুন লেখক তৈরি।
  - **পাঠক থেকে লেখক পদোন্নতি (Promote Reader Modal):** সাধারণ পাঠকদের তালিকা থেকে যেকোনো পাঠককে লেখক হিসেবে পদোন্নতি।
  - **পাসওয়ার্ড রিসেট (Reset Password Modal):** অ্যাডমিন কর্তৃক যেকোনো লেখকের পাসওয়ার্ড সরাসরি পরিবর্তন।
  - **লেখক থেকে পাঠক পদাবনতি (Demote to Reader):** লেখককে সাধারণ পাঠকে রূপান্তর।
  - **স্থগিত ও স্থায়ী অপসারণ (Suspend / Delete):** এক ক্লিকে অ্যাক্টিভ/সাসপেন্ড টগল এবং ডাটাবেজ থেকে অপসারণ।
* **বিজ্ঞাপন ও মনিটাইজেশন কন্ট্রোল (`/lens-desk/ads`):** ৫টি লাইভ কেপিআই (মোট বিজ্ঞাপন, চলমান, মোট ভিউ, ক্লিক, CTR) এবং স্ট্যান্ডার্ড সাইজ গাইডলাইন ট্র্যাকিং।
* **অনলাইন জরিপ ও পাঠক এনগেজমেন্ট (`/lens-desk/polls`):** নতুন পোল তৈরি, হোমপেজে সক্রিয়করণ, ভোট রিসেট এবং ডিলিট সুবিধা।
* **সাইট ব্র্যান্ডিং ও মেটা সেটিংস (`/lens-desk/settings`):** লোগো, পার্টনার লোগো, সম্পাদক/প্রকাশকের তথ্য, সরকারি নিবন্ধন নম্বর, সোশ্যাল লিঙ্ক ও ফুটার কনফিগারেশন।
* **প্রাতিষ্ঠানিক পেজ সিএমএস (`/lens-desk/pages`):** ৬টি মূল পেজের কন্টেন্ট সরাসরি অ্যাডমিন থেকে এডিট ও পাবলিশ করার সুবিধা।

### ৩. স্বতন্ত্র লেখক ব্যবস্থাপনা ও ওয়ার্কফ্লো (`/writer`)
* **স্বতন্ত্র ডেডিকেটেড লগইন পোর্টাল (`/writer/login`):**
  - সাধারণ ইউজার লগইন থেকে সম্পূর্ণ আলাদা।
  - ব্রুট-ফোর্স প্রটেকশন ও আইপি রেট-লিমিটিং (`RateLimiter.php`): ৫টি ব্যর্থ চেষ্টার পর ১৫ মিনিটের জন্য আইপি সাময়িক ব্লক।
  - জেনেরিক ত্রুটি বার্তা: সাধারণ পাঠকদের তথ্য দিলে বা তথ্য ভুল হলে অ্যাকাউন্ট অস্তিত্ব ফাঁস না করে সুরক্ষিত জেনেরিক মেসেজ প্রদর্শন।
  - লাইট/ডার্ক মোড ও বাংলা/ইংরেজি সুইচ সম্বলিত আধুনিক ইন্টারফেস।
* **পাবলিক রেজিস্ট্রেশন নিষিদ্ধ (`/writer/register`):**
  - সাধারণের জন্য উন্মুক্ত লেখক নিবন্ধন সম্পূর্ণ বন্ধ। এই রুটে প্রবেশ করলে ইউজারকে নোটিশ পেজে রিডাইরেক্ট করে সরাসরি লেখক লগইনে পাঠিয়ে দেওয়া হয়।
* **লেখক ড্যাশবোর্ড (`/writer/dashboard`):** 
  - মোট সংবাদ, প্রকাশিত, পর্যালোচনাধীন, প্রত্যাখ্যাত এবং মোট ভিউয়ের লাইভ কেপিআই মেট্রিক্স।
  - বাংলা ও ইংরেজি দ্বিভাষিক লেবেল এবং ডার্ক মোড সাপোর্ট।
* **কঠোর মালিকানা নিরাপত্তা (Strict Author Isolation):**
  - লেখকরা কেবল নিজের সংবাদ তৈরি, সম্পাদনা ও দেখতে পারেন (`author_id = $_SESSION['user_id']`)। অন্যের পোস্টে কোনো প্রকার প্রবেশাধিকার নেই।
* **সার্ভার-সাইড সুরক্ষা (HTTP 403 Forbidden):**
  - কোনো সাধারণ পাঠক ইউজার সেশন থাকা অবস্থায় জোরপূর্বক লেখক প্যানেল বা এপিআই অ্যাক্সেস করতে চাইলে মিডলওয়্যার তাৎক্ষণিক HTTP 403 এক্সেস ডিনাইড প্রদান করে।
* **পাবলিক লেখক পেজ (`/author/{id}`):**
  - লেখকের বায়ো, ছবি, সোশ্যাল মিডিয়া ও তার সকল প্রকাশিত প্রতিবেদনের সুবিন্যস্ত গ্রিড।

### ৪. পাঠক অ্যাকাউন্ট ও এনগেজমেন্ট (`/login`, `/register`)
* **বুকমার্ক ব্যবস্থা (`/user/bookmarks`):** পছন্দের যেকোনো খবর এক ক্লিকে বুকমার্ক (🔖) করে পরবর্তী সময়ে পড়ার সুবিধা।
* **সেলফ-সার্ভিস প্রোফাইল (`/profile`):** ছবি, বায়ো, যোগাযোগ ও পাসওয়ার্ড পরিবর্তনের পূর্ণ সুবিধা।
* **৩০-মিনিট ডুপ্লিকেট প্রতিরোধক ভিউ কাউন্টার:** প্রতিটি ইউনিক ভিউ হ্যাসড আইপি (`post_views`) দিয়ে সংরক্ষিত; একই ডিভাইস থেকে বারবার পেজ রিফ্রেশ করলেও ফেক ভিউ কাউন্ট হবে না।
* **স্মার্ট লগইন রিডাইরেক্ট:** কোনো লেখক ভুলবশত পাবলিক `/login` পেজে লগইন করার চেষ্টা করলে সিস্টেম তাকে নিরাপদ মেসেজ দিয়ে আলাদা লেখক লগইন পেজে রিডাইরেক্ট করে।

---

## 🛡️ অধিকার ও পারমিশন ম্যাট্রিক্স (Role Permissions Matrix)

| বৈশিষ্ট্য / অ্যাকশন | সাধারণ পাঠক (Reader) | অনুমোদিত লেখক (Writer) | সুপার অ্যাডমিন / এডিটর (Admin) |
| :--- | :---: | :---: | :---: |
| **লগইন পোর্টাল (Login URL)** | `/login` | **`/writer/login`** (স্বতন্ত্র) | **`/lens-desk/login`** (স্বতন্ত্র) |
| **অ্যাকাউন্ট তৈরি (Account Creation)** | পাবলিক সেলফ-রেজিস্টার (`/register`) | **অ্যাডমিন কর্তৃক তৈরি বা প্রমোট** | সুপার অ্যাডমিন কর্তৃক নিয়ন্ত্রিত |
| **সংবাদ পড়া, সার্চ ও শেয়ার** | ✅ | ✅ | ✅ |
| **সংবাদ বুকমার্ক (Bookmark)** | ✅ | ✅ | ✅ |
| **লেখক ড্যাশবোর্ড (`/writer/dashboard`)** | ❌ (HTTP 403) | ✅ | ✅ |
| **নতুন সংবাদ রচনা (`/writer/posts/create`)** | ❌ (HTTP 403) | ✅ (নিজের নামে লকড) | ✅ (যেকোনো বাইলাইন/বেনামী) |
| **অন্যের সংবাদ সম্পাদনা/ডিলিট** | ❌ | ❌ | ✅ |
| **সরাসরি প্রকাশ (Direct Publish)** | ❌ | ⚡ (অ্যাডমিনের অনুমতি সাপেক্ষে) | ✅ (তাৎক্ষণিক লাইভ) |
| **সম্পাদকীয় পর্যালোচনা (Editor Review)**| ❌ | 📝 (অ্যাডমিন অনুমোদনের অপেক্ষায়) | ✅ (অনুমোদন/বাতিলকারী) |
| **ব্রেকিং ও লিড নিউজ নির্ধারণ** | ❌ | ❌ | ✅ |
| **বিজ্ঞাপন, জরিপ ও সাইট সেটিংস** | ❌ | ❌ | ✅ |
| **লেখক অনুমোদন, তৈরি, রিসেট ও পদোন্নতি** | ❌ | ❌ | ✅ (`/lens-desk/writers`) |

---

## 🛠 প্রযুক্তি কাঠামো (Tech Stack)

| স্তর | প্রযুক্তি বিবরণ |
| :--- | :--- |
| **Frontend** | Semantic HTML5, Vanilla CSS3 (Custom Design System, Zero External CSS Frameworks), Vanilla ES6+ JS |
| **Backend** | PHP 8.2+ (Lightweight MVC Architecture, PSR-4 Autoloading, PDO Prepared Statements) |
| **Database** | MySQL 8.0+ / MariaDB (`utf8mb4_unicode_ci` বাংলা ফন্টের নিখুঁত রেন্ডারিংয়ের জন্য) |
| **Security** | CSRF Token Protection, XSS Sanitization, Password Hashing (`bcrypt`), RBAC Server-side Middleware, IP Rate Limiting (`RateLimiter.php`) |
| **Web Server** | LiteSpeed / Apache / Nginx (mod_rewrite সক্রিয়, HTTPS বাধ্যতামূলক) |
| **Background Cron** | PHP CLI (`cron/send_notifications.php`) |

---

## 🔗 সমস্ত পেজের লাইভ ও লোকাল সরাসরি লিঙ্ক তালিকা (Comprehensive URL Directory)

নিচের টেবিলে প্রতিটি মডিউলের জন্য লাইভ সার্ভার এবং লোকাল XAMPP সার্ভারের ক্লিকেবল লিঙ্ক দেওয়া হলো:

### ১. পাবলিক পাঠক ও সাধারণ পোর্টাল লিঙ্ক (Public Portal URLs)

| পেজ বা মডিউলের নাম (Module / Page) | ভূমিকা (Access) | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **হোমপেজ (Home Portal)** | সর্বসাধারণ | [`Live Home`](https://newslensbd.com/) | [`Local Home`](http://localhost/NewsLens/public/) |
| **সারাদেশ সংবাদ (৬৪ জেলা)** | সর্বসাধারণ | [`Live Saradesh`](https://newslensbd.com/saradesh) | [`Local Saradesh`](http://localhost/NewsLens/public/saradesh) |
| **ক্যাটাগরি: জাতীয় (National)** | সর্বসাধারণ | [`Live National`](https://newslensbd.com/category/national) | [`Local National`](http://localhost/NewsLens/public/category/national) |
| **ক্যাটাগরি: আন্তর্জাতিক (World)** | সর্বসাধারণ | [`Live World`](https://newslensbd.com/category/international) | [`Local World`](http://localhost/NewsLens/public/category/international) |
| **ক্যাটাগরি: রাজনীতি (Politics)** | সর্বসাধারণ | [`Live Politics`](https://newslensbd.com/category/politics) | [`Local Politics`](http://localhost/NewsLens/public/category/politics) |
| **ক্যাটাগরি: অর্থনীতি (Economy)** | সর্বসাধারণ | [`Live Economy`](https://newslensbd.com/category/economy) | [`Local Economy`](http://localhost/NewsLens/public/category/economy) |
| **ক্যাটাগরি: খেলাধুলা (Sports)** | সর্বসাধারণ | [`Live Sports`](https://newslensbd.com/category/sports) | [`Local Sports`](http://localhost/NewsLens/public/category/sports) |
| **ক্যাটাগরি: বিনোদন (Entertainment)** | সর্বসাধারণ | [`Live Entertainment`](https://newslensbd.com/category/entertainment) | [`Local Entertainment`](http://localhost/NewsLens/public/category/entertainment) |
| **ক্যাটাগরি: প্রযুক্তি (Technology)** | সর্বসাধারণ | [`Live Technology`](https://newslensbd.com/category/technology) | [`Local Technology`](http://localhost/NewsLens/public/category/technology) |
| **উন্নত সার্চ ও ফিল্টার (Search)** | সর্বসাধারণ | [`Live Search`](https://newslensbd.com/search) | [`Local Search`](http://localhost/NewsLens/public/search) |
| **লেখক পাবলিক প্রোফাইল (Author View)** | সর্বসাধারণ | [`Live Author`](https://newslensbd.com/author/1) | [`Local Author`](http://localhost/NewsLens/public/author/1) |
| **আমাদের সম্পর্কে (About Us)** | সর্বসাধারণ | [`Live About`](https://newslensbd.com/page/about) | [`Local About`](http://localhost/NewsLens/public/page/about) |
| **যোগাযোগ (Contact Us)** | সর্বসাধারণ | [`Live Contact`](https://newslensbd.com/page/contact) | [`Local Contact`](http://localhost/NewsLens/public/page/contact) |
| **বিজ্ঞাপন দিন (Advertise)** | সর্বসাধারণ | [`Live Advertise`](https://newslensbd.com/page/advertise) | [`Local Advertise`](http://localhost/NewsLens/public/page/advertise) |
| **গোপনীয়তা নীতি (Privacy Policy)** | সর্বসাধারণ | [`Live Privacy`](https://newslensbd.com/page/privacy) | [`Local Privacy`](http://localhost/NewsLens/public/page/privacy) |
| **ব্যবহারের শর্তাবলী (Terms)** | সর্বসাধারণ | [`Live Terms`](https://newslensbd.com/page/terms) | [`Local Terms`](http://localhost/NewsLens/public/page/terms) |
| **সম্পাদকীয় নীতি (Corrections Policy)** | সর্বসাধারণ | [`Live Corrections`](https://newslensbd.com/page/corrections) | [`Local Corrections`](http://localhost/NewsLens/public/page/corrections) |

### ২. পাঠক অ্যাকাউন্ট ও অথেনটিকেশন লিঙ্ক (Reader Auth & Profile URLs)

| পেজ বা ফিচারের নাম (Feature) | ভূমিকা (Access) | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **পাঠক লগইন (Reader Login)** | সাধারণ পাঠক | [`Live Reader Login`](https://newslensbd.com/login) | [`Local Reader Login`](http://localhost/NewsLens/public/login) |
| **পাঠক সেলফ-রেজিস্টার (Reader Register)** | নতুন পাঠক | [`Live Reader Register`](https://newslensbd.com/register) | [`Local Reader Register`](http://localhost/NewsLens/public/register) |
| **ওটিপি ভেরিফিকেশন (OTP Verify)** | নতুন পাঠক | [`Live OTP Verify`](https://newslensbd.com/otp/verify) | [`Local OTP Verify`](http://localhost/NewsLens/public/otp/verify) |
| **পাসওয়ার্ড ভুলে গেছেন (Forgot Password)**| যেকেউ | [`Live Forgot Password`](https://newslensbd.com/password/forgot) | [`Local Forgot Password`](http://localhost/NewsLens/public/password/forgot) |
| **সংরক্ষিত খবর (Saved Bookmarks)** | লগইন পাঠক | [`Live Bookmarks`](https://newslensbd.com/user/bookmarks) | [`Local Bookmarks`](http://localhost/NewsLens/public/user/bookmarks) |
| **পাঠক প্রোফাইল ও সেটিংস** | লগইন ইউজার | [`Live User Profile`](https://newslensbd.com/profile) | [`Local User Profile`](http://localhost/NewsLens/public/profile) |
| **পাঠক লগআউট (Logout)** | লগইন ইউজার | [`Live User Logout`](https://newslensbd.com/logout) | [`Local User Logout`](http://localhost/NewsLens/public/logout) |

### ৩. স্বতন্ত্র লেখক প্যানেল লিঙ্ক (Dedicated Writer Portal URLs)

| পেজ বা ফিচারের নাম (Feature) | ভূমিকা (Access) | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **লেখক লগইন পোর্টাল (Writer Login)** | নিবন্ধিত লেখক | [`Live Writer Login`](https://newslensbd.com/writer/login) | [`Local Writer Login`](http://localhost/NewsLens/public/writer/login) |
| **লেখক ড্যাশবোর্ড (Writer Dashboard)** | অ্যাক্টিভ লেখক | [`Live Writer Dashboard`](https://newslensbd.com/writer/dashboard) | [`Local Writer Dashboard`](http://localhost/NewsLens/public/writer/dashboard) |
| **লেখক নতুন সংবাদ রচনা (Create Post)** | অ্যাক্টিভ লেখক | [`Live Create News`](https://newslensbd.com/writer/posts/create) | [`Local Create News`](http://localhost/NewsLens/public/writer/posts/create) |
| **আমার সংবাদসমূহ (Writer's Own Posts)**| অ্যাক্টিভ লেখক | [`Live My Posts`](https://newslensbd.com/writer/posts) | [`Local My Posts`](http://localhost/NewsLens/public/writer/posts) |
| **পাবলিক রেজিস্ট্রেশন বন্ধ নোটিশ** | আবেদনকারী | [`Live Writer Notice`](https://newslensbd.com/writer/register) | [`Local Writer Notice`](http://localhost/NewsLens/public/writer/register) |
| **লেখক নিরাপদ লগআউট (Writer Logout)** | অ্যাক্টিভ লেখক | [`Live Writer Logout`](https://newslensbd.com/writer/logout) | [`Local Writer Logout`](http://localhost/NewsLens/public/writer/logout) |

### ৪. অ্যাডমিন কমান্ড ডেস্ক লিঙ্ক (Admin Desk URLs — `/lens-desk`)

| পেজ বা ম্যানেজমেন্ট ফিচার (Admin Module) | ভূমিকা (Access) | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **অ্যাডমিন লগইন ডেস্ক (Admin Login)** | সুপার অ্যাডমিন | [`Live Admin Login`](https://newslensbd.com/lens-desk/login) | [`Local Admin Login`](http://localhost/NewsLens/public/lens-desk/login) |
| **অ্যাডমিন মূল ড্যাশবোর্ড (Dashboard)** | অ্যাডমিন/এডিটর | [`Live Admin Desk`](https://newslensbd.com/lens-desk) | [`Local Admin Desk`](http://localhost/NewsLens/public/lens-desk) |
| **সংবাদ ও মিডিয়া ব্যবস্থাপনা (Posts CMS)** | অ্যাডমিন/এডিটর | [`Live Admin Posts`](https://newslensbd.com/lens-desk/posts) | [`Local Admin Posts`](http://localhost/NewsLens/public/lens-desk/posts) |
| **কেন্দ্রীয় লেখক নিয়ন্ত্রণ ডেস্ক (Writers)**| সুপার অ্যাডমিন | [`Live Writers Control`](https://newslensbd.com/lens-desk/writers) | [`Local Writers Control`](http://localhost/NewsLens/public/lens-desk/writers) |
| **নতুন লেখক তৈরি রুট (Create Action)** | সুপার অ্যাডমিন | [`Live Create Writer`](https://newslensbd.com/lens-desk/writers/create) | [`Local Create Writer`](http://localhost/NewsLens/public/lens-desk/writers/create) |
| **লেখক পাসওয়ার্ড রিসেট (Reset Pass)** | সুপার অ্যাডমিন | [`Live Reset Password`](https://newslensbd.com/lens-desk/writers/reset-password) | [`Local Reset Password`](http://localhost/NewsLens/public/lens-desk/writers/reset-password) |
| **পাঠক থেকে লেখক পদোন্নতি (Promote)** | সুপার অ্যাডমিন | [`Live Promote Reader`](https://newslensbd.com/lens-desk/writers/promote) | [`Local Promote Reader`](http://localhost/NewsLens/public/lens-desk/writers/promote) |
| **লেখক থেকে পাঠক পদাবনতি (Demote)** | সুপার অ্যাডমিন | [`Live Demote Writer`](https://newslensbd.com/lens-desk/writers/demote) | [`Local Demote Writer`](http://localhost/NewsLens/public/lens-desk/writers/demote) |
| **বিজ্ঞাপন ও স্পন্সর ডেস্ক (Ads Desk)** | সুপার অ্যাডমিন | [`Live Ads Desk`](https://newslensbd.com/lens-desk/ads) | [`Local Ads Desk`](http://localhost/NewsLens/public/lens-desk/ads) |
| **অনলাইন পাঠক জরিপ ডেস্ক (Polls Desk)** | অ্যাডমিন/এডিটর | [`Live Polls Desk`](https://newslensbd.com/lens-desk/polls) | [`Local Polls Desk`](http://localhost/NewsLens/public/lens-desk/polls) |
| **নিউজলেটার সাবস্ক্রাইবার তালিকা** | সুপার অ্যাডমিন | [`Live Subscribers`](https://newslensbd.com/lens-desk/subscribers) | [`Local Subscribers`](http://localhost/NewsLens/public/lens-desk/subscribers) |
| **সাইট মেটা, ব্র্যান্ডিং ও সেটিংস** | সুপার অ্যাডমিন | [`Live Settings`](https://newslensbd.com/lens-desk/settings) | [`Local Settings`](http://localhost/NewsLens/public/lens-desk/settings) |
| **প্রাতিষ্ঠানিক পেজ ব্যবস্থাপনা (Pages)** | অ্যাডমিন/এডিটর | [`Live Pages CMS`](https://newslensbd.com/lens-desk/pages) | [`Local Pages CMS`](http://localhost/NewsLens/public/lens-desk/pages) |
| **অ্যাডমিন লগআউট (Admin Logout)** | অ্যাডমিন সেশন | [`Live Admin Logout`](https://newslensbd.com/lens-desk/logout) | [`Local Admin Logout`](http://localhost/NewsLens/public/lens-desk/logout) |

---

## 🌐 লাইভ সার্ভার ডিপ্লয়মেন্ট ও হোস্টিং তথ্য (Live Deployment)

Newslensbd পোর্টালটি লাইভ cPanel হোস্টিং সার্ভারে সংযুক্ত ও কনফিগার করা হয়েছে।

### cPanel সংযোগ তথ্য:
* **কন্ট্রোল প্যানেল URL:** [https://s16us-app.whiteservers.net:2083/](https://s16us-app.whiteservers.net:2083/) অথবা [https://newslensbd.com:2083/](https://newslensbd.com:2083/)
* **সার্ভার আইপি:** `104.243.34.108`
* **cPanel ইউজারনেম:** `lcxdiarj`
* **ডেটাবেজ:** `lcxdiarj_newslens`
* **ওয়েব রুট:** `public_html/` (যেখানে সমস্ত রিকোয়েস্ট নিরাপদে `public/index.php`-তে রিডাইরেক্ট হয়)

---

## 🛠️ প্রযুক্তিগত অপ্টিমাইজেশন ও নিরাপত্তা সমাধান (Technical Optimizations & Security)

১. **প্রতিষ্ঠান পেজসমূহের সমাধান (`/page/{slug}`):**
   - লাইভ ডেটাবেজে `pages` টেবিল তৈরি ও ৬টি প্রাথমিক পেজ (আমাদের সম্পর্কে, যোগাযোগ, বিজ্ঞাপন, গোপনীয়তা নীতি, ব্যবহারের শর্তাবলী, সংশোধনী নীতি) স্বয়ংক্রিয়ভাবে সিড করা হয়েছে।
   - এখন ফুটার বা অন্য যেকোনো লিঙ্ক থেকে ক্লিক করলে কোনো 404 ত্রুটি ছাড়াই সম্পূর্ণ পেজ রেন্ডার হয়।

২. **লেখক ব্যবস্থাপনা সম্পূর্ণ পৃথকীকরণ ও নিরাপত্তা জোরদার (Complete Writer Separation):**
   - **পাবলিক রেজিস্ট্রেশন সুরক্ষা:** পাবলিক ফর্ম ও এপিআই এন্ডপয়েন্ট থেকে লেখকের রোল নির্বাচনের অপশন স্থায়ীভাবে সরিয়ে দেওয়া হয়েছে। যে কেউ রেজিস্ট্রেশন করলে বাধ্যতামূলকভাবে `role = 'user'` অ্যাসাইন হবে।
   - **স্বতন্ত্র লগইন রুট (`/writer/login`):** সাধারণ পাঠকদের জন্য `/login` এবং লেখকদের জন্য স্বতন্ত্র `/writer/login` স্থাপন। লেখক ভুল করে পাবলিক সাইটে লগইন করলে সিস্টেম তাকে নিরাপদে লগআউট করিয়ে লেখক পোর্টালে পাঠিয়ে দেয়।
   - **ব্রুট-ফোর্স প্রটেকশন ও আইপি রেট-লিমিটিং (`RateLimiter.php`):** ৫টি ব্যর্থ লগইন চেষ্টার পর সংশ্লিষ্ট আইপি ১৫ মিনিটের জন্য সাময়িক ব্লক।
   - **ব্যবহারকারীর অস্তিত্ব গোপন রাখা (Generic Login Error):** লেখক না এমন কোনো সাধারণ পাঠকের ইমেইল/পাসওয়ার্ড দেওয়া হলে "ইউজার পাওয়া যায়নি" না বলে জেনেরিক মেসেজ দিয়ে সিস্টেমে ইউজারের রোল বা অস্তিত্ব ফাঁস হওয়া রোধ করা হয়েছে।
   - **কঠোর সার্ভার-সাইড পারমিশন (HTTP 403 Forbidden):** সাধারণ পাঠক সেশন নিয়ে জোরপূর্বক লেখক রুটে প্রবেশের চেষ্টা করলে মিডলওয়্যারে সরাসরি HTTP 403 এরর প্রদান।
   - **কেন্দ্রীয় অ্যাডমিন নিয়ন্ত্রণ (`/lens-desk/writers`):** অ্যাডমিন কর্তৃক যেকোনো সময় নতুন লেখক তৈরি, পাসওয়ার্ড রিসেট, পাঠককে লেখক পদে প্রমোশন, লেখককে পাঠকে ডিমোশন, একাউন্ট সাসপেন্ড ও পার্মানেন্ট ডিলিট সুবিধা।
   - **লেখক পোস্ট আইসোলেশন:** লেখক ড্যাশবোর্ড ও পোস্ট তালিকায় `author_id` কঠোরভাবে ফিল্টার করা হয়েছে যাতে কোনো লেখক অন্যের পোস্টের সন্ধান না পায়।
   - **দ্বিভাষিক (বাংলা/ইংরেজি) ও ডার্ক/লাইট মোড:** লেখক লগইন, ড্যাশবোর্ড এবং পোস্ট ম্যানেজমেন্টের প্রতিটি উপাদানে দ্বিভাষিক ও আধুনিক ডার্ক থিম সংযুক্ত।

---

© 2026 **Newslensbd** | ডেভেলপমেন্ট ও টেকনিক্যাল পার্টনার: **[Stratifyx Global](https://stratifyxglobal.com)**
