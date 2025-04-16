@extends('main')
@section('title')
    @lang('messages.contacts')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section p-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item active">@lang('messages.contact')</li>
            </ol>
            <h1 class="title-xl text-underline">@lang('messages.contact')</h1>
            <div class="p-3"></div>
            <div class="row g-4 align-items-xl-center">
                <div class="col-lg-6">
                    <img class="w-100 rounded-16" src="{{ asset('upload/file/contact/' . basename($contact->image)) }}"
                        alt="" />
                </div>
                <!--col-lg-6-->
                <div class="col-lg-6">
                    <div class="boxed contact me-0" style="--width: 440px">
                        <img class="logo-contact" src="{{ asset('img/logo.png') }}" alt="" />

                        <ul class="nav nav-contact in-content">
                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-map-2.svg') }}" alt="" />
                                <div>
                                    <h4>@lang('messages.address')</h4>
                                    {{ $contact->address ?? null }}
                                </div>
                            </li>

                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-call-2.svg') }}" alt="" />
                                <div>
                                    <h4>@lang('messages.phone')</h4>
                                    <a
                                        href="tel:{{ $contact->phone ?? null }}">{{ preg_replace('/^0(\d{2})(\d{3})(\d{4})$/', '+(66)$1-$2-$3', $contact->phone) ?? null }}</a>
                                </div>
                            </li>

                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-notebook-2.svg') }}" alt="" />
                                <div>
                                    <h4>@lang('messages.fax')</h4>
                                    <a
                                        href="tel:{{ $contact->fax ?? null }}">{{ preg_replace('/^0(\d{2})(\d{3})(\d{4})$/', '+(66)$1-$2-$3', $contact->fax) ?? null }}</a>
                                </div>
                            </li>

                            <li>
                                <img class="icons" src="{{ asset('img/icons/icon-sms-2.svg') }}" alt="" />
                                <div>
                                    <h4>@lang('messages.email')</h4>
                                    <a href="mailto:{{ $contact->email ?? null }}">{{ $contact->email ?? null }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--col-lg-6-->
            </div>
            <!--row-->

            <div class="p-4"></div>

            <h2 class="title-xl text-center mb-4">@lang('messages.contact_us')</h2>

            <form class="row g-4 gx-xl-5" id="contactUsForm"
                method="POST"action="{{ route('contact_us.submit', app()->getLocale()) }}" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="title">@lang('messages.name')</label>
                        <input type="text" class="form-control" name="name" placeholder="@lang('messages.input_name')" />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="title">@lang('messages.subject')</label>
                        <input type="text" class="form-control" name="subject" placeholder="@lang('messages.input_subject')" />
                        @error('subject')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="title">@lang('messages.email')</label>
                        <input type="email" class="form-control" name="email" placeholder="@lang('messages.input_email')" />
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="title">@lang('messages.phone_number')</label>
                        <input type="tel" class="form-control" name="phone_number" placeholder="@lang('messages.input_phone')"
                            maxlength="10" />
                        @error('phone_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="title">@lang('messages.attachment')</label>
                        <div class="file-upload-group mt-2">
                            <input type="file" name="image" id="file" class="input-file"
                                accept=".jpg,.jpeg,.png,.pdf" />
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <label for="file" class="btn js-labelFile">
                                <i class="icons icon-upload"></i>
                                <span class="js-fileName">@lang('messages.upload_file')</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="title">@lang('messages.message')</label>
                        <textarea class="form-control" name="message" placeholder="@lang('messages.input_message')" rows="4"></textarea>
                        @error('message')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- <div class="recaptcha">
                        <img class="w-100" src="{{ asset('img/thumb/recaptcha.png') }}" alt="@lang('messages.recaptcha')" />
                    </div> --}}
                </div>

                <div class="col-md-6 d-flex">
                    <button type="submit" class="btn ms-md-auto mt-auto w-170">@lang('messages.send')</button>
                </div>
            </form>

            <div class="p-5"></div>
        </div>
        <!--container-->
    </div>
    <!--section-->
@endsection
@section('script')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = '{{ route('contact', app()->getLocale()) }}';
            });
        </script>
    @endif
@endsection
