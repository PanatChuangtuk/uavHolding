@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        :root {
            --modal-bg: #ffffff;
            --modal-text: #333333;
            --modal-header-color: #007bff;
            --modal-btn-bg: #007bff;
            --modal-btn-text: #ffffff;
        }

        body.modal-dark {
            --modal-bg: #1e1e1e;
            --modal-text: #ffffff;
            --modal-header-color: #17a2b8;
            --modal-btn-bg: #444444;
            --modal-btn-text: #ffffff;
        }

        .modal-content {
            background-color: var(--modal-bg);
            color: var(--modal-text);
            border-radius: 10px;
            box-shadow: 5px 4px 10px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s, color 0.3s;
        }

        .modal-header h5 {
            font-size: 1.50rem;
            font-weight: bold;
            color: var(--modal-header-color);
        }

        .modal-header .close {
            font-size: 1.5rem;
            color: var(--modal-text);
        }

        .modal-header .close:hover {
            color: #888;
        }

        .modal-footer .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            background-color: var(--modal-btn-bg);
            color: var(--modal-btn-text);
            border: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .modal-footer .btn:hover {
            background-color: #0056b3;
            color: #ffffff;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <form action="{{ route('administrator.reviews') }}" method="GET"
                            class="d-flex justify-content-between align-items-center w-100">
                            <x-search />
                            <div style="width: 100px;"></div>

                            <div class="d-flex align-items-center ms-2">
                                <x-status-filter />
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    {{-- <th class="text-center"style="font-size: 1rem;">
                                        <div class="form-check">
                                            <input class="form-check-input check-item" type="checkbox" id="checkAll" />
                                        </div>
                                    </th> --}}
                                    <th class="text-center">No</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Sku</th>
                                    <th class="text-center">Description Reviews</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-center">Status Approve</th>
                                    {{-- <th class="text-center">Actions</th> --}}
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="brandTableBody">
                                @foreach ($reviews as $item)
                                    <tr>
                                        {{-- <td class="text-center">
                                            <div class="form-check" style="font-size: 1rem;">
                                                <input type="checkbox" class="form-check-input check-item"
                                                    value="{{ $item->id }}">
                                            </div>
                                        </td> --}}
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td class="text-center">{{ $item->member->username }}</td>
                                        <td class="text-center">
                                            {{ $item->productModel->products->first()->sku }}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="view-comment" data-bs-toggle="modal"
                                                data-bs-target="#commentModal" data-comment="{{ $item->comments }}">
                                                {{ Str::limit($item->comments, 35) }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <x-status-approve :status="$item->is_show" :item="$item->id" />
                                        </td>
                                        {{-- <td> --}}
                                        {{-- <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <a class="btn btn-icon btn-outline-primary border-0"
                                                        href="{{ route('administrator.reviews.edit', ['id' => $item->id]) }}">
                                                        <i class="bx bx-edit bx"></i>
                                                    </a>

                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('administrator.reviews.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-icon btn-outline-danger border-0 btn-delete"
                                                            data-id="{{ $item->id }}">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div> --}}
                                        {{-- </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div>
                            {!! $reviews->links() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Description Reviews</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="fullComment"></p>
                </div>
                <div class="modal-footer">
                    {{-- <button id="toggleMode" class="btn btn-primary">Switch to Dark Mode</button> --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const currentPath = window.location.pathname;
        const bulkDeleteUrl = currentPath.endsWith('/') ? currentPath + 'bulk-delete' : currentPath + '/bulk-delete';
    </script>
    <script>
        $(document).ready(function() {
            $('.view-comment').on('click', function() {
                var fullComment = $(this).data('comment');
                $('#fullComment').text(fullComment);
            });
        });
    </script>
    {{-- <script>
        document.getElementById('toggleMode').addEventListener('click', function() {
            const body = document.body;
            const isDark = body.classList.toggle('modal-dark');

            this.textContent = isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode';
        });
    </script> --}}
    <script>
        $(document).ready(function() {

            $('input[type="checkbox"]').change(function() {
                var itemId = $(this).data('id');
                var status = $(this).prop('checked') ? 1 :
                    0;

                $.ajax({
                    url: '{{ route('administrator.reviews.update') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: itemId,
                        is_show: status,
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
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/delete.js') }}"></script>
@endsection
