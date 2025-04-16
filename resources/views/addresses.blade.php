@extends('main')

@section('title')
    @lang('messages.my_address')
@endsection

@section('stylesheet')
    <style>
        a.text-danger.text-decoration-none.link-delete {
            font-family: Prompt, Inter, sans-serif;
            font-size: 12px;
            font-weight: 400;
            text-decoration: none;
            color: rgb(220, 53, 69);
            position: absolute;
            top: 35px;
            right: 0px;
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        a.text-danger.text-decoration-none.link-delete:hover {
            color: rgb(200, 40, 55);
        }
    </style>
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
                    <h3 class="title-md pb-2">@lang('messages.my_address')</h3>
                    @if ($address->isEmpty())
                        <div class="empty-cart-message">
                            <h3 style="color: #375B51;">@lang('messages.item_is_empty')</h3>
                        </div>
                    @else
                        @foreach ($address as $itemAddress)
                            <div class="{{ $itemAddress->is_default ? 'card-address default border-0' : 'card-address' }}">
                                <div class="d-flex flex-column">
                                    <a href="{{ route('profile.address.edit', ['lang' => app()->getLocale(), 'id' => $itemAddress->id]) }}"
                                        class="text-primary text-decoration-none link-edit">
                                        @lang('messages.edit')
                                    </a>

                                    <a class="text-danger text-decoration-none link-delete"
                                        data-id="{{ $itemAddress->id }}">
                                        @lang('messages.delete')
                                    </a>

                                    <form method="POST"
                                        action="{{ route('profile.address.delete', ['lang' => app()->getLocale(), 'id' => $itemAddress->id]) }}"
                                        class="delete-address-form d-none " id="delete-form-{{ $itemAddress->id }}">
                                        @csrf
                                    </form>
                                </div>
                                <img class="icons" src="{{ asset('img/icons/icon-map-point.svg') }}" alt="">

                                <div class="card-body">
                                    <p class="m-0">
                                        <strong>{{ $itemAddress->first_name . ' ' . $itemAddress->last_name }}</strong>
                                    </p>
                                    <p>{{ $itemAddress->detail .
                                        ' ' .
                                        ($itemAddress->amphure
                                            ? (app()->getLocale() == 'th'
                                                ? $itemAddress->amphure->name_th
                                                : $itemAddress->amphure->name_en)
                                            : '') .
                                        ' ' .
                                        ($itemAddress->tambon
                                            ? (app()->getLocale() == 'th'
                                                ? $itemAddress->tambon->name_th
                                                : $itemAddress->tambon->name_en)
                                            : '') .
                                        ' ' .
                                        ($itemAddress->province
                                            ? (app()->getLocale() == 'th'
                                                ? $itemAddress->province->name_th
                                                : $itemAddress->province->name_en)
                                            : '') .
                                        ' ' .
                                        $itemAddress->postal_code }}<br>
                                        {{ $itemAddress->mobile_phone }}</p>
                                    @if ($itemAddress->is_default)
                                        <button class="btn btn-default" type="button">
                                            @lang('messages.default')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="d-flex py-2 mt-2">
                        <a class="btn btn-address-add btn-light-2" href="{{ url(app()->getLocale() . '/cart-address') }}">
                            <img class="icons svg-js" src="{{ asset('img/icons/icon-add-plus.svg') }}" alt="">
                            @lang('messages.add_address')
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.link-delete').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                Swal.fire({
                    title: "{{ __('messages.confirm_delete') }}",
                    // text: "{{ __('messages.are_you_sure') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('messages.yes') }}",
                    cancelButtonText: "{{ __('messages.cancel') }}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#delete-form-" + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
