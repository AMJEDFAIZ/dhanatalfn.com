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
use Illuminate\Support\Str;

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
        // $meta_title = 'أفضل معلم دهانات وديكورات جدة';
        $meta_title = $settings['site_name'] ?? config('app.name', 'أفضل معلم دهانات وديكورات جدة');
        // $meta_description = $settings['site_description'] ?? null;
        $meta_description = Str::limit(strip_tags($settings['site_description'] ?? null), 160) ?? 'أفضل معلم دهانات وديكورات في جدة (حي الروضة). خدمات دهانات داخلية وخارجية، ديكور جبس، بديل خشب ورخام، ورق جدران، تشطيبات شاملة. جودة عالية وسعر منافس.';

        $meta_keywords = collect([
            $settings['site_name'] ?? null,
            'أفضل معلم دهانات جدة',
            'أفضل معلم دهانات بجدة',
            'معلم دهانات جدة',
            'معلم دهانات جده',
            'معلم دهان جده',
            'معلم دهان جدة',
            'معلم دهان',
            'معلم بويه جدة حي الروضة',
            'معلم بوية',
            'معلم بويات جدة',
            'معلم بويه جدة الروضة',
            'معلم بويا جدة',
            'معلم بوية جدة',
            'معلم دهان في جدة',
            'معلم فوم جدة',
            'معلم ديكور جدة',
            'بديل الرخام',
            'بديل الشيبورد',
            'بديل خشب داخلي',
            'الواح شيبورد',
            'بديل الخشب',
            'شيبورد ديكور',
            'ورق جدران',
            'دهان بويه',
            'معلم بويه',
            'بويه جوتن',
            'بويه جدران',
            'دهانات وديكورات جدة',
            'ديكورات رمضان',
            'بديل خشب خارجي',
            'شيبورد خشب',
            'بديل شيبورد',
            'شيبورد',
            'بديل رخام',
        ])->filter()->unique()->implode(', ');
        return view('home', compact('services', 'projects', 'testimonials', 'skills', 'latestPosts', 'meta_title', 'meta_description', 'meta_keywords', 'totalprojects'));
    }

    public function about()
    {
        $skills = Skill::where('active', true)->orderBy('sort_order')->get();
        $totalprojects = Project::count();
        $settings = Setting::all()->pluck('value', 'key');
        $meta_title = $settings['about_meta_title'] ?? config('app.name', 'من نحن');
        $meta_description = Setting::where('key', 'about_meta_description')->value('value') ?? 'تصفح أحدث مشاريعنا المنفذة بجودة واحترافية.';

        // $meta_description = Str::limit(strip_tags($settings['site_description'] ?? null), 160) ?? 'أفضل معلم دهانات وديكورات في جدة (حي الروضة). خدمات دهانات داخلية وخارجية، ديكور جبس، بديل خشب ورخام، ورق جدران، تشطيبات شاملة. جودة عالية وسعر منافس.';
        $meta_keywords = collect([
            $settings['site_name'] ?? null,

            'بديل الرخام',
            'بديل الشيبورد',
            'بديل خشب داخلي',
            'الواح شيبورد',
            'بديل الخشب',
            'شيبورد ديكور',
            'ورق جدران',
            'دهان بويه',
            'معلم بويه',
            'بويه جوتن',
            'بويه جدران',
            'معلم دهانات وديكورات جدة',
            'دهانات داخلية جدة',
            'دهانات خارجية جدة',
            'سعر دهانات جدة',
            'أفضل معلم دهانات جدة',
            'ديكورات ودهانات جدة',
            'دهان جده',

            'مقاول دهانات',
            'مقاول بجدة',
            'دهان واجهات',
            'مقاول دهانات بجدة',
            'مقاول جبس بورد',
            'مقاول اصباغ',
            'مقاول بناء جدة',
            'مقاول ملاحق جده',
            'مقاول بناء في جدة',
            'افضل مقاول في جدة',
            'معلم بناء جدة',
            'مطلوب مقاول جبس بورد',
            'مطلوب مقاول دهانات',
            'افضل مقاول بناء في جدة',
            'مقاول تشطيب في جدة',
            'مقاولين دهانات',
            'افضل مقاول بجدة',
            'هدم عماير جده',
            'مقاول في جدة ممتاز',
            'مطلوب مقاولين دهانات',
            'مقاول عام جدة',
            'معلم دهانات جدة',
            'معلم دهانات جده',
            'معلم دهان جده',
            'معلم دهان جدة',
            'معلم دهان',
            'معلم بويه',
            'معلم بوية',
            'معلم بويات جدة',
            'معلم بويا جدة',
            'معلم بوية جدة',
            'ديكور بديل الرخام',
            'ديكور بديل الخشب',
            'ديكور مرايا',
            'معلم ديكور',
            'فني ديكور',
            'فني ديكورات جدة',
        ])->filter()->unique()->implode(', ');


        return view('about', compact('skills', 'settings', 'meta_title', 'meta_description', 'meta_keywords', 'totalprojects'));
    }

    public function contact()
    {
        $settings = Setting::all()->pluck('value', 'key');
        // $meta_title = 'تواصل معنا';
         // $meta_description = $settings['site_description'] ?? null;
        // $meta_description = Str::limit(strip_tags($settings['site_description'] ?? null), 160) ?? 'أفضل معلم دهانات وديكورات في جدة (حي الروضة). خدمات دهانات داخلية وخارجية، ديكور جبس، بديل خشب ورخام، ورق جدران، تشطيبات شاملة. جودة عالية وسعر منافس.';
        $meta_title = $settings['contact_meta_title'] ?? config('app.name', 'من نحن');
        $meta_description = Setting::where('key', 'contact_meta_description')->value('value') ?? 'تصفح أحدث مشاريعنا المنفذة بجودة واحترافية.';


        $meta_keywords = collect([
            $settings['site_name'] ?? null,
            'اتصل معلم دهانات جدة',
            'اتصال معلم دهانات جدة',
            'استفسار دهانات جدة',
            'معلم دهانات الروضة',
            'صباغ جدة',
            'دهان جده',

            'مقاول دهانات',
            'مقاول بجدة',
            'دهان واجهات',
            'مقاول دهانات بجدة',
            'مقاول جبس بورد',
            'مقاول اصباغ',
            'مقاول بناء جدة',
            'مقاول ملاحق جده',
            'مقاول بناء في جدة',
            'افضل مقاول في جدة',
            'معلم بناء جدة',
            'مطلوب مقاول جبس بورد',
            'مطلوب مقاول دهانات',
            'افضل مقاول بناء في جدة',
            'مقاول تشطيب في جدة',
            'مقاولين دهانات',
            'افضل مقاول بجدة',
            'هدم عماير جده',
            'مقاول في جدة ممتاز',
            'مطلوب مقاولين دهانات',
            'مقاول عام جدة',
            'مقاول كسر رخام',
            'مقاول عام',
            'معلم دهانات جدة',
            'معلم دهانات جده',
            'معلم دهان جده',
            'معلم دهان جدة',
            'معلم دهان',
            'معلم بويه',
            'معلم بوية',
            'معلم بويات جدة',
            'معلم بويا جدة',
            'معلم بوية جدة',
            'معلم دهان في جدة',
            'معلم فوم جدة',
            'معلم ديكور جدة',
            'دهانات وديكورات جدة',
            'معلم دهانات وديكورات جدة',
            'ديكور بديل الرخام',
            'ديكور بديل الخشب',
            'ديكور مرايا',
            'معلم ديكور',
            'فني ديكور',
            'فني ديكورات جدة',
            'الوان دهانات الحوائط بالصور',
            'بوية الجزيرة',
            'دهان جدة',
            'دهان جده',
            'ديكورات رمضان',
            'بديل خشب خارجي',
            'شيبورد خشب',
            'بديل شيبورد',
            'شيبورد',
            'بديل رخام',
            'بديل الرخام',
            'بديل الشيبورد',
            'بديل خشب داخلي',
            'الواح شيبورد',
            'بديل الخشب',
            'شيبورد ديكور',
            'ورق جدران',
            'دهان بويه',
            'بويه جوتن',
            'بويه جدران',
            'ديكور جبس اسقف',
            'ديكور جبس بورد اسقف',
            'جبسيات اسقف',
            'دهان بروفايل خارجي',
            'بروفايل خارجي',
            'معلم جبس جدة',
            'معلم جبس بجدة',
            'ديكورات تلفزيون',
            'معلم جبس بورد',
            'جبس بورد اسقف',
            'جوتن للدهانات',
            'دهانات تكنو',
            'دهانات الجزيرة',
            'دهانات الجزيره',
            'الجزيره',
            'الجزيرة',
            'دهانات داخية',
            'الجزيرة دهانات'
        ])->filter()->unique()->shuffle()->implode(', ');

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



/* 
     $meta_keywords = collect([
            $settings['site_name'] ?? null,
            'اتصل معلم دهانات جدة',
             'اتصال معلم دهانات جدة',
             'استفسار دهانات جدة',
             'معلم دهانات الروضة',
             'صباغ جدة',
             'دهان جده',

            'مقاول دهانات',
            'مقاول بجدة',
            'دهان واجهات',
            'مقاول دهانات بجدة',
            'مقاول جبس بورد',
            'مقاول اصباغ',
            'مقاول بناء جدة',
            'مقاول ملاحق جده',
            'مقاول بناء في جدة',
            'افضل مقاول في جدة',
            'معلم بناء جدة',
            'مطلوب مقاول جبس بورد',
            'مطلوب مقاول دهانات',
            'افضل مقاول بناء في جدة',
            'مقاول تشطيب في جدة',
            'مقاولين دهانات',
            'افضل مقاول بجدة',
            'هدم عماير جده',
            'مقاول في جدة ممتاز',
            'مطلوب مقاولين دهانات',
            'مقاول عام جدة',
            'مقاول كسر رخام',
            'مقاول عام',
            'معلم دهانات جدة',
            'معلم دهانات جده',
            'معلم دهان جده',
            'معلم دهان جدة',
            'معلم دهان',
            'معلم بويه',
            'معلم بوية',
            'معلم بويات جدة',
            'معلم بويا جدة',
            'معلم بوية جدة',
            'معلم دهان في جدة',
            'معلم فوم جدة',
            'معلم ديكور جدة',
            'دهانات وديكورات جدة',
            'معلم دهانات وديكورات جدة',
            'ديكور بديل الرخام',
            'ديكور بديل الخشب',
            'ديكور مرايا',
            'معلم ديكور',
            'فني ديكور',
            'فني ديكورات جدة',
            'الوان دهانات الحوائط بالصور',
            'بوية الجزيرة',
            'دهان جدة',
            'دهان جده',
            'ديكورات رمضان',
            'بديل خشب خارجي',
            'شيبورد خشب',
            'بديل شيبورد',
            'شيبورد',
            'بديل رخام',
            'بديل الرخام',
            'بديل الشيبورد',
            'بديل خشب داخلي',
            'الواح شيبورد',
            'بديل الخشب',
            'شيبورد ديكور',
            'ورق جدران',
            'دهان بويه',
            'بويه جوتن',
            'بويه جدران',
            'ديكور جبس اسقف',
            'ديكور جبس بورد اسقف',
            'جبسيات اسقف',
            'دهان بروفايل خارجي',
            'بروفايل خارجي',
            'معلم جبس جدة',
            'معلم جبس بجدة',
            'ديكورات تلفزيون',
            'معلم جبس بورد',
            'جبس بورد اسقف',
            'جوتن للدهانات',
            'دهانات تكنو',
            'دهانات الجزيرة',
            'دهانات الجزيره',
            'الجزيره',
            'الجزيرة',
            'دهانات داخية',
            'الجزيرة دهانات'
        ])->filter()->unique()->shuffle()->implode(', '); */