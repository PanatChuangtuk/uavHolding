@extends('main')
@section('title')
    @lang('messages.product_list')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section p-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ url(app()->getLocale() . '/product') }}">@lang('messages.allproduct')</a>
                </li>
                <li class="breadcrumb-item active"> {{ $product->first()?->productBrand->name ?? 'No Brand' }}</li>
            </ol>

            <div class="section-header filter">
                <h1 class="title-xl text-underline">@lang('messages.allproduct')</h1>

                <div class="d-flex gap-gl-4 gap-sm-3 gap-2">
                    <div class="select-pretty">
                        <img class="icons ms-3" src="{{ asset('img/icons/icon-row-vertical.svg') }}" alt="" />
                        <div class="dropdown form-select">
                            <a href="#" class="fw-500 selected" data-bs-toggle="dropdown" data-bs-display="static">
                                {{ request('per_page', 15) }} Products
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ([15, 30, 60] as $value)
                                    <li>
                                        <a class="dropdown-item {{ request('per_page', 15) == $value ? 'active' : '' }}"
                                            href="{{ request()->fullUrlWithQuery(['per_page' => $value]) }}">
                                            {{ $value }} Products
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="select-pretty">
                        <h6 class="ms-3">Sort By :</h6>
                        <div class="dropdown form-select">
                            <a href="#" class="fw-500 selected" data-bs-toggle="dropdown" data-bs-display="static">
                                {{ request('sort', 'desc') == 'desc' ? 'Newest' : 'Oldest' }}
                            </a>
                            <ul class="dropdown-menu">
                                <li> <a class="dropdown-item {{ request('sort') == 'desc' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}">
                                        Newest
                                    </a></li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') == 'asc' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}">
                                        Oldest
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row product-lists">
                @foreach ($product as $item)
                    {{-- {{ $item }} --}}
                    <div class="col-12">
                        <div class="card-product" data-aos="fade-in">
                            <a href={{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->id]) }}
                                class="card-link"></a>
                            <div class="card-photo">
                                <div class="photo"
                                    style="
                                background-image: url({{ asset('upload/file/product_brand/' . basename($item->productBrand->image ?? null)) }});
                            ">
                                    <img src="@if ($item->product_type_id == 222 && $item->image) {{ asset('upload/file/model-product/' . basename($item->image)) }}
                                @else {{ asset('upload/file/product_brand/' . basename($item->productBrand->image ?? null)) }} @endif"
                                        alt="" />
                                </div>
                            </div>
                            <div class="card-body">
                                <h3>
                                    {{ $item->name }}
                                </h3>
                                <h6>{{ $item->code }}</h6>

                                {{-- <p>
                                    {{ $item }}
                                </p> --}}
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                @endforeach
            </div>
            @include('pagination-front', ['items' => $product])
        </div>
    </div>
    @endsection @section('script')
@endsection
