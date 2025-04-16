@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                        <form action="{{ route('administrator.model-product') }}" method="GET">
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

                                <!-- Filter Brand -->
                                <div class="flex-grow-1">
                                    <select class="form-select" id="brandFilter" name="brand"
                                        onchange="this.form.submit()">
                                        <option value="" {{ request()->input('brand') == '' ? 'selected' : '' }}>
                                            Filter by Brand</option>
                                        @foreach ($brand_type as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ request()->input('brand') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <!-- Filter Type -->
                                <div class="flex-grow-1">
                                    <select class="form-select" id="typeFilter" name="type"
                                        onchange="this.form.submit()">
                                        <option value="" {{ request()->input('type') == '' ? 'selected' : '' }}>
                                            Filter by Type</option>
                                        @foreach ($product_types as $type)
                                            <option value="{{ $type->id }}"
                                                {{ request()->input('type') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}</option>
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
                                    <a href="{{ route('administrator.model-product.add') }}"
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
                                    <th>Name</th>
                                    <th class="text-center">Brand</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Code</th>
                                    {{-- <th>Description</th> --}}
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="modelTableBody">
                                @foreach ($productModels as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check" style="font-size: 1rem;">
                                                <input type="checkbox" class="form-check-input check-item"
                                                    value="{{ $item->id }}">
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td>{{ Str::limit($item->name ?? 'No name', 20, '...') }}</td>
                                        <td class="text-center">
                                            @php
                                                $brandId = $item->product_brand_id;
                                                $brandName = $item->productBrand ? $item->productBrand->name : null;
                                                \Log::info('Product Brand Debug', [
                                                    'model_id' => $item->id,
                                                    'brand_id' => $brandId,
                                                    'brand_relation' => $item->productBrand,
                                                ]);
                                            @endphp

                                            @if ($brandName)
                                                {{ $brandName }}
                                            @else
                                                <span class="text-danger">
                                                    Empty (Brand ID: {{ $brandId }})
                                                    @if (config('app.debug'))
                                                        <br>
                                                        <small>(Debug: {{ json_encode($item->productBrand) }})</small>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $typeId = $item->product_type_id;
                                                $typeName = $item->productType ? $item->productType->name : null;
                                                \Log::info('Product Type Debug', [
                                                    'id' => $item->id,
                                                    'type_id' => $typeId,
                                                    'type_relation' => $item->productType,
                                                ]);
                                            @endphp

                                            @if ($typeName)
                                                {{ $typeName }}
                                            @else
                                                <span class="text-danger">
                                                    Empty (Type ID: {{ $typeId }})
                                                    @if (config('app.debug'))
                                                        <br>
                                                        <small>(Debug: {{ json_encode($item->productType) }})</small>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->code }}</td>
                                        {{-- <td>{{ Str::limit($item->description ?? 'No name', 30, '...') }}</td> --}}
                                        <td class="text-center">
                                            <x-status-label :status="$item->status" />
                                        </td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <a class="btn btn-icon btn-outline-primary border-0"
                                                        href="{{ route('administrator.model-product.edit', ['id' => $item->id]) }}"
                                                        style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                        <i class="bx bx-edit bx" style="color: inherit;"></i>
                                                    </a>
                                                    <form id="deleteForm{{ $item->id }}"
                                                        action="{{ route('administrator.model-product.destroy', ['id' => $item->id, 'page' => request()->get('page')]) }}"
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
                            {!! $productModels->links() !!}
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
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const bulkDeleteUrl = currentPath.endsWith('/') ? currentPath + 'bulk-delete' : currentPath +
                '/bulk-delete';

            const checkAll = document.getElementById('checkAll');
            if (checkAll) {
                checkAll.addEventListener('change', function() {
                    document.querySelectorAll('.check-item').forEach(item => {
                        item.checked = this.checked;
                    });
                });
            }
        });
    </script>

    <script src="{{ asset('js/delete.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.querySelector('.add-button');
            if (addButton) {
                addButton.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#28a745';
                    this.style.borderColor = '#28a745';
                    this.style.color = '#ffffff';
                });

                addButton.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '';
                    this.style.borderColor = '';
                    this.style.color = '';
                });
            }
        });
    </script>
@endsection
