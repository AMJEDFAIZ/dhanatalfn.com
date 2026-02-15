<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'site_favicon' => ['nullable', 'file', 'max:256'],
        ]);

        if ($request->file('site_logo') && !$request->file('site_logo')->isValid()) {
            return redirect()->back()->withErrors([
                'site_logo' => 'فشل رفع الشعار. تحقق من إعدادات رفع الملفات أو جرّب ملفاً أصغر.',
            ]);
        }

        if ($request->file('site_favicon') && !$request->file('site_favicon')->isValid()) {
            return redirect()->back()->withErrors([
                'site_favicon' => 'فشل رفع أيقونة المتصفح. تحقق من إعدادات رفع الملفات أو جرّب ملفاً أصغر.',
            ]);
        }

        $data = $request->except(['_token', '_method', 'site_logo', 'site_favicon']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $disk = Storage::disk('public');
        $disk->makeDirectory('settings');
        if (!$disk->exists('settings')) {
            return redirect()->back()->with('error', 'تعذر إنشاء مجلد settings داخل التخزين. تأكد من صلاحيات الكتابة على storage/app/public.');
        }

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $extension = $file->extension() ?: 'png';
            $fileName = 'site-logo-' . now()->format('YmdHis') . '-' . Str::random(6) . '.' . $extension;
            $relativePath = $file->storeAs('settings', $fileName, 'public');
            if (!$relativePath) {
                return redirect()->back()->with('error', 'تعذر حفظ الشعار داخل التخزين. تأكد من صلاحيات التخزين ثم حاول مرة أخرى.');
            }

            $this->replaceSettingFile('site_logo', $relativePath);
        }

        if ($request->hasFile('site_favicon')) {
            $file = $request->file('site_favicon');
            $originalExtension = strtolower((string) $file->getClientOriginalExtension());
            if ($originalExtension !== 'ico') {
                return redirect()->back()->withErrors([
                    'site_favicon' => 'يجب رفع ملف favicon.ico فقط.',
                ]);
            }
            $relativePath = $file->storeAs('settings', 'favicon.ico', 'public');
            if (!$relativePath) {
                return redirect()->back()->with('error', 'تعذر حفظ favicon.ico داخل التخزين. تأكد من صلاحيات التخزين ثم حاول مرة أخرى.');
            }

            $this->replaceSettingFile('site_favicon', $relativePath);
        }

        Cache::forget('site_settings');

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    private function replaceSettingFile(string $key, string $newPath): void
    {
        $oldPath = Setting::where('key', $key)->value('value');

        Setting::updateOrCreate(['key' => $key], ['value' => $newPath]);

        if (is_string($oldPath) && $oldPath !== '' && $oldPath !== $newPath) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
