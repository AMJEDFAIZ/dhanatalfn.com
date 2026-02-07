@php
    /*
    |--------------------------------------------------------------------------
    | بيانات أساسية مشتركة
    |--------------------------------------------------------------------------
    | لا تقم بتغيير أسماء المفاتيح هنا إلا إذا كنت تعرف ماذا تفعل.
    */
    $logo = isset($settings['site_logo']) && $settings['site_logo']
        ? asset('storage/' . $settings['site_logo'])
        : asset('assets/img/logo.png');

    $phone = $settings['phone'] ?? '+966532791522';

    $organization = [
        "@type" => "HomeAndConstructionBusiness",
        "@id" => url('/').'#organization',
        "name" => $settings['site_name'] ?? 'معلم دهانات وديكورات جدة',
        "url" => url('/'),
        "logo" => $logo,
        "image" => $logo,
        "description" => $settings['meta_description'] ?? 'معلم دهانات وديكورات جدة لتنفيذ جميع أعمال الدهانات والديكورات الحديثة',
        "telephone" => $phone,
        "priceRange" => "SAR",
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $settings['address'] ?? 'جدة حي الروضة',
            "addressLocality" => 'جدة',
            "addressRegion" => 'مكة المكرمة',
            "addressCountry" => 'SA',
        ],
        "geo" => [
            "@type" => "GeoCoordinates",
            "latitude" => isset($settings['latitude']) ? (float)$settings['latitude'] : 21.567355,
            "longitude" => isset($settings['longitude']) ? (float)$settings['longitude'] : 39.1925,
        ],
        "openingHoursSpecification" => [
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
                "opens" => $settings['opens'] ?? "07:00",
                "closes" => $settings['closes'] ?? "22:00",
            ]
        ],
        "sameAs" => array_values(array_filter([
            $settings['whatsapp'] ?? null,
            $settings['facebook'] ?? null,
            $settings['instagram'] ?? null,
        ])),
        "contactPoint" => [
            [
                "@type" => "ContactPoint",
                "telephone" => $phone,
                "contactType" => "customer service",
                "areaServed" => $settings['addressLocality'] ?? "SA",
                "availableLanguage" => ["Arabic"]
            ]
        ]
    ];

    /*
    |--------------------------------------------------------------------------
    | نبدأ بناء الـ schema العام
    |--------------------------------------------------------------------------
    */
    $schema = [
        "@context" => "https://schema.org",
    ];


    /*
    |--------------------------------------------------------------------------
    | 1) الصفحة الرئيسية
    | شرط يعتمد على اسم الراوت أو على مسار الجذر كاحتياط
    |--------------------------------------------------------------------------
    */
    if (request()->routeIs('home') || request()->is('/')) {
        $schema['@graph'] = [$organization];
    }

    /*
    |--------------------------------------------------------------------------
    | 2) صفحة عرض جميع الخدمات (Services list)
    | نتعامل مع الحالات: $services قد تكون Paginator أو Collection أو Array
    |--------------------------------------------------------------------------
    */
    elseif (request()->routeIs('services.index') || (isset($services) && count($services) > 0 && !isset($service))) {
        // نجمع عنصر Service لكل خدمة لإدراجها ضمن hasPart
        $servicesSchema = [];
        $position = 1;

        // استخدم اسم محلي مختلف داخل الحلقة لتجنب تعارض $service في الـ view
        foreach ($services as $svc) {
            // حماية: تأكد من وجود الخصائص الأساسية
            if (is_object($svc) && isset($svc->title) && (isset($svc->slug) || isset($svc->id))) {
                $slugOrId = isset($svc->slug) ? $svc->slug : (isset($svc->id) ? $svc->id : null);
                $servicesSchema[] = [
                    "@type" => "Service",
                    "name" => $svc->title,
                    "url" => isset($svc->slug) ? route('services.show', $svc->slug) : url('/services/' . $slugOrId),
                    "position" => $position++
                ];
            }
        }

        $schema['@graph'] = [
            $organization,
            [
                "@type" => "CollectionPage",
                "name" => $settings['services_page_title'] ?? "جميع خدمات الدهانات والديكورات",
                "url" => url()->current(),
                "hasPart" => $servicesSchema
            ]
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 3) صفحة خدمة فردية (Service show)
    | يعتمد على راوت services.show أو وجود المتغير $service ككائن
    |--------------------------------------------------------------------------
    */
    elseif (request()->routeIs('services.show') || (isset($service) && is_object($service))) {
        // تأكد أن $service كائن صالح
        if (!isset($service) || !is_object($service)) {
            // إذا لم يكن $service صالحًا، فقط أطبع المؤسسة لتجنب خطأ
            $schema['@graph'] = [$organization];
        } else {
            $schema['@graph'] = [
                $organization,
                [
                    "@type" => "Service",
                    "name" => $service->title,
                    "url" => url()->current(),
                    "description" => $service->meta_description ?? (isset($service->description) ? strip_tags($service->description) : $organization['description']),
                    "provider" => [
                        "@type" => "HomeAndConstructionBusiness",
                        "@id" => $organization['@id'],
                        "name" => $organization['name'],
                    ],
                    "image" => !empty($service->image_path) ? asset('storage/' . $service->image_path) : $logo,
                ]
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4) صفحة عرض جميع المشاريع (Projects list)
    | راوت مفترض: projects.index
    | نتعامل مع $projects كـ Collection / Paginator / Array
    |--------------------------------------------------------------------------
    */
    elseif (request()->routeIs('projects.index') || (isset($projects) && count($projects) > 0 && !isset($project))) {
        $projectsSchema = [];
        $position = 1;

        foreach ($projects as $prj) {
            if (is_object($prj) && isset($prj->title) && (isset($prj->slug) || isset($prj->id))) {
                $slugOrId = isset($prj->slug) ? $prj->slug : (isset($prj->id) ? $prj->id : null);
                $projectsSchema[] = [
                    "@type" => "CreativeWork", // نستخدم CreativeWork للمشاريع
                    "name" => $prj->title,
                    "url" => isset($prj->slug) ? route('projects.show', $prj->slug) : url('/projects/' . $slugOrId),
                    "position" => $position++
                ];
            }
        }

        $schema['@graph'] = [
            $organization,
            [
                "@type" => "CollectionPage",
                "name" => $settings['projects_page_title'] ?? "جميع المشاريع",
                "url" => url()->current(),
                "hasPart" => $projectsSchema
            ]
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 5) صفحة مشروع فردي (Project show)
    | راوت مفترض: projects.show
    |--------------------------------------------------------------------------
    */
    elseif (request()->routeIs('projects.show') || (isset($project) && is_object($project))) {
        if (!isset($project) || !is_object($project)) {
            $schema['@graph'] = [$organization];
        } else {
            $projectImage = !empty($project->image_path) ? asset('storage/' . $project->image_path) : $logo;
            $schema['@graph'] = [
                $organization,
                [
                    "@type" => "CreativeWork",
                    "name" => $project->title,
                    "url" => url()->current(),
                    "description" => $project->meta_description ?? (isset($project->description) ? strip_tags($project->description) : $organization['description']),
                    "creator" => [
                        "@type" => $organization['@type'],
                        "@id" => $organization['@id'],
                        "name" => $organization['name']
                    ],
                    "image" => $projectImage
                ]
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 6) صفحات عادية / افتراضية
    |--------------------------------------------------------------------------
    */
    else {
        $schema['@graph'] = [$organization];
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
