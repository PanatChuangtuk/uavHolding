@extends('main')
@section('title')
    @lang('messages.my_coupon')
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
                        <a class="btn active" href="{{ url(app()->getLocale() . '/my-coupon') }}"> @lang('messages.my_coupon')</a>
                    </li>
                    <li class="w-185">
                        <a class="btn" href="{{ url(app()->getLocale() . '/my-point') }}">@lang('messages.my_point')</a>
                    </li>
                </ul>

                <div class="boxed voucher-lists">
                    <div class="row">

                        @foreach ($my_coupon as $coupon)
                            <div class="col-md-6">
                                <div class="card-voucher discount">
                                    <div class="card-photo">
                                        <img class="icons" src="{{ asset('img/icons/icon-discount.png') }}" alt="">
                                    </div>
                                    <div class="card-body">
                                        <div class="my-auto">
                                            <h3>{{ $coupon->name }} </h3>
                                            {{-- <p>@lang('messages.quantity') : {{ $coupon->coupon->limit }}</p> --}}
                                        </div>

                                        <div class="rows">
                                            <label class="btn btn-32 btn-orange w-110 claimed">Claimed</label>

                                            <a href="#voucherConditionModal-{{ $coupon->id }}"
                                                data-bs-toggle="modal">@lang('messages.conditions')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div><!--row-->
                </div>

            </div><!--content-->

        </div><!--container-->
    </div><!--section-->
    @foreach ($my_coupon as $item)
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
@endsection
