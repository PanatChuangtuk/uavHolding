@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .photo {
            width: 100px;
            flex-shrink: 0;
        }
    </style>
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.order') }}">Order</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    {{-- <form id="form-update" action="{{ route('administrator.order.update', $order->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf --}}
    {{-- <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.order') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div> --}}

    <div class="card p-4">
        <div class="mb-4 row">
            <label for="order_number" class="col-md-2 fw-bold">Order Number :</label>
            <div class="col-md-10">
                {{ $order->order_number }}
            </div>
        </div>



        {{-- <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">MemberID :</label>
            <div class="col-md-10">
                {{ $order->member_id }}
            </div>
        </div> --}}
        @if ($order->address)
            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Full Name :</label>
                <div class="col-md-10">
                    {{ $order->address->first_name }} {{ $order->address->last_name }}
                </div>
            </div>

            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Address :</label>
                <div class="col-md-10">
                    {{ $order->address->detail }}
                    {{ $order->member->addresses->first()->province->name_th }}
                    {{ $order->member->addresses->first()->amphure->name_th }}
                    {{ $order->member->addresses->first()->tambon->name_th }}
                    {{ $order->member->addresses->first()->postal_code }}

                </div>
            </div>

            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Email :</label>
                <div class="col-md-10">
                    {{ $order->address->email }}
                </div>
            </div>

            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Phone :</label>
                <div class="col-md-10">
                    {{ preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $order->address->mobile_phone) }}
                </div>
            </div>
        @endif

        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Detail Payment :</label>
            <div class="col-md-10">
                <a href="#" data-bs-toggle="modal" data-bs-target="#detailPaymentModal">
                    Click Detail
                </a>

            </div>
        </div>

        @if ($order->po_number)
            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">PO Number :</label>
                <div class="col-md-10">
                    @if ($order->orderPo && $order->orderPo->image)
                        <a href="{{ asset('upload/file/order_po/' . basename($order->orderPo->image ?? '')) }}" download>
                            {{ $order->po_number }}
                        </a>
                    @else
                        {{ $order->po_number }}
                    @endif
                </div>
            </div>
        @endif

        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Status Order :</label>
            <div class="col-md-10">
                <x-approve-order-dropdown :status="$order->status" :item="$order->id" /></span>
                {{-- <span
                    class="badge 
            @if ($order->status === 'Approve') bg-success text-white 
            @elseif ($order->status === 'Cancel') 
                bg-danger text-white 
            @else 
                bg-warning text-dark @endif 
            rounded-pill px-3 py-2 shadow-sm text-uppercase fw-semibold">
                    @if ($order->status == 'Approve')
                        อนุมัติ
                    @elseif($order->status == 'Cancel')
                        ยกเลิก
                    @else
                        รออนุมัติ
                    @endif
                </span> --}}
            </div>
        </div>

        {{-- <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Status Payment :</label>
            <div class="col-md-10">
                @if ($order->orderPayments && $order->orderPayments->first())
                    <span
                        class="badge  @if ($order->orderPayments->first()?->payment_status === 'success') bg-success text-white 
                        @elseif ($order->orderPayments->first()?->payment_status === 'fail') 
                        bg-danger text-white 
                        @else bg-warning text-dark @endif rounded-pill px-3 py-2 shadow-sm text-uppercase fw-semibold">
                        @if ($order->orderPayments->first()?->payment_status == 'success')
                            อนุมัติ
                        @elseif($order->orderPayments->first()?->payment_status == 'fail')
                            ยกเลิก
                        @else
                            รออนุมัติ
                        @endif
                    </span>
                @else
                    ไม่มีข้อมูลการชำระเงิน
                @endif

                
            </div>
        </div> --}}{{-- {{ $order->orderPayments->first()->payment_status }} --}}
        @if ($order->orderPayments && $order->orderPayments->first())
            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Reference :</label>
                <div class="col-md-10">
                    {{ $order->orderPayments->first()->reference }}
                </div>
            </div>
        @endif

        @if ($order->tracking_no)
            <div class="mb-4 row">
                <label for="order_number" class="col-md-2 fw-bold">Tracking No :</label>
                <div class="col-md-10" style="color: rgb(98, 98, 233);">
                    @foreach ($tracking as $item)
                        {{ $item->tracking_no }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @php
            use App\Models\Tracking;
            $totalTracking = 0;
            $totalQuantity = 0;
            foreach ($order->orderProducts as $orderProduct) {
                $tracking = Tracking::where('order_id', $order->id)
                    ->where('order_product_id', $orderProduct->id)
                    ->sum('shipmentQty');
                $totalTracking += $tracking;
                $totalQuantity += $orderProduct->quantity;
            }

            $numberOrderW = $totalQuantity - $totalTracking;
        @endphp

        @if ($totalTracking > 0 && $totalQuantity !== $totalTracking)
            <div class="mb-4 row">
                <label for="order_number" class="col-md-2 fw-bold" style="color: #198754;">อนุมัติการจัดส่ง :</label>
                <div class="col-md-10" style="color: #198754;">
                    {{ $totalTracking }} ชิ้น
                </div>
            </div>
        @elseif ($totalTracking > 0)
            <div class="mb-4 row">
                <label for="order_number" class="col-md-2 fw-bold">อนุมัติการจัดส่ง :</label>
                <div class="col-md-10" style="color: #198754;">
                    ส่งสินค้าครบแล้ว
                </div>
            </div>
        @endif

        @if ($totalTracking > 0 && $numberOrderW !== 0)
            <div class="mb-4 row">
                <label for="order_number" class="col-md-2 fw-bold" style="color:#FF3E40;">รอการจัดส่งอีก
                    :</label>
                <div class="col-md-10" style="color:  #FF3E40;">
                    {{ $numberOrderW }} ชิ้น
                </div>
            </div>
        @endif

        {{-- <div class="row mb-4">
                <label class="col-md-2 " for="status">Status</label>
                <div class="col-md-10 d-flex align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" value="1" name="status"
                            {{ $order->status == 1 ? 'checked' : '0' }} />
                    </div>
                </div>
            </div> --}}

        <div class="card p-4">
            <h4 class="display-4">Product</h3>
                <div class="table">
                    <table class="table table-bordered border-light">
                        <thead>
                            <tr>
                                <th class="text-center"> </th>
                                {{-- <th class="text-center">Name</th> --}}
                                <th class="text-center">Product</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">UnitPrice</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Order Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="orderTableBody">
                            @foreach ($order->orderProducts as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        <img class ="photo"
                                            src="{{ asset($item->product?->productModel?->productBrand?->image ? 'upload/file/product_brand/' . $item->product->productModel->productBrand->image : 'upload/404.jpg') }}"
                                            alt="" />
                                    </td>
                                    {{-- @php
                                        $searchedItem = collect($unitOfMeasureIds)
                                            ->where(
                                                'unitOfMeasureCode',
                                                $item->product->productSizes->first()->productUnitValue->name,
                                            )
                                            ->first();
                                    @endphp --}}
                                    {{-- ERP UOM No.:{{ $searchedItem['unitOfMeasureId'] ?? 'N/A' }} --}}

                                    <td>
                                        {{ $item->name }}<br>
                                        ERP Item No.: {{ $item->product->item_id ?? 'N/A' }}<br>
                                        ERP UOM
                                        No.:
                                        {{ $item->product->productSizes->first()->productUnitValue->uom_id ?? 'N/A' }}
                                        <br>
                                        @php
                                            $tracking = \App\Models\Tracking::where('order_id', $item->order_id)
                                                ->where('order_product_id', $item->id)
                                                ->get();

                                            $initialQuantity = $item->quantity;
                                            $remainingQuantity = $initialQuantity;
                                        @endphp

                                        @foreach ($tracking as $index => $itemTracking)
                                            <span class="fw-bold">
                                                Shipment {{ $index + 1 }} :
                                            </span>
                                            <span style="color: #198754;">
                                                อนุมัติการจัดส่ง :
                                                {{ $itemTracking->shipmentQty }} ชิ้น
                                            </span>
                                            @php
                                                $remainingQuantity -= $itemTracking->shipmentQty;
                                            @endphp

                                            @if ($remainingQuantity > 0)
                                                <span class="ms-2 text-end" style="max-width: 200px; color:  #FF3E40;">
                                                    รอการจัดส่งอีก: {{ $remainingQuantity }} ชิ้น
                                                </span>
                                            @else
                                                <span class="ms-2 text-end" style="color: #198754; max-width: 200px;">
                                                    ส่งสินค้าครบแล้ว
                                                </span>
                                            @endif
                                        @endforeach
                                        {{-- @php
                                            $tracking = \App\Models\Tracking::where('order_id', $item->order_id)
                                                ->where('order_product_id', $item->id)
                                                ->sum('shipmentQty');

                                            $numberOrderW = $item->quantity - $tracking;
                                        @endphp

                                        @if ($tracking && $item->quantity !== $tracking)
                                            <span style="color: #198754;">อนุมัติการจัดส่ง :
                                                {{ $tracking }}
                                                ชิ้น</span>
                                        @else
                                        @endif
                                        @if ($tracking && $numberOrderW !== 0)
                                            <span class="text-danger ms-2 text-end" style="max-width: 200px;">รอการจัดส่งอีก
                                                : {{ $numberOrderW }} ชิ้น</span>
                                        @elseif ($tracking)
                                            <span class=" ms-2 text-end"
                                                style="color: #198754; max-width: 200px;">ส่งสินค้าครบแล้ว</span>
                                        @endif --}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $item->sku }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $item->product->productSizes->first()->productUnitValue->name }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ number_format($item->product->productPrices->first()->price, 2) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ number_format($item->product->productPrices->first()->price * $item->quantity, 2) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <x-approve-status-dropdown :status="$item->status_product" :item="$item->id" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>

        <div class="row justify-content-end">
            <div class="p-4" style="max-width: 400px;">
                <div class="mb-2 row">
                    <label for="total" class="col-md-5 fw-bold">Subtotal :</label>
                    <div class="col-md-6 text-end ">
                        <span>฿ {{ number_format($order->subtotal, 2, '.', ',') }}</span>
                    </div>
                </div>

                <div class="mb-2 row">
                    <label for="total" class="col-md-5 fw-bold">Discount :</label>
                    <div class="col-md-6 text-end ">
                        <span>฿ {{ number_format($order->discount, 2, '.', ',') }}</span>
                    </div>
                </div>

                <div class="mb-2 row">
                    <label for="total" class="col-md-5 fw-bold">ShippingFree :</label>
                    <div class="col-md-6 text-end ">
                        <span>฿ {{ number_format($order->shipping_free, 2, '.', ',') }}</span>
                    </div>
                </div>

                <div class="mb-2 row">
                    <label for="total" class="col-md-5 fw-bold">Vat7% :</label>
                    <div class="col-md-6 text-end ">
                        <span>฿ {{ number_format($order->vat, 2, '.', ',') }}</span>
                    </div>
                </div>

                <div class="mb-2 row">
                    <label for="total" class="col-md-5 fw-bold">Total Amount :</label>
                    <div class="col-md-6 text-end ">
                        <span>฿ {{ number_format($order->total, 2, '.', ',') }}</span>
                    </div>
                </div>
            </div>
        </div>



    </div>
    {{-- </form> --}}

    <div class="modal fade" id="detailPaymentModal" tabindex="-1" aria-labelledby="detailPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow-lg border-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPaymentModalLabel">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Payment method :</strong>
                        @if ($order->orderPayments && $order->orderPayments->first())
                            @if ($order->orderPayments->first()->cart_type == 'V')
                                Visa Card
                            @elseif($order->orderPayments->first()->cart_type == 'PP')
                                PromptPay
                            @else
                                {{ $order->orderPayments->first()->cart_type }}
                            @endif
                        @else
                            ไม่มีข้อมูลการชำระเงิน
                        @endif
                    </p>
                    @if ($order->orderPayments && $order->orderPayments->first())
                        <p><strong>Payment date/time :</strong> {{ $order->orderPayments->first()->created_at }}
                        </p>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.dropdown-item', function() {
            var status = $(this).data('status');
            var item = $(this).data('item');
            $.ajax({
                url: '{{ route('administrator.approve-order.update') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item: item,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            text: 'อัปเดตสถานะสำเร็จ',
                            confirmButtonText: 'OK'
                        });
                    }
                },
            });
        });
    </script>
    <script>
        $(document).on('click', '.dropdown-item', function() {
            var status = $(this).data('status');
            var button = $(this).closest('.dropdown').find('button');

            if (status === 'approve') {
                button.text('เสร็จสมบูรณ์');
            } else if (status === 'cancel') {
                button.text('ยกเลิก');
            } else if (status === 'processed') {
                button.text('กำลังดำเนินการ');
            } else if (status === 'delivery') {
                button.text('เตรียมจัดส่ง');
            }
            // else {
            //     button.text('รออนุมัติ');
            // }
        });
    </script>
    {{-- <script>
        $(document).on('click', '.dropdown-item', function() {
            var status = $(this).data('status');
            var item = $(this).data('item');
            $.ajax({
                url: '{{ route('administrator.order.update') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item: item,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            text: 'อัปเดตสถานะสำเร็จ',
                            confirmButtonText: 'OK'
                        });
                    }
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.dropdown-item').on('click', function() {
                var status = $(this).data('status');
                var button = $(this).closest('.dropdown').find('button');
                 if (status === 'approve') {
                button.text('อนุมัติ');
            } else if (status === 'cancel') {
                button.text('ยกเลิก');
            } else if (status === 'delivery') {
                button.text('เตรียมจัดส่ง ');
            }
            });
        });
    </script> --}}
    <script>
        $(document).on('click', '.status-item', function() {
            var status = $(this).data('status');
            var item = $(this).data('item');
            $.ajax({
                url: '{{ route('administrator.order.status.update') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item: item,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            text: 'อัปเดตสถานะสำเร็จ',
                            confirmButtonText: 'OK'
                        });
                    }
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.status-item').on('click', function() {
                var status = $(this).data('status');
                var button = $(this).closest('.dropdown').find('button');
                var statusMap = {
                    'canceled': 'ยกเลิก',
                    'failed': 'ล้มเหลว',
                    'pending': 'รออนุมัติ',
                    'processed': 'กำลังดำเนินการ',
                    'processing': 'เตรียมจัดส่ง',
                    'shipped': 'ส่งสินค้าแล้ว',
                    'refunded': 'คืนเงิน',
                    'complete': 'เสร็จสมบูรณ์',
                    'expired': 'หมดอายุ'
                };

                button.text(statusMap[status] || 'รออนุมัติ');
            });
        });
    </script>
@endsection
