@extends('main')
@section('title')
    @lang('messages.change_password')
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
                <div class="card-info main px-5">
                    <div class="boxed py-3">
                        <div class="article pb-3" style="--font-size: 14px; --color: #375b51">
                            <h2 class="title-xl text-secondary fw-600">
                                @lang('messages.setup_new_password')
                            </h2>

                            <p>@lang('messages.password_must_be_at_least_8_characters')</p>
                        </div>

                        <form class="form" method="POST"
                            action="{{ route('profile.reset.password.submit', ['lang' => app()->getLocale()]) }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="title">@lang('messages.current_password')</label>
                                        <div class="group">
                                            <span class="icons icon-eye right"></span>
                                            <input type="password" class="form-control pw" name="password_old"
                                                id="password_old" placeholder="@lang('messages.enter_old_password')" />
                                        </div> @error('password_old')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="title">@lang('messages.new_password')</label>
                                        <div class="group">
                                            <span class="icons icon-eye right"></span>
                                            <input type="password" class="form-control pw" name="password" id="password"
                                                placeholder="@lang('messages.enter_your_password')" />

                                        </div> @error('new_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="title">@lang('messages.confirm_password')
                                        </label>
                                        <div class="group">
                                            <span class="icons icon-eye right"></span>
                                            <input type="password" class="form-control pw" name="password_confirmation"
                                                id="password_confirmation" placeholder="@lang('messages.enter_your_password')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex pt-sm-4">
                                    <button class="btn px-5 ms-auto me-sm-0 me-auto" type="submit"
                                        data-bs-target="#successModal" data-bs-toggle="modal">
                                        <span class="px-3">@lang('messages.confirm')</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!--boxed-->
                </div>
                <!--card-info-->
            </div>
            <!--content-->
        </div>
        <!--container-->
    </div>
    <!--section-->
    @endsection @section('script')
@endsection
