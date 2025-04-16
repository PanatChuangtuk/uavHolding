@extends('main')
@section('title')
    @lang('messages.privacy_policy')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section pt-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item active">@lang('messages.privacy_policy')</li>
            </ol>

            <h1 class="title-xl fw-600 text-secondary text-center">
                {{ $condition->content_name }}
            </h1>
            <p class="fs-14 text-secondary mb-2 text-center"> {{ $condition->description }}</p>
            <div class="article">
                <ul>
                    {!! $condition->content !!}
                </ul>
            </div>


        </div>
        <!--container-->
    </div>
    <!--section-->
    @endsection @section('script')
@endsection
