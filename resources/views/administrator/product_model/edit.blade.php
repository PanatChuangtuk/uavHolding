@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.model-product') }}">Product Model</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    <form id="form-update" action="{{ route('administrator.model-product.update', $productModels->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.model-product') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card">
            <div class="nav-align-top nav-tabs-shadow mb-6">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-main" aria-controls="tab-main" aria-selected="false">
                            Main
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-settings" aria-controls="tab-settings" aria-selected="false">
                            Image
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-product" aria-controls="tab-product" aria-selected="false">
                            Product
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-main" role="tabpanel">
                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" value="{{ $productModels->name }}" id="name"
                                    name="name" />
                            </div>
                        </div>

                        {{-- <div class="mb-4 row">
                            <label for="url" class="col-md-2 col-form-label">Code</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ $productModels->code }}">
                            </div>
                        </div> --}}

                        <div class="mb-4 row">
                            <label for="url" class="col-md-2 col-form-label">Description</label>
                            <div class="col-md-10">
                                <textarea type="text" class="form-control areaEditor" id="description" name="description">{{ $productModels->description }}</textarea>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label for="typesSelect" class="col-md-2 col-form-label">Type</label>
                            <div class="col-md-10">
                                <select name="types_id" id="typesSelect"
                                    class="form-control @error('types_id') is-invalid @enderror">
                                    <option value="{{ $productModels->productType->id ?? null }}">
                                        {{ $productModels->productType->name ?? null }}</option>
                                    <!-- เพิ่มตัวเลือกใน select ที่นี่ -->
                                </select>
                                @error('types_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="brandSelect" class="col-md-2 col-form-label">Brand</label>
                            <div class="col-md-10">
                                <select name="brand_id" id="brandSelect"
                                    class="form-control @error('brand_id') is-invalid @enderror">
                                    <option value="{{ $productModels->productBrand->id ?? null }}">
                                        {{ $productModels->productBrand->name ?? null }}</option>
                                    <!-- เพิ่มตัวเลือกใน select ที่นี่ -->
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="categorySelect" class="col-md-2 col-form-label">Category</label>
                            <div class="col-md-10">
                                <select name="category_id" id="categorySelect"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="{{ $productModels->productCategory->id ?? null }}">
                                        {{ $productModels->productCategory->name ?? null }}</option>
                                    <!-- เพิ่มตัวเลือกใน select ที่นี่ -->
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-settings" role="tabpanel">
                        <div class="mb-4 row">
                            <label for="image" class="col-md-2 col-form-label">Image</label>
                            <div class="col-md-10">
                                <input id="image" name="image" type="file" data-browse-on-zone-click="true" />
                                @error('image')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-2 col-form-label" for="status">Status</label>
                            <div class="col-md-10 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" value="1"
                                        name="status" {{ $productModels->status == 1 ? 'checked' : '0' }} />
                                    <label class="form-check-label ms-2" for="status">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
    </form>
    <div class="tab-pane fade" id="tab-product" role="tabpanel">
        <div class="table">
            <table class="table table-bordered border-light">
                <thead>
                    <tr>

                        <th class="text-center">Product</th>
                        <th class="text-center">Size</th>
                        <th class="text-center"></th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="orderTableBody">
                    @foreach ($productModels->products as $item)
                        <tr>
                            <td class="text-center">
                                {{ $item->sku }}<br>
                            </td>
                            <td class="text-center">
                                {{ optional(optional($item->productSizes->first())->productUnitValue)->name }}<br>
                            </td>
                            <td class="text-center"><a href="{{ route('administrator.product.edit', $item->id) }}"
                                    class="btn btn-primary">
                                    <i class="bx bx-edit bx"></i>
                                </a>
                                <br>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    </div>
    </div>
    </div>
    {{-- <div class="row mt-2">
            <div class="col-md-12">
                <div class="card p-4">
                    @foreach ($product_info as $item)
                        <div class="mb-4 row">
                            <label for="description_[{{ $item->id }}]"
                                class="col-md-2 col-form-label">{{ $item->productAttribute->name ?? null }}</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="description_[{{ $item->id }}]"
                                    name="descriptionAttribute[{{ $item->id }}]" value="{{ $item->detail }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div> --}}
@endsection

@section('script')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = '{{ route('administrator.model-product') }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $("#image").fileinput({
                deleteUrl: "{{ route('administrator.model-product.delete.image', $productModels->id) . '?_token=' . csrf_token() }}",
                enableResumableUpload: true,
                showRemove: false,
                uploadAsync: false,
                initialPreviewAsData: true,
                showCancel: true,
                showUpload: false,
                elErrorContainer: '#kartik-file-errors',
                allowedFileExtensions: ["jpg", "png", "jpeg", "svg", "raw", "gif", "tif", "webp"],
                resumableUploadOptions: {
                    chunkSize: 5,
                },
                initialPreview: [
                    @if ($productModels->image)
                        src =
                            "{{ asset('upload/file/model-product/' . basename($productModels->image)) }}"
                    @else
                        null
                    @endif
                ],
                initialPreviewConfig: [
                    @if ($productModels)
                        {
                            caption: "{{ basename($productModels->image) }}",
                            key: 1
                        }
                    @else
                        {
                            caption: "No image available",
                            key: 0
                        }
                    @endif
                ],
                maxFileCount: 1,
                theme: "bs5",
                fileActionSettings: {
                    showZoom: function(config) {
                        if (config.type === 'pdf' || config.type === 'image') {
                            return true;
                        }
                        return false;
                    },
                }
            }).on('filebeforedelete', function() {
                return new Promise(function(resolve, reject) {
                    $.confirm({
                        title: 'Confirmation!',
                        content: 'Are you sure you want to delete this file?',
                        type: 'red',
                        buttons: {
                            ok: {
                                btnClass: 'btn-primary text-white',
                                keys: ['enter'],
                                action: function() {
                                    resolve();
                                }
                            },
                            cancel: function() {}
                        }
                    });
                });
            }).on('filedeleted', function() {});
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedBrands = {{ json_encode($productModels->product_brand_id) }} ?? 0;
            let selectedCategory = {{ json_encode($productModels->product_category_id) }} ?? 0;
            let selectedTypes = {{ json_encode($productModels->product_type_id) }} ?? 0;
            console.log(selectedBrands);
            $('#brandSelect').select2({
                allowClear: true,
                closeOnSelect: true,
                ajax: {
                    url: '/get-brands-single',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            selected_ids: selectedBrands
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#typesSelect').select2({
                allowClear: true,
                closeOnSelect: true,
                ajax: {
                    url: '/get-types-single',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            selected_ids: selectedTypes
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            $('#categorySelect').select2({
                allowClear: true,
                closeOnSelect: true,
                ajax: {
                    url: '/get-category-single',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            selected_ids: selectedCategory
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
    <script>
        window.csrfToken = @json(csrf_token());
    </script>
    <script type="module" src="{{ asset('administrator/js/ckeditor.js') }}"></script>
@endsection
