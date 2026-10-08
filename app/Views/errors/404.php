<?php
$appUrl = rtrim($config['app']['url'] ?? '', '/');
?>
<div class="container error-404-container" style="text-align: center; padding: 90px 20px; min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
  <div style="font-size: 88px; font-weight: 900; color: #E31E24; line-height: 1; letter-spacing: -2px; margin-bottom: 10px;" data-bn="৪০৪" data-en="404">৪০৪</div>
  <div style="display: inline-block; padding: 4px 14px; background: rgba(227, 30, 36, 0.1); color: #E31E24; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px;" data-bn="পাতাটি পাওয়া যায়নি" data-en="Page Not Found">
    পাতাটি পাওয়া যায়নি
  </div>
  <h1 style="font-size: 28px; margin: 0 0 14px; color: #0b1f44; font-weight: 700;" data-bn="দুঃখিত! আপনি যে পাতাটি খুঁজছেন তা বিদ্যমান নেই" data-en="Sorry! The page you are looking for does not exist">দুঃখিত! আপনি যে পাতাটি খুঁজছেন তা বিদ্যমান নেই</h1>
  <p style="font-size: 16px; color: #64748b; max-width: 520px; margin: 0 auto 32px; line-height: 1.6;" data-bn="সংবাদটি হয়তো সরানো হয়েছে, মুছে ফেলা হয়েছে অথবা আপনি ভুল লিংক প্রবেশ করিয়েছেন। সঠিক তথ্য পেতে আমাদের প্রচ্ছদ পাতা বা অনুসন্ধান বক্স ব্যবহার করুন।" data-en="The news may have been moved, deleted, or you entered an incorrect URL. Please visit our homepage or use the search bar.">
    সংবাদটি হয়তো সরানো হয়েছে, মুছে ফেলা হয়েছে অথবা আপনি ভুল লিংক প্রবেশ করিয়েছেন। সঠিক তথ্য পেতে আমাদের প্রচ্ছদ পাতা বা অনুসন্ধান বক্স ব্যবহার করুন।
  </p>
  <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
    <a href="<?= $appUrl ?>/" style="display: inline-flex; align-items: center; gap: 8px; background: #0b1f44; color: #ffffff; padding: 12px 26px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(11,31,68,0.15); transition: all 0.2s ease;" data-bn="🏠 প্রচ্ছদে ফিরে যান" data-en="🏠 Back to Homepage">
      🏠 প্রচ্ছদে ফিরে যান
    </a>
    <a href="<?= $appUrl ?>/search" style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; color: #0b1f44; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid #cbd5e1; transition: all 0.2s ease;" data-bn="🔍 সংবাদ খুঁজুন" data-en="🔍 Search News">
      🔍 সংবাদ খুঁজুন
    </a>
  </div>
</div>
