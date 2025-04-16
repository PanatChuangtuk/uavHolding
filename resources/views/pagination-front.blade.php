<style>
    .page-link.arrow {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
    }

    .double-arrow {
        font-size: 14px;
        font-weight: bold;
    }

    .icons.A {
        width: 14px;
        height: auto;
    }
</style>

<style>
    .page-link.arrow {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
    }

    .double-arrow {
        font-size: 14px;
        font-weight: bold;
    }

    .icons.A {
        width: 14px;
        height: auto;
    }
</style>

@if ($items->lastPage() >= 1)
    <ul class="pagination">
        @php
            $prevTen = max(1, $items->currentPage() - 10);
        @endphp
        <li class="page-item {{ $items->currentPage() <= 10 ? 'disabled' : '' }}">
            <a class="page-link arrow prev-ten" href="{{ $items->url($prevTen) }}">
                << </a>
        </li>
        <li class="page-item {{ $items->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link arrow prev" href="{{ $items->onFirstPage() ? '#' : $items->previousPageUrl() }}">
                <img class="icons A svg-js" src="{{ asset('img/icons/icon-next.svg') }}" alt="">
            </a>
        </li>

        @if ($items->currentPage() > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $items->url(1) }}">1</a>
            </li>
        @endif

        @if ($items->currentPage() > 4)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif
        @for ($i = max(2, $items->currentPage() - 1); $i < $items->currentPage(); $i++)
            <li class="page-item">
                <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item active">
            <span class="page-link">{{ $items->currentPage() }}</span>
        </li>

        @for ($i = $items->currentPage() + 1; $i <= min($items->lastPage() - 5, $items->currentPage() + 3); $i++)
            <li class="page-item">
                <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($items->lastPage() - $items->currentPage() > 6)
            <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif

        @for ($i = max($items->lastPage() - 4, $items->currentPage() + 3); $i <= $items->lastPage(); $i++)
            <li class="page-item">
                <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ $items->currentPage() == $items->lastPage() ? 'disabled' : '' }}">
            <a class="page-link arrow next"
                href="{{ $items->currentPage() == $items->lastPage() ? '#' : $items->nextPageUrl() }}">
                <img class="icons A svg-js" src="{{ asset('img/icons/icon-next.svg') }}" alt="">
            </a>
        </li>
        @php
            $nextTen = min($items->lastPage(), $items->currentPage() + 10);
        @endphp
        <li class="page-item {{ $items->currentPage() + 10 > $items->lastPage() ? 'disabled' : '' }}">
            <a class="page-link arrow next-ten" href="{{ $items->url($nextTen) }}">
                >></a>
        </li>
    </ul>
@endif
