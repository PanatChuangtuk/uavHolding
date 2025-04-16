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
                    <div class="p-3">
                        <form action="{{ route('administrator.member') }}" method="GET">
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

                                <!-- Filter Customer Group Type -->
                                <div class="flex-grow-1">
                                    <select class="form-select" id="memberGroupFilter" name="member_group"
                                        onchange="this.form.submit()">
                                        <option value=""
                                            {{ request()->input('member_group') == '' ? 'selected' : '' }}>
                                            Filter by Customer Group
                                        </option>
                                        @foreach ($memberGroups as $group)
                                            <option value="{{ $group->name }}"
                                                {{ request()->input('member_group') == $group->name ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Account Type -->
                                <div class="flex-grow-1">
                                    <select class="form-select" id="couponFilter" name="account"
                                        onchange="this.form.submit()">
                                        <option value="" {{ request()->input('account') == '' ? 'selected' : '' }}>
                                            Filter by Account Type
                                        </option>
                                        <option value="government"
                                            {{ request()->input('account') == 'government' ? 'selected' : '' }}>
                                            Government
                                        </option>
                                        <option value="private"
                                            {{ request()->input('account') == 'private' ? 'selected' : '' }}>
                                            Private
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
                                    <a href="{{ route('administrator.member.add') }}"
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
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Mobile Phone</th>
                                    <th class="text-center">VAT Number</th>
                                    <th class="text-center">Customer Group</th>
                                    <th class="text-center">Account Type</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0">
                                @foreach ($members as $item)
                                    @php
                                        $accountType = strtolower($item->info->account_type ?? '');
                                        $badgeClass = match ($accountType) {
                                            'private' => 'bg-label-success',
                                            'government' => 'bg-label-info',
                                            default => 'bg-label-secondary',
                                        };
                                    @endphp
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
                                                        $avatar = optional($item->info)->avatar;
                                                    @endphp

                                                    @if ($avatar && trim($avatar) !== '')
                                                        <img src="{{ asset('upload/file/customer/' . $avatar) }}"
                                                            alt="Profile" class="rounded-circle" width="50"
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
                                                        {{ optional($item->info)->first_name ?? 'Unknown' }}
                                                        {{ optional($item->info)->last_name ?? '' }}
                                                    </strong>
                                                    <span class="text-muted small">
                                                        {{ optional($item)->email ?? 'No Email' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>
                                        <td class="text-center">{{ $item->mobile_phone }}</td>
                                        <td class="text-center">{{ $item->info->vat_register_number ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if ($item->info && $item->info->memberGroups->isNotEmpty())
                                                @foreach ($item->info->memberGroups as $memberToGroup)
                                                    @php
                                                        $badgeColor = '';
                                                        switch (strtolower($memberToGroup->memberGroup->name)) {
                                                            case 'user':
                                                                $badgeColor = 'badge-danger';
                                                                break;
                                                            case 'dealer':
                                                                $badgeColor = 'badge-warning';
                                                                break;
                                                            case 'wholesaler':
                                                                $badgeColor = 'badge-primary';
                                                                break;
                                                            case 'partner':
                                                                $badgeColor = 'badge-success';
                                                                break;
                                                            default:
                                                                $badgeColor = 'badge-secondary';
                                                                break;
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badgeColor }}">
                                                        {{ ucfirst($memberToGroup->memberGroup->name) }}
                                                    </span><br>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst($accountType) ?: 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $item->created_at }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <a class="btn btn-icon btn-outline-primary border-0"
                                                        href="{{ route('administrator.member.edit', ['id' => $item->id]) }}"
                                                        style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                        <i class="bx bx-edit bx" style="color: inherit;"></i>
                                                    </a>

                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('administrator.member.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
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
                            {!! $members->links() !!}
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
