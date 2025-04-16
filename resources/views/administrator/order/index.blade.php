@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .bg-custom {
            background-color: #5D6A85 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <!-- Order List Widget -->
                    {{-- <div class="card mb-3"> --}}
                    {{-- <div class="card-body"> --}}
                    <div class="row g-3 justify-content-center">
                        <!-- Pending Payment -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-center p-4 bg-white border rounded-3 shadow-sm hover-shadow">
                                <div>
                                    <h4 class="mb-0 fs-5">{{ $pending->count() }}</h4>
                                    <p class="mb-0 fs-6 text-secondary">Pending Payment</p>
                                </div>
                                <span
                                    class="avatar w-px-45 h-px-45 bg-label-warning rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-calendar fs-4 text-warning"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Completed -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-center p-4 bg-white border rounded-3 shadow-sm hover-shadow">
                                <div>
                                    <h4 class="mb-0 fs-5">{{ $success->count() }}</h4>
                                    <p class="mb-0 fs-6 text-secondary">Completed</p>
                                </div>
                                <span
                                    class="avatar w-px-45 h-px-45 bg-label-success rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-check-double fs-4 text-success"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Failed -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-center p-4 bg-white border rounded-3 shadow-sm hover-shadow">
                                <div>
                                    <h4 class="mb-0 fs-5">{{ $fail->count() }}</h4>
                                    <p class="mb-0 fs-6 text-secondary">Failed</p>
                                </div>
                                <span
                                    class="avatar w-px-45 h-px-45 bg-label-danger rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-error-alt fs-4 text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                {{-- Header --}}
                <div class="p-3">
                    <form action="{{ route('administrator.order') }}" method="GET">
                        <div class="d-flex justify-content-between align-items-center">
                            <x-search />
                            <div class="flex-grow-2">
                                <select class="form-select" id="statusFilter" name="status" onchange="this.form.submit()">
                                    <option value="" {{ request()->input('status') == '' ? 'selected' : '' }}>
                                        Filter by Payment Status</option>
                                    <option value="Processed"
                                        {{ request()->input('status') == 'Processed' ? 'selected' : '' }}>
                                        กำลังดำเนินการ</option>
                                    <option value="Delivery"
                                        {{ request()->input('status') == 'Delivery' ? 'selected' : '' }}>
                                        เตรียมจัดส่ง</option>
                                    <option value="Approve" {{ request()->input('status') == 'Approve' ? 'selected' : '' }}>
                                        เสร็จสมบูรณ์
                                    </option>
                                    <option value="Cancel" {{ request()->input('status') == 'Cancel' ? 'selected' : '' }}>
                                        ยกเลิก</option>
                                </select>
                            </div>

                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="table-nowrap ">
                    <table class="table table-hover">
                        <thead>
                            <tr>

                                <th class="text-center">Order</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Order Price</th>
                                <th class="text-center">Order date</th>
                                <th class="text-center">Approve date</th>
                                <th class="text-center">Order Status</th>
                                <th class="text-center">Payment Status</th>
                                <th class="text-center"> </th>
                            </tr>
                        </thead>

                        <tbody class="table-border-bottom-0 " id="orderTableBody">
                            @foreach ($orders as $item)
                                <tr>
                                    <td class="text-center  align-middle ">{{ $item->order_number }}</td>
                                    <td class="text-start">
                                        @if ($item->address)
                                            <div class="text-center  align-middle">
                                                <div class="flex-grow-1">
                                                    <strong class="d-block">
                                                        {{ $item->address->first_name }} |
                                                        {{ $item->address->last_name }}
                                                    </strong>
                                                    <span class="text-muted small">
                                                        {{ $item->address->mobile_phone }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    </td>
                                    <td class="text-center  align-middle">{{ number_format($item->total, 2) }} </td>
                                    <td class="text-center  align-middle">
                                        {{ $item->created_at->setTimezone('Asia/Bangkok')->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="text-center  align-middle">
                                        {{ $item->updated_at->setTimezone('Asia/Bangkok')->format('d/m/Y H:i:s') ?? '∞' }}
                                    </td>
                                    <td class="text-center  align-middle">
                                        <span
                                            class="badge 
                                                    @if ($item->status === 'Approve') bg-success text-white 
                                                    @elseif ($item->status === 'Cancel') 
                                                        bg-danger text-white 
                                                        @elseif ($item->status === 'Delivery') 
                                                        bg-custom text-white
                                                    @else 
                                                        bg-warning text-dark @endif 
                                                    rounded-pill px-3 py-2 shadow-sm text-uppercase fw-semibold">
                                            @if ($item->status == 'Approve')
                                                เสร็จสมบูรณ์
                                            @elseif($item->status == 'Cancel')
                                                ยกเลิก
                                            @elseif($item->status == 'Delivery')
                                                เตรียมจัดส่ง
                                            @elseif($item->status == 'Processed')
                                                กำลังดำเนินการ
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-center  align-middle">
                                        @if ($item->type === 'po')
                                            <span class="badge bg-label-warning">
                                                @if ($item->orderPo && $item->orderPo->image)
                                                    <a href="{{ asset('upload/file/order_po/' . basename($item->orderPo->image)) }}"
                                                        download>
                                                        {{ $item->po_number }}
                                                    </a>
                                                @else
                                                    {{ $item->po_number }}
                                                @endif
                                            </span>
                                        @elseif ($item->orderPayments->first())
                                            <span>
                                                {{ Str::ucfirst($item->orderPayments->first()->payment_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="d-inline-block ">
                                                <a class="btn btn-icon btn-outline-primary border-0"
                                                    href="{{ route('administrator.order.edit', ['id' => $item->id]) }}"
                                                    style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                    <i class="bx bx-show  align-middle" style="color: inherit;"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {!! $orders->links() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.dropdown-item', function() {
            var status = $(this).data('status');
            var button = $(this).closest('.dropdown').find('button');

            if (status === 'approve') {
                button.text('เสร็จสมบูรณ์');
            } else if (status === 'cancel') {
                button.text('ยกเลิก');
            } else if (status === 'delivery') {
                button.text('กำลังดำเนินการ');
            }
        });
    </script>
@endsection
