@extends('admin.layouts.admin')

@section('title', 'الإعدادات العامة')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <label for="site_name" class="form-label">اسم الموقع</label>
                <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-lg-6">
                <label for="site_description" class="form-label">وصف الموقع (SEO)</label>
                <textarea name="site_description" id="site_description" rows="1" class="form-control">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
            <div class="col-12 col-md-6">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" id="phone" value="{{ $settings['phone'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ $settings['email'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12">
                <label for="address" class="form-label">العنوان</label>
                <input type="text" name="address" id="address" value="{{ $settings['address'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12">
                <h5 class="mt-3">الموقع وأوقات العمل (Schema)</h5>
            </div>
            <div class="col-12 col-md-6">
                <label for="latitude" class="form-label">خط العرض (Latitude)</label>
                <input type="text" name="latitude" id="latitude" value="{{ $settings['latitude'] ?? '' }}" class="form-control" placeholder="مثال: 21.567355">
            </div>
            <div class="col-12 col-md-6">
                <label for="longitude" class="form-label">خط الطول (Longitude)</label>
                <input type="text" name="longitude" id="longitude" value="{{ $settings['longitude'] ?? '' }}" class="form-control" placeholder="مثال: 39.1925">
            </div>
            <div class="col-12 col-md-6">
                <label for="opens" class="form-label">وقت الفتح</label>
                <input type="time" name="opens" id="opens" value="{{ $settings['opens'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label for="closes" class="form-label">وقت الإغلاق</label>
                <input type="time" name="closes" id="closes" value="{{ $settings['closes'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12">
                <h5 class="mt-3">تواصل اجتماعي</h5>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="facebook" class="form-label">Facebook</label>
                <input type="url" name="facebook" id="facebook" value="{{ $settings['facebook'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="twitter" class="form-label">Twitter (X)</label>
                <input type="url" name="twitter" id="twitter" value="{{ $settings['twitter'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="url" name="instagram" id="instagram" value="{{ $settings['instagram'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="linkedin" class="form-label">LinkedIn</label>
                <input type="url" name="linkedin" id="linkedin" value="{{ $settings['linkedin'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="whatsapp" class="form-label">WhatsApp (Number)</label>
                <input type="text" name="whatsapp" id="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" class="form-control" placeholder="9665XXXXXXXX">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="youtube" class="form-label">Youtube</label>
                <input type="url" name="youtube" id="youtube" value="{{ $settings['youtube'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="tiktok" class="form-label">TikTok</label>
                <input type="url" name="tiktok" id="tiktok" value="{{ $settings['tiktok'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label for="snapchat" class="form-label">Snapchat</label>
                <input type="url" name="snapchat" id="snapchat" value="{{ $settings['snapchat'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12">
                <h5 class="mt-3">الشعارات</h5>
            </div>
            <div class="col-12 col-md-6">
                <label for="site_logo" class="form-label">شعار الموقع (Logo)</label>
                @if(isset($settings['site_logo']))
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="rounded border p-2" style="height:64px">
                </div>
                @endif
                <input type="file" name="site_logo" id="site_logo" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label for="site_favicon" class="form-label">أيقونة المتصفح (Favicon)</label>
                @if(isset($settings['site_favicon']))
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="rounded border p-1" style="height:32px;width:32px">
                </div>
                @endif
                <input type="file" name="site_favicon" id="site_favicon" class="form-control">
            </div>
            <div class="col-12">
                <h5 class="mt-4">إعدادات SEO للأقسام</h5>
            </div>
            <div class="col-12 col-lg-6">
                <label for="about_meta_title" class="form-label">عنوان SEO - من نحن</label>
                <input type="text" name="about_meta_title" id="about_meta_title" value="{{ $settings['about_meta_title'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-lg-6">
                <label for="about_meta_description" class="form-label">وصف SEO - من نحن</label>
                <textarea name="about_meta_description" id="about_meta_description" rows="2" class="form-control">{{ $settings['about_meta_description'] ?? '' }}</textarea>
            </div>
            <div class="col-12 col-lg-6">
                <label for="contact_meta_title" class="form-label">عنوان SEO - اتصل بنا</label>
                <input type="text" name="contact_meta_title" id="contact_meta_title" value="{{ $settings['contact_meta_title'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-lg-6">
                <label for="contact_meta_description" class="form-label">وصف SEO - اتصل بنا</label>
                <textarea name="contact_meta_description" id="contact_meta_description" rows="2" class="form-control">{{ $settings['contact_meta_description'] ?? '' }}</textarea>
            </div>
            <div class="col-12 col-lg-6">
                <label for="services_meta_title" class="form-label">عنوان SEO - الخدمات</label>
                <input type="text" name="services_meta_title" id="services_meta_title" value="{{ $settings['services_meta_title'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-lg-6">
                <label for="services_meta_description" class="form-label">وصف SEO - الخدمات</label>
                <textarea name="services_meta_description" id="services_meta_description" rows="2" class="form-control">{{ $settings['services_meta_description'] ?? '' }}</textarea>
            </div>
            <div class="col-12 col-lg-6">
                <label for="projects_meta_title" class="form-label">عنوان SEO - المشاريع</label>
                <input type="text" name="projects_meta_title" id="projects_meta_title" value="{{ $settings['projects_meta_title'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-lg-6">
                <label for="projects_meta_description" class="form-label">وصف SEO - المشاريع</label>
                <textarea name="projects_meta_description" id="projects_meta_description" rows="2" class="form-control">{{ $settings['projects_meta_description'] ?? '' }}</textarea>
            </div>
            <div class="col-12 col-lg-6">
                <label for="blog_meta_title" class="form-label">عنوان SEO - المدونة</label>
                <input type="text" name="blog_meta_title" id="blog_meta_title" value="{{ $settings['blog_meta_title'] ?? '' }}" class="form-control">
            </div>
            <div class="col-12 col-lg-6">
                <label for="blog_meta_description" class="form-label">وصف SEO - المدونة</label>
                <textarea name="blog_meta_description" id="blog_meta_description" rows="2" class="form-control">{{ $settings['blog_meta_description'] ?? '' }}</textarea>
            </div>

            <div class="col-12">
                <hr class="my-4">
                <h5 class="mb-3">إعدادات خريطة الموقع (Sitemap)</h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    يمكنك التحكم في ظهور الصفحات الثابتة في خريطة الموقع وتحديد أولويتها وتكرار تحديثها.
                </div>
            </div>

            <!-- الصفحة الرئيسية -->
            <div class="col-12 col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-light fw-bold">الصفحة الرئيسية (Home)</div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="sitemap_home_active" value="0">
                            <input class="form-check-input" type="checkbox" id="sitemap_home_active" name="sitemap_home_active" value="1" {{ isset($settings['sitemap_home_active']) && $settings['sitemap_home_active'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="sitemap_home_active">تضمين في خريطة الموقع</label>
                        </div>
                        <div class="mb-3">
                            <label for="sitemap_home_priority" class="form-label">الأولوية (0.0 - 1.0)</label>
                            <input type="number" step="0.1" min="0.0" max="1.0" name="sitemap_home_priority" id="sitemap_home_priority" value="{{ $settings['sitemap_home_priority'] ?? '1.0' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="sitemap_home_freq" class="form-label">تكرار التغيير</label>
                            <select name="sitemap_home_freq" id="sitemap_home_freq" class="form-select">
                                <option value="always" {{ ($settings['sitemap_home_freq'] ?? '') == 'always' ? 'selected' : '' }}>Always</option>
                                <option value="hourly" {{ ($settings['sitemap_home_freq'] ?? '') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                <option value="daily" {{ ($settings['sitemap_home_freq'] ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ ($settings['sitemap_home_freq'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ ($settings['sitemap_home_freq'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ ($settings['sitemap_home_freq'] ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="never" {{ ($settings['sitemap_home_freq'] ?? '') == 'never' ? 'selected' : '' }}>Never</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- صفحة من نحن -->
            <div class="col-12 col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-light fw-bold">صفحة من نحن (About)</div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="sitemap_about_active" value="0">
                            <input class="form-check-input" type="checkbox" id="sitemap_about_active" name="sitemap_about_active" value="1" {{ isset($settings['sitemap_about_active']) && $settings['sitemap_about_active'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="sitemap_about_active">تضمين في خريطة الموقع</label>
                        </div>
                        <div class="mb-3">
                            <label for="sitemap_about_priority" class="form-label">الأولوية (0.0 - 1.0)</label>
                            <input type="number" step="0.1" min="0.0" max="1.0" name="sitemap_about_priority" id="sitemap_about_priority" value="{{ $settings['sitemap_about_priority'] ?? '0.8' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="sitemap_about_freq" class="form-label">تكرار التغيير</label>
                            <select name="sitemap_about_freq" id="sitemap_about_freq" class="form-select">
                                <option value="daily" {{ ($settings['sitemap_about_freq'] ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ ($settings['sitemap_about_freq'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ ($settings['sitemap_about_freq'] ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ ($settings['sitemap_about_freq'] ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- صفحة اتصل بنا -->
            <div class="col-12 col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-light fw-bold">صفحة اتصل بنا (Contact)</div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="sitemap_contact_active" value="0">
                            <input class="form-check-input" type="checkbox" id="sitemap_contact_active" name="sitemap_contact_active" value="1" {{ isset($settings['sitemap_contact_active']) && $settings['sitemap_contact_active'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="sitemap_contact_active">تضمين في خريطة الموقع</label>
                        </div>
                        <div class="mb-3">
                            <label for="sitemap_contact_priority" class="form-label">الأولوية (0.0 - 1.0)</label>
                            <input type="number" step="0.1" min="0.0" max="1.0" name="sitemap_contact_priority" id="sitemap_contact_priority" value="{{ $settings['sitemap_contact_priority'] ?? '0.8' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="sitemap_contact_freq" class="form-label">تكرار التغيير</label>
                            <select name="sitemap_contact_freq" id="sitemap_contact_freq" class="form-select">
                                <option value="daily" {{ ($settings['sitemap_contact_freq'] ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ ($settings['sitemap_contact_freq'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ ($settings['sitemap_contact_freq'] ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ ($settings['sitemap_contact_freq'] ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 text-end">
            <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush