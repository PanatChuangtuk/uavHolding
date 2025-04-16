@extends('administrator.layouts.main')

@section('stylesheet')
    <style>
        .dropdown-right {
            display: flex;
            justify-content: flex-end;
        }

        .td-clickable {
            cursor: pointer;
            color: rgb(10, 103, 243);
            transition: color 0.2s ease-in-out;
        }

        .td-clickable:hover {
            color: black;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .card-body:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-outline-info {
            border: 1px solid #17a2b8;
            color: #17a2b8;
            background-color: transparent;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-outline-info:hover {
            background-color: #17a2b8;
            color: #fff;
        }

        .badge {
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 12px;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <h5 class="mb-2">TOTAL ORDERS</h5>
                        </div>
                        <h3 class="card-title mb-3 text-center" style=" font-weight: bold;">
                            {{ number_format($orders->count()) }}</h3>
                        <div class="mt-4">
                            <button type="button" onclick="window.location.href='{{ route('administrator.order') }}'"
                                class="btn btn-outline-info" style="float: right;">View More</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <h5 class="mb-2">TOTAL SALE</h5>
                        </div>
                        <h3 class="card-title mb-3 text-center" style=" font-weight: bold;">
                            {{ number_format($orders->sum('total'), 2) }} ฿</h3>
                        <div class="mt-4">
                            {{-- <button type="button" class="btn btn-outline-info" style="float: right;">View More</button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <h5 class="mb-2">TOTAL CUSTOMER</h5>
                        </div>
                        <h3 class="card-title mb-3 text-center" style=" font-weight: bold;">
                            {{ number_format($member->count()) }}</h3>
                        <div class="mt-4">
                            <button type="button" onclick="window.location.href='{{ route('administrator.member') }}'"
                                class="btn btn-outline-info" style="float: right;">View More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row row justify-content-center">
            <div class="col-lg-8 col-md-6 mb-4 text-center">
                <div class="card" style="min-height: 350px;">
                    <h5 class="card-header">Latest Order</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Customer</th>
                                    <th>Order Number</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($orders_member as $item)
                                    <tr>
                                        <td>{{ $item->orders_id }}</td>
                                        <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                                        <td onclick="window.location.href='@if ($item->status == 'Waiting Approve') {{ route('administrator.approve-order.edit', ['id' => $item->orders_id]) }}@else{{ route('administrator.order.edit', ['id' => $item->orders_id]) }} @endif'"
                                            class="td-clickable">
                                            {{ $item->order_number }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                            @if ($item->status == 'Waiting Approve') bg-label-warning
													@elseif($item->status == 'Approve')
														bg-label-success
													@else
														bg-label-danger @endif
                                            me-1">{{ $item->status }}</span>
                                        </td>
                                        <td><span
                                                class="badge bg-label-primary me-1">{{ \Carbon\Carbon::parse($item->order_created_at)->format('d/m/Y') }}
                                            </span></td>
                                        <td>
                                            {{ number_format($item->total, 2) }}฿
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
@endsection
