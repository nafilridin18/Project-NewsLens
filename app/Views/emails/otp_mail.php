<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Newslensbd ওটিপি ভেরিফিকেশন</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 30px 10px;">
    <tr>
      <td align="center">
        <table width="100%" max-width="560" style="max-width: 560px; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
          <!-- Header -->
          <tr>
            <td style="background-color: #0b1f44; padding: 24px; text-align: center;">
              <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                NEWS<span style="color: #0E7A3A;">LENS</span><span style="background: #E31E24; color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 4px; margin-left: 4px; vertical-align: middle;">BD</span>
              </h1>
              <p style="margin: 4px 0 0; color: #94a3b8; font-size: 12px;">সাধারণের বাইরে, সত্যের খোঁজে</p>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding: 32px 28px;">
              <h2 style="margin: 0 0 12px; color: #0f172a; font-size: 18px;">আসসালামু আলাইকুম, <?= htmlspecialchars($name ?? 'পাঠক') ?>!</h2>
              <p style="margin: 0 0 20px; color: #475569; font-size: 14px; line-height: 1.6;">
                আপনার <strong>Newslensbd</strong> অ্যাকাউন্টের <?= htmlspecialchars($purposeLabel ?? 'যাচাইকরণ') ?> সম্পন্ন করতে নিচের ৬-সংখ্যার ওটিপি (OTP) কোডটি ব্যবহার করুন।
              </p>
              
              <!-- OTP Box -->
              <div style="background: #f8fafc; border: 2px dashed #0E7A3A; border-radius: 8px; padding: 18px; text-align: center; margin: 24px 0;">
                <span style="display: block; font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #0b1f44;"><?= htmlspecialchars($code) ?></span>
                <span style="display: block; margin-top: 6px; font-size: 12px; color: #64748b;">এই কোডটি আগামী <strong><?= htmlspecialchars($expiry ?? '১০ মিনিট') ?></strong> কার্যকর থাকবে।</span>
              </div>

              <p style="margin: 0 0 16px; color: #64748b; font-size: 13px; line-height: 1.5;">
                ⚠️ আপনি যদি এই অনুরোধটি না করে থাকেন, তবে নির্দ্বিধায় এই ইমেইলটি উপেক্ষা করুন। আপনার পাসওয়ার্ড বা ওটিপি কখনোই কারো সাথে শেয়ার করবেন না।
              </p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 24px; text-align: center; font-size: 12px; color: #94a3b8;">
              © <?= date('Y') ?> Newslensbd (নিউজলেন্সবিডি) | টেকনোলজি পার্টনার: Stratifyx Global
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
