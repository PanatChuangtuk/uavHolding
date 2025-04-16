@extends('main')
@section('title')
    @lang('messages.reviews')
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
                <ul class="nav nav-buttons">
                    <li class="w-185">
                        <a class="btn active" href="{{ url(app()->getLocale() . '/reviews') }}">To Review</a>
                    </li>
                    <li class="w-185">
                        <a class="btn" href="{{ url(app()->getLocale() . '/my-reviews') }}">My Review</a>
                    </li>
                </ul>

                @if ($review->isEmpty())
                    <div class="empty-cart-message">
                        <h3>@lang('messages.item_is_empty')</h3>
                    </div>
                @else
                    @foreach ($review as $item)
                        <div class="card-info purchase pt-2 px-5">
                            <div class="info-row border-bottom-1 pe-0">
                                <p><strong>Order No :</strong>{{ $item->order_number }}</p>

                                <label class="purchase-status completed">Completed</label>
                            </div>

                            <ul class="ul-table ul-table-body infos">
                                <li class="photo">
                                    <img src="{{ asset('img/thumb/photo-400x455--1.jpg') }}" alt="" />
                                </li>
                                <li class="info">
                                    <div class="product-info">
                                        <h3>{{ $item->order_product_name }}</h3>
                                        <label class="label">Size : {{ $item->product_size }}</label>
                                        <p><small>Model : {{ $item->product_model }}</small></p>
                                    </div>
                                </li>
                                <li class="button pt-0 ms-auto">
                                    <a class="btn btn-yellow-light review"
                                        href="{{ url(app()->getLocale() . '/product-detail', $item->product_model_id) }}">Review</a>
                                </li>
                            </ul>

                            <hr class="m-0 light" />

                            <div class="info-row">
                                <p>Total : {{ $item->order_product_quantity }}</p>
                                <p class="price">
                                    {{ number_format($item->order_product_price * $item->order_product_quantity, 2) }}฿
                                </p>
                            </div>

                            <div class="info-row">
                                <p style="color: #faad17">Please Review the Product</p>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <!--content-->
        </div>
        <!--container-->
    </div>
    <!--section-->
    @endsection @section('script')
@endsection
