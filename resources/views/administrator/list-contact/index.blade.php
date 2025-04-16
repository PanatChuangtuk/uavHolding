@extends('administrator.layouts.main')

@section('title')

@section('stylesheet')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />

            <div class="card">
                <div class="card-body">
                    {{-- Search and Filter --}}
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <form action="{{ route('administrator.list-contact') }}" method="GET"
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
                                    <th style="font-size: 1rem;">
                                        <div class="form-check">
                                            <input class="form-check-input check-item" type="checkbox" id="checkAll" />
                                        </div>
                                    </th>
                                    <th class="text-center">No</th>

                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Subject</th>
                                    <th class="text-center">Message</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check" style="font-size: 1rem;">
                                                <input type="checkbox" class="form-check-input check-item"
                                                    value="{{ $item->id }}">
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->id }}</td>

                                        <td class="text-start">
                                            <div class="d-flex align-items-start">
                                                {{-- ข้อมูลสมาชิก --}}
                                                <div class="flex-grow-1">
                                                    <strong class="d-block">
                                                        {{ optional($item)->name ?? 'Unknown' }}
                                                        <!-- {{ optional($item->info)->last_name ?? '' }} -->
                                                    </strong>
                                                    <span class="text-muted small">
                                                        {{ optional($item)->email ?? 'No Email' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->phone_number }}</td>
                                        <td class="text-center">{{ $item->subject }}</td>
                                        <td class="text-center">{{ Str::limit($item->message, 30) }}</td>
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>
                                        <td class="text-center">{{ $item->created_at }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <button class="btn btn-icon btn-outline-primary border-0"
                                                        onclick="viewContact({{ json_encode($item) }}); setStatusOne({{ $item->id }}, this);"
                                                        style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                        <i
                                                            class="bx {{ $item->status === 1 ? 'bx-show' : 'bx-hide' }}"></i>
                                                    </button>

                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('administrator.list-contact.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-icon btn-outline-danger border-0 btn-delete"
                                                            data-id="{{ $item->id }}">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div>
                        {!! $contacts->links() !!}
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
        function viewContact(contact) {
            let fileUrl = `{{ asset('upload/file/contact_us') }}/${contact.image}`;
            Swal.fire({
                title: 'Detail Contact',
                html: `
                <div style="text-align: left;">
                    <p><strong>Name :</strong> ${contact.name}</p>
                    <p><strong>Email :</strong> ${contact.email}</p>
                    <p><strong>Phone :</strong> ${contact.phone_number}</p>
                    <p><strong>Subject :</strong> ${contact.subject}</p>
                    <p><strong>Message :</strong> ${contact.message}</p>
                    <p><strong>Date :</strong> ${new Date(contact.created_at).toLocaleString()}</p>
                    <p><strong>Download: <a href="${fileUrl}" download>Click here</a></strong></p>
                </div>
            `,
                icon: 'info',
                confirmButtonText: 'Close',
            });
        }
    </script>
    <script>
        function setStatusOne(id, button) {
            let icon = $(button).find('i');
            console.log(id, );
            $.ajax({
                url: '{{ route('administrator.update.status', ['id' => ':id']) }}'.replace(':id',
                    id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        icon.removeClass('bx-hide').addClass('bx-show');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
