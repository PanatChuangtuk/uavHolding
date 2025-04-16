@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />

            {{-- Content --}}
            <div class="card">
                <div class="card-body">
                    {{-- Head --}}
                    <div class="p-3">
                        <form action="{{ route('administrator.users') }}" method="GET">
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
                                <div class="flex-grow-1">
                                    <select class="form-select" id="couponFilter" name="admin"
                                        onchange="this.form.submit()">
                                        <option value="" {{ request()->input('admin') == '' ? 'selected' : '' }}>
                                            Filter by Role
                                        </option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ request()->input('admin') == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="border-bottom mx-n3 my-3"></div>
                            <!-- Search Section -->
                            <div class="d-flex justify-content-between align-items-center">
                                <x-search />

                                <!-- Add Button Section -->
                                <div class="d-flex align-items-center ms-2">
                                    <a href="{{ route('administrator.users.add') }}"
                                        class="btn btn-primary d-flex align-items-center" style="white-space: nowrap;"
                                        onmouseover="this.style.backgroundColor='#28a745'; this.style.borderColor='#28a745'; this.style.color='#ffffff';"
                                        onmouseout="this.style.backgroundColor=''; this.style.borderColor=''; this.style.color='';">
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
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="userTableBody">
                                @foreach ($users as $item)
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
                                                {{-- รูปโปรไฟล์ --}}
                                                <div class="me-2">
                                                    @php
                                                        $profile_image = optional($item)->profile_image;
                                                    @endphp

                                                    @if ($profile_image && trim($profile_image) !== '')
                                                        <img id="imagePreview"
                                                            src="{{ $item->profile_image ? asset('upload/' . $item->profile_image) : '#' }}"
                                                            alt="N/A" class="rounded-circle" width="50"
                                                            height="50">
                                                    @else
                                                        <img src="{{ asset('upload/file/customer/default-avatar-profile-icon.jpg') }}"
                                                            alt="N/A" class="rounded-circle" width="50"
                                                            height="50">
                                                    @endif
                                                </div>

                                                {{-- ข้อมูลสมาชิก --}}
                                                <div class="flex-grow-1">
                                                    <strong class="d-block">
                                                        {{ optional($item)->name ?? 'Unknown' }}
                                                        <!-- {{ optional($item)->last_name ?? '' }} -->
                                                    </strong>
                                                    <span class="text-muted small">
                                                        {{ optional($item)->email ?? 'No Email' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->role->name }}</td>
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>
                                        {{-- <td class="text-center">{{ $item->created_at }}</td> --}}
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <a class="btn btn-icon btn-outline-primary border-0"
                                                        href="{{ route('administrator.users.edit', ['id' => $item->id]) }}"
                                                        style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                        <i class="bx bx-edit bx" style="color: inherit;"></i>
                                                    </a>

                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('administrator.users.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
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

                        {{-- Pagination --}}
                        <div>
                            {!! $users->links() !!}
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
@endsection
