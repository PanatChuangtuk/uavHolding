@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <x-bread-crumb />
    <div class="card p-5 shadow-lg border-0 rounded-4 bg-light">
        <h4 class="mb-4 text-center text-dark">Export Products</h4>

        <form action="{{ route('administrator.product.export.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="type" class="form-label fs-5 fw-medium">Select Product Type</label>
                <select name="type" id="type" class="form-select shadow-sm border-0 rounded-3" required>
                    <option value="">-- Select Product Type --</option>
                    @foreach ($productTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5 py-3 shadow-sm rounded-3">
                    <i class="bi bi-file-earmark-arrow-down-fill me-2"></i> Export
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    @if (session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mt-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
@endsection
