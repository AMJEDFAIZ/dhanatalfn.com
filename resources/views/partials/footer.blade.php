 <!-- Footer -->
 <footer class="bg-primary text-white pt-16 pb-8 border-t-4 border-accent">


     <div class="container mx-auto px-4">

         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
             <!-- About -->

             <div>
                 <div class="flex items-center gap-3 mb-6">
                     <img src="{{ isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('logo.PNG') }}"
                         alt="شعار" class="h-12 bg-white p-1 rounded">
                     <h3 class="text-xl font-bold">{{ $settings['site_name'] ?? 'معلم دهانات وديكورات جدة' }}</h3>
                 </div>
                 <p class="text-gray-400 mb-6 leading-relaxed text-sm">
                     {{ $settings['site_description'] ?? 'الفـن الـحـديـث هي شركة رائدة في مجال المقاولات والإنشاءات، نسعى لتقديم أفضل الخدمات بجودة عالية واحترافية.' }}
                 </p>
                 <div class="flex space-x-reverse space-x-4">

                     @if (isset($settings['phone']))
                     <a href="tel:{{ $settings['phone'] }}"
                         class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"><i
                             class="fas fa-phone"></i></a>
                     @endif


                     @if (isset($settings['whatsapp']))
                     <a href="{{ $settings['whatsapp'] }}"
                         class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                         target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                     @endif



                     @if (isset($settings['tiktok']))
                     <a href="{{ $settings['tiktok'] }}"
                         class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                         target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                     @endif

                     @if (isset($settings['facebook']))
                     <a href="{{ $settings['facebook'] }}"
                         class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                         target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                     @endif
                     @if (isset($settings['twitter']))
                     <a href="{{ $settings['twitter'] }}"
                         class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                         target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-twitter"></i></a>
                     @endif
                     @if (isset($settings['instagram']))
                     <a href="{{ $settings['instagram'] }}"
                         class=" w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors "
                         target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                     @endif
                     @if (isset($settings['linkedin']))
                     <a href="{{ $settings['linkedin'] }}"
                         class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                         target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                     @endif

                 </div>
             </div>

             <!-- Links -->
             <div>
                 <h4
                     class="text-lg font-bold mb-6 relative inline-block after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-10 after:h-1 after:bg-accent after:rounded-full">
                     روابط سريعة</h4>
                 <ul class="space-y-3 text-gray-400">
                     <li><a href="{{ route('home') }}"
                             class="hover:text-accent transition-colors flex items-center gap-2"><i
                                 class="fa-solid fa-angle-left text-xs"></i> الرئيسية</a></li>
                     <li><a href="{{ route('about') }}"
                             class="hover:text-accent transition-colors flex items-center gap-2"><i
                                 class="fa-solid fa-angle-left text-xs"></i> من نحن</a></li>
                     {{-- <li><a href="vision.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> الرؤية والرسالة</a></li>
                        <li><a href="team.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> فريق العمل</a></li>
                        <li><a href="career.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> الوظائف</a></li> --}}
                     <li><a href="{{ route('services.index') }}"
                             class="hover:text-accent transition-colors flex items-center gap-2"><i
                                 class="fa-solid fa-angle-left text-xs"></i> خدماتنا </a></li>
                     <li><a href="{{ route('projects.index') }}"
                             class="hover:text-accent transition-colors flex items-center gap-2"><i
                                 class="fa-solid fa-angle-left text-xs"></i> اعمالنا</a></li>

                     <li><a href="{{ route('blog.index') }}"
                             class="hover:text-accent transition-colors flex items-center gap-2"><i
                                 class="fa-solid fa-angle-left text-xs"></i> المقالات</a></li>
                     {{-- <li><a href="quote.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> طلب عرض سعر</a></li> --}}
                     <li><a href="{{ route('contact') }}"
                             class="hover:text-accent transition-colors flex items-center gap-2"><i
                                 class="fa-solid fa-angle-left text-xs"></i> تواصل معنا</a></li>
                 </ul>
             </div>

             <!-- Services -->
             {{-- <div>
                    <h4 class="text-lg font-bold mb-6 relative inline-block after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-10 after:h-1 after:bg-accent after:rounded-full">خدماتنا</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="service-detail.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> الإنشاءات والمباني</a></li>
                        <li><a href="service-detail-2.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> البنية التحتية</a></li>
                        <li><a href="service-detail-3.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> التصميم الداخلي</a></li>
                        <li><a href="service-detail-4.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> الترميم والصيانة</a></li>
                        <li><a href="services.html" class="hover:text-accent transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-left text-xs"></i> جميع الخدمات</a></li>
                    </ul>
                </div> --}}
             <div>
                 <h4
                     class="text-lg font-bold mb-6 relative inline-block after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-10 after:h-1 after:bg-accent after:rounded-full">
                     معلومات التواصل </h4>
                 <ul class="space-y-3 text-gray-400">
                     <li class="hover:text-accent transition-colors flex items-center gap-2">

                         <i class="fa-solid fa-location text-xs"></i>
                         <span
                             class="hover:text-accent transition-colors">{{ $settings['address'] ?? 'المملكة العربية السعودية' }}</span>
                     </li>
                     <li class="hover:text-accent transition-colors flex items-center gap-2">

                         <i class="fa-solid fa-phone text-xs"></i>
                         <span dir="ltr" class="hover:text-accent transition-colors"><a
                                 href="tel:{{ $settings['phone'] ?? '+966 5 3279 1522' }}">{{ $settings['phone'] ?? '+966 5 3279 1522' }}</a></span>
                     </li>
                     <li class="hover:text-accent transition-colors flex items-center gap-2">

                         <i class="fab fa-whatsapp text-xs"></i>
                         <span dir="ltr" class="hover:text-accent transition-colors"> <a
                                 href="{{ $settings['whatsapp'] ?? '+966 5 3279 1522' }}" target="_blank"
                                 rel="noopener noreferrer">+966 5 3279
                                 1522</a></span>
                     </li>
                     <li class="hover:text-accent transition-colors flex items-center gap-2">

                         <i class="fa-solid fa-envelope text-xs"></i>
                         <span
                             class="hover:text-accent transition-colors">{{ $settings['email'] ?? 'info@alfan.com' }}</span>
                     </li>
                 </ul>
             </div>

             <!-- Newsletter -->
             {{-- <div>
                    <h4 class="text-lg font-bold mb-6 relative inline-block after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-10 after:h-1 after:bg-accent after:rounded-full">النشرة البريدية</h4>
                    <p class="text-gray-400 mb-4 text-sm">اشترك في نشرتنا البريدية ليصلك كل جديد</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="بريدك الإلكتروني" class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 text-white focus:border-accent focus:outline-none">
                        <button type="submit" class="bg-accent text-white px-4 py-2 rounded hover:bg-yellow-600 transition-colors"><i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div> --}}
         </div>


         <div
             class="border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-lg text-gray-400">

             <p>تم التصميم والتطوير بواسطة <span class="text-white ">
                     <a href="https://wa.me/+967775226109"
                         class="hover:text-accent transition-colors">Amjed.Dev</a></span></p>
             <p> &copy; {{ date('Y') }}
                 {{ $settings['site_name'] ?? 'الفـن الـحـديـث' }}. جميع الحقوق محفوظة.
             </p>

         </div>

     </div>
 </footer>