<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة تواصل جديدة</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background-color: #f4f4f7;">
        <tr>
            <td align="center" style="padding: 20px 0;">

                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

                    <tr>
                        <td align="center" style="background-color: #2d3748; padding: 30px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">الفـن الـحـديـث
                            </h1>
                            <p style="color: #cbd5e0; margin: 5px 0 0 0; font-size: 14px;">نظام إشعارات الموقع</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px; text-align: right; direction: rtl;">

                            <h2
                                style="color: #2d3748; font-size: 20px; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                                📩 رسالة جديدة من نموذج التواصل
                            </h2>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 8px 0; color: #718096; font-weight: bold; width: 80px;">الاسم:
                                    </td>
                                    <td style="padding: 8px 0; color: #2d3748;">{{ $data['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; color: #718096; font-weight: bold;">البريد:</td>
                                    <td style="padding: 8px 0; color: #2d3748;">
                                        <a href="mailto:{{ $data['email'] }}"
                                            style="color: #3182ce; text-decoration: none;">{{ $data['email'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; color: #718096; font-weight: bold;">الهاتف:</td>
                                    <td style="padding: 8px 0; color: #2d3748;">{{ $data['phone'] ?? 'غير متوفر' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; color: #718096; font-weight: bold;">الموضوع:</td>
                                    <td style="padding: 8px 0; color: #2d3748;">{{ $data['subject'] }}</td>
                                </tr>
                            </table>

                            <div
                                style="background-color: #edf2f7; padding: 20px; border-radius: 6px; border-right: 4px solid #3182ce;">
                                <p style="margin: 0 0 10px 0; font-weight: bold; color: #2d3748;">نص الرسالة:</p>
                                <p style="margin: 0; color: #4a5568; line-height: 1.6; white-space: pre-line;">
                                    {{ $data['message'] }}
                                </p>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td align="center"
                            style="background-color: #f7fafc; padding: 20px; border-top: 1px solid #e2e8f0; color: #718096; font-size: 12px;">
                            <p style="margin: 0;">هذا بريد تلقائي تم إرساله من موقع الفـن الـحـديـث.</p>
                            <p style="margin: 5px 0 0 0;">&copy; {{ date('Y') }} جميع الحقوق محفوظة.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
