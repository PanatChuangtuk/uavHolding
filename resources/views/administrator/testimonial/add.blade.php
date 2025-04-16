@extends('administrator.layouts.main')

@section('title')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.testimonial') }}">Testimonial</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
    <form id="form-create" action="{{ route('administrator.testimonial.submit') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="demo-inline-spacing">
            <div class="text-end">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.testimonial') }}"> <button type="button"
                        class="btn btn-danger">Cancel</button></a>
            </div>

            <div class="card">
                <div class="nav-align-top nav-tabs-shadow mb-6">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach ($language as $index => $languages)
                            <li class="nav-item">
                                <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }} " role="tab"
                                    data-bs-toggle="tab" data-bs-target="#tab-{{ $languages->code }}"
                                    aria-controls="tab-{{ $languages->code }}" aria-selected="false">
                                    {{ $languages->code }}
                                </button>
                            </li>
                        @endforeach
                        <li class="nav-item">
                            <button name="" type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#tab-settings" aria-controls="tab-settings" aria-selected="false">
                                Setting
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        @foreach ($language as $index => $languages)
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                id="tab-{{ $languages->code }}" role="tabpanel">
                                <div class="mb-4 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label">Name</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Input Name" id="name"
                                            name="name[{{ $languages->id }}]"
                                            value="{{ old('name.' . $languages->id) }}" />
                                        @error('name.' . $languages->id)
                                            <div class="text-danger col-form-label">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label">Position Name</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Input Position Name"
                                            id="profile_name" name="profile_name[{{ $languages->id }}]" />
                                    </div>
                                </div>

                                <div class="mb-4 row">
                                    <label for="html5-text-input" class="col-md-2 col-form-label">Profile Position</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="Input Position"
                                            id="profile_position" name="profile_position[{{ $languages->id }}]" />
                                    </div>
                                </div>

                                <div class="mb-4 row">
                                    <label for="html5-url-input" class="col-md-2 col-form-label">Content</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control areaEditor" id="content" style="height:221px" name="content[{{ $languages->id }}]"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="tab-pane fade" id="tab-settings" role="tabpanel">
                            <div class="mb-4 row">
                            </div>

                            <div class="container mt-3">
                                <div class="mb-4">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input id="profile_image" name="profile_image" type="file" class="form-control"
                                        data-browse-on-zone-click="true" />
                                    <div id="kartik-file-errors" class="text-danger mt-2"></div>
                                    @error('profile_image')
                                        <div class="text-danger col-form-label">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="status" value="1"
                                        name="status" />
                                    <label class="form-check-label" for="status">
                                        <span id="switchStatus">Status</span>
                                    </label>
                                </div>
                            </div>

                        </div>
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
                window.location.href = '{{ route('administrator.testimonial') }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $("#profile_image").fileinput({
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
    <script src="{{ asset('administrator/js/switchTabOnError.js') }}"></script>
@endsection
