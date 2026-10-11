<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>ব্রেকিং নিউজ - Newslensbd</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 30px 10px;">
    <tr>
      <td align="center">
        <table width="100%" max-width="560" style="max-width: 560px; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
          <tr>
            <td style="background-color: #E31E24; padding: 18px; text-align: center;">
              <span style="color: #ffffff; font-weight: 800; font-size: 18px; letter-spacing: 1px;">🚨 ব্রেকিং নিউজ | BREAKING NEWS</span>
            </td>
          </tr>
          <tr>
            <td style="padding: 28px 24px;">
              <h2 style="margin: 0 0 14px; color: #0b1f44; font-size: 20px; line-height: 1.4; font-weight: 800;">
                <?= htmlspecialchars($title) ?>
              </h2>
              <p style="margin: 0 0 24px; color: #475569; font-size: 15px; line-height: 1.6;">
                <?= nl2br(htmlspecialchars($excerpt)) ?>
              </p>
              <div style="text-align: center; margin: 20px 0;">
                <a href="<?= htmlspecialchars($url) ?>" style="background: #0b1f44; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; display: inline-block;">সম্পূর্ণ সংবাদ পড়ুন ➔</a>
              </div>
            </td>
          </tr>
          <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px; text-align: center; font-size: 11px; color: #94a3b8;">
              আপনি Newslensbd ব্রেকিং নিউজ নোটিফিকেশন চালু করায় এই ইমেইলটি পেয়েছেন।
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
