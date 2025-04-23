@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 border border-gray-300 cursor-default leading-5 rounded-lg classic">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 border border-gray-300 leading-5 rounded-lg classic-clicked hover:text-gray-500 focus:outline-none active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="hidden sm:flex sm:items-center">
            <p class="text-sm text-gray-700 leading-5 inconsolata-regular">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 border border-gray-300 leading-5 rounded-lg classic-clicked hover:text-gray-500 focus:outline-none active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 border border-gray-300 cursor-default leading-5 rounded-lg classic">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
