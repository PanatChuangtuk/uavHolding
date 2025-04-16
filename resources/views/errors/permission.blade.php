@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
            <div style="text-align: center;">
                <h1 style="font-weight: bold; font-size: 2em;">Permission Denied</h1>
                <p style="font-size: 1.5em;">You don't have permission to access this page.</p>
            </div>
        </div>
    </div>
@endsection