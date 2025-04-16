@extends('main')
@section('title')
    @lang('messages.my_point')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section section-profile bg-light pt-0">
        <div class="container has-sidebar">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">@lang('messages.profile')</li>
            </ol>

            <x-nav-profile />

            <div class="content">
                <ul class="nav nav-buttons ">
                    <li class="w-185">
                        <a class="btn" href="{{ url(app()->getLocale() . '/my-coupon') }}">@lang('messages.my_coupon')</a>
                    </li>
                    <li class="w-185">
                        <a class="btn active" href="{{ url(app()->getLocale() . '/my-point') }}">@lang('messages.my_point')</a>
                    </li>
                </ul>

                <ul class="nav nav-buttons point">
                    <li>
                        <div class="btn points">
                            <img class="icons" src="{{ asset('img/icons/icon-point.png') }}" alt="">
                            <span class="text-orange">{{ $userId->point }}</span>
                            <small>Point</small>
                        </div>
                    </li>
                    <li>
                        <button class="btn" type="button" data-bs-toggle="tab"
                            onclick="location.href='?type=all'">@lang('messages.all_history')</button>
                    </li>
                    <li>
                        <button class="btn" type="button" data-bs-toggle="tab"
                            onclick="location.href='?type=received'">@lang('messages.received')</button>
                    </li>
                    <li>
                        <button class="btn" type="button" data-bs-toggle="tab"
                            onclick="location.href='?type=used'">@lang('messages.used')</button>
                    </li>
                </ul>
                @foreach ($point as $item)
                    <div class="card-point @if ($item->status == 1) plus @endif">
                        <div class="card-icon">
                            <img class="icons" src="{{ asset('img/icons/icon-point.png') }}" alt="">
                        </div>

                        <div class="card-body">
                            <div>
                                <p>@lang('messages.order_number') : {{ $item->order->order_number }}</p>
                                <p><small>{{ $item->created_at->setTimezone('Asia/Bangkok') }}</small></p>
                            </div>
                            @if ($item->status === 1)
                                <p class="point">+ {{ $item->point }}</p>
                            @else
                                <p class="point">- {{ $item->point }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
                @include('pagination-front', ['items' => $point])

            </div><!--content-->

        </div><!--container-->
    </div><!--section-->
@endsection
@section('script')
@endsection
