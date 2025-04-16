@extends('main')
@section('title')
    @lang('messages.product')
@endsection
@section('stylesheet')
    <style>
        .border-left {
            border-left: 2px solid #375B51;

            padding-left: 15px;
        }

        .ul-collapse {
            list-style: none;
            padding: 5;
        }

        .ul-collapse li {
            margin-bottom: 10px;
        }

        .ul-collapse a {
            color: #375B51;
            text-decoration: none;
            -webkit-transition: color 0.15s ease-in-out;
            transition: color 0.15s ease-in-out;
            display: inline-block;
        }
    </style>
@endsection
@section('content')
    <div class="section p-0 bg-light">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item active">@lang('messages.allproduct')</li>
            </ol>

            <div class="section-header product-header pt-2 pb-3">
                <h1 class="title-xl text-underline">@lang('messages.allproduct')</h1>
            </div>

            <div class="row nav card-category-lists">
                @foreach ($product_type as $item)
                    <div class="col-4">
                        <div class="card-category" data-bs-toggle="tab" data-bs-target="#tab-category-{{ $loop->index }}">
                            <div class="card-photo">
                                <div class="photo"
                                    style="background-image: url(
                                    {{ $loop->index === 0
                                        ? asset('img/thumb/photo-800x650--1.jpg')
                                        : ($loop->index === 1
                                            ? asset('img/thumb/photo-800x650--2.jpg')
                                            : asset('img/thumb/photo-800x650--3.jpg')) }}
                                );">
                                    <img class="d-block" src="{{ asset('img/thumb/frame-100x81.png') }}"
                                        alt="{{ $item->name }}" />
                                </div>
                            </div>

                            <div class="card-body">
                                <h3>{{ $item->name }}</h3>
                                <p class="d-flex">
                                    <a class="viewmore">@lang('messages.view_more')</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <!--row-->
        </div>
        <!--container-->
    </div>
    <!--section-->

    <div class="section section-product">
        <div id="s_products" class="section-target"></div>
        <div class="container">
            <div class="tab-content tab-category-content m-0">
                @foreach ($product_type as $index => $item)
                    <div id="tab-category-{{ $loop->index }}" class="tab-pane">
                        <div class="hgroup mt-0">
                            <h2 class="title-line text-uppercase">
                                <span>{{ $item->name }}</span>
                            </h2>
                        </div>
                        @if ($item->sort === 1)
                            <div class="row">
                                @foreach ($product_brand as $model)
                                    <div class="col-4">
                                        <a
                                            href="{{ url(app()->getLocale() . '/product-list/?type=' . $item->id . '&id=' . $model->id) }}">
                                            <img class="logo-brand"
                                                src="{{ asset('upload/file/product_brand/' . $model->image) }}"
                                                alt="{{ $model->name }}">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($item->sort === 2)
                            <div class="row">
                                @foreach ($product_category as $model)
                                    @if ($model->product_type_id === 231)
                                        <div class="col-4 border-left">
                                            <a
                                                href="{{ url(app()->getLocale() . '/product-list/?type=' . $item->id . '&categoryid=' . $model->id) }}">
                                                <ul class="ul-collapse">
                                                    <li
                                                        href="{{ url(app()->getLocale() . '/product-list/?type=' . $item->id . '&categoryid=' . $model->id) }}">
                                                        {{ $model->name }}</li>
                                                </ul>

                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($item->sort === 3)
                            <div class="row">
                                @foreach ($product_category as $model)
                                    @if ($model->product_type_id === 221)
                                        <div class="col-4 border-left">
                                            <a
                                                href="{{ url(app()->getLocale() . '/product-list/?type=' . $item->id . '&categoryid=' . $model->id) }}">
                                                <ul class="ul-collapse">
                                                    <li
                                                        href="{{ url(app()->getLocale() . '/product-list/?type=' . $item->id . '&categoryid=' . $model->id) }}">
                                                        {{ $model->name }}</li>
                                                </ul>

                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>


            {{-- <div class="tab-content tab-category-content m-0">
                @foreach ($product_type as $index => $item)
                    <div id="tab-category-{{ $loop->index }}" class="tab-pane">
                        <div class="hgroup mt-0">
                            <h2 class="title-line text-uppercase">
                                <span>{{ $item->name }}</span>
                            </h2>
                        </div>
                        <div class="row">
                            @foreach ($product_brand as $model)
                                @if ($item->id === $model->product_type_id)
                                    <div class="col-2">
                                        <a
                                            href="{{ url(app()->getLocale() . '/product-list/?type=' . $model->product_type_id . '&id=' . $model->id) }}">
                                            @if ($model && $model->image)
                                                <img class="logo-brand"
                                                    src="{{ asset('upload/file/product_brand/' . $model->image) }}"
                                                    alt="{{ $model->name }}">
                                            @else
                                                <ul class="ul-collapse col-md-4 text-position">
                                                    <li
                                                        href="{{ url(app()->getLocale() . '/product-list/?type=' . $model->product_type_id . '&id=' . $model->id) }}">
                                                        {{ $model->name }}</li>
                                                </ul>
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div> --}}
            <div class="swiper-overflow news" data-aos="fade-in">
                <div class="swiper-overflow-inner">
                    <div class="swiper-container swiper-news">
                        <div class="swiper-wrapper">
                            @foreach ($news as $newsItem)
                                <div class="swiper-slide">
                                    <div class="card-news">
                                        <a class="card-link"
                                            href="{{ route('news.detail', ['lang' => app()->getLocale(), 'id' => $newsItem->slug ?: $newsItem->news_id]) }}"></a>
                                        <div class="card-body">
                                            <h3>{{ $newsItem->name }}</h3>
                                            <h4 class="title-icon">
                                                <img class="icons"
                                                    src="{{ asset('img/icons/icon-circle-right-arrow.svg') }}"
                                                    alt="" />
                                                @lang('messages.view')
                                            </h4>
                                            <p>
                                                <small>{{ $newsItem->updated_at }}</small>
                                            </p>
                                        </div>
                                        <!--card-body-->
                                        <div class="card-photo">
                                            <div class="photo">
                                                @if ($newsItem->image !== null)
                                                    <img src="{{ asset('upload/file/news/' . basename($newsItem->image)) }}"
                                                        alt="" class="custom-image">
                                                @else
                                                    <img src="{{ asset('upload/file/404.png') }}" class="custom-image">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="swiper-pagination-group">
                    <div class="swiper-pagination d-flex d-xl-none"></div>
                </div>
                <div class="swiper-button swiper-button-prev">
                    <span class="icons"></span>
                </div>
                <div class="swiper-button swiper-button-next">
                    <span class="icons"></span>
                </div>
            </div>
        </div>
        <!--container-->
    </div>
    <!--section-->
@endsection
@section('scripts')
    <script>
        // var myModal = new bootstrap.Modal(document.getElementById('projectModal1'))
        // myModal.show();
        $(".card-category-lists").on("click", function(event) {
            $("html, body").animate({
                    scrollTop: $("#s_products").offset().top,
                },
                800,
                function() {}
            );
        });
    </script>
@endsection
