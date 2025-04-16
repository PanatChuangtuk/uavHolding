@extends('main')
@section('title')
    @lang('messages.news_detail')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section p-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/news') }}">@lang('messages.news')</a></li>
                <li class="breadcrumb-item active">{{ $newsContent->content_name ?? null }}</li>
            </ol>
            <div class="news-detail-boxed">
                <div class="news-hgroup">
                    <h1>{{ $newsContent->content_name ?? null }}</h1>
                    <div class="date">
                        <img class="icons" src="{{ asset('img/icons/icon-calendar.svg') }}" alt="Calendar Icon" />
                        <small>@lang('messages.update') {{ $newsContent->updated_at ?? null }}</small>
                    </div>
                </div>
                {!! $newsContent->content ?? null !!}
            </div>
        </div>
    </div>
    @endsection @section('script')
@endsection
