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
