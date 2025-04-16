@extends('main')

@section('title')
    @lang('messages.checkout')
@endsection

@section('stylesheet')
    <style>
        .input-file {
            width: auto;
            min-width: 200px;
            max-width: 100%;
        }

        .copy-text {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="section section-cart bg-light pt-0">
        <div class="container has-sidebar">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/profile ') }}">@lang('messages.profile')</a></li>
            </ol>
            <div class="hgroup w-100 d-flex pb-4 mb-1">
                <a href="{{ url(app()->getLocale() . '/my-purchase') }}" class="btn btn-outline back">
                    <img class="svg-js icons" src="{{ asset('img/icons/icon-arrow-back.svg') }}" alt="">
                    @lang('messages.back')
                </a>
            </div>


            <div class="content">
                <div class="card-info">
                    <h3 class="fs-18 mb-2 d-flex"> @lang('messages.address')
                        @if ($order->status == 'Approve')
                            <label class="purchase-status completed ms-auto">Complete</label>
                        @elseif ($order->status == 'Cancel' || $order->status == 'Fail')
                            <label class="purchase-status cancel ms-auto">Cancel</label>
                        @elseif ($order->status == 'Processed')
                            <label class="purchase-status delivery ms-auto">To Delivery</label>
                        @elseif ($order->status == 'Delivery')
                            <label class="purchase-status receive ms-auto">To Receive</label>
                        @elseif (!$order->orderPayments->isEmpty() || !empty($order->po_number))
                            <label class="purchase-status topay ms-auto">To Pay</label>
                        @else
                            <a class="link-edit" href="#addressModal" data-bs-toggle="modal">@lang('messages.edit')</a>
                        @endif
                    </h3>

                    {{-- <form action="{{ route('cart.order.submit', ['lang' => app()->getLocale(), 'id' => $order->id]) }}"
                        method="POST">
                        @csrf --}}

                    @foreach ($address as $itemAddress)
                        @if ($itemAddress->is_default)
                            <input type="text" class="form-control" name="id_address" value="{{ $itemAddress->id }}"
                                style="display: none;" />
                            <div class="d-flex gap-3">
                                <img class="icons mt-1" src="{{ asset('img/icons/icon-map-point.svg') }}" alt="">
                                <div>
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

                                </div>
                            </div>
                        @endif
                    @endforeach
                </div><!--card-info-->

                <div class="card-info">
                    <h3 class="fs-18 mb-2">
                        @lang('messages.product')
                    </h3>

                    <div class="table-boxed">
                        @foreach ($order_product as $item)
                            <ul class="ul-table ul-table-body infos">
                                <li class="photo">
                                    <img src="{{ asset('img/thumb/photo-400x455--1.jpg') }}" alt="">
                                </li>
                                <li class="info">
                                    <a class="product-info"
                                        href="{{ url(app()->getLocale() . '/product-detail/' . $item->product->product_model_id) }}">
                                        <h3>{{ $item->name }}</h3>
                                        <label class="label">Size : {{ $item->size }}</label>
                                        <p><small>Model : {{ $item->model }}</small></p>
                                    </a>
                                </li>
                                <li class="qty">
                                    <strong class="fs-16 text-black">x{{ $item->quantity }}</strong>
                                </li>
                                <li class="total"><strong>{{ number_format($item->price * $item->quantity, 2) }}
                                        ฿</strong>
                                </li>
                            </ul>
                        @endforeach
                    </div><!--table-boxed-->
                </div><!--card-info-->

                <div class="card-info">
                    <h3 class="fs-18 mb-3">
                        @lang('messages.shippings')
                    </h3>

                    <div class="d-flex gap-3">
                        <img class="icons" src="{{ asset('img/icons/icon-delivery-truck.svg') }}" alt="">
                        <div>
                            <p class="fs-15 text-black m-0"><strong> @lang('messages.shipping_official')</strong></p>
                            <p class="fs-13 text-highlight"> @lang('messages.estimated_delivery')</p>
                        </div>

                        <p class="text-secondary ms-auto me-md-4">
                            <strong>
                                @if ($order->shipping_free > 0)
                                    {{ number_format($order->shipping_free, 2) }} ฿
                                @else
                                    @lang('messages.free_shipping')
                                @endif
                            </strong>
                        </p>
                    </div>
                </div><!--card-info-->

                <div class="card-info">
                    <h3 class="fs-18 mb-2">
                        @lang('messages.work_description')
                    </h3>
                    <textarea class="form-control h-145" name="work_description" placeholder="@lang('messages.work_description')"></textarea>
                </div>
                <div class="card-info">

                    <h3 class="fs-18 mb-2 d-flex">
                        @lang('messages.tax_invoice')<small class="my-auto ms-2">(@lang('messages.optional'))</small>
                        @if ($order->status == 'Waiting Approve')
                            <a class="link-edit" href="#taxInvoiceModal" data-bs-toggle="modal">@lang('messages.edit')</a>
                        @endif
                    </h3>

                    @foreach ($tax as $itemTax)
                        @if ($itemTax->is_default)
                            <input type="text" class="form-control" name="id_tax" value="{{ $itemTax->id }}"
                                style="display: none;" />
                            <div class="d-flex gap-3">
                                <img class="icons mt-1" src="{{ asset('img/icons/icon-map-point.svg') }}" alt="">
                                <div>
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
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="card-info">
                    <h3 class="fs-18 mb-2">
                        Payment
                    </h3>

                    @if (!$order->orderPayments->isEmpty())
                        @if ($order->orderPayments->cart_type == 'V')
                            <label class="form-check-label" for="credit">
                                Credit/Debit
                                <img class="icon" src="{{ asset('img/icons/icon-creditcard.png') }}" alt="">
                            </label>
                        @else
                            <label class="form-check-label" for="promptpay">
                                QR Promptpay
                                <img class="icon" src="{{ asset('img/icons/icon-promptpay.png') }}" alt="">
                            </label>
                        @endif
                    @elseif(!empty($order->po_number))
                        <div class="form-check payment">

                            <label class="form-check-label d-block" for="po">
                                Purchase Order (PO Number)<br>
                                <input type="text" id="po_number" class="form-control bg-white mt-2"
                                    value="{{ $order->po_number }}" placeholder="Enter PO Number"
                                    {{ $order->po_number ? '' : 'disabled' }}>
                            </label>
                        </div>
                    @else
                        <div class="form-check payment">
                            <input class="form-check-input" type="radio" id="credit" name="payment"value="full"
                                checked>
                            <label class="form-check-label" for="credit">
                                Credit/Debit
                                <img class="icon" src="{{ asset('img/icons/icon-creditcard.png') }}" alt="">
                            </label>
                        </div>
                        <div class="form-check payment">
                            <input class="form-check-input" type="radio" id="promptpay" name="payment"
                                value="promptpay">
                            <label class="form-check-label" for="promptpay">
                                QR Promptpay
                                <img class="icon" src="{{ asset('img/icons/icon-promptpay.png') }}" alt="">
                            </label>
                        </div>
                        <div class="form-check payment">
                            <input class="form-check-input" type="radio" id="po" name="payment"
                                value="po">
                            <label class="form-check-label d-block" for="po">
                                Purchase Order (PO Number)<br>
                                <input type="text" id="po_number" class="form-control form-control-sm bg-white mt-2"
                                    placeholder="Enter PO Number" disabled>
                            </label>

                            <label class="form-check-label d-block"> Attach file PO <span style="color: red;">(.PDF
                                    Only)<br>
                                    <div class="file-upload-group mt-2">
                                        <input type="file" name="image" id="file" class="input-file"
                                            accept=".pdf" />
                                        <label for="file" class="btn js-labelFile ms-2">
                                            <i class="icons icon-upload"></i>
                                            <span class="js-fileName">@lang('messages.upload_file')</span>
                                        </label>
                                    </div>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
            <div class="sidebar">
                <div class="card-info">
                    <h3 class="fs-18 mb-2">@lang('messages.summary')</h3>
                    <table class="table-summary">
                        <tr>
                            <td>@lang('messages.subtotal')</td>
                            <td class="number">{{ number_format($order->subtotal, 2) }} ฿</td>
                        </tr>
                        <tr>
                            <td>@lang('messages.vat')</td>
                            <td class="number">{{ number_format($order->vat, 2) }} ฿</td>
                        </tr>
                        <tr>
                            <td>@lang('messages.shipping')</td>
                            <td class="number">{{ number_format($order->shipping_free, 2) }} ฿</td>
                        </tr>
                        <tr>
                            <td>@lang('messages.discount')</td>
                            <td class="number text-danger">{{ number_format($order->discount, 2) }} ฿</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr>
                            </td>
                        </tr>
                        <tr class="total">
                            <td>@lang('messages.total') </td>
                            <td class="number">{{ number_format($order->total, 2) }} ฿</td>
                        </tr>
                    </table>
                    @if ($order->orderPayments->isEmpty() && empty($order->po_number))
                        <div class="buttons flex-column pb-0 pt-4">
                            <button class="btn btn-48" type="button" data-bs-target="#ksherModal"
                                data-bs-toggle="modal" id="submitOrderButton">
                                <span class="fs-13" id="submitButtonText">@lang('messages.proceed_to_checkout')</span>
                            </button>
                        </div>
                    @endif
                    {{-- </form> --}}
                    @if (!$order->orderPayments->isEmpty() || !empty($order->po_number))
                        @if ($order->status === 'Approve')
                            <div class="po-status-block">
                                <img class="icons" src="{{ asset('img/icons/icon-po-status-confirm.svg') }}"
                                    alt="">
                                <label class="purchase-status confirmed">
                                    Confirmed
                                </label>
                            </div>
                        @elseif($order->status === 'Waiting Approve')
                            <div class="po-status-block">
                                <img class="icons" src="{{ asset('img/icons/icon-po-status-waiting.svg') }}"
                                    alt="">
                                <label class="purchase-status topay">
                                    Waiting for seller Confirm
                                </label>
                            </div>
                        @endif
                    @endif
                </div>
                @if (!$order->orderPayments->isEmpty() || !empty($order->po_number))
                    <div class="card-info d-flex">
                        <h3 class="fs-15">Point</h3>
                        <span class="fs-15 ms-auto" style="color:#FFCA38;">+ {{ $order->point }} Point</span>
                    </div>
                @endif

                @if (!$order->orderPayments->isEmpty() || !empty($order->po_number))
                    <div class="card-info">
                        <div class="info-row border-bottom-1">
                            <div class="d-flex w-100">
                                <h3 class="fs-15">Status Order :</h3>
                                <span class="fs-14 ms-auto">{{ $order->order_number }}</span>
                            </div>
                @endif
                @if (!$order->orderPayments->isEmpty() || (!empty($order->po_number) && $order->status === 'Approve'))
                    {{-- <div class="info-row border-0"> --}}
                    <div class="d-flex w-100">
                        <h3 class="fs-15">Tracking Number :</h3>
                        <strong class="fs-14 ms-auto text-highlight copy-text" id="trackingNumber"
                            onclick="copyToClipboard(event)">
                            {{ $order->tracking_no ?? 'Wait Approve' }}
                        </strong>
                    </div>
                    {{-- </div> --}}
                @endif
            </div>
            @if (!$order->orderPayments->isEmpty() || !empty($order->po_number))
                <div class="info-row border-0">
                    <div class="d-flex  fs-13 w-100">
                        <h3 class="fs-15">Place Order :</h3>
                        <span class="ms-auto nowrap">{{ $order->created_at->setTimezone('Asia/Bangkok') }}</span>
                    </div>
                </div>
            @endif
        </div>

        <form method="POST" action="https://payments.paysolutions.asia/payment" id="autoSubmitForm">

            <input type="hidden" name="customeremail" value="{{ $order->member->email }}">
            <input type="hidden" name="productdetail" value="{{ $order->order_number }}">
            <input type="hidden" name="refno" value="{{ $reference }}">
            <input type="hidden" name="merchantid" value="{{ env('MERCHANT_ID') }}">

            <input type="hidden" name="cc" value="00">
            <input type="hidden" name="total" value="{{ $order->total }}">
            <input type="hidden" name="lang" value="TH">
            <input type="hidden" name="channel" id="channel" value="full">
            <input type="submit" name="Submit" value="Comfirm Order" style="display:none;">
        </form>

    </div><!--container-->
    </div><!--section-->
    <div id="addressModal" class="modal fade" style="--bs-modal-width:550px">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content px-sm-2">
                <div class="modal-body">
                    <h3 class="fs-20 mb-1">@lang('messages.my_address')</h3>
                    @foreach ($address as $itemAddress)
                        <div class="{{ $itemAddress->is_default ? 'card-address default border-0' : 'card-address' }}">
                            <a href="{{ route('profile.address.edit', ['lang' => app()->getLocale(), 'id' => $itemAddress->id]) }}"
                                class="link-edit">@lang('messages.edit')</a>
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

                    <div class="d-flex py-2">
                        <a class="btn btn-address-add btn-light-2"
                            href="{{ url(app()->getLocale() . '/cart-address') }}">
                            <img class="icons svg-js" src="{{ asset('img/icons/icon-add-plus.svg') }}" alt="">
                            @lang('messages.add_address')
                        </a>
                    </div>

                    <div class="buttons button-confirm mt-4">
                        <button class="btn btn-outline-red" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div><!--modal-body-->
            </div><!--modal-content-->
        </div>
    </div>

    <div id="taxInvoiceModal" class="modal fade" style="--bs-modal-width:550px">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content px-sm-2">
                <div class="modal-body">
                    <h3 class="fs-20 mb-1">@lang('messages.full_tax_invoice')</h3>
                    @foreach ($tax as $itemTax)
                        <div class="{{ $itemTax->is_default ? 'card-address default border-0' : 'card-address' }}">
                            <a href="{{ route('tax.edit', ['lang' => app()->getLocale(), 'id' => $itemTax->id]) }}"
                                class="link-edit">@lang('messages.edit')</a>
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


                    <div class="d-flex py-2 mt-2">
                        <a class="btn btn-address-add btn-light-2"
                            href="{{ url(app()->getLocale() . '/request-full-tax-invoice') }}">
                            <img class="icons svg-js" src="{{ asset('img/icons/icon-add-plus.svg') }}" alt="" />
                            @lang('messages.add_tax_invoice')
                        </a>
                    </div>

                    <div class="buttons button-confirm mt-4">
                        <button class="btn btn-outline-red" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div><!--modal-body-->
            </div><!--modal-content-->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                var data = {
                    _token: '{{ csrf_token() }}',
                    order_data: JSON.stringify(@json($order_product))
                };
                $.ajax({
                    url: "{{ route('exit.checkout', ['lang' => app()->getLocale()]) }}",
                    type: "POST",
                    data: data,
                    async: false,

                });
            });
        });
    </script>
    <script>
        document.getElementById("submitOrderButton").addEventListener("click", function(e) {
            e.preventDefault();
            var payment = $('input[type=radio][name=payment]:checked').val();
            if (payment === 'po') {
                var fileInput = $("#file")[0];
                var file = fileInput.files[0];

                if (!file) {
                    Swal.fire({
                        title: "{{ __('messages.error_title') }}",
                        text: "{{ __('messages.no_file') }}",
                        icon: "warning",
                        confirmButtonText: "{{ __('messages.confirm') }}"
                    });
                    return;
                }

                if (file.type !== "application/pdf") {
                    Swal.fire({
                        title: "{{ __('messages.error_title') }}",
                        text: "{{ __('messages.invalid_file') }}",
                        icon: "error",
                        confirmButtonText: "{{ __('messages.confirm') }}"
                    });
                    return;
                }
                $('#submitButtonText').text('{{ __('messages.loading') }}');
                $(this).prop('disabled', true);
                Swal.fire({
                    title: 'กำลังเปลี่ยนหน้า...',
                    text: 'กรุณารอสักครู่',
                    icon: 'info',
                    showConfirmButton: false,
                });
                var formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file', $("#file")[0].files[0]);
                formData.append('po_number', $("#po_number").val());
                formData.append('productdetail', $('input[name="productdetail"]').val());
                formData.append('refno', $('input[name="refno"]').val());
                formData.append('statusname', 'COMPLETED');
                $.ajax({
                    url: '/get-po-back',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        window.location.href =
                            "{{ url(app()->getLocale() . '/my-purchase') }}";
                        Swal.close();
                    },

                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON.message;
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            } else {
                document.getElementById("autoSubmitForm").submit();
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="payment"]').on('change', function() {
                $('#channel').val($(this).val());
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('input[type=radio][name=payment]').change(function() {
                if ($(this).val() === 'po') {
                    $('#po_number').prop('disabled', false);
                    $('#file').prop('disabled', false);
                } else {
                    $('#po_number').prop('disabled', true);
                    $('#file').prop('disabled', true);
                }
            });
        });
    </script>
    <script>
        function copyToClipboard(event) {
            event.preventDefault();
            const text = event.target.innerText;
            navigator.clipboard.writeText(text).then(() => {

            });
        }
    </script>
@endsection
