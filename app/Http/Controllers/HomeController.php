<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Message;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;
use App\Mail\ContactAutoReplyMail;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('active', true)->orderBy('sort_order')->take(9)->get();
        $projects = Project::where('active', true)->orderBy('sort_order')->take(9)->get();
        $totalprojects = Project::count();
        $testimonials = Testimonial::where('active', true)->orderBy('sort_order')->paginate(3);
        $skills = Skill::where('active', true)->orderBy('sort_order')->get();

        $latestPosts = BlogPost::where('active', true)->orderBy('published_at', 'desc')->take(6)->get();
        $settings = Setting::all()->pluck('value', 'key');
        $meta_title = $settings['site_name'] ?? config('app.name', 'الفـن الـحـديـث');
        $meta_description = $settings['site_description'] ?? null;
        $meta_keywords = collect([
    $settings['site_name'] ?? null,
    'من نحن',
    'الفن الحديث ',
    'معلم دهانات جدة',
    'دهانات جدة',
    'ديكور جدة',
    'معلم بويه جدة',
    'دهانات داخلية جدة',
    'دهانات خارجية جدة',
    'سعر دهانات جدة',
    'أفضل معلم دهانات جدة',
    'ديكورات ودهانات جدة',
    'خدمات دهانات وديكور جدة',
    'ورق جدران جدة',
    'ترميمات جدة',
    'تشطيبات ودهانات جدة',

    // كلمات طويلة متعددة نوايا بحث (Long-tail)
    'سعر المتر دهان داخلي جدة',
    'أفضل دهانات داخلية للفلل جدة',
    'أعمال دهانات داخلية وجدران جدة',
    'خدمات ديكور داخلية وخارجية جدة',
    'دهانات مقاومة للرطوبة جدة',
    'ديكور بديل خشب جدة',
    'ديكور بديل رخام جدة',
    'بديل شيبورد جدة',
    'بديل حجر جدة',
    'تركيب ورق جدران ودهانات جدة',
    'دهانات فوم وبانوهات فوم جدة',

    // كلمات عامة غير محلية (خدمات + تصاميم)
    'معلم دهانات',
    'دهانات وديكورات',
    'دهانات داخلية',
    'دهانات خارجية',
    'ديكورات فوم',
    'ديكور بديل خشب',
    'ديكور بديل رخام',
    'ترميمات ودهانات',
    'تشطيبات عامة',
    'ورق جدران وديكور',
    'ألوان بويات داخلية',
    'أحدث ألوان دهانات',
    'خدمات دهانات للمنازل',
    'خدمات ديكور للمنازل',
    ])->filter()->implode(', ');
        return view('home', compact('services', 'projects', 'testimonials', 'skills', 'latestPosts', 'meta_title', 'meta_description', 'meta_keywords', 'totalprojects'));
    }

    public function about()
    {
        $skills = Skill::where('active', true)->orderBy('sort_order')->get();
        $totalprojects = Project::count();
        $settings = Setting::all()->pluck('value', 'key');
        $meta_title = 'من نحن';
        $meta_description = $settings['site_description'] ?? null;
        $meta_keywords = collect([
    $settings['site_name'] ?? null,
    'من نحن',
    'الفن الحديث ',
    'معلم دهانات جدة',
    'دهانات جدة',
    'ديكور جدة',
    'معلم بويه جدة',
    'دهانات داخلية جدة',
    'دهانات خارجية جدة',
    'سعر دهانات جدة',
    'أفضل معلم دهانات جدة',
    'ديكورات ودهانات جدة',
    'خدمات دهانات وديكور جدة',
    'ورق جدران جدة',
    'ترميمات جدة',
    'تشطيبات ودهانات جدة',

    // كلمات طويلة متعددة نوايا بحث (Long-tail)
    'سعر المتر دهان داخلي جدة',
    'أفضل دهانات داخلية للفلل جدة',
    'أعمال دهانات داخلية وجدران جدة',
    'خدمات ديكور داخلية وخارجية جدة',
    'دهانات مقاومة للرطوبة جدة',
    'ديكور بديل خشب جدة',
    'ديكور بديل رخام جدة',
    'بديل شيبورد جدة',
    'بديل حجر جدة',
    'تركيب ورق جدران ودهانات جدة',
    'دهانات فوم وبانوهات فوم جدة',

    // كلمات عامة غير محلية (خدمات + تصاميم)
    'معلم دهانات',
    'دهانات وديكورات',
    'دهانات داخلية',
    'دهانات خارجية',
    'ديكورات فوم',
    'ديكور بديل خشب',
    'ديكور بديل رخام',
    'ترميمات ودهانات',
    'تشطيبات عامة',
    'ورق جدران وديكور',
    'ألوان بويات داخلية',
    'أحدث ألوان دهانات',
    'خدمات دهانات للمنازل',
    'خدمات ديكور للمنازل',
    ])->filter()->implode(', ');

        return view('about', compact('skills', 'settings', 'meta_title', 'meta_description', 'meta_keywords', 'totalprojects'));
    }

    public function contact()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $meta_title = 'تواصل معنا';
        $meta_description = $settings['site_description'] ?? null;
        $meta_keywords = collect([
            $settings['site_name'] ?? null,
            'تواصل معنا',
            'تواصل',
            'الاتصال',
            'الفن الحديث ',
            'معلم دهان',
            'ديكورات',
            'دهانات',
            'معلم دهانات',
            'دهانات وديكورات'
        ])->filter()->implode(', ');

        return view('contact', compact('settings', 'meta_title', 'meta_description', 'meta_keywords'));
    }

    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:500'],
        ]);
        $adminEmail = Setting::where('key', 'email')->value('value') ?? config('mail.from.address');
        try {

            $message = Message::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'is_read' => false,
            ]);
            // البيانات المشتركة للإيميلات
            $data = [
                'name'    => $message->name,
                'email'   => $message->email,
                'phone'   => $message->phone,
                'subject' => $message->subject,
                'message' => $message->message,
                'id'      => $message->id,
            ];

            //  إرسال إشعار للإدارة (Queue)
            Mail::to($adminEmail)
                ->queue(new ContactAdminMail($data));

            //  رد تلقائي للعميل (Queue)
            Mail::to($message->email)
                ->queue(new ContactAutoReplyMail($data));

            return response()->json([
                'status' => 'ok'
            ]);
        } catch (\Throwable $e) {

            // تسجيل الخطأ
            Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إرسال الرسالة'
            ], 500);
        }
    }
}


/*
    $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:500'],
        ]);
        $adminEmail = Setting::where('key', 'email')->value('value') ?? config('mail.from.address');
        try {

            $message = Message::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'is_read' => false,
            ]);
            // البيانات المشتركة للإيميلات
            $mailData = [
                'name'    => $message->name,
                'email'   => $message->email,
                'phone'   => $message->phone,
                'subject' => $message->subject,
                'message' => $message->message,
                'id'      => $message->id,
            ];

            $body = "اسم: {$validated['name']}\nبريد: {$validated['email']}\nهاتف: " . ($validated['phone'] ?? '') . "\nموضوع: {$validated['subject']}\n\nرسالة:\n{$validated['message']}";
            Mail::raw($body, function ($m) use ($adminEmail, $validated) {
                $m->to($adminEmail)->subject('رسالة تواصل جديدة: ' . $validated['subject']);
            });

            return response()->json(['status' => 'ok']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error'], 500);
        }

*/
