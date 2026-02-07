{{-- resources/views/pagination/custom.blade.php --}}



@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-8">
        <ul class="flex items-center space-x-2 space-x-reverse"> {{-- زر الصفحة السابقة --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 border border-gray-200 rounded cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-accent hover:bg-accent transition duration-150 ease-in-out">
                       <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            @endif

            {{-- أرقام الصفحات --}}
            {{-- هذا الجزء يقوم بإنشاء مصفوفة الأرقام --}}
            @foreach ($elements as $element)

                {{-- فاصل "..." عند وجود صفحات كثيرة --}}
                @if (is_string($element))
                    <li>
                        <span class="px-4 py-2 text-gray-500 border border-transparent">{{ $element }}</span>
                    </li>
                @endif

                {{-- مصفوفة الروابط --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            {{-- الصفحة الحالية (نشطة) --}}
                            <li>
                                <span class="px-4 py-2 text-white bg-accent border bg-accent rounded shadow-sm">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            {{-- الصفحات الأخرى --}}
                            <li>
                                <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-accent hover:bg-accent transition duration-150 ease-in-out">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- زر الصفحة التالية --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-accent hover:bg-accent transition duration-150 ease-in-out">
                       <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
            @else
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 border border-gray-200 rounded cursor-not-allowed">
                       <i class="fa-solid fa-chevron-left"></i>
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif


