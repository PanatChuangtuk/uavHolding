@extends('main')

@section('title')
    @lang('messages.cart')
@endsection

@section('stylesheet')
    <style>
        #coupon-dropdown {
            width: 100%;
            height: 45px;
            padding: 0 10px;
            font-size: 15px;
            color: #333;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            appearance: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        #coupon-dropdown:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.5);
        }

        #coupon-dropdown:hover {
            background-color: #f0f0f0;
        }

        #coupon-dropdown::-ms-expand {
            display: none;
        }

        #coupon-dropdown {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23333"><path d="M7 10l5 5 5-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }
    </style>
@endsection

@section('content')
    <form action="{{ route('order.submit', ['lang' => app()->getLocale()]) }}" id="orderForm" method="POST">
        @csrf
        <div class="section section-cart bg-light pt-0">
            <div class="container has-sidebar">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a>
                    </li>
                    <li class="breadcrumb-item">@lang('messages.cart')</li>
                </ol>

                <div class="hgroup py-3 w-100">
                    <h1 class="h2 text-capitalize text-underline">@lang('messages.cart')</h1>
                </div>

                <div class="content">
                    <div class="table-boxed">
                        <ul class="ul-table ul-table-header cart">
                            @if ($cart)
                                <li class="checker">
                                    <input type="checkbox" id="select_all" class="form-check-input" />
                                </li>
                            @endif
                        </ul>

                        @if (empty($cart))
                            <div class="empty-cart-message">
                                <h3>@lang('messages.item_is_empty')</h3>
                            </div>
                        @else
                            @foreach ($cart as $id => $item)
                                <ul class="ul-table ul-table-body cart">
                                    <li class="checker">
                                        <input type="checkbox" class="form-check-input" id="select_id" name="items[]"
                                            data-id="{{ $id }}" value="{{ $id }}" />
                                    </li>
                                    <li class="photo">
                                        <img href="{{ url(app()->getLocale() . '/product-detail', ['id' => $products->firstWhere('id', $id)->product_model_id]) }}"
                                            src="{{ asset('img/thumb/photo-400x455--1.jpg') }}" alt="" />
                                    </li>
                                    <li class="info">
                                        <a class="product-info">
                                            <h3>{{ $item['name'] }}</h3>
                                            <label class="label">Size : {{ $item['size'] }}</label>
                                            <p><small>Model :
                                                    {{ $item['model'] }}</small></p>
                                        </a>
                                    </li>
                                    <li class="qty">
                                        <div class="qty-item">
                                            <button type="button" data-id="{{ $id }}" class="btn sub"></button>
                                            <input class="form-control count" type="text"
                                                value="{{ $item['quantity'] }}" min="1" />
                                            <button type="button" data-id="{{ $id }}" class="btn add"></button>
                                        </div>
                                    </li>
                                    <li class="price" style="white-space: nowrap;">
                                        <strong>{{ number_format($item['price'], 2) }} ฿</strong>
                                    </li>
                                    <li class="total" style="white-space: nowrap;"><strong>0.00 ฿</strong></li>
                                    <li class="action">
                                        <button type="button" data-id="{{ $id }}"
                                            class="btn btn-action btn-trans">
                                            <img class="icons svg-js red" src="{{ asset('img/icons/icon-trash.svg') }}"
                                                alt="" />
                                        </button>
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="sidebar">
                    <div class="card-info">
                        <h3 class="fs-18 mb-2">@lang('messages.summary')</h3>
                        <table class="table-summary">
                            <tr>
                                <td>@lang('messages.subtotal')</td>
                                <input type="hidden" name="subtotal" id="subtotal">
                                <td class="number subtotal">0.00 ฿</td>
                            </tr>
                            <tr>
                                <input type="hidden" name="vat" id="vat">
                                <td>@lang('messages.vat')</td>
                                <td class="number vat">0.00 ฿</td>
                            </tr>
                            <tr>
                                <input type="hidden" name="shipping_fee" id="shipping-fee"
                                    data-model="{{ $shippingCost->price }}">
                                <td>@lang('messages.shipping')</td>
                                <td class="number shipping-fee">0.00 ฿</td>
                            </tr>
                            <tr>
                                <input type="hidden" name="discount" id="discount">
                                <td>@lang('messages.discount')</td>
                                <td class="number discount text-danger">0.00 ฿</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <hr />
                                </td>
                            </tr>
                            <tr class="total">
                                <input type="hidden" name="total" id="total">
                                <td>@lang('messages.total')</td>
                                <td class="net-total number">0.00 ฿</td>
                            </tr>
                        </table>
                        <div class="buttons flex-column pb-0 pt-4">
                            <input class="btn btn-48" type="submit" value="Continue">
                            <a class="btn btn-48 btn-outline" href="{{ url(app()->getLocale() . '/product') }}">
                                <span class="fs-13">Add Product</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-info">
                        <div class="card-header">
                            <h3 class="title-icon fs-18">
                                <img class="icons" src="{{ asset('img/icons/icon-ticket.svg') }}" alt="">
                                Coupon Discout
                            </h3>
                        </div>

                        {{-- <div class="card-body"> --}}
                        {{-- <div class="form-group coupon has-value"> --}}
                        {{-- <input class="form-control"> --}}

                        <div class="label-lists">
                            <select id="coupon-dropdown" data-id="{{ $id ?? null }}">
                                <option value="">@lang('messages.select_coupon')</option>

                            </select>
                            <input type="hidden" name="coupon_id" id="coupon_id" value="">
                        </div>
                        {{-- </div> --}}
                        {{-- </div> --}}
                    </div><!--card-info-->

                    <div class="card-info d-flex"> <input type="hidden" id="points" name="points" value="">
                        <h3 class="fs-15">Point</h3>
                        <span class="fs-15 ms-auto" style="color:#FFCA38;">
                            + <span id="pointDisplay">0</span> Point
                        </span>
                    </div><!--card-info-->

                    <div class="card-info">
                        <div class="card-header">
                            <h3 class="title-icon fs-18">
                                <img class="icons" src="{{ asset('img/icons/icon-crown.svg') }}" alt="">
                                Point
                            </h3>

                            <div class="d-block ms-auto">
                                <span class="text-warning fs-15">{{ $user->point ?? 0 }}</span>
                                <span class="text-black fs-14">Point</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group coupon" data-bs-toggle="modal" data-bs-target="#choseCouponModal">
                                <input id="points-input" name ="usePoint"class="form-control" value=""
                                    placeholder="Enter">
                                <button class="btn point" type="button">
                                    <span class="icons"></span>
                                </button>
                            </div>

                            <p class="fs-12 pt-2">10 Point = 1 THB</p>
                        </div>
                    </div><!--card-info-->

                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function loadCoupons(productId) {
                let selectedIds = $('.form-check-input:checked').map(function() {
                    return $(this).val();
                }).get();
                const dropdown = $('#coupon-dropdown');
                dropdown.empty();
                dropdown.append('<option value="">@lang('messages.loading_coupon')</option>');
                $.ajax({
                    url: '/get-coupon-product',
                    method: 'GET',
                    data: {
                        product_id: selectedIds
                    },
                    success: function(response) {
                        dropdown.empty();
                        dropdown.append('<option value="">@lang('messages.select_coupon')</option>');
                        if (Array.isArray(response.coupons) && response.coupons.length > 0) {
                            response.coupons.forEach(function(couponData) {
                                if (couponData && couponData.coupon) {
                                    dropdown.append(
                                        `<option value="${couponData.coupon.id}">${couponData.coupon.name}</option>`
                                    );
                                }
                            });
                        } else {
                            dropdown.append('<option value="">@lang('messages.no_available_coupons')</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching coupons:", xhr);
                        dropdown.empty();
                        dropdown.append('<option value="">@lang('messages.error_loading_coupons')</option>');
                    }
                });
            }

            $('.form-check-input').on('change', function() {
                const productId = $(this).data('id');
                loadCoupons(productId);
            });

            $('.form-check-input:checked').each(function() {
                const productId = $(this).data('id');
                loadCoupons(productId);
            });

            //point 
            $(window).on('pageshow', function(event) {
                if (event.originalEvent.persisted) {
                    location.reload();
                }
            });
            var getPoint = 0;
            var totalDiscount = 0;
            var haveShipping = 0;
            $('.btn.point').on('click', function() {
                const enteredPoints = parseInt($('#points-input').val()) || 0;
                const points = {{ $user->point ?? 0 }};
                const subtotal = parseFloat($('#subtotal').val()) || 0;
                const maxPoints = subtotal * 0.25;
                if (enteredPoints > points) {
                    Swal.fire('Not Enough Points', 'You don\'t have enough points to use.', 'warning');
                } else if (enteredPoints > maxPoints) {
                    Swal.fire('Points Limit Exceeded',
                        `You can only use up to ${maxPoints.toFixed(2)} ฿ worth of points.`, 'warning');
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to use ${enteredPoints} points?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, use points',
                        cancelButtonText: 'No, keep points',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Success!', 'Your points have been used.', 'success');
                            getPoint = enteredPoints;
                            calculateTotal();
                        } else {
                            Swal.fire('Cancelled', 'Your points remain intact', 'info');
                        }
                    });
                }
            });
            //point |^|

            $('#coupon-dropdown').on('click', function() {
                var selectedCoupons = [];
                var subtotalText = $('.subtotal').text().trim();
                var subtotalValue = parseFloat(subtotalText.replace(/[^0-9.]/g, ''));
                var couponId = $(this).val();
                let selectedIds = $('.form-check-input:checked').map(function() {
                    return $(this).val();
                }).get();
                if (couponId) {
                    selectedCoupons.push({
                        coupon_id: couponId,
                        product_id: selectedIds
                    });
                }
                if (!couponId) {
                    totalDiscount = 0;
                    $('.discount').text('0.00 ฿');
                    $('#discount').val('0.00');
                    calculateTotal();
                }

                if (selectedCoupons.length > 0) {
                    $.ajax({
                        url: '/get-coupon-discount',
                        method: 'GET',
                        data: {
                            coupons: selectedCoupons,
                            subtotal: subtotalValue
                        },
                        success: function(response) {
                            if (response.discount) {
                                totalDiscount = response.discount;
                            } else if (response.free_shipping) {
                                haveShipping = response.free_shipping;
                            }
                            calculateTotal();
                        },
                    });
                }
                var couponId = $(this).val();
                $('#coupon_id').val(couponId);
            });

            function calculateTotal() {
                let subtotal = 0;
                let selectedItems = $('.form-check-input:checked').length;
                if (selectedItems === 0) {
                    getPoint = 0;
                    totalDiscount = 0;
                    $('#points-input').val(0);
                }
                $('.ul-table-body.cart').each(function() {
                    const checkbox = $(this).find('.form-check-input');
                    if (checkbox.prop('checked')) {
                        const qty = parseInt($(this).find('.count').val()) || 1;
                        const price = parseFloat($(this).find('.price strong').text().replace(/[^0-9.-]+/g,
                            '')) || 0;
                        const itemTotal = qty * price;
                        $(this).find('.total strong').text(itemTotal.toFixed(2) + ' ฿');
                        subtotal += itemTotal;
                    } else {
                        $(this).find('.total strong').text('0.00 ฿');
                    }
                });

                $('.subtotal').text(subtotal.toFixed(2) + ' ฿');
                $('#subtotal').val(subtotal.toFixed(2));
                var subtotalWithPoint = subtotal - getPoint
                var subtotalWithDiscount = (subtotal - totalDiscount) - getPoint;

                calculateVAT(subtotalWithDiscount);
                calculateNetTotal(subtotalWithDiscount);
                totalDiscount = 0;
            }

            function calculateVAT(subtotal) {
                const vatRate = 0.07;
                const vat = subtotal * vatRate;
                $('#vat').val(vat.toFixed(2));
                $('.vat').text(vat.toFixed(2) + ' ฿');
            }

            function calculateNetTotal(subtotalWithDiscount) {
                var shippingFee = 100;
                if (haveShipping === 100) {
                    shippingFee = subtotalWithDiscount === 0 ? 0 : (subtotalWithDiscount < $('#shipping-fee')
                        .data('model') ? 100 : 0) - haveShipping;
                } else {
                    shippingFee = subtotalWithDiscount === 0 ? 0 : (subtotalWithDiscount < $('#shipping-fee')
                        .data('model') ? 100 : 0);
                }
                haveShipping = 0;
                shippingFee = Math.max(0, shippingFee);
                const subtotalWithShipping = subtotalWithDiscount + shippingFee;
                const vat = subtotalWithShipping * 0.07;
                const netTotal = subtotalWithShipping + vat;
                const points = Math.floor(subtotalWithDiscount / 100);
                $('#total').val(netTotal.toFixed(2));
                $('.net-total').text(netTotal.toFixed(2) + ' ฿');
                $('.shipping-fee').text(shippingFee.toFixed(2) + ' ฿');
                $('#shipping-fee').val(shippingFee.toFixed(2));
                const totalDiscountIncludingPoints = totalDiscount + getPoint;
                if (totalDiscountIncludingPoints > 0) {
                    $('.discount').text(totalDiscountIncludingPoints.toFixed(2) + ' ฿');
                    $('#discount').val(totalDiscountIncludingPoints.toFixed(2));
                } else {
                    $('.discount').text('0.00 ฿');
                    $('#discount').val('0.00');
                }
                $('#pointDisplay').text(points);
                $('#points').val(points);
            }

            // เพิ่มลดจำนวนสินค้า
            $('.ul-table-body').on('click', '.add', function() {
                const input = $(this).siblings('.count');
                let currentValue = parseInt(input.val()) || 1;
                input.val(currentValue++);
                calculateTotal();
                const dropdown = $(
                    '#coupon-dropdown');
                dropdown.find('option:first').prop('selected', true);
            });

            $('.ul-table-body').on('click', '.sub', function() {
                const input = $(this).siblings('.count');
                let currentValue = parseInt(input.val()) || 1;
                if (currentValue >= 1) {
                    input.val(currentValue--);
                }
                calculateTotal();
                const dropdown = $(
                    '#coupon-dropdown');
                dropdown.find('option:first').prop('selected', true);
            });

            $('#select_all').on('click', function() {
                const isChecked = this.checked;
                $('.form-check-input').not('#select_all').prop('checked', isChecked);
                if (isChecked) {
                    $('.form-check-input:checked').each(function() {
                        const productId = $(this).data('id');
                        loadCoupons(productId);
                    });
                } else {
                    $('select').each(function() {
                        $(this).empty();
                        $(this).append('<option value="">@lang('messages.select_coupon')</option>');
                    });
                }
                calculateTotal();
            });

            $('.form-check-input').not('#select_all').on('click', function() {
                const isChecked = $('.form-check-input').not('#select_all').length === $(
                    '.form-check-input:checked').not('#select_all').length;
                $('#select_all').prop('checked', isChecked);
                calculateTotal();
            });

            $('select').on('change', function() {
                const selectedCoupon = $(this).val();
                $('select').not(this).each(function() {
                    const currentSelect = $(this);
                    if (currentSelect.val() === selectedCoupon) {
                        currentSelect.val('');
                    }
                });
            });

            // ลบสินค้า
            $('.btn.sub').on('click', function() {
                var itemId = $(this).data('id');
                $.ajax({
                    url: '{{ route('cart.remove', ['lang' => app()->getLocale(), 'id' => ':id']) }}'
                        .replace(':id', itemId),
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                });
            });

            // เพิ่มสินค้า
            $('.btn.add').on('click', function() {
                var itemId = $(this).data('id');
                $.ajax({
                    url: '{{ route('cart.add', ['lang' => app()->getLocale(), 'id' => ':id']) }}'
                        .replace(':id', itemId),
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                });
            });

            // การยืนยันการลบ
            $('.btn-action').on('click', function() {
                var itemId = $(this).data('id');
                var itemElement = $(this).closest('ul.ul-table-body.cart');
                Swal.fire({
                    title: "@lang('messages.are_you_sure')",
                    text: "@lang('messages.your_action_cannot_be_undone')",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "@lang('messages.yes')",
                    cancelButtonText: "@lang('messages.cancel')"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('cart.delete', ['lang' => app()->getLocale(), 'id' => ':id']) }}'
                                .replace(':id', itemId),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status == 'delete') {
                                    itemElement.fadeOut(320, function() {
                                        $(this).remove();
                                        calculateTotal();
                                    });
                                    Swal.fire({
                                        title: "@lang('messages.deleted')",
                                        text: "@lang('messages.your_item_has_been_deleted')",
                                        icon: "success",
                                        confirmButtonText: "@lang('messages.ok')"
                                    });
                                }
                            },
                        });
                    }
                });
            });
            calculateTotal();

            // ตรวจสอบการส่งแบบฟอร์ม
            $('#orderForm').on('submit', function(event) {
                var isLoggedIn = @json(Auth::guard('member')->check());
                if (!isLoggedIn) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        text: '@lang('messages.please_login_to_continue')',
                        confirmButtonText: "@lang('messages.login')",
                    }).then(function() {
                        window.location.href =
                            "{{ route('login', ['lang' => app()->getLocale()]) }}";
                    });
                } else if ($('.form-check-input:checked').not('#select_all').length === 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: "@lang('messages.select_at_least_one_item')",
                        confirmButtonText: "@lang('messages.ok')"
                    });
                }
            });
        });
    </script>
@endsection
