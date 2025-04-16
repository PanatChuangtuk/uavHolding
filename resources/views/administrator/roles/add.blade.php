@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.roles') }}">Roles</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
    <form id="form-create" action="{{ route('administrator.roles.submit') }}" method="POST" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.roles') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card p-4">
            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">Role Name</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Enter Role Name" id="name" name="name"
                        value="{{ old('name') }}" required />
                    @error('name')
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
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = '{{ route('administrator.role') }}';
            });
        </script>
    @endif
@endsection
