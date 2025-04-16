@extends('main')

@section('title')
    @lang('messages.tax_invoice')
@endsection

@section('stylesheet')
    <style>
        a.text-danger.text-decoration-none.link-delete {
            font-family: Prompt, Inter, sans-serif;
            font-size: 12px;
            font-weight: 400;
            text-decoration: none;
            color: rgb(220, 53, 69);
            /* สีแดง */
            position: absolute;
            top: 35px;
            /* ขยับลงมาใต้ "แก้ไข" */
            right: 0px;
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        a.text-danger.text-decoration-none.link-delete:hover {
            color: rgb(200, 40, 55);
            /* เปลี่ยนสีตอน hover */
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
                    <h3 class="title-md pb-2">@lang('messages.tax_invoice')</h3>
                    @if ($tax->isEmpty())
                        <div class="empty-cart-message">
                            <h3>@lang('messages.item_is_empty')</h3>
                        </div>
                    @else
                        @foreach ($tax as $itemTax)
                            <div class="{{ $itemTax->is_default ? 'card-address default border-0' : 'card-address' }}">
                                <div class="d-flex flex-column">
                                    <a href="{{ route('tax.edit', ['lang' => app()->getLocale(), 'id' => $itemTax->id]) }}"
                                        class="text-primary text-decoration-none link-edit">@lang('messages.edit')</a>

                                    <a class="text-danger text-decoration-none link-delete" data-id="{{ $itemTax->id }}">
                                        @lang('messages.delete')
                                    </a>

                                    <form method="POST"
                                        action="{{ route('tax.delete', ['lang' => app()->getLocale(), 'id' => $itemTax->id]) }}"
                                        class="delete-address-form d-none " id="delete-form-{{ $itemTax->id }}">
                                        @csrf
                                    </form>
                                </div>
                                <img class="icons" src="{{ asset('img/icons/icon-map-point.svg') }}" alt="">

                                <div class="card-body">
                                    <p class="m-0">
                                        <strong>{{ $itemTax->first_name . ' ' . $itemTax->last_name . '   TaxID : ' . $itemTax->tax_id }}</strong>
                                    </p>
                                    <p>{{ $itemTax->detail .
                                        ' ' .
                                        ($itemTax->amphure ? (app()->getLocale() == 'th' ? $itemTax->amphure->name_th : $itemTax->amphure->name_en) : '') .
                                        ' ' .
                                        ($itemTax->tambon ? (app()->getLocale() == 'th' ? $itemTax->tambon->name_th : $itemTax->tambon->name_en) : '') .
                                        ' ' .
                                        ($itemTax->province
                                            ? (app()->getLocale() == 'th'
                                                ? $itemTax->province->name_th
                                                : $itemTax->province->name_en)
                                            : '') .
                                        ' ' .
                                        $itemTax->postal_code }}<br>
                                        {{ $itemTax->mobile_phone }}</p>
                                    @if ($itemTax->is_default)
                                        <button class="btn btn-default" type="button">@lang('messages.default')</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="d-flex py-2 mt-2">
                        <a class="btn btn-address-add btn-light-2"
                            href="{{ url(app()->getLocale() . '/request-full-tax-invoice') }}">
                            <img class="icons svg-js" src="{{ asset('img/icons/icon-add-plus.svg') }}" alt="" />
                            @lang('messages.add_tax_invoice')
                        </a>
                    </div>
                </div>
                <!-- card-info -->
            </div>
            <!-- content -->
        </div>
        <!-- container -->
    </div>
    <!-- section -->
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
