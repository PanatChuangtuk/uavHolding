@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .label-center {
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav-tabs .nav-item {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Seo</li>
    </ol>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">SEO {{ strtoupper($seo->type) }}</h5>
            <form action="{{ route('administrator.seo.update', $seo->id) }}" method="POST">
                @csrf


                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach ($languages as $index => $language)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $index === 0 ? 'active' : '' }}" id="{{ $language->code }}-tab"
                                data-bs-toggle="tab" href="#{{ $language->code }}" role="tab"
                                aria-controls="{{ $language->code }}" aria-selected="true">
                                {{ $language->code }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="myTabContent">
                    @foreach ($languages as $index => $language)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $language->code }}"
                            role="tabpanel" aria-labelledby="{{ $language->code }}-tab">


                            <div class="mb-4 row">
                                <label for="tag_title{{ $language->id }}"
                                    class="col-md-3 col-form-label text-end fw-bold">Tag
                                    Title
                                    ({{ $language->code }})
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text"
                                        value="{{ $seoContent[$language->id]->tag_title ?? '' }}"
                                        id="tag_title-{{ $language->id }}" name="tag_title[{{ $language->id }}]" />
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="tag_description_{{ $language->code }}"
                                    class="col-md-3 col-form-label text-end fw-bold">Tag Description
                                    ({{ $language->code }})</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text"
                                        value="{{ $seoContent[$language->id]->tag_description ?? '' }}"
                                        id="tag_description{{ $language->id }}"
                                        name="tag_description[{{ $language->id }}]" />
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="tag_keywords_{{ $language->code }}"
                                    class="col-md-3 col-form-label text-end fw-bold">Tag Keywords
                                    ({{ $language->code }})</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text"
                                        value="{{ $seoContent[$language->id]->tag_keywords ?? '' }}"
                                        id="tag_keywords{{ $language->id }}" name="tag_keywords[{{ $language->id }}]" />
                                </div>
                            </div>


                        </div>
                    @endforeach
                </div>
                <div class="mb-4 row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
                window.location.href = '{{ route('administrator.seo') }}';
            });
        </script>
    @endif
@endsection
