 <!-- Footer -->
 <footer class="bg-primary text-white pt-16 pb-8 border-t-4 border-accent">


     <div class="container mx-auto px-4">

         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
             <!-- About -->

             <div>
                 <div class="flex items-center gap-3 mb-6">
                     <img src="{{ isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('logo.PNG') }}"
                         alt="شعار" class="h-12 bg-white p-1 rounded">
                     <h3 class="text-xl font-bold">الفـن الـحـديـث</h3>
                 </div>
                 <p class="text-gray-400 mb-6 leading-relaxed text-sm">
                     {{ $settings['site_description'] ?? 'الفـن الـحـديـث هي شركة رائدة في مجال المقاولات والإنشاءات، نسعى لتقديم أفضل الخدمات بجودة عالية واحترافية.' }}
                 </p>
                 <div class="flex space-x-reverse space-x-4">
                     @if (isset($settings['whatsapp']))
                         <a href="{{ $settings['linkedin'] }}"
                             class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                             target="_blank"><i class="fab fa-whatsapp"></i></a>
                     @endif
                     @if (isset($settings['facebook']))
                         <a href="{{ $settings['facebook'] }}"
                             class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                             target="_blank"><i class="fab fa-facebook-f"></i></a>
                     @endif
                     @if (isset($settings['twitter']))
                         <a href="{{ $settings['twitter'] }}"
                             class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                             target="_blank"><i class="fa-brands fa-twitter"></i></a>
                     @endif
                     @if (isset($settings['instagram']))
                         <a href="{{ $settings['instagram'] }}"
                             class=" w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors "
                             target="_blank"><i class="fab fa-instagram"></i></a>
                     @endif
                     @if (isset($settings['linkedin']))
                         <a href="{{ $settings['linkedin'] }}"
                             class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                             target="_blank"><i class="fab fa-linkedin-in"></i></a>
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
                     <li class="flex items-start">
                         <svg class="w-6 h-6 ml-2 text-secondary" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                             </path>
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                         </svg>
                         <span>{{ $settings['address'] ?? 'المملكة العربية السعودية' }}</span>
                     </li>
                     <li class="flex items-start">
                         <svg class="w-6 h-6 ml-2 text-secondary" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                             </path>
                         </svg>
                         <span dir="ltr">{{ $settings['phone'] ?? '+966 5 3279 1522' }}</span>
                     </li>
                     <li class="flex items-start">
                         <svg class="w-6 h-6 ml-2 text-secondary" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                             </path>
                         </svg>
                         <span>{{ $settings['email'] ?? 'info@alfan.com' }}</span>
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
