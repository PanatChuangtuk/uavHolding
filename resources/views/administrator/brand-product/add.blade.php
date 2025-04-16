@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <x-bread-crumb />
    <form id="form-create" action="{{ route('administrator.brand-product.submit') }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <label for="typeSelect" class="form-label col-auto">Select Type</label>
            <select id="typeSelect" name="type_id" class="form-select" style="width: 155px;">
                @foreach ($product_type as $option)
                    <option value="{{ $option->id }}">{{ $option->name }}</option>
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
                    <input class="form-control" type="text" placeholder="Input Name" id="name" name="name"
                        value="{{ old('name') }}" />
                    @error('name')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mb-4 row">
                <label for="code" class="col-md-2 col-form-label">Code</label>
                <div class="col-md-10">
                    <input type="code" class="form-control" id="code" name="code" placeholder="Enter Code">
                </div>
            </div>

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
                        <input class="form-check-input" type="checkbox" id="status" value="1" name="status" />
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
@endsection
