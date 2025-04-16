@extends('main')
@section('title')
    @lang('messages.index')
@endsection
@section('stylesheet')
    <style>
        .banner-images {
            width: 1320px;
            height: 720px;
            object-fit: cover;
        }

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
    <div class="section p-0">
        <div class="container">
            <div class="banner">
                <div class="swiper-container swiper-banner">
                    <div class="swiper-wrapper">
                        @foreach ($banner as $item)
                            @if ($item->image !== null)
                                <div class="swiper-slide">
                                    <a href="{{ $item->url }}" target="_blank">
                                        <img class="banner-images"
                                            src="{{ asset('upload/file/banner/' . basename($item->image)) }}" alt="">
                                    </a>
                                </div>
                            @else
                                <div class="swiper-slide">
                                    <img src="{{ asset('upload/404.jpg') }}" alt=""class="banner-images">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div><!--swiper-container-->
                <div class="swiper-pagination-group">
                    <div class="swiper-pagination banner"></div>
                </div>
            </div>

        </div><!--container-->
    </div><!--section-->
    <div class="section p-0">
        <div class="container">
            <div class="category-block" data-aos="fade-in">
                <div class="cols left hgroup">
                    <h2 class="h1">@lang('messages.category')</h2>
                    <p>@lang('messages.products')</p>
                </div>

                <div class="cols right">
                    <div class="row nav">

                        @foreach ($product_type as $item)
                            <div class="col-4">
                                <div class="card-category" data-bs-toggle="tab"
                                    data-bs-target="#tab-category-{{ $loop->index }}">
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
                    </div><!--row-->
                </div>
            </div><!--category-block-->

            <div class="row logo-brand-lists">
                <div class="tab-content tab-category-content" data-aos="fade-in">
                    @foreach ($product_type as $index => $item)
                        <div id="tab-category-{{ $loop->index }}" class="tab-pane">
                            <div class="hgroup">
                                <h2 class="title-line text-uppercase">
                                    <span>{{ $item->name }}</span>
                                </h2>
                            </div>
                            @if ($item->sort === 1)
                                <div class="row">
                                    @foreach ($product_brand as $model)
                                        @if (!empty($model->image))
                                            <div class="col-2">
                                                <a
                                                    href="{{ url(app()->getLocale() . '/product-list/?type=' . $item->id . '&id=' . $model->id) }}">
                                                    <img class="logo-brand"
                                                        src="{{ asset('upload/file/product_brand/' . $model->image) }}"
                                                        alt="{{ $model->name ?? '' }}">
                                                </a>
                                            </div>
                                        @endif
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
                                                            {{ $model->name ?? '' }}</li>
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
                                                            {{ $model->name ?? '' }}</li>
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




            </div><!--container-->
        </div><!--section-->

        <div class="section pt-0 bg-light">
            <div class="container">
                <div class="section-header">
                    <div class="text-center mx-auto">
                        <h2 class="h1 textrow"><span data-aos="fade-up">@lang('messages.recommend')</span></h2>
                        <p data-aos="fade-in">@lang('messages.products')</p>
                    </div>
                </div>
                <div class="swiper-overflow" data-aos="fade-in">
                    <div class="swiper-container swiper-highlight product">
                        <div class="swiper-wrapper">
                            <!-- Loop over recommended products -->
                            @foreach ($recommends->chunk(4) as $chunk)
                                <div class="swiper-slide">
                                    <div class="product-lists">
                                        <div class="row">
                                            @foreach ($chunk as $item)
                                                <div class="col-md-3">
                                                    <div class="card-product thumb">
                                                        <a class="card-link"
                                                            href="{{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->product_model_id]) }}"></a>
                                                        <div class="card-photo">
                                                            <div class="photo"
                                                                style="background-image:  url('{{ asset('upload/file/product_brand/' . basename($item->product_brand_image)) }}');">
                                                                <img src="{{ asset('upload/file/product_brand/' . basename($item->product_brand_image)) }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h3>{{ $item->product_model_name }}</h3>
                                                            <p class="code">@lang('messages.product_code') : {{ $item->code }}
                                                            </p>

                                                            <div class="price-block">
                                                                @if ($item->product_price != $item->discounted_price)
                                                                    <p class="price">
                                                                        ฿{{ number_format($item->discounted_price ?? 0, 2) }}

                                                                    </p>
                                                                    <p class="price-old">
                                                                        ฿{{ number_format($item->product_price ?? 0, 2) }}
                                                                    </p>
                                                                @else
                                                                    <p class="price">
                                                                        ฿{{ number_format($item->product_price ?? 0, 2) }}
                                                                    </p>
                                                                @endif



                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div><!--row-->
                                    </div><!--product-lists-->
                                </div><!--swiper-slide-->
                            @endforeach
                        </div><!--swiper-wrapper-->
                    </div><!--swiper-container-->

                    <div class="swiper-pagination-group">
                        <div class="swiper-pagination d-flex d-xl-none"></div>
                    </div>

                    <div class="swiper-button swiper-button-prev"><span class="icons"></span></div>
                    <div class="swiper-button swiper-button-next"><span class="icons"></span></div>
                </div>
            </div><!--container-->
        </div><!--section-->


        <div class="section pt-0">
            <div class="container">
                <div class="section-header">
                    <div class="text-center mx-auto">
                        <h2 class="h1 textrow"><span data-aos="fade-up">@lang('messages.vouchers')</span></h2>
                        <p data-aos="fade-in">@lang('messages.discount')</p>
                    </div>
                </div>

                <div class="swiper-overflow" data-aos="fade-in">
                    <div class="swiper-container swiper-highlight voucher">
                        <div class="swiper-wrapper">

                            @foreach ($coupon->chunk(3) as $chunk)
                                <div class="swiper-slide">
                                    <div class="voucher-lists">
                                        <div class="row ">
                                            @foreach ($chunk as $item)
                                                <div class="col-sm-4">
                                                    <div class="card-voucher discount">
                                                        <div class="card-photo">
                                                            <img class="icons"
                                                                src="{{ asset('img/icons/icon-discount.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="my-sm-auto">
                                                                <h3>{{ $item->name }}</h3>
                                                                <p>@lang('messages.quantity') : {{ $item->limit }}</p>
                                                            </div>
                                                            <div class="rows">
                                                                <label
                                                                    class="btn btn-32 btn-orange w-110 btn-claim "style="cursor: pointer;"
                                                                    data-coupon-id="{{ $item->id }}">
                                                                    Claim
                                                                </label>
                                                                <a href="#voucherConditionModal-{{ $item->id }}"
                                                                    data-bs-toggle="modal">@lang('messages.conditions')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div><!--row-->
                                    </div><!--product-lists-->

                                </div><!--swiper-slide-->
                            @endforeach
                        </div><!--swiper-wrapper-->
                    </div><!--swiper-container-->

                    <div class="swiper-pagination-group">
                        <div class="swiper-pagination d-flex d-xl-none"></div>
                    </div>
                    <div class="swiper-button swiper-button-prev"><span class="icons"></span></div>
                    <div class="swiper-button swiper-button-next"><span class="icons"></span></div>
                </div>
            </div><!--container-->
        </div><!--section-->

        <div class="section pt-0 bg-light">
            <div class="container">
                <div class="section-header">
                    <div class="text-center mx-auto">
                        <h2 class="h1 textrow"><span data-aos="fade-up">@lang('messages.best_seller')</span></h2>
                    </div>
                </div>
                <div class="swiper-overflow" data-aos="fade-in">
                    <div class="swiper-container swiper-highlight product">
                        <div class="swiper-wrapper">
                            @foreach ($productsBestSeller->chunk(4) as $chunk)
                                <div class="swiper-slide">
                                    <div class="product-lists">
                                        <div class="row ">
                                            @foreach ($chunk as $index => $item)
                                                <div class="col-md-3">
                                                    <div class="card-product thumb">
                                                        <a class="card-link"
                                                            href="{{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->productModel->id]) }}"></a>
                                                        <div class="card-photo">
                                                            <div class="photo"
                                                                style="background-image: url('{{ asset('upload/file/product_brand/' . basename($item->productModel->productBrand->image)) }}');">
                                                                <img src="{{ asset('upload/file/product_brand/' . basename($item->productModel->productBrand->image)) }}"
                                                                    alt="">
                                                            </div>
                                                            <span class="status best-seller">TOP
                                                                {{ $index + 1 }}</span>
                                                        </div>
                                                        <div class="card-body">
                                                            <h3>{{ $item->name }}</h3>
                                                            <p class="code">@lang('messages.product_code') : {{ $item->sku }}
                                                            </p>

                                                            <div class="price-block">
                                                                @if ($item->productPrices->where('member_group_id', $userGroup)->first()->price != $item->discounted_price)
                                                                    <p class="price">
                                                                        ฿{{ $item->discounted_price }}
                                                                    </p>
                                                                    <p class="price-old">
                                                                        ฿{{ number_format($item->productPrices->where('member_group_id', $userGroup)->first()->price) }}
                                                                    </p>
                                                                @else
                                                                    <p class="price">
                                                                        ฿{{ number_format($item->productPrices->where('member_group_id', $userGroup)->first()->price) }}
                                                                    </p>
                                                                @endif
                                                                {{-- <p class="price-old">
                                                                ฿{{ number_format($item->price * 1.5, 2) }}</p> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div><!--row-->
                                    </div><!--product-lists-->

                                </div><!--swiper-slide-->
                            @endforeach
                        </div><!--swiper-wrapper-->
                    </div><!--swiper-container-->

                    <div class="swiper-pagination-group">
                        <div class="swiper-pagination d-flex d-xl-none"></div>
                    </div>
                    <div class="swiper-button swiper-button-prev"><span class="icons"></span></div>
                    <div class="swiper-button swiper-button-next"><span class="icons"></span></div>
                </div>
            </div><!--container-->
        </div><!--section-->

        <div class="section">
            <div class="container">
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
                                                        <img src="{{ asset('upload/404.jpg') }}" alt=""
                                                            class="custom-image">
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
        </div>
        @foreach ($coupon as $item)
            <div id="voucherConditionModal-{{ $item->id }}" class="modal fade" style="--bs-modal-width:550px">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content px-sm-2">
                        <div class="modal-body px-sm-5">
                            <h3 class="fs-20 mb-2">T&C</h3>
                            <div class="card-voucher @if ($item->coupon_type === 'discount') discount @endif">
                                <div class="card-photo">
                                    <img class="icons"
                                        @if ($item->coupon_type === 'free_shipping') src="{{ asset('img/icons/icon-free-shipping.png') }}"@else src="{{ asset('img/icons/icon-discount.png') }}" @endif
                                        alt="">
                                </div>
                                <div class="card-body">
                                    <div class="my-auto">
                                        <h3>{{ $item->name }}</h3>
                                        <p>@lang('messages.quantity') : {{ $item->limit }}</p>
                                    </div>
                                </div>
                            </div>
                            @php
                                $content = $item->couponContent
                                    ->where('coupon_id', $item->id)
                                    ->where('language_id', $language->id)
                                    ->first();
                            @endphp
                            <div class="article pt-4 mt-2" style="--font-size:12px">
                                <h5>@lang('messages.valid_period') </h5>
                                <p>{{ $item->start_date }} - {{ $item->end_date }}</p>

                                <h5>{{ $content->name ?? '' }}</h5>
                                <p>{!! $content->description ?? '' !!}</p>


                            </div>
                            <div class="buttons pb-2 pt-sm-3 pt-2">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                                    <span class="fs-13">OK</span>
                                </button>
                            </div>
                        </div><!--modal-body-->
                    </div><!--modal-content-->
                </div>
            </div>
        @endforeach
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
                    window.location.href = '{{ route('login', ['lang' => app()->getLocale()]) }}';
                });
            </script>
        @endif
        <script>
            $(document).ready(function() {
                $('.btn-claim').click(function() {
                    var couponId = $(this).data('coupon-id');
                    $.ajax({
                        url: '{{ route('coupon.claim', ['lang' => app()->getLocale()]) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            coupon_id: couponId
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Coupon claimed successfully!',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 401) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: '@lang('messages.login_required')',
                                    text: '@lang('messages.please_login_to_continue')',
                                    showConfirmButton: true,
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('login', ['lang' => app()->getLocale()]) }}";
                                });
                            } else if (xhr.status === 400) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: '@lang('messages.coupon_collected')',
                                    text: '@lang('messages.coupon_already_collected')',
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endsection
