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
        <li class="breadcrumb-item"><a href="{{ route('administrator.approve-order') }}">Approve-order</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    {{-- <form id="form-update" action="{{ route('administrator.approve-order.update', $order->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf --}}
    {{-- <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.approve-order') }}" class="btn btn-danger">Cancel</a>
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

        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Full Name :</label>
            <div class="col-md-10">
                {{ $order->address->first_name ?? $order->member->info->first_name }}
                {{ $order->address->last_name ?? $order->member->info->last_name }}
            </div>
        </div>

        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Address :</label>
            <div class="col-md-10">
                @if ($order->address)
                    {{ $order->address->detail }}
                    {{ $order->member->addresses->first()->province->name_th }}
                    {{ $order->member->addresses->first()->amphure->name_th }}
                    {{ $order->member->addresses->first()->tambon->name_th }}
                    {{ $order->member->addresses->first()->postal_code }}
                @else
                    {{ $order->member->addresses->where('is_default', 1)->first()->detail }}
                    {{ $order->member->addresses->where('is_default', 1)->first()->province->name_th }}
                    {{ $order->member->addresses->where('is_default', 1)->first()->amphure->name_th }}
                    {{ $order->member->addresses->where('is_default', 1)->first()->tambon->name_th }}
                    {{ $order->member->addresses->where('is_default', 1)->first()->postal_code }}
                @endif
            </div>
        </div>

        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Email :</label>
            <div class="col-md-10">
                {{ $order->address->email ?? $order->member->email }}
            </div>
        </div>

        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Phone :</label>
            <div class="col-md-10">
                {{ preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $order->address->mobile_phone ?? $order->member->mobile_phone) }}
            </div>
        </div>

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

        @if ($order->orderPayments && $order->orderPayments->first())
            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Reference :</label>
                <div class="col-md-10">
                    {{ $order->orderPayments->first()->reference }}
                </div>
            </div>
        @endif
        <div class="mb-4 row">
            <label for="member_id" class="col-md-2 fw-bold">Status Payment :</label>
            <div class="col-md-10">
                @if ($order->orderPayments && $order->orderPayments->first())
                    <x-approve-orderstatus-dropdown :status="$order->orderPayments->first()?->payment_status" :item="$order->id" />
                @else
                    <span style="color: red;">ไม่มีข้อมูลการชำระเงิน</span>
                @endif

                {{-- {{ $order->orderPayments->first()->payment_status }} --}}
            </div>
        </div>
        @if (!$order->orderPayments)
            <div class="mb-4 row">
                <label for="member_id" class="col-md-2 fw-bold">Reference :</label>
                <div class="col-md-10">
                    {{ $order->orderPayments->first()->reference ?? null }}
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
                                <th class="text-center">Product</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">UnitPrice</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="orderTableBody">
                            @foreach ($order->orderProducts as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        <img class ="photo"
                                            src="{{ asset($item->product->productModel->productBrand->image ? 'upload/file/product_brand/' . $item->product->productModel->productBrand->image : 'upload/404.jpg') }}"
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
                                        ERP UOM No.:
                                        {{ $item->product->productSizes->first()->productUnitValue->uom_id ?? 'N/A' }}
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
                @if ($order->orderPayments && $order->orderPayments->first())
                    <div class="modal-body">
                        <p><strong>Payment method :</strong>
                            @if ($order->orderPayments->first()->cart_type == 'V')
                                Visa Card
                            @elseif($order->orderPayments->first()->cart_type == 'PP')
                                PromptPay
                            @else
                                {{ $order->orderPayments->first()->cart_type }}
                            @endif
                        </p>
                        <p><strong>Payment date/time :</strong> {{ $order->orderPayments->first()->created_at }}</p>
                    </div>
                @elseif($order->type === 'po')
                    <div class="modal-body">
                        <p><strong>Payment method :</strong>
                            {{ $order->type }}</p>
                        <p><strong>Po Number:</strong>
                            {{ $order->po_number }}</p>
                        <p><strong>Payment date/time :</strong>
                            {{ $order->created_at }}</p>
                    </div>
                @else
                    <div class="modal-body">
                        <p><strong>Payment method :</strong>
                            รอการชำระเงิน</p>
                    </div>
                @endif
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

                if (status == 'success') {
                    button.text('อนุมัติ');
                } else if (status == 'fail') {
                    button.text('ยกเลิก');
                } else {
                    button.text('รออนุมัติ');
                }
            });
        });
    </script>
@endsection
