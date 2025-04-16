@extends('main')

@section('title')
    @lang('messages.abouts')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <div class="section pt-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item active">@lang('messages.about')</li>
            </ol>
            <h3>Success</h3>

        </div>
    </div>
@endsection

@section('script')
@endsection
