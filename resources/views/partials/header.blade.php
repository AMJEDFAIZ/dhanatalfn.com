  <!-- Header -->
  <header id="header"
      class="fixed w-full top-0 z-50 transition-all duration-300  bg-white/85 backdrop-blur-md shadow-sm">
      <div class="container mx-auto px-4 py-2 flex justify-between items-center">
          <!-- Logo -->
          <a href="{{ route('home') }}" class="flex items-center gap-3 group">
              @if (isset($settings['site_logo'], $settings['site_name']))
                  <img src="{{ isset($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('assets/img/logo.PNG') }}"
                      alt="{{ $settings['site_name'] ?? 'معلم دهانات وديكور' }}"
                      class="h-20 md:h-25  object-contain group-hover:scale-105 transition-transform duration-300">
              @endif
              <div class="md:block ">
                  <p class="text-primary font-bold text-lg leading-tight ">الفن الحديث</p>
                  <p class="text-base text-accent font-bold ">للدهانات والديكور</p>
                  @if (isset($settings['phone']))
                      <p class="text-base text-accent font-bold">{{ $settings['phone'] }} </p>
                  @endif

              </div>
          </a>
          @php
              $active = function ($route) {
                  return request()->routeIs($route)
                      ? 'text-accent after:w-full'
                      : 'text-gray-600 after:w-0 hover:after:w-full';
              };
              $mobileActive = function ($route) {
                  return request()->routeIs($route) ? 'text-accent' : 'text-white hover:text-accent';
              };
          @endphp



          <!-- Desktop Nav -->
          <nav class="hidden lg:flex items-center gap-6 xl:gap-8">

              <a href="{{ route('home') }}"
                  class="font-medium transition-colors py-2 relative after:content-[''] after:absolute after:bottom-0 after:right-0 after:h-0.5 after:bg-accent after:transition-all {{ $active('home') }}">
                  الرئيسية
              </a>

              <a href="{{ route('about') }}"
                  class="font-medium transition-colors py-2 relative after:content-[''] after:absolute after:bottom-0 after:right-0 after:h-0.5 after:bg-accent after:transition-all {{ $active('about') }}">
                  من نحن
              </a>

              <a href="{{ route('services.index') }}"
                  class="font-medium transition-colors py-2 relative after:content-[''] after:absolute after:bottom-0 after:right-0 after:h-0.5 after:bg-accent after:transition-all {{ $active('services.*') }}">
                  خدماتنا
              </a>

              <a href="{{ route('projects.index') }}"
                  class="font-medium transition-colors py-2 relative after:content-[''] after:absolute after:bottom-0 after:right-0 after:h-0.5 after:bg-accent after:transition-all {{ $active('projects.*') }}">
                  أعمالنا
              </a>

              <a href="{{ route('home') }}#testimonials"
                  class="font-medium transition-colors py-2 relative after:content-[''] after:absolute after:bottom-0 after:right-0 after:h-0.5 after:bg-accent after:transition-all">
                  آراء العملاء
              </a>
              {{--  {{ request()->is('/') ? 'text-accent after:w-full' : 'text-gray-600 after:w-0 hover:after:w-full' }} --}}
              <a href="{{ route('blog.index') }}"
                  class="font-medium transition-colors py-2 relative after:content-[''] after:absolute after:bottom-0 after:right-0 after:h-0.5 after:bg-accent after:transition-all {{ $active('blog.*') }}">
                  المقالات
              </a>

              <a href="{{ route('contact') }}"
                  class="bg-primary text-white px-6 py-2.5 rounded-full font-bold hover:bg-opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                  تواصل معنا
              </a>

          </nav>


          <!-- Mobile Menu Button -->
          <button id="mobile-menu-btn" class="lg:hidden text-primary text-2xl focus:outline-none">
              <i class="fa-solid fa-bars"></i>
          </button>
      </div>

      <!-- Mobile Menu Overlay -->
      <div id="mobile-menu"
          class="fixed inset-0 bg-primary/90 z-50 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col pt-24 ">

          <!-- Close Button -->
          <button id="close-menu-btn"
              class="absolute top-6 left-6 text-white text-3xl hover:text-accent transition-colors">
              <i class="fa-solid fa-times"></i>
          </button>
          <nav class="flex flex-col items-center gap-6 bg-primary/90 pb-1">

              <a href="{{ route('home') }}"
                  class="mobile-link text-xl font-bold transition-colors {{ $mobileActive('home') }}">
                  الرئيسية
              </a>

              <a href="{{ route('about') }}"
                  class="mobile-link text-xl font-bold transition-colors {{ $mobileActive('about') }}">
                  من نحن
              </a>

              <a href="{{ route('services.index') }}"
                  class="mobile-link text-xl font-bold transition-colors {{ $mobileActive('services.*') }}">
                  خدماتنا
              </a>

              <a href="{{ route('projects.index') }}"
                  class="mobile-link text-xl font-bold transition-colors {{ $mobileActive('projects.*') }}">
                  أعمالنا
              </a>

              <!-- رابط آراء العملاء مع نفس منطق السكـرول -->
              <a href="{{ route('home') }}#testimonials" id="mobile-testimonials"
                  class="mobile-link text-xl font-bold text-white hover:text-accent transition-colors">
                  آراء العملاء
              </a>

              <a href="{{ route('blog.index') }}"
                  class="mobile-link text-xl font-bold transition-colors {{ $mobileActive('blog.*') }}">
                  المقالات
              </a>

              <a href="{{ route('contact') }}"
                  class="mobile-link bg-white text-primary px-8 py-3 rounded-full font-bold hover:bg-secondary transition-all mt-4 mb-2">
                  تواصل معنا
              </a>

          </nav>

      </div>


  </header>
