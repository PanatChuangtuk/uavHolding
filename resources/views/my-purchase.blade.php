@extends('main')

@section('title')
    @lang('messages.my_purchase')
@endsection

@section('stylesheet')
    <style>
        .po-number-label {
            font-weight: bold;
            color: #333;
        }

        .po-number-value {
            color: #0D6EFD;

        }

        .po-number-value:hover {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
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
                    <li>
                        <a href="{{ route('purchase', ['lang' => app()->getLocale(), 'status' => 'all']) }}">
                            <button class="btn {{ $status == 'all' ? 'active' : '' }}"
                                type="button">@lang('messages.all')</button>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchase', ['lang' => app()->getLocale(), 'status' => 'Pending']) }}">
                            <button class="btn {{ $status == 'Pending' ? 'active' : '' }}"
                                type="button">@lang('messages.to_pay')</button>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchase', ['lang' => app()->getLocale(), 'status' => 'Processed']) }}">
                            <button class="btn {{ $status == 'Processed' ? 'active' : '' }}"
                                type="button">@lang('messages.to_delivery')</button>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchase', ['lang' => app()->getLocale(), 'status' => 'Processing']) }}">
                            <button class="btn {{ $status == 'Processing' ? 'active' : '' }}"
                                type="button">@lang('messages.to_receive')</button>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchase', ['lang' => app()->getLocale(), 'status' => 'Complete']) }}">
                            <button class="btn {{ $status == 'Complete' ? 'active' : '' }}"
                                type="button">@lang('messages.completed')</button>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('purchase', ['lang' => app()->getLocale(), 'status' => 'cancelled']) }}">
                            <button class="btn {{ $status == 'cancelled' ? 'active' : '' }}"
                                type="button">@lang('messages.cancelled')</button>
                        </a>
                    </li>
                </ul>


                @if ($orderProduct->isEmpty())
                    <div class="empty-cart-message">
                        <h3>@lang('messages.item_is_empty')</h3>
                    </div>
                @endif

                @foreach ($orderProduct as $order)
                    <div class="card-info purchase pt-2 px-5">
                        <div class="info-row border-bottom-1">
                            <p><strong>@lang('messages.purchase_order_no') :</strong> {{ $order->order->order_number }}
                                @if ($order->order->po_number)
                                    @if ($order->order->orderPo && $order->order->orderPo->image)
                                        <a href="{{ asset('upload/file/order_po/' . basename($order->order->orderPo->image ?? '')) }}"
                                            download>
                                            <strong class="po-number-label">PO Number:</strong>
                                            <span class="po-number-value">{{ $order->order->po_number }}</span>

                                        </a>
                                    @else
                                        <strong>&nbsp; PO Number : </strong> {{ $order->order->po_number }}
                                    @endif
                                @endif
                            </p>

                            <label
                                class="purchase-status 
                            @if ($order->status_product == 'Canceled') cancel
                            @elseif ($order->status_product == 'Failed')
                                cancel
                            @elseif ($order->status_product == 'Pending')
                                topay
                            @elseif ($order->status_product == 'Processed')
                                delivery
                            @elseif ($order->status_product == 'Processing')
                                delivery
                            @elseif ($order->status_product == 'Shipped')
                                confirmed
                            @elseif ($order->status_product == 'Refunded')
                                return
                            @elseif ($order->status_product == 'Complete')
                                completed
                            @elseif ($order->status_product == 'Expired')
                                return
                            @else
                                confirmed @endif
                        ">
                                {{ $order->status_product }}
                            </label>
                            {{-- @if ($order->order->orderPayments->first()?->payment_status === 'success')
                            <label class="purchase-status completed">Completed</label>
                                <label class="purchase-status completed">Completed</label>
                            @elseif($order->order->orderPayments->first()?->payment_status === 'fail')
                                <label class="purchase-status cancel">Cancel</label>
                            @else
                                <label class="purchase-status toplay">
                                    @lang('messages.to_pay')
                                </label>
                            @endif --}}
                        </div>

                        <ul class="ul-table ul-table-body infos">
                            <li class="photo">
                                <img src="{{ asset('upload/file/product_brand/' . basename($order->first()->product->productModel->productBrand->image ?? null)) }}"
                                    alt="" />


                            </li>

                            {{-- @foreach ($order->orderProducts as $index => $product)
                                @if ($index == 0) --}}
                            <li class="info">
                                <div class="product-info">
                                    <h3>{{ $order->name }}</h3>
                                    <label class="label">Size: {{ $order->size }}</label>
                                    <p><small>Model: {{ $order->model }}</small></p>
                                </div>
                            </li>
                            <li class="qty">
                                <strong class="fs-16 text-black">x{{ $order->quantity }}</strong>
                            </li>
                            <li class="total">
                                <strong>{{ number_format($order->price * $order->quantity, 2) }} ฿</strong>
                            </li>
                            {{-- @endif
                            @endforeach --}}
                        </ul>

                        <div class="info-row">
                            <p>@lang('messages.total') : {{ $order->order->orderProducts->count() }} @lang('messages.item')</p>
                            {{-- @foreach ($order->orderProducts as $index => $product)
                                @if ($index == 0) --}}
                            <p class="price">
                                {{ number_format($order->price * $order->quantity, 2) }} ฿
                                {{-- {{ number_format($order->orderProducts->sum(function ($product) {return $product->price * $product->quantity;}),2) }} --}}
                                {{-- ฿ --}}
                            </p>
                            {{--  @endif
                            @endforeach --}}
                        </div>

                        <div class="info-row">
                            <div class="d-flex gap-1">
                                @if ($order->status_product === 'Complete')
                                    <p class="text-success">Your product has been shipped.</p>
                                @elseif($order->status_product === 'Failed' || $order->status_product === 'Canceled')
                                    <p class="text-danger">You cancel the order</p>
                                @elseif($order->status_product === 'Refunded')
                                    <p class="text-orange">Waiting for return approval By : admin</p>
                                @elseif($order->status_product === 'Processed' || $order->status_product === 'Processing')
                                    <p>We will deliver the product to you<br>
                                        within 3-5 days.</p>
                                    {{-- @elseif($order->status_product === 'Failed')
                                    <p class="text-danger">You cancel the order</p> --}}
                                @else
                                    <img class="icons w-34" src="{{ asset('img/icons/icon-34x34--1.svg') }}"
                                        alt="" />
                                    <label class="purchase-status">
                                        <span class="px-sm-2">@lang('messages.waiting_seller_confirm')</span>
                                    </label>
                                @endif
                            </div>

                            <p><a class="link link-primary"
                                    href="{{ url(app()->getLocale() . '/cart-check-out', substr(base64_encode($order->order->id), 0, 10)) }}">@lang('messages.see_more')</a>
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-1">
                            @php
                                $tracking = \App\Models\Tracking::where('order_id', $order->order_id)
                                    ->where('order_product_id', $order->id)
                                    ->sum('shipmentQty');

                                $numberOrderW = $order->quantity - $tracking;

                            @endphp

                            @if ($tracking && $order->quantity !== $tracking)
                                <span class="text-success">อนุมัติการจัดส่ง : {{ $tracking }} ชิ้น</span>
                            @else
                            @endif

                            @if ($tracking && $numberOrderW !== 0)
                                <span class="text-danger">รอการจัดส่งอีก : {{ $numberOrderW }} ชิ้น</span>
                            @elseif ($tracking)
                                <span class="text-success">ส่งสินค้าครบแล้ว</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
