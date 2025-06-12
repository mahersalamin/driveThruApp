@if ($paginator->hasPages())
    <nav>
        <ul style="display: flex; list-style: none; padding: 0; gap: 6px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li style="color: #999;">&laquo;</li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" style="text-decoration: none; color: #D32F2F;">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li style="color: #999;">{{ $element }}</li>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li style="font-weight: bold; color: black;">{{ $page }}</li>
                        @else
                            <li><a href="{{ $url }}" style="text-decoration: none; color: #D32F2F;">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" style="text-decoration: none; color: #D32F2F;">&raquo;</a></li>
            @else
                <li style="color: #999;">&raquo;</li>
            @endif
        </ul>
    </nav>
@endif
