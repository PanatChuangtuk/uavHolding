@extends('main') @section('title')
    @lang('messages.notification')
    @endsection @section('stylesheet')
    @endsection @section('content')
    <div class="section section-profile bg-light pt-0">
        <div class="container has-sidebar">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">@lang('messages.profile')</li>
            </ol>

            <x-nav-profile />

            <div class="content">
                <div class="card-info px-md-5">
                    <h3 class="text-secondary fs-20 mb-3">All Notification</h3>

                    <div class="card-notify-lists">
                        @foreach ($notification as $item)
                            @if ($item->module_name === 'news')
                                @php
                                    $content = $item->news->content
                                        ->where('language_id', $language->id)
                                        ->where('news_id', $item->news->id)
                                        ->first();
                                @endphp
                                <div class="card-notify md">
                                    <a href="{{ route('news.detail', ['lang' => app()->getLocale(), 'id' => $item->news->slug ?: $item->module_id]) }}"
                                        class="card-link"></a>
                                    <img class="card-photo"
                                        src="{{ asset('upload/file/news/' . basename($item->news->images->first()->image)) }}"alt="" />
                                    <div class="card-body">
                                        <div class="group">
                                            <h3>{{ $content->name }}</h3>
                                            <p>{{ Str::limit($content->description, 150) }}</p>
                                        </div>
                                        <div class="link-blue">
                                            @lang('messages.see_detail')</div>
                                    </div>
                                </div>
                            @elseif($item->module_name === 'promotion')
                                @php
                                    $content = $item->promotion->content
                                        ->where('language_id', $language->id)
                                        ->where('promotion_id', $item->promotion->id)
                                        ->first();
                                @endphp
                                <div class="card-notify md">
                                    <a href="{{ route('promotion.detail', ['lang' => app()->getLocale(), 'id' => $item->module_id]) }}"
                                        class="card-link"></a>
                                    <img class="card-photo"
                                        src="{{ asset('upload/file/promotion/' . $item->promotion->image) }}"alt="" />
                                    <div class="card-body">
                                        <div class="group">
                                            <h3>{{ $item->promotion->name }}</h3>
                                            <p>{{ Str::limit($content->description, 150) }}
                                            </p>
                                        </div>
                                        <div class="link-blue">@lang('messages.see_detail')</div>
                                    </div>
                                </div>
                            @elseif($item->module_name === 'promotion_discount')
                                <div class="card-notify md">
                                    <a
                                        href={{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->discount->discountProducts->first()->product->id]) }}class="card-link"></a>
                                    <img class="card-photo"
                                        src="{{ asset('upload/file/product_brand/' . $item->discount->discountProducts->first()->product->ProductModel->productBrand->image) }}"
                                        alt="" />
                                    <div class="card-body">
                                        <div class="group">
                                            <h3>{{ $item->discount->name }}</h3>
                                            <p>{{ $item->discount->discountProducts->first()->product->name }}
                                            </p>
                                        </div>
                                        <div class="link-blue">@lang('messages.see_detail')</div>
                                    </div>
                                </div>
                            @elseif($item->module_name === 'coupon_discount')
                                <div class="card-voucher discount card-notify"
                                    style="display: flex; align-items: center; gap: 10px; height: 90px;">
                                    <a href="{{ url(app()->getLocale() . '/ ') }}" class="card-link"></a>
                                    <img class="card-photo" src="{{ asset('img/icons/icon-discount.png') }}"
                                        style="width: 80px; height: 90px; object-fit: cover;" alt="" />
                                    <div class="card-body">
                                        <h3 style="margin-bottom: 5px;">{{ $item->coupon->name }}</h3>
                                        <!-- เพิ่ม margin-bottom -->
                                        <p style="margin-top: 0;">@lang('messages.quantity') : {{ $item->coupon->limit }}</p>
                                        <!-- ลด margin-top -->

                                    </div>
                                    <div class="link-blue" style="text-align: right;">
                                        @lang('messages.see_detail')
                                    </div>
                                </div>
                            @elseif($item->module_name === 'order')
                                @foreach ($item->order->orderProducts as $orderProduct)
                                    <div class="card-notify md">
                                        <a href="{{ route('purchase', ['lang' => app()->getLocale()]) }}"
                                            class="card-link"></a>
                                        <img class="card-photo"
                                            src="{{ asset('upload/file/product_brand/' . $orderProduct->product->productModel->productBrand->image) }}"
                                            alt="" />
                                        <div class="card-body">
                                            <div class="group">
                                                <h3>@lang('messages.product_status')</h3>
                                                <p>{{ $item->order->order_number . ' - ' . $item->order->status }}</p>
                                                <p>{{ $orderProduct->product->name }}</p>
                                                <p><i class='bx bx-calendar-check'>{{ $orderProduct->created_at }}</i></p>
                                            </div>
                                            <div class="link-blue">@lang('messages.see_detail')</div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($item->module_name === 'order_checkout')
                                @foreach ($item->orderCheckout->orderProducts as $orderProduct)
                                    <div class="card-notify md">
                                        <a href="{{ route('purchase', ['lang' => app()->getLocale()]) }}"
                                            class="card-link"></a>
                                        <img class="card-photo"
                                            src="{{ asset('upload/file/product_brand/' . $orderProduct->product->productModel->productBrand->image) }}"
                                            alt="" />
                                        <div class="card-body">
                                            <div class="group">
                                                <h3>@lang('messages.new_order_placed')</h3>
                                                <p>@lang('messages.order')
                                                    : {{ $item->order->order_number . ' เลขจัดส่ง :' . 'WAITFORAPI' }}
                                                </p>
                                                <p><i class='bx bx-calendar-check'>{{ $orderProduct->created_at }}</i></p>
                                            </div>
                                            <div class="link-blue">@lang('messages.see_detail')</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach

                    </div>
                </div>
                <!--card-info-->
            </div>
            <!--content-->
        </div>
        <!--container-->
    </div>
    <!--section-->
    @endsection @section('script')
@endsection
