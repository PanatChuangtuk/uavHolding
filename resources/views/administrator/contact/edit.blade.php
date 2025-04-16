@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.contact') }}">Contact</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>

    <form id="form-update" action="{{ route('administrator.contact.update', $contact->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="demo-inline-spacing">
            <div class="text-end">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.contact') }}">
                    <button type="button" class="btn btn-danger">Cancel</button>
                </a>
            </div>

            <div class="card">
                <div class="nav-align-top nav-tabs-shadow mb-6">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach ($language as $index => $languages)
                            <li class="nav-item">
                                <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#tab-{{ $languages->code }}"
                                    aria-controls="tab-{{ $languages->code }}" aria-selected="false">
                                    {{ $languages->code }}
                                </button>
                            </li>
                        @endforeach

                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
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
                                    <label for="name-{{ $languages->id }}" class="col-md-2 col-form-label">Name</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" id="name-{{ $languages->id }}"
                                            name="name[{{ $languages->id }}]"
                                            value="{{ $contactContent[$languages->id]->name ?? '' }}" />
                                        @error('name.' . $languages->id)
                                            <div class="text-danger col-form-label">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4 row">
                                    <label for="address-{{ $languages->id }}"
                                        class="col-md-2 col-form-label">Address</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" id="address-{{ $languages->id }}"
                                            name="address[{{ $languages->id }}]"
                                            value="{{ $contactContent[$languages->id]->address ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="contact-info-fields">
                            <div class="mb-4 row">
                                <label for="phone" class="col-md-2 col-form-label">Phone</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" placeholder="Input Phone" id="phone"
                                        name="phone" value="{{ old('phone', $contact->phone ?? '') }}" maxlength="10" />
                                    @error('phone')
                                        <div class="text-danger col-form-label">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="mb-4 row">
                                <label for="fax" class="col-md-2 col-form-label">Fax</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" placeholder="Input Fax" id="fax"
                                        name="fax" maxlength="10" value="{{ $contact->fax }}" />
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="email" class="col-md-2 col-form-label">Email</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="email" placeholder="Input Email" id="email"
                                        name="email" value="{{ $contact->email }}" required />
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-settings" role="tabpanel">
                            <div class="mb-4 row">
                                <input id="image" name="image" type="file" data-browse-on-zone-click="true" />
                                <div class="col-md-10"></div>
                                <div id="kartik-file-errors"></div>
                                @error('image')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
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
                window.location.href = '{{ route('administrator.contact') }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $("#image").fileinput({
                deleteUrl: "{{ route('administrator.contact.delete.image', $contact->id) . '?_token=' . csrf_token() }}",
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
                    @if ($contact->image)
                        "{{ asset('upload/file/contact/' . basename($contact->image)) }}"
                    @else
                        null
                    @endif
                ],
                initialPreviewConfig: [
                    @if ($contact->image)
                        {
                            caption: "{{ basename($contact->image) }}",
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
                        return config.type === 'pdf' || config.type === 'image';
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
            $(".nav-link").on("click", function() {
                var target = $(this).data("bs-target");
                if (target === "#tab-settings") {
                    $("#contact-info-fields").hide();
                } else {
                    $("#contact-info-fields").show();
                }
            });
            $('#form-update').on('submit', function(e) {
                e.preventDefault();
                this.submit();
            });
        });
    </script>
    <script>
        document.getElementById('fax').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
    <script src="{{ asset('administrator/js/switchTabOnError.js') }}"></script>
@endsection
