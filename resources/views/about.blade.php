@extends('layouts.site')
@section('title', ' من نحن')

@section('meta_description', $settings['site_description'] ?? null)
@section('meta_keywords',$meta_keywords)



@section('content')



<!--  Hero -->
<section class="relative h-[40vh] min-h-[300px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/img/hero.webp') }}" alt="من نـحـن" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-primary/80 mix-blend-multiply"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-center pt-20">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up text-accent">من نـحـن</h1>
        <nav
            class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
            <span>/</span>
            <span
                class="text-accent/4 transition-colors py-1 relative  after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">من
                نـحـن</span>
        </nav>
        @if (isset($pageContentKeywords))
        <div class="mt-5 flex justify-center">
            @include('partials.keyword-tags', ['keywords' => $pageContentKeywords])
        </div>
        @endif
    </div>
</section>



<!-- Detailed About Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 reveal animate-fade-in-up">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl max-w-[580px] h-[422px] ">
                    <img src="{{ asset('assets/img/hero.webp') }}" alt="About Us"
                        class="w-full  max-w-[580px] h-[422px] ">
                    <div class="absolute inset-0 bg-primary/10"></div>
                </div>

            </div>
            <div class="w-full lg:w-1/2 reveal animate-fade-in-up">
                <div class="flex items-center gap-2 mb-7 text-2xl">
                    <div class="w-12 h-1 bg-accent rounded-full"></div>
                    <span class="text-accent font-bold uppercase tracking-wider"> من نحن- ABOUT US</span>
                </div>
                <p class="text-gray-600 leading-relaxed mb-6 text-lg">

                    <span class="text-accent font-bold">معلم دهانات وديكورات جدة </span>نحن فريق ذو خبرة طويلة في مجالات الدهانات والديكورات والتشطيبات. نسعى دائمًا لتحقيق رضا عملائنا من خلال تقديم أعمال احترافية تتوافق مع معايير الجودة العالمية. نجلب خبرتنا في تجهيز الأسطح جيدًا قبل الطلاء بعمليات اللياسة والمعجون والصنفرة لتحقيق لمسة نهائية مثالية. نولي اهتمامًا خاصًا بتجهيز جدران المنازل واستشارة العميل في اختيار الألوان المناسبة، خاصةً أن ألوان الجدران تؤثر على نفسية قاطني المنزل، فنساعدكم في تنسيق الألوان حسب ذوقكم وحسب سطوع الإضاءة ومساحة المكان. كما نوفر ضمانات على التنفيذ نهتم بها شخصيًا لضمان أن كل مشروع يخرج بأعلى جودة ممكنة.

                    <!--دهانات وديكورات <span class="text-accent font-bold">الفـن الـحـديـث</span> تقدم أفضل خدمات الدهانات-->
                    <!--والديكورات في جدة، وجميع مناطقها، حيث نتميز بالجودة-->
                    <!--العالية والاحترافية في العمل. نقدم مجموعة متنوعة من خدمات الدهانات الداخلية والخارجية باستخدام أحدث-->
                    <!--التقنيات وأفضل المواد لضمان مظهر جذاب وحماية تدوم طويلاً. كما نوفر تصميم وتنفيذ ديكورات عصرية مثل-->
                    <!--الفوم، الجبس بورد، بديل الرخام والخشب، لنضفي لمسة من الفخامة والأناقة على منازلكم ومكاتبكم. فريقنا-->
                    <!--المتخصص يعمل على تحقيق رؤيتكم بكل دقة واحترافية، مع الالتزام بالمواعيد وأسعار تنافسية ترضي جميع-->
                    <!--العملاء.-->
                </p>

                <div class="border-r-4 border-accent pr-6 mb-8 bg-gray-50 p-4 rounded-r-lg">
                    <p class="text-primary font-bold italic text-lg">
                        " معلم دهانات وديكورات جدة ، لأعمال الدهان والديكور."
                    </p>
                    <span class="block mt-2 text-sm text-gray-500">- المعلم احمد</span>
                </div>
            </div>
        </div>
    </div>
</section>












<section class="py-20 bg-primary text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('assets/img/cubes.png')] opacity-10"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row gap-16 items-center">

            <!-- Text Content (Right Side) -->
            <div class="w-full lg:w-1/2 animate-fade-in-up ">
                <span class="text-accent text-2xl font-bold uppercase tracking-wider block mb-6">
                    ماذا نقدم
                </span>
                <p class="mx-auto mb-8 leading-relaxed text-lg">

                    دهانات وديكورات <span class="text-accent font-bold">الفـن الـحـديـث</span> تقدم أفضل خدمات الدهانات
                    والديكورات في جدة، وجميع مناطقها، حيث نتميز بالجودة
                    العالية والاحترافية في العمل. نقدم مجموعة متنوعة من خدمات الدهانات الداخلية والخارجية باستخدام أحدث
                    التقنيات وأفضل المواد لضمان مظهر جذاب وحماية تدوم طويلاً. كما نوفر تصميم وتنفيذ ديكورات عصرية مثل
                    الفوم، الجبس بورد، بديل الرخام والخشب، لنضفي لمسة من الفخامة والأناقة على منازلكم ومكاتبكم. فريقنا
                    المتخصص يعمل على تحقيق رؤيتكم بكل دقة واحترافية، مع الالتزام بالمواعيد وأسعار تنافسية ترضي جميع
                    العملاء.

                    <!--رؤيتنا في دهانات وديكورات <span class="text-accent  font-bold">-->
                    <!--    الفـن الـحـديـث</span> هي أن نصبح الخيار الأول للعملاء في المملكة العربية السعودية، وجميع-->
                    <!--مناطقها بتقديم خدمات-->
                    <!--دهانات وديكورات مميزة تجمع بين الجودة العالية، التصاميم العصرية، والتنفيذ الاحترافي، مع ضمان رضا-->
                    <!--العملاء وتلبية احتياجاتهم بكل دقة.-->

                </p>
            </div>

            <!-- Image Content (Left Side) -->
            <div class="w-full lg:w-1/2 flex justify-center animate-fade-in-up ">
                <div class="relative w-full max-w-[580px] h-[422px] rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('assets/img/vision.webp') }}" alt="  2030  الفـن الـحـديـث"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-primary/10"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 reveal animate-fade-in-up">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl max-w-[580px] h-[422px]">
                    <img src="{{ asset('assets/img/vision2.webp') }}" alt="About Us"
                        class="w-full max-w-[580px] h-[422px] ">
                    <div class="absolute inset-0 bg-primary/10"></div>
                </div>
            </div>
            <div class="w-full lg:w-1/2 reveal animate-fade-in-up">
                <span class="text-accent text-2xl font-bold uppercase tracking-wider block mb-7">
                    أفضل معلم دهانات وديكورات في جدة
                </span>
                <p class="mx-auto mb-8 leading-relaxed text-lg">

                    نحن متواجدون في <span class="font-bold">حي الروضة – جدة </span>لخدمة جميع أحياء جدة ومحيطها. سواء كنتم تبحثون عن ّ معلم دهانات محترف
                    أو مقاول ديكور موثوق، تواصلوا معنا عبر الهاتف أو عبر الواتساب للحصول على استشارة مجانية أو طلب عرض
                    أسعار. فريقنا جاهز للرد على استفساراتكم وتقديم الحلول المناسبة لاحتياجاتكم.

                    <!--دهانات وديكورات <span class="text-accent  font-bold">-->
                    <!--    الفـن الـحـديـث</span> تقدم خدمات متميزة تشمل الجودة العالية، استخدام أفضل المواد، تصاميم عصرية-->
                    <!--تناسب جميع الأذواق، تنفيذ دقيق واحترافي، التزام تام بالمواعيد، أسعار تنافسية، وحلول شاملة لتجديد-->
                    <!--المساحات الداخلية والخارجية.-->
                </p>
            </div>
        </div>
    </div>
</section>






<!-- Experience & Skills Section -->
<section id="skills" class="py-20 bg-primary text-white relative overflow-hidden border-t-4 border-accent">
    <div class="absolute inset-0 bg-[url('assets/img/cubes.png')] opacity-10"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="reveal">
                <span class="text-accent font-bold uppercase tracking-wider block mb-2">خبراتنا</span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    لماذا تختار الفـن الـحـديـث؟
                </h2>
                <p class="text-gray-300 mb-8 leading-relaxed">
                    نمتلك سجلاً حافلاً من الإنجازات والخبرات المتراكمة التي تجعلنا
                    الخيار الأمثل لمشروعك القادم. نجمع بين الدقة الـفـنـية والإبداع
                    المعماري.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 mb-8">
                    <a href="{{ route('about') }}"
                        class="inline-flex items-center gap-2 text-accent font-bold hover:text-white transition-colors group">
                        اكتشف المزيد من مهاراتنا
                        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Skills Progress -->
                <div class="space-y-6">
                    @foreach ($skills as $skill)
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-bold">{{ $skill->name }}</span>
                            <span class="text-accent">{{ $skill->percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            <div class="progress-bar bg-accent h-2.5 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ trim($skill->percentage) }}%" data-width="95"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 reveal counter-container">
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-paint-roller text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="{{ $totalprojects }}" data-suffix="+">
                        {{ $totalprojects }}
                    </div>
                    <div class="text-gray-300">اعمال مكتملة</div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-users text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="24" data-suffix="+">
                        0
                    </div>
                    <div class="text-gray-300">معلمين متميزين</div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-award text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="8">
                        0
                    </div>
                    <div class="text-gray-300">سـنـوات الـخـبـرة</div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-smile text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="100" data-suffix="%">
                        0
                    </div>
                    <div class="text-gray-300">رضا العملاء</div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- CTA Section -->
<section class="py-20 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('assets/img/cubes.png')] opacity-10"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">هل لديك مشروع تود تحويلة الى تحفة فنيه؟</h2>
        <p class="text-gray-300 text-lg mb-10 max-w-2xl mx-auto">نحن هنا لمساعدتك في تحويل رؤيتك إلى واقع. تواصل
            معا أفضل معلم دهانات وديكورات في جدة
            اليوم للحصول على استشارة مجانية وعرض سعر.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}"
                class="bg-accent hover:bg-yellow-600 text-white px-8 py-4 rounded-lg font-bold transition-all shadow-lg hover:shadow-accent/50 transform hover:-translate-y-1">
                تواصل معنا الآن
            </a>
            <a href="{{ route(name: 'projects.index') }}"
                class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white px-8 py-4 rounded-lg font-bold transition-all hover:shadow-lg transform hover:-translate-y-1">
                شاهد أعمالنا
            </a>
        </div>
    </div>
</section>

@endsection