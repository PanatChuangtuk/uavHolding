@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <x-bread-crumb />
    <form id="form-update" action="{{ route('administrator.brand-product.update', $productBrands->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <label for="typeSelect" class="form-label col-auto">Select Type</label>
            <select id="typeSelect" name="type_id" class="form-select" style="width: 155px;">
                @foreach ($product_type as $option)
                    <option value="{{ $option->id }} "
                        {{ $productBrands->product_type_id === $option->id ? 'selected' : '' }}>
                        {{ $option->name }}</option>
                @endforeach
            </select>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.brand-product') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card p-4">
            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">Name</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" value="{{ $productBrands->name }}" id="name"
                        name="name" />
                    @error('name')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4 row">
                <label for="code" class="col-md-2 col-form-label">Code</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="code" name="code"
                        value="{{ $productBrands->code }}">
                </div>
            </div>

            <div class="mb-4 row">
                <label for="image" class="col-md-2 col-form-label">Image</label>
                <div class="col-md-10">
                    <input id="image" name="image" type="file" data-browse-on-zone-click="true" />
                    <div id="kartik-file-errors"></div> @error('image')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-md-2 col-form-label" for="status">Status</label>
                <div class="col-md-10 d-flex align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" value="1" name="status"
                            {{ $productBrands->status == 1 ? 'checked' : '0' }} />
                    </div>
                </div>
            </div>
        </div>
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
                window.location.href = '{{ route('administrator.brand-product') }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $("#image").fileinput({
                deleteUrl: "{{ route('administrator.brand-product.delete.image', $productBrands->id) . '?_token=' . csrf_token() }}",
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
                    @if ($productBrands->image)
                        src =
                            "{{ asset('upload/file/product_brand/' . basename($productBrands->image)) }}"
                    @else
                        null
                    @endif
                ],
                initialPreviewConfig: [
                    @if ($productBrands)
                        {
                            caption: "{{ basename($productBrands->image) }}",
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
                            cancel: function() {
                                reject();

                            }
                        }
                    });
                });
            }).on('filedeleted', function() {

            });
            $('#form-update').on('submit', function(e) {
                e.preventDefault();
                this.submit();
            });
        });
    </script>
@endsection
