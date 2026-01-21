@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <div class="flex justify-between gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   class="px-2 py-1 rounded-md bg-[#30AFBB] text-white hover:bg-[#269aa6]">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden md:flex">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2 py-1">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-2 py-1 rounded-md bg-gray-100 font-semibold">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="px-2 py-1 rounded-md hover:bg-gray-100">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   class="px-2 py-1 rounded-md bg-[#30AFBB] text-white hover:bg-[#269aa6]">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
