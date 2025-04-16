@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />
            <div class="card">
                <div class="card-body">
                    {{-- Head --}}
                    <div class="p-3">
                        <form action="{{ route('administrator.promo_discount') }}" method="GET">
                            <!-- Filter Section -->
                            <div class="d-flex justify-content-between gap-3 mb-3">
                                <!-- Status Filter -->
                                <div class="flex-grow-1">
                                    <select class="form-select" id="statusFilter" name="status"
                                        onchange="this.form.submit()">
                                        <option value="" {{ request()->input('status') == '' ? 'selected' : '' }}>
                                            Filter by Status
                                        </option>
                                        <option value="active"
                                            {{ request()->input('status') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ request()->input('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <!-- Filter Promo Type -->
                                <div class="flex-grow-1">
                                    <select class="form-select" id="promoFilter" name="promo"
                                        onchange="this.form.submit()">
                                        <option value="" {{ request()->input('promo') == '' ? 'selected' : '' }}>
                                            Filter by Discount Type
                                        </option>
                                        <option value="percentage"
                                            {{ request()->input('promo') == 'percentage' ? 'selected' : '' }}>
                                            Percentage
                                        </option>
                                        <option value="amount"
                                            {{ request()->input('promo') == 'amount' ? 'selected' : '' }}>
                                            Amount
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="border-bottom mx-n3 my-3"></div>
                            <!-- Search Section -->
                            <div class="d-flex justify-content-between align-items-center">
                                <x-search />

                                <!-- Add Button Section -->
                                <div class="d-flex align-items-center ms-2">
                                    <a href="{{ route('administrator.promo_discount.add') }}"
                                        class="btn btn-primary d-flex align-items-center add-button"
                                        style="white-space: nowrap;">
                                        Add
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="font-size: 1rem;">
                                        <div class="form-check">
                                            <input class="form-check-input check-item" type="checkbox" id="checkAll" />
                                        </div>
                                    </th>
                                    <th class="text-center">No</th>
                                    {{-- <th>ModelName</th> --}}
                                    <th class="text-center">Promotion Name</th>
                                    <th class="text-center">discount type</th>
                                    <th class="text-center">End Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="brandTableBody">
                                @foreach ($recommend as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check" style="font-size: 1rem;">
                                                <input type="checkbox" class="form-check-input check-item"
                                                    value="{{ $item->id }}">
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->id }}</td>
                                        {{-- <td>
                                            {{ substr($item->productModel->name, 0, 35) }}
                                        </td> --}}
                                        <td class="text-center">{{ Str::limit($item->name, 35, '...') }}</td>

                                        <td class="text-center">
                                            @if ($item->discount_type == \App\Enum\DiscountType::AMOUNT->value)
                                                {{ \App\Enum\DiscountType::AMOUNT->name }}
                                            @elseif ($item->discount_type == \App\Enum\DiscountType::PERCENTAGE->value)
                                                {{ \App\Enum\DiscountType::PERCENTAGE->name }}
                                            @endif
                                            {{-- {{ number_format((float) str_replace(',', '', $item->discount_amount), 0, '.', ',') }}
                                            @if ($item->discount_type == \App\Enum\DiscountType::AMOUNT->value)
                                                ฿
                                            @elseif ($item->discount_type == \App\Enum\DiscountType::PERCENTAGE->value)
                                                %
                                            @endif --}}
                                        </td>
                                        {{-- <td class="text-center">{{ $item->discount_amount }}</td> --}}

                                        <td class="text-center">{{ $item->end_date }}</td>
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i
                                                        class="icon-base bx bx-dots-vertical-rounded icon-lg text-body-secondary"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="d-inline-block text-nowrap">
                                                            <a class="btn btn-icon btn-outline-primary border-0"
                                                                href="{{ route('administrator.promo_discount.edit', ['id' => $item->id]) }}">
                                                                <i class="bx bx-edit bx"></i>
                                                            </a>
                                                            <form id="deleteForm{{ $item->id }}"
                                                                action="{{ route('administrator.promo_discount.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
                                                                style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-icon btn-outline-danger border-0 btn-delete"
                                                                    data-id="{{ $item->id }}">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </form>
                                                            <form id="notificationForm{{ $item->id }}"
                                                                action="{{ route('administrator.promo_discount.notifications.show', ['id' => $item->id]) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="button"
                                                                    class="btn btn-icon btn-outline-info border-0 btn-notification"
                                                                    data-id="{{ $item->id }}">
                                                                    <i class='bx bxs-bell-ring'></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div>
                            {!! $recommend->links() !!}
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/delete.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.btn-notification').click(function() {
                var notificationId = $(this).data('id');
                var form = $('#notificationForm' + notificationId);

                Swal.fire({
                    title: 'คุณแน่ใจไหม?',
                    text: 'คุณต้องการส่งการแจ้งเตือนนี้หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ส่งการแจ้งเตือน!',
                    cancelButtonText: 'ไม่, ยกเลิก!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'กำลังดำเนินการ...',
                            text: 'กรุณารอสักครู่',
                            icon: 'info',
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                Swal.close();
                                Swal.fire(
                                    'ส่งแล้ว!',
                                    'การแจ้งเตือนถูกส่งเรียบร้อยแล้ว.',
                                    'success'
                                );
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
