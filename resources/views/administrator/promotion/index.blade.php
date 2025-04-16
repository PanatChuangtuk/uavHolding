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
                    {{-- Head --}}
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <form action="{{ route('administrator.promotion') }}" method="GET"
                            class="d-flex justify-content-between align-items-center w-100">
                            <x-search />
                            <div style="width: 100px;"></div>

                            <div class="d-flex align-items-center ms-2">
                                <x-status-filter />
                                <a href="{{ route('administrator.promotion.add') }}"
                                    class="btn btn-primary d-flex align-items-center" style="white-space: nowrap;"
                                    onmouseover="this.style.backgroundColor='#28a745'; this.style.borderColor='#28a745'; this.style.color='#ffffff';"
                                    onmouseout="this.style.backgroundColor=''; this.style.borderColor=''; this.style.color='';">
                                    Add
                                </a>
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
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="promotionTableBody">
                                @foreach ($promotion as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check" style="font-size: 1rem;">
                                                <input type="checkbox" class="form-check-input check-item"
                                                    value="{{ $item->id }}">
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td>
                                            @if ($item->image)
                                                <img src="{{ asset('upload/file/promotion/' . $item->image) }}"
                                                    alt="{{ $item->image }}" class="img-thumbnail w-px-50 h-px-50" />
                                            @else
                                                <img src="https://via.placeholder.com/50" alt="Placeholder Image"
                                                    class="img-thumbnail w-px-50 h-px-50" />
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($item->name ?? 'No name', 30, '...') }}</td>
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>
                                        <td class="text-center">{{ $item->created_at }}</td>
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
                                                                href="{{ route('administrator.promotion.edit', ['id' => $item->id]) }}"
                                                                style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                                <i class="bx bx-edit bx" style="color: inherit;"></i>
                                                            </a>

                                                            <form id="deleteForm{{ $item->id }}"
                                                                action="{{ route('administrator.promotion.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-icon btn-outline-danger border-0 btn-delete"
                                                                    data-id="{{ $item->id }}">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </form>
                                                            <form id="notificationForm{{ $item->id }}"
                                                                action="{{ route('administrator.promotion.notifications.show', ['id' => $item->id]) }}"
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
                            {!! $promotion->links() !!}
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
