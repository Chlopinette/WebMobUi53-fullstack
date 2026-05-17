@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between font-black uppercase">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-6 py-3 bg-gray-300 text-black/50 border-4 border-black shadow-[4px_4px_0px_black] cursor-not-allowed">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-6 py-3 bg-white text-black border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-6 py-3 bg-white text-black border-4 border-black shadow-[4px_4px_0px_black] hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="px-6 py-3 bg-gray-300 text-black/50 border-4 border-black shadow-[4px_4px_0px_black] cursor-not-allowed">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
