@props([
    'paginator',
    'showPerPage' => true,
    'perPageOptions' => [10, 25, 50, 100],
])

@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $total = $paginator->total();
        $perPage = $paginator->perPage();

        // Build page range
        if ($lastPage <= 7) {
            $pages = range(1, $lastPage);
        } else {
            $pages = [];
            $pages[] = 1;
            if ($currentPage > 4) {
                $pages[] = '...';
            }
            $start = max(2, $currentPage - 1);
            $end = min($lastPage - 1, $currentPage + 1);
            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $i;
            }
            if ($currentPage < $lastPage - 3) {
                $pages[] = '...';
            }
            $pages[] = $lastPage;
        }

        // Helper to build URL with query params
        $buildUrl = function ($page) use ($paginator) {
            $query = array_merge(request()->query(), ['page' => $page]);
            return $paginator->path() . '?' . http_build_query($query);
        };
    @endphp

    <nav {{ $attributes->class('flex items-center justify-between gap-4 flex-wrap pt-3') }} aria-label="Pagination">

        {{-- Left: info + per-page --}}
        <div class="flex items-center gap-4 text-sm text-neutral-600 dark:text-neutral-400">
            <span>
                Showing <span class="font-medium text-neutral-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                to <span class="font-medium text-neutral-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                of <span class="font-medium text-neutral-900 dark:text-white">{{ number_format($total) }}</span> results
            </span>

            @if ($showPerPage)
                <form method="GET" action="{{ $paginator->path() }}" class="flex items-center gap-2">
                    @foreach (request()->query() as $key => $value)
                        @if ($key !== 'per_page' && $key !== 'page')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="per-page-select" class="sr-only">Items per page</label>
                    <select
                        id="per-page-select"
                        name="per_page"
                        onchange="this.form.submit()"
                        class="text-xs rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 px-2 py-1.5 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 cursor-pointer"
                    >
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                {{ $option }} / page
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        {{-- Right: page buttons --}}
        <div
            class="flex items-center gap-0.5 border dark:border-white/10 border-neutral-900/10 rounded-lg p-1.5"
            role="navigation"
            aria-label="Page navigation"
        >
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <button type="button" disabled
                    class="flex justify-center items-center size-8 rounded-md text-neutral-300 dark:text-neutral-600 cursor-not-allowed"
                    aria-label="Previous page"
                >
                    <x-ui.icon name="chevron-left" class="size-4" />
                </button>
            @else
                <a href="{{ $buildUrl($currentPage - 1) }}"
                    class="flex justify-center items-center size-8 rounded-md text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 hover:text-neutral-900 dark:hover:text-white transition-colors"
                    aria-label="Previous page"
                >
                    <x-ui.icon name="chevron-left" class="size-4" />
                </a>
            @endif

            {{-- Page buttons --}}
            @foreach ($pages as $page)
                @if ($page === '...')
                    <div class="flex justify-center items-center size-8 text-xs font-medium text-neutral-400 dark:text-neutral-500">
                        ...
                    </div>
                @else
                    @if ($page == $currentPage)
                        <div class="flex justify-center items-center size-8 rounded-md text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                            {{ $page }}
                        </div>
                    @else
                        <a href="{{ $buildUrl($page) }}"
                            class="flex justify-center items-center size-8 rounded-md text-xs font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 hover:text-neutral-900 dark:hover:text-white transition-colors"
                            aria-label="Go to page {{ $page }}"
                        >
                            {{ $page }}
                        </a>
                    @endif
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $buildUrl($currentPage + 1) }}"
                    class="flex justify-center items-center size-8 rounded-md text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 hover:text-neutral-900 dark:hover:text-white transition-colors"
                    aria-label="Next page"
                >
                    <x-ui.icon name="chevron-right" class="size-4" />
                </a>
            @else
                <button type="button" disabled
                    class="flex justify-center items-center size-8 rounded-md text-neutral-300 dark:text-neutral-600 cursor-not-allowed"
                    aria-label="Next page"
                >
                    <x-ui.icon name="chevron-right" class="size-4" />
                </button>
            @endif
        </div>
    </nav>
@endif
