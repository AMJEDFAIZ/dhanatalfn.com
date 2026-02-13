@extends('layouts.site')

@section('title', ' تواصل معنا')
@section('meta_title', $settings['site_name'] ?? config('app.name', 'أفضل معلم دهانات وديكورات في جدة'))
@section('meta_description', $settings['site_description'] ?? null)
@section('meta_keywords',
    collect([$settings['site_name'] ?? null, 'تواصل معنا', 'تواصل', 'الاتصال', 'دهانات', 'ديكورات', 'الفن الحديث', ' معلم دهانات وديكورات', 'معلم دهانات جدة', 'معلم دهانات'])->filter()->implode(', '))

@section('content')


    <!-- Page Hero -->
    <section class="relative h-[40vh] min-h-[300px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/img/hero.webp') }}" alt="تواصل معنا " class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-primary/80 mix-blend-multiply"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center pt-20">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up text-accent">تـواصـل مـعـنـا</h1>
            <nav
                class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
                <span>/</span>
                <span
                    class="text-accent/4 transition-colors py-1 relative  after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">تـواصـل
                    مـعـنـا</span>
            </nav>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">

            <!-- Contact Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Phone -->
                <div class="bg-gray-50 p-8 rounded-xl text-center border border-gray-100 shadow-sm reveal">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-accent text-2xl mb-6 mx-auto shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-2">اتصل بنا</h3>
                    <p class="text-gray-600 mb-1">نحن جاهزون للرد على استفساراتك</p>
                    <p class="text-lg font-bold text-primary" dir="ltr">
                        {{ $settings['phone'] ?? '+966 5 3279 1522' }}
                    </p>
                </div>

                <!-- Email -->
                <div class="bg-gray-50 p-8 rounded-xl text-center border border-gray-100 shadow-sm reveal delay-100">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-accent text-2xl mb-6 mx-auto shadow-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-2">راسلنا</h3>
                    <p class="text-gray-600 mb-1">أرسل لنا رسالة وسنرد عليك قريباً</p>
                    <p class="text-lg font-bold text-primary">
                        {{ $settings['email'] ?? 'info@example.com' }}
                    </p>
                </div>

                <!-- Location -->
                <div class="bg-gray-50 p-8 rounded-xl text-center border border-gray-100 shadow-sm reveal delay-200">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-accent text-2xl mb-6 mx-auto shadow-sm">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-2">زرنا</h3>
                    <p class="text-gray-600 mb-1">تفضل بزيارة مكتبنا الرئيسي</p>
                    <p class="text-lg font-bold text-primary">
                        {{ $settings['address'] ?? 'المملكة العربية السعودية' }}
                    </p>
                </div>
            </div>

            <!-- Form + Map -->
            <div class="flex flex-col lg:flex-row gap-12">

                <!-- Contact Form -->
                <div class="w-full lg:w-1/2 reveal">
                    <h2 class="text-3xl font-bold text-primary mb-6">أرسل لنا رسالة</h2>
                    <p class="text-gray-600 mb-8">
                        هل لديك سؤال أو استفسار؟ املأ النموذج أدناه وسيقوم فريقنا بالتواصل معك في أقرب وقت ممكن.
                    </p>

                    <div id="contact-alert" class="hidden mb-4 p-4 rounded text-sm"></div>

                    <form id="contact-form" method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                                <input id="name" type="text"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all"
                                    placeholder="أدخل اسمك">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input id="email" type="email"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all"
                                    placeholder="أدخل بريدك الإلكتروني">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <input id="phone" type="tel"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all"
                                    placeholder="أدخل رقم هاتفك">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الموضوع</label>
                                <select id="subject"
                                    class="w-full px-4 py-3 border rounded-md focus:outline-none focus:border-accent text-center">
                                    <option value="">اختر الموضوع</option>
                                    <option value="استشارة">استشارة</option>
                                    <option value="عرض سعر">عرض سعر</option>
                                    <option value="دعم">دعم</option>
                                    <option value="أخرى">أخرى</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الرسالة</label>
                            <textarea id="message" rows="5"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition-all"
                                placeholder="اكتب رسالتك هنا..."></textarea>
                            <div class="text-xs text-gray-500 mt-1">
                                عدد الأحرف: <span id="char-count">0</span>
                            </div>
                        </div>

                        <button id="submit-btn" type="submit"
                            class="w-full bg-accent text-white py-3 rounded-lg font-bold hover:bg-primary transition-all">
                            إرسال الرسالة
                        </button>
                    </form>
                </div>

                <!-- Map -->
                <div class="w-full lg:w-1/2 reveal delay-100">
                    <div class="bg-gray-200 w-full h-full min-h-full rounded-2xl overflow-hidden shadow-lg relative">
                        <!-- Placeholder for Google Maps -->
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d423226.8599982442!2d39.42386696262551!3d21.532167539432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c3d01fb1137e59%3A0xe059579737b118db!2z2KzYr9ipINin2YTYs9i52YjYr9mK2Kk!5e0!3m2!1sar!2s!4v1769112078421!5m2!1sar!2s"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

            </div>
        </div>
    </section>




@endsection

@push('scripts')
    <script>
        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const alertBox = document.getElementById('contact-alert');
        const emailInput = document.getElementById('email');
        const nameInput = document.getElementById('name');
        const subjectSelect = document.getElementById('subject');
        const messageInput = document.getElementById('message');
        const charCount = document.getElementById('char-count');

        function showAlert(text, type) {
            alertBox.textContent = text;
            alertBox.className = 'mb-4 p-4 rounded text-sm ' + (type === 'success' ? 'bg-green-100 text-green-700' :
                'bg-red-100 text-red-700');
            alertBox.classList.remove('hidden');
        }

        function hideAlert() {
            alertBox.classList.add('hidden');
        }

        function validEmail(v) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
        }
        messageInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const subject = subjectSelect.value.trim();
            const message = messageInput.value.trim();
            if (!name || !email || !subject || !message) {
                showAlert('يرجى ملء جميع الحقول المطلوبة', 'error');
                return;
            }
            if (!validEmail(email)) {
                showAlert('صيغة البريد الإلكتروني غير صحيحة', 'error');
                return;
            }
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري الإرسال...';
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('{{ route('contact.submit') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        subject,
                        message,
                        phone: document.getElementById('phone').value
                    })
                });
                const data = await res.json();
                if (res.ok && data.status === 'ok') {
                    showAlert('تم إرسال رسالتك بنجاح', 'success');
                    form.reset();
                    charCount.textContent = '0';
                } else {
                    showAlert('تعذر إرسال الرسالة، حاول لاحقاً', 'error');
                }
            } catch (err) {
                showAlert('حدث خطأ غير متوقع', 'error');
                console.error(err);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'إرسال';
            }
        });
    </script>
@endpush

