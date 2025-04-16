@extends('main')
@section('title')
    @lang('messages.otp_verification')
@endsection
@section('stylesheet')
    <style>
        .uppercase {
            text-transform: uppercase;
        }
    </style>
@endsection
@section('content')
    <div class="section section-column">
        <div class="container">
            <div class="row row-main">
                <div class="cols col-photo" data-aos="fade-in">
                    <img src="{{ asset('img/thumb/photo-1000x965--1.jpg') }}" alt="" />
                </div>
                <!--cols-->
                <div class="cols col-form" data-aos="fade-in">
                    <div class="boxed me-lg-0">
                        <form class="form form-otp" method="post"
                            action="{{ route('forgot.password.submit', ['lang' => app()->getLocale()]) }}">
                            @csrf
                            <div class="article pb-3" style="--font-size: 14px; --color: #375b51">
                                <h2 class="uppercase">@lang('messages.otp_verification')</h2>

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
                                </div>

                                <div class="col-12 d-flex fs-14 text-gray font-inter">
                                    <p class="m-0">
                                        <a id="resend-otp" href="javascript:void(0);"class="resend"></a>
                                    </p>
                                    <p class="m-0 ms-auto" id="otp-timer"></p>
                                </div>

                                <div class="col-12 pt-4">
                                    <button class="btn px-4 ms-auto" type="submit">
                                        <span class="px-4">@lang('messages.submit')</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!--boxed-->
                </div>
                <!--cols-->
            </div>
            <!--row-main-->
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
    @elseif(session('success'))
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
                        resendButton.text(@json(__('messages.resend_code'))).removeClass('disabled').prop(
                            'disabled', false);
                    } else {
                        const minutes = Math.floor(distance / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        otpTimer.text(
                            (minutes < 10 ? "0" : "") + minutes + ":" +
                            (seconds < 10 ? "0" : "") + seconds
                        );
                        resendButton.text(@json(__('messages.please_wait_5_minutes'))).addClass('disabled').prop(
                            'disabled',
                            true);
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
                        _token: "{{ csrf_token() }}",
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
            $(".form-control.digit").val("");
            $(".form-control.digit").keyup(function() {
                if ($(this).val()) {
                    $(this).addClass("active");
                } else {
                    $(this).removeClass("active");
                }

                if (this.value.length == this.maxLength) {
                    $(this).next(".digit").focus();
                }
            });

            $(".numbersOnly").keypress(function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });
    </script>
@endsection
