<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رد على رسالتك</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f4f7;">
    <tr>
        <td align="center" style="padding:20px 0;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.05);">
                <tr>
                    <td align="center" style="background-color:#3182ce;padding:30px 20px;">
                        <h1 style="color:#ffffff;margin:0;font-size:24px;">رد على رسالتك</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 25px;text-align:right;direction:rtl;">
                        <p style="font-size:16px;color:#2d3748;margin-top:0;">
                            أهلاً بك
                            <strong>{{ $data['name'] }}</strong>
                        </p>
                        <p style="color:#4a5568;line-height:1.8;font-size:15px;">
                            هذا رد على رسالتك بخصوص:
                            <span style="background-color:#ebf8ff;color:#2c5282;padding:2px 6px;border-radius:4px;">
                                {{ $data['subject'] }}
                            </span>
                        </p>
                        <div style="margin-top:25px;margin-bottom:20px;">
                            <p style="margin:0 0 8px 0;font-weight:bold;color:#2d3748;">نص رسالتك الأصلية:</p>
                            <div style="background-color:#edf2f7;padding:15px;border-radius:6px;border-right:4px solid #3182ce;color:#4a5568;line-height:1.8;white-space:pre-line;">
                                {{ $data['original_message'] }}
                            </div>
                        </div>
                        <div style="margin-top:25px;">
                            <p style="margin:0 0 8px 0;font-weight:bold;color:#2d3748;">ردنا على رسالتك:</p>
                            <div style="background-color:#fffaf0;padding:15px;border-radius:6px;border-right:4px solid #f6ad55;color:#744210;line-height:1.8;white-space:pre-line;">
                                {{ $data['reply_content'] }}
                            </div>
                        </div>
                        <p style="margin-top:30px;color:#2d3748;font-weight:bold;">
                            مع خالص التحية،<br>
                            <span style="color:#3182ce;">فريق الفـن الـحـديـث</span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding-bottom:25px;">
                        <a href="{{ url('/') }}" style="background-color:#3182ce;color:#ffffff;padding:10px 22px;text-decoration:none;border-radius:5px;font-weight:bold;display:inline-block;">
                            زيارة الموقع الإلكتروني
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="background-color:#edf2f7;padding:18px;color:#718096;font-size:12px;">
                        <p style="margin:0;">&copy; {{ date('Y') }} الفـن الـحـديـث. جميع الحقوق محفوظة.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

