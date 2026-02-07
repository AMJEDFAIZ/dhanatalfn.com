<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شكرًا لتواصلك معنا</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background-color: #f4f4f7;">
        <tr>
            <td align="center" style="padding: 20px 0;">

                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">

                    <tr>
                        <td align="center" style="background-color: #3182ce; padding: 40px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">شكرًا لتواصلك معنا </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px; text-align: right; direction: rtl;">

                            <p style="font-size: 18px; color: #2d3748; margin-top: 0;">أهلاً بك،
                                <strong>{{ $data['name'] }}</strong>
                            </p>

                            <p style="color: #4a5568; line-height: 1.8; font-size: 16px;">
                                لقد استلمنا رسالتك بنجاح بخصوص: <span
                                    style="background-color: #ebf8ff; color: #2c5282; padding: 2px 6px; border-radius: 4px;">{{ $data['subject'] }}</span>.
                                <br><br>
                                يقدر فريق <strong>الفـن الـحـديـث</strong> اهتمامك، وسيقوم أحد ممثلينا بمراجعة طلبك
                                والرد
                                عليك في أقرب وقت ممكن.
                            </p>

                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="background-color: #fffaf0; border: 1px solid #fbd38d; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="margin: 0 0 10px 0; color: #c05621; font-weight: bold;">⏱ أوقات العمل
                                            الرسمية:</p>
                                        <p style="margin: 0; color: #744210; font-size: 14px; line-height: 1.6;">
                                            الأحد – الخميس<br>
                                            8:00 صباحًا – 5:00 مساءً
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top: 30px; color: #2d3748; font-weight: bold;">
                                مع خالص التحية،<br>
                                <span style="color: #3182ce;">فريق الفـن الـحـديـث</span>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-bottom: 30px;">
                            <a href="{{ url('/') }}"
                                style="background-color: #3182ce; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">زيارة
                                الموقع الإلكتروني</a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center"
                            style="background-color: #edf2f7; padding: 20px; color: #718096; font-size: 12px;">
                            <p style="margin: 0;">&copy; {{ date('Y') }} الفـن الـحـديـث. جميع الحقوق محفوظة.</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
