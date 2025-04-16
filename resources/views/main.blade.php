<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>
        {{ optional($seo?->seoContents->firstWhere('language_id', $language->id))->tag_title ?? (app()->getLocale() == 'th' ? 'บริษัท ยูแอนด์วี โฮลดิ้ง (ไทยแลนด์) จำกัด' : 'U&V HOLDING THAILAND') }}
    </title>
    <meta name="description"
        content="{{ optional($seo?->seoContents->firstWhere('language_id', $language->id))->tag_description ?? null }}">
    <meta name="keywords"
        content="{{ optional($seo?->seoContents->firstWhere('language_id', $language->id))->tag_keywords ?? null }}">

    <link rel="icon" href="{{ asset('uandvlogo.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1.0, 
maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    {{-- <title>@yield('title', 'U&V HOLDING')</title> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('css/swiper.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <!-- โหลด Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .empty-cart-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            text-align: center;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        /* เพิ่มความสูงให้กับ select2 */
        .select2-container--default .select2-selection--single {
            font-family: Prompt, Inter, sans-serif;
            font-size: 16px;
            line-height: 39px;
            /* ปรับให้ตัวอักษรสูงขึ้น */
            color: #212529;
            background: #fff;
            border: 1px solid #89A082;
            border-radius: 12px;
            padding: 0 15px 0 40px;
            box-sizing: border-box;
            height: 40px;
            transition: 0.2s ease-in-out;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 39px;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #6F8F6B;
            box-shadow: 0 0 5px rgba(137, 160, 130, 0.5);
        }

        .select2-container--default .select2-results__option {
            font-family: Prompt, Inter, sans-serif;
            font-size: 16px;
            padding: 10px;
        }
    </style>
    @yield('stylesheet')
</head>

<body>

    <div class="preload">
        <span class="loader"></span>
    </div>

    <div class="page  {{ request()->routeIs('index') ? '' : 'logo-hidden' }}">
        <header class="header">
            <div class="navbar-toppage">
                <div class="container">
                    <button class="btn btn-icon navbar-toggle" type="button">
                        <span class="group">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                    <ul class="nav nav-general left member">
                        @foreach ($social as $socialItem)
                            <li>
                                <div class="followus">
                                    <a href="{{ strip_tags($socialItem->html) }}" target="_blank"><img
                                            class="svg-js icons"
                                            src="{{ asset('upload/file/social/' . $socialItem->image) }}"
                                            alt=""></a>
                                </div>
                            </li>
                        @endforeach


                        @guest('member')
                            <div class="member-links">
                                @if (Route::has('login'))
                                    <li>
                                        <a href="{{ url(app()->getLocale() . '/login') }}" class="link">
                                            @lang('messages.login')
                                        </a>
                                    </li>
                                @endif

                                /

                                @if (Route::has('register'))
                                    <li>
                                        <a href="{{ url(app()->getLocale() . '/register') }}" class="link">
                                            @lang('messages.register')
                                        </a>
                                    </li>
                                @endif
                            </div>
                        @endguest

                        @auth('member')
                            <div class="member-links member dropdown">
                                <li>
                                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="link">
                                        @if (empty($profileUser->avatar))
                                            <img class="avatar" src="{{ asset('img/thumb/avatar-1.png') }}"
                                                alt="" />
                                        @else
                                            <img class="avatar"
                                                src="{{ file_exists(public_path('upload/file/profile/' . basename($profileUser->avatar)))
                                                    ? asset('upload/file/profile/' . basename($profileUser->avatar))
                                                    : asset('img/thumb/avatar-2.png') }}"
                                                alt="" />
                                        @endif
                                        <span
                                            class="username">{{ $profileUser->first_name . ' ' . $profileUser->last_name }}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ url(app()->getLocale() . '/profile') }}"> @lang('messages.my_account')</a>
                                        </li>
                                        <li><a href="{{ url(app()->getLocale() . '/my-purchase') }}">@lang('messages.my_purchase')</a>
                                        </li>
                                        <li>
                                            <a href="#" class="logout" onclick="confirmLogout(event)">
                                                @lang('messages.sign_out')
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </div>
                        @endauth

                        <li>
                            <a class="cart-mini" href="{{ route('cart.index', ['lang' => app()->getLocale()]) }}">
                                <div class="btn btn-outline">
                                    <img class="svg-js icons" src="{{ asset('img/icons/icon-cart.svg') }}"
                                        alt="">
                                </div>
                                {{ sizeof($cart) }} @lang('messages.item') (s)
                                @if ($totalPrice > 0)
                                    - {{ number_format($totalPrice, 2) }}฿
                                @endif
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-general right">
                        <li class="nav-search">
                            <a href="#" class="btn btn-outline d-desktop-none" data-bs-toggle="dropdown"
                                data-bs-display="static">
                                <img class="svg-js icons icon-bell" src="{{ asset('img/icons/icon-search.svg') }}"
                                    alt="Search">
                            </a>
                            <div class="dropdown-menu p-3" style="min-width: 250px;">
                                <div class="form-group search position-relative">
                                    <span class="icons icon-search left"></span>
                                    <input type="text" id="searchInput" class="form-control border"
                                        placeholder="ค้นหา...">
                                    <ul id="searchResults" class="list-group position-absolute w-100 mt-2 shadow"></ul>
                                </div>
                            </div>
                        </li>

                        <li class="d-desktop-none">
                            <a class="btn btn-outline"
                                href="{{ route('cart.index', ['lang' => app()->getLocale()]) }}">
                                <img class="icons cart svg-js" src="{{ asset('img/icons/icon-cart.svg') }}"
                                    alt="">
                            </a>
                        </li>
                        @guest('member')
                            <li class="dropdown d-desktop-none">
                                <a class="btn btn-outline" href="#" data-bs-toggle="dropdown"
                                    data-bs-display="static">
                                    <img class="icons avatar svg-js" src="{{ asset('img/icons/icon-user.svg') }}"
                                        alt="">
                                </a>
                                <ul class="dropdown-menu right" style="--width:96px">
                                    @if (Route::has('login'))
                                        <li><a
                                                href="{{ route('login', ['lang' => app()->getLocale()]) }}">@lang('messages.login')</a>
                                        </li>
                                    @endif
                                    @if (Route::has('register'))
                                        <li><a
                                                href="{{ route('register', ['lang' => app()->getLocale()]) }}">@lang('messages.register')</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endguest

                        @auth('member')
                            <li class="dropdown d-desktop-none">
                                <a class="btn btn-outline" href="#" data-bs-toggle="dropdown"
                                    data-bs-display="static">
                                    <img class="icons avatar svg-js" src="{{ asset('img/icons/icon-user.svg') }}"
                                        alt="">
                                </a>
                                <ul class="dropdown-menu right" style="--width:96px">
                                    <li>
                                        <a href="{{ url(app()->getLocale() . '/profile') }}"> @lang('messages.my_account')</a>
                                    </li>
                                    <li><a href="{{ url(app()->getLocale() . '/my-purchase') }}">@lang('messages.my_purchase')</a>
                                    </li>
                                    <li>
                                        <form id="logout-form" action="{{ url(app()->getLocale() . '/logout') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <a href="#" class="logout" onclick="confirmLogout(event)">
                                            @lang('messages.sign_out')
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endauth

                        @auth('member')
                            <li class="dropdown notify">
                                <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                    <img class="svg-js icons icon-bell" src="{{ asset('img/icons/icon-bell.svg') }}"
                                        alt="">
                                    <span class="badge notification-badge">{{ $notificationCount }}</span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <h5 class="title"><a href="notification">@lang('messages.notification')</a></h5>
                                    </li>
                                    @foreach ($notification as $item)
                                        @if ($item->module_name === 'news')
                                            @php
                                                $content = $item->news->content
                                                    ->where('language_id', $language->id)
                                                    ->where('news_id', $item->news->id)
                                                    ->first();
                                            @endphp
                                            <li>
                                                <div class="card-notify">
                                                    <a class="card-link"
                                                        href="{{ route('news.detail', ['lang' => app()->getLocale(), 'id' => $item->news->slug ?: $item->module_id]) }}"
                                                        onclick="updateNotificationClick({{ $item->id }})">
                                                    </a>
                                                    <img class="card-photo"
                                                        src="{{ asset('upload/file/news/' . basename($item->news->images->first()->image)) }}"
                                                        alt="">
                                                    <div class="card-body">
                                                        <h3>{{ $content->name }}</h3>
                                                        <p>{{ Str::limit($content->description, 150) }}</p>
                                                    </div>
                                                </div>
                                            </li>
                                        @elseif($item->module_name === 'promotion')
                                            @php
                                                $content = $item->promotion->content
                                                    ->where('language_id', $language->id)
                                                    ->where('promotion_id', $item->promotion->id)
                                                    ->first();
                                            @endphp
                                            <li>
                                                <div class="card-notify">
                                                    <a href="{{ route('promotion.detail', ['lang' => app()->getLocale(), 'id' => $item->module_id]) }}"
                                                        class="card-link"
                                                        onclick="updateNotificationClick({{ $item->id }})"></a>
                                                    <img class="card-photo"
                                                        src="{{ asset('upload/file/promotion/' . $item->promotion->image) }}"
                                                        alt="">
                                                    <div class="card-body">
                                                        <h3>{{ $item->promotion->name }}</h3>
                                                        <p>{{ Str::limit($content->description, 150) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        @elseif($item->module_name === 'promotion_discount')
                                            <li>
                                                <div class="card-notify">
                                                    <a class="card-link"onclick="updateNotificationClick({{ $item->id }})"
                                                        href={{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->discount->discountProducts->first()->product->id]) }}></a>
                                                    <img class="card-photo"
                                                        src="{{ asset('upload/file/product_brand/' . ($item->discount->discountProducts->first()?->product?->ProductModel?->productBrand?->image ?? 'default.png')) }}"
                                                        alt="">

                                                    <div class="card-body">
                                                        <h3>{{ $item->discount->name }}</h3>
                                                        <p>{{ $item->discount->discountProducts->first()->product->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        @elseif($item->module_name === 'coupon_discount')
                                            <li>
                                                <div class="card-voucher discount"
                                                    style="display: flex; align-items: center; gap: 10px; height: 78px;object-fit: cover;">
                                                    <img class="card-photo"
                                                        src="{{ asset('img/icons/icon-discount.png') }}"
                                                        style="width: 70px; height: 75px; object-fit: cover;"
                                                        alt="">
                                                    <div class="card-body" style="text-align: left;">
                                                        <h3>{{ $item->coupon->name }}</h3>
                                                        <p>@lang('messages.quantity') : {{ $item->coupon->limit }}</p>
                                                        <a href="{{ url(app()->getLocale() . '/ ') }}"
                                                            onclick="updateNotificationClick({{ $item->id }})"
                                                            class="card-link"
                                                            style="display: block; text-decoration: none; color: inherit;">

                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @elseif($item->module_name === 'order')
                                            {{-- @foreach ($item->order->orderProducts as $orderProduct) --}}
                                            <div class="card-notify">
                                                <a href="{{ route('purchase', ['lang' => app()->getLocale()]) }}"
                                                    onclick="updateNotificationClick({{ $item->id }})"
                                                    class="card-link"></a>
                                                {{-- <a href="{{ route('cart.check.index', ['lang' => app()->getLocale(), 'id' => $item->module_id]) }}"
                                                    onclick="updateNotificationClick({{ $item->id }})"
                                                    class="card-link"></a> --}}
                                                <img class="card-photo"
                                                    src="{{ asset('upload/file/product_brand/' . $item->order->orderProducts->first()->product->productModel->productBrand->image) }}"
                                                    alt="" />
                                                <div class="card-body">
                                                    <div class="group">
                                                        <h3>@lang('messages.product_status')</h3>
                                                        <p>{{ $item->order->order_number . ' - ' . $item->order->status }}
                                                        </p>
                                                        <p>{{ $item->order->orderProducts->first()->product->name }}</p>
                                                        <p><i
                                                                class='bx bx-calendar-check'>{{ $item->order->orderProducts->first()->created_at }}</i>
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                            {{-- @endforeach --}}
                                        @elseif($item->module_name === 'order_checkout')
                                            {{-- @foreach ($item->orderCheckout->orderProducts as $orderProduct) --}}
                                            <div class="card-notify">
                                                {{-- <a href="{{ route('cart.check.index', ['lang' => app()->getLocale(), 'id' => $item->module_id]) }}"
                                                    onclick="updateNotificationClick({{ $item->id }})"
                                                    class="card-link"></a> --}}
                                                <a href="{{ route('purchase', ['lang' => app()->getLocale()]) }}"
                                                    onclick="updateNotificationClick({{ $item->id }})"
                                                    class="card-link"></a>
                                                <img class="card-photo"
                                                    src="{{ asset('upload/file/product_brand/' . $item->order->orderProducts->first()->product->productModel->productBrand->image) }}"
                                                    alt="" />
                                                <div class="card-body">
                                                    <div class="group">
                                                        <h3>@lang('messages.new_order_placed')</h3>
                                                        <p>@lang('messages.order')
                                                            :
                                                            {{ $item->order->order_number . ' เลขจัดส่ง :' . 'WAITFORAPI' }}
                                                        </p>
                                                        <p><i
                                                                class='bx bx-calendar-check'>{{ $item->order->orderProducts->first()->created_at }}</i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- @endforeach --}}
                                        @endif
                                    @endforeach
                                    <li class="viewall">
                                        <a
                                            href="{{ route('notification', ['lang' => app()->getLocale()]) }}">@lang('messages.view_all')</a>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                        <li class="dropdown lang">
                            <a class="btn btn-outline" href="#" data-bs-toggle="dropdown"
                                data-bs-display="static">
                                {{ strtoupper(app()->getLocale()) }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ url('lang/en/ ') }}"
                                        class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ url('lang/th/ ') }}"class="{{ app()->getLocale() == 'th' ? 'active' : '' }}">TH</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="navbar-main">
                <div class="container">
                    <div class="navbar-brand">
                        <a href="{{ url(app()->getLocale() . '/ ') }}">
                            <img src="{{ asset('img/logo.png') }}" alt="">
                        </a>
                    </div>

                    <ul class="nav nav-main">
                        <li class="{{ request()->path() === app()->getLocale() ? 'active' : '' }}">
                            <a href="{{ url(app()->getLocale() . '/ ') }}"
                                style="text-transform: uppercase;">@lang('messages.home')</a>
                        </li>
                        <li class="{{ request()->is(app()->getLocale() . '/about') ? 'active' : '' }}">
                            <a href="{{ url(app()->getLocale() . '/about') }}"
                                style="text-transform: uppercase;">@lang('messages.about')</a>
                        </li>
                        <li
                            class="dropdown {{ request()->is(app()->getLocale() . '/product') || request()->is(app()->getLocale() . '/download') || request()->is(app()->getLocale() . '/track-trace') ? 'active' : '' }}">
                            <a href="#" data-bs-toggle="dropdown" data-bs-display="static"
                                style="text-transform: uppercase;">@lang('messages.products')</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url(app()->getLocale() . '/product') }}"
                                        style="text-transform: uppercase;">@lang('messages.products')</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/download') }}"
                                        style="text-transform: uppercase;">@lang('messages.download')</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/track-trace') }}"
                                        style="text-transform: uppercase;">@lang('messages.track_and_trace')</a></li>
                            </ul>
                        </li>
                        <li
                            class="dropdown {{ request()->is(app()->getLocale() . '/service') || request()->is(app()->getLocale() . '/faq') ? 'active' : '' }}">
                            <a href="{{ url(app()->getLocale() . '/service') }}" data-bs-toggle="dropdown"
                                data-bs-display="static">@lang('messages.service')</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url(app()->getLocale() . '/service') }}"
                                        style="text-transform: uppercase;">@lang('messages.service')</a></li>
                                <li><a href="{{ url(app()->getLocale() . '/faq') }}"
                                        style="text-transform: uppercase;">@lang('messages.qa')</a></li>
                            </ul>
                        </li>
                        <li class="{{ request()->is(app()->getLocale() . '/promotion') ? 'active' : '' }}">
                            <a href="{{ url(app()->getLocale() . '/promotion') }}"
                                style="text-transform: uppercase;">@lang('messages.promotion')</a>
                        </li>
                        <li class="{{ request()->is(app()->getLocale() . '/news') ? 'active' : '' }}">
                            <a href="{{ url(app()->getLocale() . '/news') }}"
                                style="text-transform: uppercase;">@lang('messages.news')</a>
                        </li>
                        <li class="{{ request()->is(app()->getLocale() . '/contact') ? 'active' : '' }}">
                            <a href="{{ url(app()->getLocale() . '/contact') }}"
                                style="text-transform: uppercase;">@lang('messages.contact')</a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="navbar-slider">
            <div class="hgroup">
                <button class="btn btn-icon navbar-toggle" type="button">
                    <span class="group">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <ul class="nav nav-general left">
                    <li>
                        <div class="followus">
                            <a href="{{ $socialItem->url }}" target="_blank"><img class="svg-js icons"
                                    src="{{ asset('upload/file/social/' . $socialItem->image) }}" alt=""></a>
                        </div>
                    </li>

                    <div class="followus">
                        <a href="#" target="_blank"><img class="svg-js icons"
                                src="{{ asset('img/icons/icon-facebook.svg') }}" alt=""></a>
                        <a href="#" target="_blank"><img class="svg-js icons"
                                src="{{ asset('img/icons/icon-line.svg') }}" alt=""></a>
                        <a href="#" target="_blank"><img class="svg-js icons"
                                src="{{ asset('img/icons/icon-letter.svg') }}" alt=""></a>
                    </div>
            </div>

            <ul class="nav nav-accordion">
                <li>
                    <h5><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url(app()->getLocale() . '/about') }}">@lang('messages.about')</a></h5>
                </li>
                <li>
                    <h5 data-bs-toggle="collapse" data-bs-target="#product-sub"><a
                            href="#">@lang('messages.products')</a>
                    </h5>
                    <div id="product-sub" class="accordion-collapse collapse" data-bs-parent=".nav-accordion">
                        <ul class="nav">
                            <li><a href="product">@lang('messages.products')</a></li>
                            <li><a href="{{ url(app()->getLocale() . '/download') }}">@lang('messages.download')</a>
                            </li>
                            <li><a href="track-trace">@lang('messages.track_and_trace')</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <h5 data-bs-toggle="collapse" data-bs-target="#service-sub"><a
                            href="{{ url(app()->getLocale() . '/service') }}">@lang('messages.service')</a></h5>
                    <div id="service-sub" class="accordion-collapse collapse" data-bs-parent=".nav-accordion">
                        <ul class="nav">
                            <li><a href="{{ url(app()->getLocale() . '/service') }}">@lang('messages.service')</a>
                            </li>
                            <li><a href="{{ url(app()->getLocale() . '/faq') }}">@lang('messages.qa')</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <h5><a href="{{ url(app()->getLocale() . '/promotion') }}">@lang('messages.promotion')</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url(app()->getLocale() . '/news') }}">@lang('messages.news')</a></h5>
                </li>
                <li>
                    <h5><a href="{{ url(app()->getLocale() . '/contact') }}">@lang('messages.contact')</a></h5>
                </li>
            </ul>
        </div>

        @yield('content')

        <footer class="footer">
            <div class="container">
                <div class="cols footer-info">
                    <div class="group">
                        <p>
                            <span class="fs-24">©</span><br class="d-none d-lg-block">
                            <script>
                                document.write(new Date().getFullYear());
                            </script><br>
                            ALL RIGHTS RESERVED
                        </p>
                        <hr>
                        <p>
                            U&V HOLDING<br class="d-none d-lg-block">
                            THAILAND
                        </p>
                    </div>
                </div><!--cols-->
                <div class="cols footer-links">
                    <div class="group">
                        <ul class="nav">
                            <li><a href="{{ url(app()->getLocale() . '/about') }}">@lang('messages.about')</a></li>
                            <li><a href="{{ url(app()->getLocale() . '/product') }}">@lang('messages.products')</a>
                            </li>
                            <li><a href="{{ url(app()->getLocale() . '/service') }}">@lang('messages.service')</a>
                            </li>
                        </ul>

                        <ul class="nav">
                            <li><a href="{{ url(app()->getLocale() . '/promotion') }}">@lang('messages.promotion')</a>
                            </li>
                            <li><a href="{{ url(app()->getLocale() . '/news') }}">@lang('messages.news')</a></li>
                            <li><a href="{{ url(app()->getLocale() . '/contact') }}">@lang('messages.contact')</a>
                            </li>
                        </ul>

                        <ul class="nav">
                            <li><a
                                    href="{{ url(app()->getLocale() . '/term-condition-user') }}">@lang('messages.term_and_condition')</a>
                            </li>
                            <li><a
                                    href="{{ url(app()->getLocale() . '/privacy-policy-user') }}">@lang('messages.privacy_policy')</a>
                            </li>
                        </ul>
                    </div>
                </div><!--cols-->
                <div class="cols footer-contact">
                    <div class="group">

                        <div class="followus">
                            @foreach ($social as $socialItem)
                                <a href="{{ strip_tags($socialItem->html) }}" target="_blank"><img
                                        class="svg-js icons"
                                        src="{{ asset('upload/file/social/' . $socialItem->image) }}"
                                        alt=""></a>
                            @endforeach
                        </div>

                        <ul class="nav nav-contact in-content" style="margin-top: -30px;">
                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-map.svg') }}" alt="">
                                <span>{{ $contact->address ?? null }}</span>
                            </li>

                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-call.svg') }}" alt="">
                                <a
                                    href="tel:{{ $contact->phone ?? null }}">{{ preg_replace('/^0(\d{2})(\d{3})(\d{4})$/', '+(66)$1-$2-$3', $contact->phone) ?? null }}</a>

                            </li>

                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-notebook.svg') }}"
                                    alt="">
                                <a
                                    href="tel:{{ $contact->fax ?? null }}">{{ preg_replace('/^0(\d{2})(\d{3})(\d{4})$/', '+(66)$1-$2-$3', $contact->fax) ?? null }}</a>
                            </li>

                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-sms.svg') }}" alt="">
                                <a href="mailto:{{ $contact->email ?? null }}">{{ $contact->email ?? null }}</a>
                            </li>
                        </ul>

                    </div><!--group-->
                </div><!--cols-->
            </div><!--container-->

            <div class="totop">
                <a class="icons" href="#">
                    <img class="svg-js" src="{{ asset('img/icons/icon-totop.svg') }}" alt="">
                </a>
            </div>
        </footer>

        <div id="cookiePolicyPopup" class="cookie-policy">
            <div class="container-fluid">
                <div class="cols">
                    <h6>@lang('messages.website_privacy_policy')</h6>
                    <p>@lang('messages.website_experience_improvement')<br class="d-none d-lg-block">
                        @lang('messages.confirm_permission')<a href="#">@lang('messages.privacy_policy')</a></p>
                </div>

                <div class="cols">
                    <div class="buttons">
                        <button class="btn btn-secondary accept" type="button">@lang('messages.accept')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.4.1.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fancybox.js') }}" defer></script>
    <script src="{{ asset('js/swiper.js') }}" defer></script>
    <script src="{{ asset('js/aos.js') }}" defer></script>
    <script src="{{ asset('js/jquery.scrollbar.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function eraseCookie(name) {
            document.cookie = name + '=; Max-Age=-86400';
        }

        $(document).ready(function() {
            if (getCookie('cookieAccepted')) {
                $('#cookiePolicyPopup').hide();
            }
            $('.accept').on('click', function() {
                setCookie('cookieAccepted', 'true', 1);
                $('#cookiePolicyPopup').hide();
            });
        });
    </script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: '@lang('messages.are_you_sure')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '@lang('messages.ok')',
                cancelButtonText: '@lang('messages.cancel')',
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    <script>
        function updateNotificationClick(newsId) {
            $.ajax({
                url: '{{ route('notification.update', ['lang' => app()->getLocale(), 'id' => '__ID__']) }}'
                    .replace('__ID__', newsId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log('News clicked and updated successfully!');
                    updateNotificationCount();
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        }

        function updateNotificationCount() {
            $.ajax({
                url: '{{ route('notification.count', ['lang' => app()->getLocale()]) }}',
                type: 'GET',
                success: function(response) {
                    $('.notification-badge').text(response.count);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#searchInput").on("input", function() {
                var query = $(this).val().trim();
                if (query.length < 1) {
                    $("#searchResults").empty();
                    return;
                }

                $.ajax({
                    url: "/get-model-single",
                    type: "GET",
                    data: {
                        query: query
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#searchResults").empty();
                        if (data.results.length > 0) {
                            $.each(data.results, function(index, item) {
                                $("#searchResults").append(
                                    `<li class="list-group-item" data-id="${item.id}" data-type="${item.product_type_id}" data-brand="${item.product_brand_id}" data-category="${item.product_category_id}">
                                        <a href="#" class="text-dark text-decoration-none">${item.name}</a>
                                    </li>`
                                );
                            });
                        } else {
                            $("#searchResults").append(
                                `<li class="list-group-item text-muted">ไม่พบข้อมูล</li>`
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            });

            $(document).on("click", "#searchResults .list-group-item", function() {
                var selectedId = $(this).data("id");
                var type = $(this).data("type");
                var brand = $(this).data("brand");
                var category = $(this).data("category");

                var url = "{{ url(app()->getLocale() . '/product-list') }}?type=" + type +
                    (type === 222 ? "&id=" + brand : "&categoryid=" + category) +
                    "&Modelid=" + selectedId;
                window.location.href = url;
            });

            $(document).on("click", function(event) {
                if (!$(event.target).closest(".nav-search").length) {
                    $("#searchResults").empty();
                }
            });
            $("#searchInput").on("keypress", function(e) {
                if (e.which == 13) {
                    var query = $(this).val().trim();
                    if (query.length >= 2) {
                        $.ajax({
                            url: "/get-model-single",
                            type: "GET",
                            data: {
                                query: query
                            },
                            dataType: "json",
                            success: function(data) {

                                // if (data.results.length > 0) {
                                var query = data.query;
                                var url =
                                    "{{ url(app()->getLocale() . '/product-list') }}?queryVaule=" +
                                    query;
                                window.location.href = url;
                                // }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching data:", error);
                            }
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>
@yield('script')
