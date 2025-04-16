@props(['status', 'item'])

<div class="dropdown">
    <button
        class="btn w-10 btn-{{ strtolower($status) == 'approve' ? '' : (strtolower($status) == 'cancel' ? '' : '') }} dropdown-toggle"
        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 14px;">
        @if (strtolower($status) == 'approve')
            เสร็จสมบูรณ์
        @elseif (strtolower($status) == 'delivery')
            ส่งสินค้าแล้ว
        @elseif(strtolower($status) == 'processed')
            กำลังดำเนินการ
        @elseif(strtolower($status) == 'cancel')
            ยกเลิก

            {{-- @else
            รออนุมัติ --}}
        @endif
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        {{-- <li><a class="dropdown-item" data-item="{{ $item }}" data-status="waiting approve"
                style="font-size: 14px;">รออนุมัติ</a></li> --}}
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="processed"
                style="font-size: 14px;">กำลังดำเนินการ</a></li>
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="delivery"
                style="font-size: 14px;">เตรียมจัดส่ง</a></li>
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="approve"
                style="font-size: 14px;">เสร็จสมบูรณ์</a></li>
        <li><a class="dropdown-item" data-item="{{ $item }}" data-status="cancel"
                style="font-size: 14px;">ยกเลิก</a></li>

    </ul>
</div>
