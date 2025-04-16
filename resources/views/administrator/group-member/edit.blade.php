@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.group-member') }}">Member Group</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    <form id="form-create" action="{{ route('administrator.group-member.update', $memberGroup->id) }}" method="POST"
        class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.group-member') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>
        <div class="card p-4">
            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">Name</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" value="{{ $memberGroup->name }}" id="name"
                        name="name" disabled />
                </div>
            </div>

            <div class="mb-4 row">
                <label for="code" class="col-md-2 col-form-label">Code</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" value="{{ $memberGroup->code }}" id="code"
                        name="code" disabled />
                </div>
            </div>


            <div class="row mb-4">
                <label class="col-md-2 col-form-label" for="status">Status</label>
                <div class="col-md-10 d-flex align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" value="1" name="status"
                            {{ $memberGroup->status ? 'checked' : '' }} />
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
                window.location.href = '{{ route('administrator.group-member') }}';
            });
        </script>
    @endif
    <script>
        window.csrfToken = @json(csrf_token());
    </script>
    <script type="module" src="{{ asset('administrator/js/ckeditor.js') }}"></script>
@endsection
