@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .empty-cart-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            text-align: center;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        /* เพิ่มความสูงให้กับ select2 */
        .select2-container--default .select2-selection--single {
            font-family: Prompt, Inter, sans-serif;
            font-size: 16px;
            line-height: 39px;
            /* ปรับให้ตัวอักษรสูงขึ้น */
            color: #212529;
            background: #fff;
            border: 1px solid #89A082;
            border-radius: 12px;
            padding: 0 15px 0 40px;
            box-sizing: border-box;
            height: 40px;
            transition: 0.2s ease-in-out;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 39px;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #6F8F6B;
            box-shadow: 0 0 5px rgba(137, 160, 130, 0.5);
        }

        .select2-container--default .select2-results__option {
            font-family: Prompt, Inter, sans-serif;
            font-size: 16px;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.model-product') }}">Product Model</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    <form id="form-create" action="{{ route('administrator.model-product.submit') }}" method="POST"
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

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-main" role="tabpanel">
                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Name</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" value="" id="name" name="name" />
                            </div>
                        </div>
                        {{-- <div class="mb-4 row">
                            <label for="url" class="col-md-2 col-form-label">Code</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="code" name="code"
                                    value="">
                            </div>
                        </div> --}}


                        <div class="mb-4 row">
                            <label for="url" class="col-md-2 col-form-label">Description</label>
                            <div class="col-md-10">
                                <textarea type="text" class="form-control areaEditor" id="description" name="description"></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="typesSelect" class="col-md-2 col-form-label">Type</label>
                            <div class="col-md-10">
                                <select name="types_id" id="typesSelect"
                                    class="form-control @error('types_id') is-invalid @enderror">
                                    <option value=""></option>

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
                                    <option value=""></option>

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
                                    <option value=""></option>

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
                        <div class="row mb-3">
                            <label class="col-md-1 col-form-label" for="status">Status</label>
                            <div class="col-md-5 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" value="1"
                                        name="status" />
                                </div>
                            </div>
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
    </form>
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
                showRemove: false,
                enableResumableUpload: true,
                initialPreviewAsData: true,
                showCancel: true,
                showUpload: false,
                elErrorContainer: '#kartik-file-errors',
                allowedFileExtensions: ["jpg", "png", "jpeg", "svg", "raw", "gif", "tif", "webp"],
                resumableUploadOptions: {
                    chunkSize: 5,
                },
                maxFileCount: 1,
                theme: "bs5",
                fileActionSettings: {
                    showZoom: function(config) {
                        if (config.type === 'pdf' || config.type === 'image') {
                            return true;
                        }
                        return false;
                    }
                }
            });
        });
    </script>
    <script>
        window.csrfToken = @json(csrf_token());
    </script>
    <script type="module" src="{{ asset('administrator/js/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            let selectedBrands = 0;
            let selectedCategory = 0;
            let selectedTypes = 0;
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
@endsection
