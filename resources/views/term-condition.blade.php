@extends('main')
@section('title')
    @lang('messages.term_and_condition')
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
                    <article class="boxed fs-18" style="--width: 880px">
                        <div class="hgroup py-4 text-center">
                            <h2 class="title-xl fw-600 text-secondary">
                                {{ $condition->content_name }}
                            </h2>
                            <p class="fs-14 text-secondary mb-0">
                                {{ $condition->description }}
                            </p>
                        </div>



                        <p>
                            {!! $condition->content !!}
                        </p>
                    </article>
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
    <script>
        // var myModal = new bootstrap.Modal(document.getElementById('choseCouponModal'))
        // myModal.show();

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
    </script>
@endsection
