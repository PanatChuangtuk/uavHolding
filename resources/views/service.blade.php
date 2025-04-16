@extends('main')
@section('title')
    @lang('messages.services')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section pt-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item active">@lang('messages.service')</li>
            </ol>



            <div class="article">
                <ul>
                    {!! $service->content !!}
                </ul>
            </div>

            <div class="buttons mb-5">
                <a class="btn btn-48 gap-3 px-5" href="{{ url(app()->getLocale() . '/product') }}">
                    <img class="icons svg-js white" src="{{ asset('img/icons/icon-cart.svg') }}" alt="" />
                    Go to Shop
                    <img class="icons svg-js white" src="{{ asset('img/icons/icon-next-2.svg') }}" alt="" />
                </a>
            </div>
        </div>
        <!--container-->
    </div>
    <!--section-->
    @endsection @section('script')
@endsection
