@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />


            {{-- Content --}}
            <div class="card">
                <div class="card-body">

                    {{-- Header --}}
                    <div class="p-3">
                        <form action="{{ route('administrator.approve-order') }}" method="GET">
                            <div class="d-flex justify-content-between align-items-center">
                                <x-search />
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
                                    <th class="text-center">PO Number</th>
                                    <th class="text-center">Order Status</th>
                                    <th class="text-center"> </th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="orderTableBody">
                                @foreach ($orders as $item)
                                    <tr>
                                        <td class="text-center  align-middle">{{ $item->order_number }}</td>
                                        <td class="text-start">
                                            <div class="text-center align-middle">
                                                <div class="flex-grow-1">
                                                    <strong class="d-block">
                                                        @if ($item->address)
                                                            {{ $item->address->first_name }} |
                                                            {{ $item->address->last_name }}
                                                        @else
                                                            {{ $item->member->info->first_name }} |
                                                            {{ $item->member->info->last_name }}
                                                        @endif
                                                    </strong>
                                                    <span class="text-muted small">
                                                        @if ($item->address)
                                                            {{ $item->address->mobile_phone }}
                                                        @else
                                                            {{ $item->member->mobile_phone }}
                                                        @endif

                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center  align-middle">{{ number_format($item->total, 2) }}
                                        </td>
                                        <td class="text-center  align-middle">
                                            {{ $item->created_at->setTimezone('Asia/Bangkok')->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="text-center  align-middle">
                                            @if ($item->type === 'po')
                                                {{ $item->po_number }}
                                            @elseif($item->orderPayments && $item->orderPayments->first())
                                                <span
                                                    class="badge 
                                                    bg-success text-white 
                                                    rounded-pill px-3 py-2 shadow-sm text-uppercase fw-semibold">
                                                    ชำระเงินสำเร็จ
                                                </span>
                                            @else
                                                รอการชำระเงิน
                                            @endif
                                        </td>
                                        </td>
                                        <td class="text-center  align-middle"><x-approve-dropdown :status="$item->status"
                                                :item="$item->id" />
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <a class="btn btn-icon btn-outline-primary border-0"
                                                        href="{{ route('administrator.approve-order.edit', ['id' => $item->id]) }}"
                                                        style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                        <i class="bx bx-show  align-middle" style="color: inherit;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @endif --}}
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
    </div>
    <script>
        const currentPath = window.location.pathname;
        const bulkDeleteUrl = currentPath.endsWith('/') ? currentPath + 'bulk-delete' : currentPath + '/bulk-delete';
    </script>
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
                button.text('อนุมัติ');
            } else if (status === 'cancel') {
                button.text('ยกเลิก');
            } else if (status === 'delivery') {
                button.text('กำลังดำเนินการ');
            }
        });
    </script>


    <script src="{{ asset('js/delete.js') }}"></script>
@endsection
