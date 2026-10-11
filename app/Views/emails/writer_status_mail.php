<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>লেখক অ্যাকাউন্ট স্ট্যাটাস</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 30px 10px;">
    <tr>
      <td align="center">
        <table width="100%" max-width="560" style="max-width: 560px; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
          <tr>
            <td style="background-color: #0b1f44; padding: 24px; text-align: center;">
              <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800;">
                NEWS<span style="color: #0E7A3A;">LENS</span><span style="background: #E31E24; color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">BD</span>
              </h1>
            </td>
          </tr>
          <tr>
            <td style="padding: 32px 28px;">
              <h2 style="margin: 0 0 12px; color: #0f172a; font-size: 18px;">প্রিয় <?= htmlspecialchars($name) ?>,</h2>
              
              <?php if ($status === 'approved'): ?>
                <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 14px; border-radius: 4px; margin-bottom: 20px;">
                  <strong style="color: #065f46; font-size: 15px;">🎉 আপনার লেখক আবেদন অনুমোদিত হয়েছে!</strong>
                  <p style="margin: 6px 0 0; color: #047857; font-size: 13px;">
                    আপনার পারমিশন লেভেল: <strong><?= ($permission === 'direct_publish') ? 'সরাসরি প্রকাশ (Direct Publish)' : 'সম্পাদকীয় পর্যালোচনা (Editor Review)' ?></strong>
                  </p>
                </div>
                <p style="color: #475569; font-size: 14px; line-height: 1.6;">
                  আপনি এখন আপনার অ্যাকাউন্টে লগইন করে নিউজ ও কলাম লিখতে পারবেন।
                </p>
                <div style="text-align: center; margin: 24px 0;">
                  <a href="https://newslensbd.com/lens-desk/login" style="background: #0E7A3A; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; display: inline-block;">রাইটার প্যানেলে প্রবেশ করুন ➔</a>
                </div>
              <?php elseif ($status === 'rejected'): ?>
                <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 14px; border-radius: 4px; margin-bottom: 20px;">
                  <strong style="color: #991b1b; font-size: 15px;">আপনার লেখক আবেদনটি গ্রহণ করা সম্ভব হয়নি।</strong>
                  <?php if (!empty($reason)): ?>
                    <p style="margin: 6px 0 0; color: #b91c1c; font-size: 13px;">কারণ: <?= htmlspecialchars($reason) ?></p>
                  <?php endif; ?>
                </div>
              <?php elseif ($status === 'suspended'): ?>
                <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 14px; border-radius: 4px; margin-bottom: 20px;">
                  <strong style="color: #92400e; font-size: 15px;">আপনার লেখক অ্যাকাউন্টটি সাময়িকভাবে স্থগিত করা হয়েছে।</strong>
                  <?php if (!empty($reason)): ?>
                    <p style="margin: 6px 0 0; color: #b45309; font-size: 13px;">কারণ: <?= htmlspecialchars($reason) ?></p>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <p style="margin: 16px 0 0; color: #64748b; font-size: 13px;">যেকোনো তথ্যের জন্য আমাদের সম্পাদকীয় টিমের সাথে যোগাযোগ করতে পারেন।</p>
            </td>
          </tr>
          <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 24px; text-align: center; font-size: 12px; color: #94a3b8;">
              © <?= date('Y') ?> Newslensbd (নিউজলেন্সবিডি)
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
