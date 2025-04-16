@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />
            <div class="card">
                <div class="card-body">
                    <h2>SEO Configuration</h2>
                    {{-- Table --}}
                    <div class="table-responsive">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="font-size: 1rem;">
                                    </th>
                                    <th>Page</th>
                                    <th class="text-center ">Tag Title</th>
                                    <th class="text-center">Tag Description</th>
                                    <th class="text-center">Tag Keywords</th>

                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="table-border-bottom-0" id="seoTableBody">
                                @foreach ($seo as $item)
                                    <tr>
                                        <td>

                                        </td>
                                        <td style="text-transform: uppercase; font-weight: bold;"class="align-middle">
                                            {{ $item->type }}
                                        </td>
                                        <td class="align-middle">
                                            {{ Str::limit($item->seoContents->first()->tag_title ?? '', 100) }}</td>
                                        <td class="align-middle">
                                            {{ Str::limit($item->seoContents->first()->tag_description ?? '', 100) }}</td>
                                        <td class="align-middle">
                                            {{ Str::limit($item->seoContents->first()->tag_keywords ?? '', 100) }}</td>

                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="d-inline-block text-nowrap">
                                                    <a class="btn btn-icon btn-outline-primary border-0"
                                                        href="{{ route('administrator.seo.edit', ['id' => $item->id]) }}"
                                                        style="--bs-btn-hover-bg: #28a745; --bs-btn-hover-border-color: #28a745; --bs-btn-hover-color: white;">
                                                        <i class="bx bx-edit bx" style="color: inherit;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div>
                            {!! $seo->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const currentPath = window.location.pathname;
        const bulkDeleteUrl = currentPath.endsWith('/') ? currentPath + 'bulk-delete' : currentPath + '/bulk-delete';
    </script>
@endsection

@section('script')
@endsection
