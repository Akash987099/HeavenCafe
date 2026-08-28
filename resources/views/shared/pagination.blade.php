@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-shell">
        <div class="pagination-meta">
            Showing
            @if ($paginator->firstItem())
                <strong>{{ $paginator->firstItem() }}</strong>
                to
                <strong>{{ $paginator->lastItem() }}</strong>
            @else
                <strong>{{ $paginator->count() }}</strong>
            @endif
            of
            <strong>{{ $paginator->total() }}</strong>
            results
        </div>

        <div class="pagination-actions">
            @if ($paginator->onFirstPage())
                <span class="pagination-link is-disabled">&laquo; Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link">&laquo; Previous</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-link is-active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-link"
                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link">Next &raquo;</a>
            @else
                <span class="pagination-link is-disabled">Next &raquo;</span>
            @endif
        </div>
    </nav>
@endif
