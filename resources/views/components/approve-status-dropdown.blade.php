@props(['status', 'item'])

<div class="dropdown">
    <button
        class="btn btn-{{ strtolower($status) == 'success' ? '' : (strtolower($status) == '' ? 'danger' : '') }} dropdown-toggle"
        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        @if (strtolower($status) == 'pending')
            รออนุมัติ
        @elseif(strtolower($status) == 'failed')
            ล้มเหลว
        @elseif(strtolower($status) == 'canceled')
            ยกเลิก
        @elseif(strtolower($status) == 'processed')
            กำลังดำเนินการ
        @elseif(strtolower($status) == 'processing')
            เตรียมจัดส่ง
        @elseif(strtolower($status) == 'shipped')
            ส่งสินค้าแล้ว
        @elseif(strtolower($status) == 'refunded')
            คืนเงิน
        @elseif(strtolower($status) == 'complete')
            เสร็จสมบูรณ์
        @elseif(strtolower($status) == 'expired')
            หมดอายุ
        @else
            รออนุมัติ
        @endif
    </button>

    @if (strtolower($status) != 'complete' && strtolower($status) != 'canceled')
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item status-item" data-item="{{ $item }}" data-status="pending">รออนุมัติ</a>
            </li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}"
                    data-status="processed">กำลังดำเนินการ</a></li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}"
                    data-status="processing">เตรียมจัดส่ง</a></li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}"
                    data-status="shipped">ส่งสินค้าแล้ว</a>
            </li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}" data-status="canceled">ยกเลิก</a>
            </li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}" data-status="refunded">คืนเงิน</a>
            </li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}"
                    data-status="complete">เสร็จสมบูรณ์</a>
            </li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}" data-status="failed"> ล้มเหลว</a>
            </li>
            <li><a class="dropdown-item status-item" data-item="{{ $item }}" data-status="expired">หมดอายุ</a>
            </li>
        </ul>
    @endif
</div>
