@props(['status', 'item'])

<div class="dropdown">
    <button
        class="btn btn-{{ strtolower($status) == 'success' ? '' : (strtolower($status) == 'fail' ? '' : '') }} dropdown-toggle"
        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        @if (strtolower($status) == 'success')
            อนุมัติ
        @elseif(strtolower($status) == 'fail')
            ยกเลิก
        @else
            รออนุมัติ
        @endif
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="pending">รออนุมัติ</a></li>
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="success">อนุมัติ</a></li>
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="fail">ยกเลิก</a></li>
    </ul>
</div>
