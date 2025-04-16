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
                        <form id="forgotPasswordForm" class="form" method="POST"
                            action="{{ route('otp.request.submit', ['lang' => app()->getLocale()]) }}">
                            @csrf
                            <div class="article pb-3" style="--font-size: 14px; --color: #375b51">
                                <h2 class="title-xl text-secondary fw-600">
                                    @lang('messages.otp_verification')
                                </h2>

                                <p>
                                    @lang('messages.check_phone_verification')
                                </p>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="fs-14 text-black">@lang('messages.otp_code')</h5>
                                </div>

                                <div class="col-12">
                                    <div class="form-otp-group">
                                        @foreach (range(1, 6) as $i)
                                            <input type="text" class="form-control digit numbersOnly" maxlength="1"
                                                pattern="[0-9]*" inputmode="numeric" name="otp[]" />
                                        @endforeach
                                    </div>
                                    <!--form-otp-->
                                </div>
                                <div class="col-12 d-flex fs-14 text-gray font-inter">
                                    <p class="m-0">
                                        <a id="resend-otp" href="javascript:void(0);"class="resend"></a>
                                    </p>
                                    <p class="m-0 ms-auto" id="otp-timer"></p>
                                </div>
                                <div class="col-12 d-flex justify-content-end pt-4">
                                    <a class="btn px-4" id="get-otp"href="javascript:void(0);">
                                        <span class="px-2">@lang('messages.get_otp')</span>
                                    </a>
                                    <button class="btn px-4 ms-2" type="submit">
                                        <span class="px-2">@lang('messages.change_password')</span>
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
@endsection
@section('script')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
            });
        </script>
    @elseif (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const resendButton = $('#resend-otp');
            const otpTimer = $('#otp-timer');
            let countdown;
            let targetDate = new Date(@json($expiresAt)).getTime();

            function startCountdown() {
                countdown = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = targetDate - now;

                    if (distance <= 0) {
                        clearInterval(countdown);
                        otpTimer.text(@json(__('messages.time_up')));
                        resendButton.text(@json(__('messages.resend_code')))
                            .removeClass('disabled')
                            .prop('disabled', false);
                        $('#get-otp').show();
                    } else {
                        const minutes = Math.floor(distance / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        otpTimer.text(
                            (minutes < 10 ? "0" : "") + minutes + ":" +
                            (seconds < 10 ? "0" : "") + seconds
                        );
                        if (distance > 60 * 1000) {
                            $('#get-otp').hide();
                        } else {
                            $('#get-otp').show();
                        }

                        resendButton.text(@json(__('messages.please_wait_5_minutes')))
                            .addClass('disabled')
                            .prop('disabled', true);
                    }
                }, 1000);
            }
            startCountdown();
            resendButton.on('click', function() {
                if ($(this).prop('disabled')) return;

                $(this).addClass('disabled').prop('disabled', true);
                $.ajax({
                    url: "{{ route('send.otp.password', ['lang' => app()->getLocale()]) }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        targetDate = new Date().getTime() + 5 * 60 * 1000;
                        startCountdown();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: xhr.responseJSON.message || 'Something went wrong.',
                        });
                        resendButton.removeClass('disabled').prop('disabled', false);
                    }
                });
            });
            $('#get-otp').on('click', function() {
                $.ajax({
                    url: "{{ route('get.otp.request', ['lang' => app()->getLocale()]) }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '@lang('messages.success')',
                                text: '@lang('messages.otp_sent_success')',
                                confirmButtonText: '@lang('messages.ok')'
                            });
                            targetDate = new Date().getTime() + 5 * 60 * 1000;
                            startCountdown();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '@lang('messages.error')',
                                text: '@lang('messages.otp_request_failed')',
                                confirmButtonText: '@lang('messages.ok')'
                            });
                        }
                    },
                });
            });
            $(".form-control.digit").val("");
            $(".form-control.digit").on('keyup', function() {
                if ($(this).val()) {
                    $(this).addClass("active");
                } else {
                    $(this).removeClass("active");
                }
                if (this.value.length === this.maxLength) {
                    $(this).next(".digit").focus();
                }
            });
            $(".numbersOnly").on('keypress', function(e) {
                if (e.which < 48 || e.which > 57) {
                    return false;
                }
            });
        });
    </script>
@endsection
