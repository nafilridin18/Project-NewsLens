# Newslensbd (নিউজলেন্সবিডি) — Professional Bangla News Portal & Editorial CMS

<div align="center">

![NewsLens BD Brand](public/assets/images/logo.png)

### **সাধারণের বাইরে, সত্যের খোঁজে | News Beyond the Ordinary**

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Architecture](https://img.shields.io/badge/Architecture-Custom%20MVC-0E7A3A?style=for-the-badge)](/)
[![License](https://img.shields.io/badge/License-Proprietary-E31E24?style=for-the-badge)](/)
[![Security](https://img.shields.io/badge/Security-RBAC%20%7C%20CSRF%20%7C%20RateLimit-0284c7?style=for-the-badge)](/)

**লাইভ ওয়েবসাইট:** [https://newslensbd.com](https://newslensbd.com) • **টেকনোলজি পার্টনার:** [Stratifyx Global](https://stratifyxglobal.com)

</div>

---

## 📑 সূচিপত্র (Table of Contents)
- [🔑 দ্রুত অ্যাক্সেস ও লগইন ক্রেডেনশিয়াল (Quick Access & Credentials)](#-দ্রুত-অ্যাক্সেস-ও-লগইন-ক্রেডেনশিয়াল-quick-access--credentials)
- [🎨 ব্র্যান্ড পরিচয় ও ইন্টারফেস ডিজাইন (Brand Identity & UI Design)](#-ব্র্যান্ড-পরিচয়-ও-ইন্টারফেস-ডিজাইন-brand-identity--ui-design)
- [🛡️ রোল ও পারমিশন ম্যাট্রিক্স (Role Permissions Matrix)](#-রোল-ও-পারমিশন-ম্যাট্রিক্স-role-permissions-matrix)
- [🚀 প্রধান ফিচার ও মডিউলসমূহ (Core Modules)](#-প্রধান-ফিচার-ও-মডিউলসমূহ-core-modules)
  - [১. পাবলিক পাঠক পোর্টাল (Public Reader Portal)](#১-পাবলিক-পাঠক-পোর্টাল-public-reader-portal)
  - [২. অ্যাডমিন কমান্ড হাব (`/lens-desk`)](#২-অ্যাডমিন-কমান্ড-হাব-lens-desk)
  - [৩. স্বতন্ত্র লেখক ব্যবস্থাপনা (`/writer`)](#৩-স্বতন্ত্র-লেখক-ব্যবস্থাপনা-writer)
  - [৪. পাঠক প্রোফাইল ও বুকমার্ক](#৪-পাঠক-প্রোফাইল-ও-বুকমার্ক)
- [🔗 সর্বাঙ্গীন পেজ ও সরাসরি লিঙ্ক ডিরেক্টরি (Complete URL Directory)](#-সর্বাঙ্গীন-পেজ-ও-সরাসরি-লিঙ্ক-ডিরেক্টরি-complete-url-directory)
- [🛠 প্রযুক্তি কাঠামো (Tech Stack)](#-প্রযুক্তি-কাঠামো-tech-stack)
- [🌐 সার্ভার ও ডিপ্লয়মেন্ট আর্কিটেকচার (Deployment & Hosting)](#-সার্ভার-ও-ডিপ্লয়মেন্ট-আর্কিটেকচার-deployment--hosting)
- [🔒 নিরাপত্তা ও পারফরম্যান্স অপ্টিমাইজেশন (Security & Optimizations)](#-নিরাপত্তা-ও-পারফরম্যান্স-অপ্টিমাইজেশন-security--optimizations)

---

## 🔑 দ্রুত অ্যাক্সেস ও লগইন ক্রেডেনশিয়াল (Quick Access & Credentials)

সিস্টেমের সুপার অ্যাডমিন, অনুমোদিত লেখক ও সাধারণ পাঠক পোর্টালে সরাসরি অ্যাক্সেসের জন্য যাচাইকৃত ক্রেডেনশিয়াল নিচে দেওয়া হলো:

### ১. অথেনটিকেশন পোর্টাল ও ক্রেডেনশিয়াল তালিকা

| পোর্টাল (Portal) | ভূমিকা (Role) | ইমেইল / ইউজার আইডি (Email / ID) | ডিফল্ট পাসওয়ার্ড (Password) | লাইভ সরাসরি লিঙ্ক (Live Link) | লোকাল সরাসরি লিঙ্ক (Local Link) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **অ্যাডমিন কমান্ড ডেস্ক** | Super Admin | `admin@newslensbd.com` | `password123` | [`🌐 Live Admin Login`](https://newslensbd.com/lens-desk/login) | [`💻 Local Admin Login`](http://localhost/NewsLens/public/lens-desk/login) |
| **লেখক পোর্টাল** | Dedicated Writer | অ্যাডমিন কর্তৃক প্রদত্ত লেখকের ইমেইল | লেখকের নিজস্ব পাসওয়ার্ড | [`✍️ Live Writer Login`](https://newslensbd.com/writer/login) | [`✍️ Local Writer Login`](http://localhost/NewsLens/public/writer/login) |
| **সাধারণ পাঠক পোর্টাল** | Public Reader | নিবন্ধিত পাঠকের ইমেইল | পাঠকের নিজস্ব পাসওয়ার্ড | [`📖 Live Reader Login`](https://newslensbd.com/login) | [`📖 Local Reader Login`](http://localhost/NewsLens/public/login) |

> [!IMPORTANT]
> **সম্পূর্ণ পৃথকীকৃত লেখক সিস্টেম (Zero Public Writer Registration):**  
> সাধারণ পাবলিক রেজিস্ট্রেশন পেজ (`/register`) থেকে লেখকদের রেজিস্ট্রেশন বা আবেদন করার সমস্ত অপশন বিলুপ্ত করা হয়েছে। পাঠকরা সেলফ-রেজিস্ট্রেশনে সর্বদা সাধারণ পাঠক (`role = 'user'`) হিসেবে নিবন্ধিত হবেন। নতুন লেখক অ্যাকাউন্ট শুধুমাত্র সুপার অ্যাডমিন কর্তৃক [`Admin Writers Desk`](https://newslensbd.com/lens-desk/writers) থেকে তৈরি করা অথবা বিদ্যমান পাঠককে পদোন্নতি (Promote) দেওয়ার মাধ্যমে সক্রিয় করা যায়।

> [!TIP]
> **ইনস্ট্যান্ট টেস্ট মাস্টার ওটিপি (Universal Test OTP):**  
> নতুন পাঠক নিবন্ধন, ইমেইল যাচাইকরণ কিংবা পাসওয়ার্ড পুনরুদ্ধারের সময় যেকোনো ইমেইল ঠিকানায় ওটিপি কোড হিসেবে **`123456`** ব্যবহার করা যাবে। এছাড়াও স্ক্রিনে এক ক্লিকেই ওটিপি ইনপুট করার অটো-ফিল বোতাম অন্তর্ভুক্ত রয়েছে।

### ২. ডাটাবেজ কনফিগারেশন

| পরিবেশ (Environment) | হোস্ট (Host) | পোর্ট (Port) | ডাটাবেজ নাম (DB Name) | ব্যবহারকারী (Username) | পাসওয়ার্ড (Password) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **লোকাল এনভায়রনমেন্ট (XAMPP)** | `127.0.0.1` | `3306` / `3307` | `newslensbd` | `root` | *(ফাঁকা / কোনো পাসওয়ার্ড নেই)* |
| **লাইভ প্রোডাকশন (cPanel)** | `localhost` | `3306` | `lcxdiarj_newslens` | `lcxdiarj_lensuser` | `Lens#Secure2026!Db` |

---

## 🎨 ব্র্যান্ড পরিচয় ও ইন্টারফেস ডিজাইন (Brand Identity & UI Design)

Newslensbd-এর প্রতিটি পোর্টালে স্বতন্ত্র ভিজ্যুয়াল হায়ারার্কি ও ব্র্যান্ডিং অনুসরণ করা হয়েছে:

| পোর্টাল হেডার | ব্র্যান্ড ব্যাজ স্ট্রাকচার | কালার স্কিম ও ভিজ্যুয়াল বৈশিষ্ট্য |
| :--- | :--- | :--- |
| **সুপার অ্যাডমিন লগইন** | `NEWSLENS` `[BD]` `[ADMIN]` | `NEWS` (কালো/সাদা) + `LENS` (`#0E7A3A`) + রক্তিম লাল `BD` পিল (`#E31E24`) + ব্লু গ্র্যাডিয়েন্ট `ADMIN` পিল |
| **স্বতন্ত্র লেখক লগইন** | `NEWSLENS` `[BD]` `[WRITER]` | `NEWS` (কালো/সাদা) + `LENS` (`#0E7A3A`) + রক্তিম লাল `BD` পিল (`#E31E24`) + ব্লু গ্র্যাডিয়েন্ট `WRITER` পিল |
| **পাবলিক পাঠক পোর্টাল** | `NEWSLENS` `[BD]` | জাতীয় ব্র্যান্ড লোগো, সাবটাইটেল এবং ডাইনামিক ডেট/ওয়েদার টপবার |

### 🗺️ ইন্টারেক্টিভ বাংলাদেশ ভেক্টর এসভিজি ম্যাপ (Dynamic Vector Map):
- **ডার্ক মোড (Dark Theme):** ডিপ নেভি পটভূমিতে বাংলাদেশের ৮টি বিভাগ ডাইনামিক রেড (`#E31E24` ও `#991b1b`) প্যালেটে রেন্ডার হয়, বঙ্গোপসাগর ওয়েভ ও ঢাকার বুকে উজ্জ্বল পালসিং বীকন প্রদর্শিত হয়।
- **লাইট মোড (Light Theme):** সাদা ও ধূসর পটভূমিতে ম্যাপের জাতীয় সীমানা এবং বিভাগীয় বিভাজন নিখুঁত কনট্রাস্টের জন্য সলিড ব্ল্যাক (`#000000`, 7px stroke) লাইনে দৃশ্যমান থাকে এবং বিভাগের ভেতরের অংশ উজ্জ্বল পান্না সবুজ (`#0E7A3A`) রঙে রেন্ডার হয়।
- **দ্বিভাষিক ইনস্ট্যান্ট সুইচ:** এক ক্লিকে সমগ্র ইন্টারফেস বাংলা অথবা ইংরেজিতে (BN ⇄ EN) পরিবর্তিত হয়।

---

## 🛡️ রোল ও পারমিশন ম্যাট্রিক্স (Role Permissions Matrix)

| বৈশিষ্ট্য ও সুবিধা | সাধারণ পাঠক (Reader) | অনুমোদিত লেখক (Writer) | সুপার অ্যাডমিন / এডিটর (Admin) |
| :--- | :---: | :---: | :---: |
| **লগইন ইউআরএল (Login URL)** | `/login` | **`/writer/login`** *(স্বতন্ত্র)* | **`/lens-desk/login`** *(নন-ডিফল্ট)* |
| **অ্যাকাউন্ট তৈরি পদ্ধতি** | সেলফ-রেজিস্টার (`/register`) | **অ্যাডমিন কর্তৃক তৈরি বা প্রমোট** | সুপার অ্যাডমিন কর্তৃক নিয়ন্ত্রিত |
| **সংবাদ পাঠ ও সোশ্যাল শেয়ার** | ✅ | ✅ | ✅ |
| **খবর বুকমার্ক সংরক্ষণ (Bookmarks)**| ✅ | ✅ | ✅ |
| **লেখক ড্যাশবোর্ড (`/writer/dashboard`)** | ❌ *(HTTP 403)* | ✅ | ✅ |
| **সংবাদ তৈরি ও নিজস্ব পোস্ট ড্রাফট** | ❌ *(HTTP 403)* | ✅ *(নিজের নামে লকড)* | ✅ *(যেকোনো বাইলাইন/বেনামী)* |
| **অন্যের পোস্ট এডিট বা ডিলিট** | ❌ | ❌ | ✅ |
| **সম্পাদকীয় অনুমোদন (Editor Review)** | ❌ | 📝 *(অনুমোদনের অপেক্ষায়)* | ✅ *(অনুমোদনকারী)* |
| **লাইভ সরাসরি প্রকাশ (Direct Publish)** | ❌ | ⚡ *(অ্যাডমিন অনুমতি সাপেক্ষে)* | ✅ *(তাৎক্ষণিক লাইভ)* |
| **ব্রেকিং ও লিড নিউজ নির্ধারণ** | ❌ | ❌ | ✅ |
| **বিজ্ঞাপন, স্পন্সর ও পাঠক জরিপ (Polls)**| ❌ | ❌ | ✅ |
| **লেখক অনুমোদন, পাসওয়ার্ড রিসেট ও পদোন্নতি**| ❌ | ❌ | ✅ *(সম্পূর্ণ কমান্ড)* |
| **প্রাতিষ্ঠানিক পেজ কন্টেন্ট এডিটর (CMS)** | ❌ | ❌ | ✅ (`/lens-desk/pages`) |

---

## 🚀 প্রধান ফিচার ও মডিউলসমূহ (Core Modules)

### ১. পাবলিক পাঠক পোর্টাল (Public Reader Portal)
* **স্মার্ট টপবার:** স্বয়ংক্রিয় বাংলা ও হিজরি ক্যালেন্ডার রূপান্তরকারী (`BanglaDate`), লাইভ আবহাওয়া, ফন্ট স্কেলার (অ-, অ, অ+), ভাষা সুইচ (`[বাংলা | English]`) এবং ডার্ক/লাইট মোড সিলেক্টর।
* **ব্রেকিং নিউজ টিকার:** মসৃণ অটোপ্লে, লাল পালসিং লাইভ ডট, মাউস হোভারে পজ এবং ম্যানুয়াল তীরচিহ্ন নেভিগেশন।
* **সারাদেশ ও ৬৪ জেলার সংবাদ নেটওয়ার্ক (`/saradesh`):** ৮টি বিভাগ ও ৬৪টি জেলার ক্যাসকেডিং ড্রপডাউন ফিল্টারিং সহ জেলাভিত্তিক সংবাদ পাঠের ব্যবস্থা।
* **সিঙ্গেল আর্টিকেল রিডার:** ক্যাটাগরি, লেখক তথ্য, প্রকাশের সময়, সোশ্যাল শেয়ারিং বাটন (Facebook, WhatsApp, X, Copy Link), সম্পর্কিত সংবাদ গ্রিড এবং ভিডিও পপআপ লাইটবক্স।
* **মাল্টি-ক্রাইটেরিয়া সার্চ (`/search`):** কীওয়ার্ড, ক্যাটাগরি, সময়কাল (আজ, ৭ দিন, ৩০ দিন) এবং জেলাভিত্তিক গভীর সার্চ ফিল্টার।
* **প্রাতিষ্ঠানিক ও সম্পাদকীয় পেজসমূহ (`/page/{slug}`):** আমাদের সম্পর্কে, যোগাযোগ, বিজ্ঞাপন, গোপনীয়তা নীতি, ব্যবহারের শর্তাবলী এবং সংশোধনী নীতি সরাসরি ডেটাবেজ-ম্যানেজড ও 404 ত্রুটিমুক্ত।
* **ইন্টারেক্টিভ সাইডবার:** ডাটাবেজ-সংযুক্ত অনলাইন পাঠক জরিপ (Poll) লাইভ ভোট ও রিয়েলটাইম পার্সেন্টেজ ফলাফল, নামাজের সময়সূচি, নিউজলেটার সাবস্ক্রিপশন এবং বিজ্ঞাপন স্লট।
* **PWA ও ওয়েব পুশ নোটিফিকেশন:** সার্ভিস ওয়ার্কার (`/sw.js`) ও ব্যাকগ্রাউন্ড পুশ সাপোর্ট (`/manifest.json`); ব্রেকিং নিউজ প্রকাশিত হওয়ামাত্রই সক্রিয় পাঠকদের ইনস্ট্যান্ট ব্রাউজার নোটিফিকেশন।

### ২. অ্যাডমিন কমান্ড হাব (`/lens-desk`)
* **নিরাপদ নন-ডিফল্ট রুট:** সাধারণ `/admin` রুটের বদলে আক্রমণ প্রতিহত করতে নন-ডিফল্ট `/lens-desk` পাথ ব্যবহৃত।
* **দ্বিভাষিক অ্যাডমিন ইন্টারফেস (BN ⇄ EN):** অ্যাডমিন প্যানেলের প্রতিটি মেনু, বাটন, টেবিল, ফর্ম এবং স্ট্যাটিস্টিক কার্ড ইনস্ট্যান্ট দ্বিভাষিক রূপান্তরযোগ্য।
* **মাল্টি-বাইলাইন সিলেকশন:** সংবাদ রচনার সময় ৩ ধরনের লেখক অপশন:
  1. *অ্যাকাউন্ট নাম:* লগইনকৃত অ্যাডমিনের মূল নাম।
  2. *বেনামী (Anonymous):* স্পর্শকাতর সংবাদের ক্ষেত্রে পরিচয় গোপন রাখা।
  3. *কাস্টম বাইলাইন:* বিশেষ প্রতিনিধি, নিজস্ব প্রতিবেদক বা নির্দিষ্ট প্রতিনিধির নাম।
* **সংবাদ ও মিডিয়া ব্যবস্থাপনা (`/lens-desk/posts`):** ড্রাফট, এডিটর রিভিউ, তাৎক্ষণিক প্রকাশ, লোকাল ফাইল আপলোড, সরাসরি ইমেজ URL ও ইউটিউব ভিডিও এম্বেডিং সাপোর্ট।
* **কেন্দ্রীয় লেখক নিয়ন্ত্রণ ডেস্ক (`/lens-desk/writers`):**
  - **নতুন লেখক তৈরি:** নাম, ইমেইল, মোবাইল ও সুরক্ষিত পাসওয়ার্ড দিয়ে সরাসরি লেখক অ্যাকাউন্ট তৈরি।
  - **পাঠক থেকে লেখক পদোন্নতি:** যেকোনো নিবন্ধিত সাধারণ পাঠককে তাৎক্ষণিক লেখক পদে পদোন্নতি।
  - **পাসওয়ার্ড রিসেট:** অ্যাডমিন কর্তৃক যেকোনো লেখকের পাসওয়ার্ড সরাসরি পরিবর্তন।
  - **লেখক থেকে পাঠক পদাবনতি:** প্রয়োজনে লেখক অ্যাকাউন্টকে সাধারণ পাঠক স্তরে রূপান্তর।
  - **স্থগিত ও স্থায়ী অপসারণ:** এক ক্লিকে অ্যাক্টিভ/সাসপেন্ড টগল এবং স্থায়ী ডাটাবেজ ডিলিট।
* **বিজ্ঞাপন ও স্পন্সর কন্ট্রোল (`/lens-desk/ads`):** ৫টি লাইভ কেপিআই (মোট বিজ্ঞাপন, চলমান, মোট ভিউ, ক্লিক, CTR) এবং স্ট্যান্ডার্ড সাইজ গাইডলাইন ট্র্যাকিং।
* **অনলাইন জরিপ ও পাঠক এনগেজমেন্ট (`/lens-desk/polls`):** নতুন পোল তৈরি, হোমপেজে সক্রিয়করণ, ভোট রিসেট এবং ডিলিট সুবিধা।
* **সাইট ব্র্যান্ডিং ও মেটা সেটিংস (`/lens-desk/settings`):** লোগো, পার্টনার লোগো, সম্পাদক/প্রকাশকের তথ্য, সরকারি নিবন্ধন নম্বর, সোশ্যাল লিঙ্ক ও ফুটার কনফিগারেশন।
* **প্রাতিষ্ঠানিক পেজ সিএমএস (`/lens-desk/pages`):** ৬টি মূল প্রাতিষ্ঠানিক পেজের কন্টেন্ট সরাসরি অ্যাডমিন ড্যাশবোর্ড থেকে এডিট ও পাবলিশ করার সুবিধা।

### ৩. স্বতন্ত্র লেখক ব্যবস্থাপনা (`/writer`)
* **স্বতন্ত্র ডেডিকেটেড লগইন পোর্টাল (`/writer/login`):** সাধারণ পাঠক লগইন থেকে সম্পূর্ণ বিচ্ছিন্ন।
* **ব্রুট-ফোর্স প্রটেকশন ও আইপি রেট-লিমিটিং (`RateLimiter.php`):** ৫টি ব্যর্থ চেষ্টার পর ১৫ মিনিটের জন্য আইপি সাময়িক ব্লক।
* **ইউজার অস্তিত্ব সুরক্ষা (Generic Login Error):** ভুল পাসওয়ার্ড বা সাধারণ পাঠকের তথ্য দিলে অ্যাকাউন্ট অস্তিত্ব প্রকাশ না করে নিরাপদ জেনেরিক মেসেজ প্রদর্শন।
* **পাবলিক রেজিস্ট্রেশন নিষিদ্ধ (`/writer/register`):** সাধারণের জন্য উন্মুক্ত লেখক নিবন্ধন বন্ধ; প্রবেশ করলে নোটিশ পেজ প্রদর্শন করে সরাসরি লেখক লগইনে রিডাইরেক্ট করা হয়।
* **লেখক ড্যাশবোর্ড (`/writer/dashboard`):** মোট সংবাদ, প্রকাশিত, পর্যালোচনাধীন, প্রত্যাখ্যাত এবং মোট ভিউয়ের লাইভ কেপিআই মেট্রিক্স।
* **কঠোর মালিকানা নিরাপত্তা (Strict Author Isolation):** লেখকরা কেবল নিজের সংবাদ তৈরি, সম্পাদনা ও দেখতে পারেন (`author_id = $_SESSION['user_id']`)। অন্যের পোস্টে কোনো প্রকার প্রবেশাধিকার নেই।
* **সার্ভার-সাইড সুরক্ষা (HTTP 403 Forbidden):** সাধারণ পাঠক সেশন নিয়ে জোরপূর্বক লেখক প্যানেল বা এপিআই অ্যাক্সেস করতে চাইলে মিডলওয়্যার তাৎক্ষণিক HTTP 403 প্রদান করে।
* **পাবলিক লেখক পেজ (`/author/{id}`):** লেখকের বায়ো, ছবি, সোশ্যাল মিডিয়া ও তার সকল প্রকাশিত প্রতিবেদনের সুবিন্যস্ত গ্রিড।

### ৪. পাঠক প্রোফাইল ও বুকমার্ক
* **বুকমার্ক ব্যবস্থা (`/user/bookmarks`):** পছন্দের যেকোনো খবর এক ক্লিকে বুকমার্ক (🔖) করে পরবর্তীতে পড়ার সুবিধা।
* **সেলফ-সার্ভিস প্রোফাইল (`/profile`):** ছবি, বায়ো, যোগাযোগ ও পাসওয়ার্ড পরিবর্তনের পূর্ণ সুবিধা।
* **৩০-মিনিট ডুপ্লিকেট প্রতিরোধক ভিউ কাউন্টার:** প্রতিটি ইউনিক ভিউ হ্যাসড আইপি (`post_views`) দিয়ে সংরক্ষিত; একই ডিভাইস থেকে বারবার পেজ রিফ্রেশ করলেও কৃত্রিম ভিউ কাউন্ট হবে না।
* **স্মার্ট লগইন রিডাইরেক্ট:** কোনো লেখক ভুলবশত পাবলিক `/login` পেজে লগইন করার চেষ্টা করলে সিস্টেম তাকে নিরাপদ নোটিশ দিয়ে আলাদা লেখক লগইন পেজে রিডাইরেক্ট করে।

---

## 🔗 সর্বাঙ্গীন পেজ ও সরাসরি লিঙ্ক ডিরেক্টরি (Complete URL Directory)

### ১. পাবলিক পাঠক ও সাধারণ পোর্টাল লিঙ্ক

| পেজ বা মডিউল (Module / Page) | অ্যাক্সেস লেভেল | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **হোম পোর্টাল (Home Portal)** | সর্বসাধারণ | [`Live Home`](https://newslensbd.com/) | [`Local Home`](http://localhost/NewsLens/public/) |
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

### ২. পাঠক অ্যাকাউন্ট ও অথেনটিকেশন লিঙ্ক

| পেজ বা ফিচার (Feature) | অ্যাক্সেস লেভেল | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **পাঠক লগইন (Reader Login)** | সাধারণ পাঠক | [`Live Reader Login`](https://newslensbd.com/login) | [`Local Reader Login`](http://localhost/NewsLens/public/login) |
| **পাঠক সেলফ-রেজিস্টার (Register)** | নতুন পাঠক | [`Live Reader Register`](https://newslensbd.com/register) | [`Local Reader Register`](http://localhost/NewsLens/public/register) |
| **ওটিপি ভেরিফিকেশন (OTP Verify)** | নতুন পাঠক | [`Live OTP Verify`](https://newslensbd.com/otp/verify) | [`Local OTP Verify`](http://localhost/NewsLens/public/otp/verify) |
| **পাসওয়ার্ড পুনরুদ্ধার (Forgot Password)**| যেকেউ | [`Live Forgot Password`](https://newslensbd.com/password/forgot) | [`Local Forgot Password`](http://localhost/NewsLens/public/password/forgot) |
| **সংরক্ষিত খবর (Saved Bookmarks)** | লগইন পাঠক | [`Live Bookmarks`](https://newslensbd.com/user/bookmarks) | [`Local Bookmarks`](http://localhost/NewsLens/public/user/bookmarks) |
| **পাঠক প্রোফাইল ও সেটিংস** | লগইন পাঠক | [`Live User Profile`](https://newslensbd.com/profile) | [`Local User Profile`](http://localhost/NewsLens/public/profile) |
| **পাঠক লগআউট (Logout)** | লগইন পাঠক | [`Live User Logout`](https://newslensbd.com/logout) | [`Local User Logout`](http://localhost/NewsLens/public/logout) |

### ৩. স্বতন্ত্র লেখক প্যানেল লিঙ্ক

| পেজ বা ফিচার (Feature) | অ্যাক্সেস লেভেল | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
| :--- | :--- | :--- | :--- |
| **লেখক লগইন পোর্টাল (Writer Login)** | নিবন্ধিত লেখক | [`Live Writer Login`](https://newslensbd.com/writer/login) | [`Local Writer Login`](http://localhost/NewsLens/public/writer/login) |
| **লেখক ড্যাশবোর্ড (Writer Dashboard)** | অ্যাক্টিভ লেখক | [`Live Writer Dashboard`](https://newslensbd.com/writer/dashboard) | [`Local Writer Dashboard`](http://localhost/NewsLens/public/writer/dashboard) |
| **নতুন সংবাদ রচনা (Create Post)** | অ্যাক্টিভ লেখক | [`Live Create News`](https://newslensbd.com/writer/posts/create) | [`Local Create News`](http://localhost/NewsLens/public/writer/posts/create) |
| **আমার সংবাদসমূহ (Writer's Own Posts)**| অ্যাক্টিভ লেখক | [`Live My Posts`](https://newslensbd.com/writer/posts) | [`Local My Posts`](http://localhost/NewsLens/public/writer/posts) |
| **পাবলিক রেজিস্ট্রেশন বন্ধ নোটিশ** | আবেদনকারী | [`Live Writer Notice`](https://newslensbd.com/writer/register) | [`Local Writer Notice`](http://localhost/NewsLens/public/writer/register) |
| **লেখক নিরাপদ লগআউট (Writer Logout)** | অ্যাক্টিভ লেখক | [`Live Writer Logout`](https://newslensbd.com/writer/logout) | [`Local Writer Logout`](http://localhost/NewsLens/public/writer/logout) |

### ৪. অ্যাডমিন কমান্ড ডেস্ক লিঙ্ক (`/lens-desk`)

| পেজ বা ম্যানেজমেন্ট ফিচার (Admin Module) | অ্যাক্সেস লেভেল | লাইভ ওয়েবসাইট লিঙ্ক (Live URL) | লোকাল সার্ভার লিঙ্ক (Local URL) |
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

## 🛠 প্রযুক্তি কাঠামো (Tech Stack)

| স্তর (Layer) | প্রযুক্তি বিবরণ (Details) |
| :--- | :--- |
| **Frontend** | Semantic HTML5, Vanilla CSS3 (Custom Design System, Zero Heavy Frameworks), Vanilla ES6+ JS |
| **Backend** | PHP 8.2+ (Lightweight MVC Architecture, PSR-4 Autoloading, PDO Prepared Statements) |
| **Database** | MySQL 8.0+ / MariaDB (`utf8mb4_unicode_ci` বাংলা ফন্টের নিখুঁত ও ক্ষিপ্র রেন্ডারিংয়ের জন্য) |
| **Security** | CSRF Token Protection, XSS Sanitization, Password Hashing (`bcrypt`), RBAC Server-side Middleware, IP Rate Limiting (`RateLimiter.php`) |
| **Web Server** | LiteSpeed / Apache / Nginx (mod_rewrite সক্রিয়, HTTPS বাধ্যতামূলক) |
| **Background Cron** | PHP CLI (`cron/send_notifications.php`) |

---

## 🌐 সার্ভার ও ডিপ্লয়মেন্ট আর্কিটেকচার (Deployment & Hosting)

Newslensbd পোর্টালটি লাইভ cPanel হোস্টিং সার্ভারে সংযুক্ত ও কনফিগার করা হয়েছে:

* **কন্ট্রোল প্যানেল URL:** [https://s16us-app.whiteservers.net:2083/](https://s16us-app.whiteservers.net:2083/) অথবা [https://newslensbd.com:2083/](https://newslensbd.com:2083/)
* **সার্ভার আইপি:** `104.243.34.108`
* **cPanel ইউজারনেম:** `lcxdiarj`
* **ডেটাবেজ:** `lcxdiarj_newslens`
* **ওয়েব রুট:** `public_html/` (যেখানে সমস্ত রিকোয়েস্ট নিরাপদে `public/index.php`-তে রিডাইরেক্ট হয়)

---

## 🔒 নিরাপত্তা ও পারফরম্যান্স অপ্টিমাইজেশন (Security & Optimizations)

১. **প্রতিষ্ঠান পেজসমূহের স্থায়ী সমাধান (`/page/{slug}`):**
   - লাইভ ডেটাবেজে `pages` টেবিল তৈরি ও ৬টি প্রাথমিক পেজ (আমাদের সম্পর্কে, যোগাযোগ, বিজ্ঞাপন, গোপনীয়তা নীতি, ব্যবহারের শর্তাবলী, সংশোধনী নীতি) ডাটাবেজে কনফিগার করা হয়েছে। ফুটার ও হেডারের সকল লিঙ্ক এখন 404 মুক্ত।

২. **লেখক ব্যবস্থাপনা সম্পূর্ণ পৃথকীকরণ ও নিরাপত্তা জোরদার:**
   - **পাবলিক রেজিস্ট্রেশন সুরক্ষা:** পাবলিক ফর্ম ও এপিআই এন্ডপয়েন্ট থেকে লেখকের রোল নির্বাচনের অপশন স্থায়ীভাবে সরিয়ে দেওয়া হয়েছে। যে কেউ রেজিস্ট্রেশন করলে বাধ্যতামূলকভাবে `role = 'user'` অ্যাসাইন হবে।
   - **স্বতন্ত্র লগইন রুট (`/writer/login`):** সাধারণ পাঠকদের জন্য `/login` এবং লেখকদের জন্য স্বতন্ত্র `/writer/login` স্থাপন। লেখক ভুল করে পাবলিক সাইটে লগইন করলে সিস্টেম তাকে নিরাপদে লগআউট করিয়ে লেখক পোর্টালে পাঠিয়ে দেয়।
   - **ব্রুট-ফোর্স প্রটেকশন ও আইপি রেট-লিমিটিং (`RateLimiter.php`):** ৫টি ব্যর্থ লগইন চেষ্টার পর সংশ্লিষ্ট আইপি ১৫ মিনিটের জন্য সাময়িক ব্লক।
   - **ইউজার অস্তিত্ব সুরক্ষা (Generic Login Error):** লেখক না এমন কোনো সাধারণ পাঠকের ইমেইল/পাসওয়ার্ড দেওয়া হলে "ইউজার পাওয়া যায়নি" না বলে জেনেরিক মেসেজ দিয়ে সিস্টেমে ইউজারের রোল বা অস্তিত্ব ফাঁস হওয়া রোধ করা হয়েছে।
   - **কঠোর সার্ভার-সাইড পারমিশন (HTTP 403 Forbidden):** সাধারণ পাঠক সেশন নিয়ে জোরপূর্বক লেখক রুটে প্রবেশের চেষ্টা করলে মিডলওয়্যারে সরাসরি HTTP 403 এরর প্রদান।
   - **কেন্দ্রীয় অ্যাডমিন নিয়ন্ত্রণ (`/lens-desk/writers`):** অ্যাডমিন কর্তৃক যেকোনো সময় নতুন লেখক তৈরি, পাসওয়ার্ড রিসেট, পাঠককে লেখক পদে প্রমোশন, লেখককে পাঠকে ডিমোশন, একাউন্ট সাসপেন্ড ও পার্মানেন্ট ডিলিট সুবিধা।
   - **লেখক পোস্ট আইসোলেশন:** লেখক ড্যাশবোর্ড ও পোস্ট তালিকায় `author_id` কঠোরভাবে ফিল্টার করা হয়েছে যাতে কোনো লেখক অন্যের পোস্টের সন্ধান না পায়।
   - **দ্বিভাষিক (বাংলা/ইংরেজি) ও ডার্ক/লাইট মোড:** লেখক লগইন, ড্যাশবোর্ড এবং পোস্ট ম্যানেজমেন্টের প্রতিটি উপাদানে দ্বিভাষিক ও আধুনিক ডার্ক থিম সংযুক্ত।

---

<div align="center">

© 2026 **Newslensbd** | ডেভেলপমেন্ট ও টেকনিক্যাল পার্টনার: **[Stratifyx Global](https://stratifyxglobal.com)**

</div>
