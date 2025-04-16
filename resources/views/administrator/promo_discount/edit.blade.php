@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <x-bread-crumb />
    <form id="form-create" action="{{ route('administrator.promo_discount.update', $promo->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.promo_discount') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card p-3">
            <!-- Select Product -->
            <div class="mb-4 row" id="nameRow">
                <label for="name" class="col-md-2 col-form-label">Name:</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Input Name" id="name" name="name"
                        value="{{ $promo->name }}" />
                    @error('name')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4 row">
                <label for="product" class="col-md-2 col-form-label">Select Discount Type:</label>
                <div class="col-md-10">
                    <select name="discount_type" id="productDiscountSelect" class="form-control">
                        <option value="">-- Select Discount Type --</option>
                        @foreach ($discountTypes as $type)
                            <option value="{{ $type->value }}"
                                {{ $promo->discount_type == $type->value ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('discount_type')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>



            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">DISCOUNT AMOUNT:</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="Input Discount" id="discount_amount"
                        name="discount_amount" value="{{ old('discount_amount', $promo->discount_amount) }}" />
                    @error('discount_amount')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">START DATE:</label>
                <div class="col-md-10">
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                        value="{{ old('start_date', $promo->start_date) }}">
                    @error('start_date')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">END DATE:</label>
                <div class="col-md-10">
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                        value="{{ old('end_date', $promo->end_date) }}">
                    @error('end_date')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status Checkbox -->
            <div class="row mb-4">
                <label class="col-md-2 col-form-label" for="status">Status</label>
                <div class="col-md-10 d-flex align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" value="1" name="status"
                            {{ old('status', $promo->status) ? 'checked' : '' }} />
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-4 mt-4">
            <div class="mb-3 row">
                <label for="product" class="col-md-2 col-form-label">Select Product:</label>
                <div class="col-md-8">
                    <select name="product_id[]" id="productSelect" class="form-control" multiple="multiple">
                        <option value=""></option>
                    </select>
                    @error('product_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="addProductBtn">Add Product</button>
                </div>
            </div>

            <div class="selected-products mt-4 card p-4 mt-4" style="display: none;" id="selectedProductsCard">
                <div class="d-flex justify-content-between mb-3">
                    <button type="button" class="btn btn-danger" id="deleteSelectedBtn">Delete Selected</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="selectedProductsList">
                    </tbody>
                </table>
            </div>
        </div>
    </form>
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
                window.location.href = '{{ route('administrator.promo_discount') }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            let selectedProducts = [];
            $('#productSelect').select2({
                allowClear: true,
                closeOnSelect: false,
                ajax: {
                    url: '/get-products',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            selected_ids: selectedProducts
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.sku + ' - ' + item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            const promoDiscount = @json($promoDiscount);
            promoDiscount.forEach(function(item) {
                var productText = item.product.sku + ' - ' + item.product.name;
                selectedProducts.push(item.product_id);

                var priceInputHtml = item.discount_type === 'amount' ?
                    `<input type="text" class="form-control product-price-input" id="price-${item.product_id}" name="price[${item.product_id}]" placeholder="Enter price" value="${item.discount_amount}">` :
                    '';

                $('#selectedProductsList').append(
                    `<tr class="selected-product" id="product-${item.product_id}">
            <td><input type="checkbox" class="product-checkbox" data-product-id="${item.product_id}"></td>
            <td>${productText}</td>
            <td>${priceInputHtml}</td>
            <td><button type="button" class="btn btn-icon btn-outline-danger border-0 btn-delete" data-product-id="${item.product_id}">
                <i class="bx bx-trash"></i></button></td>
        </tr>`
                );
                updateHiddenInput();
                $('#selectedProductsCard').show();
            });

            $('#productDiscountSelect').change(function() {
                var discountType = $(this).val();
                if (discountType === 'amount') {
                    $('#discount_amount').closest('.mb-4').hide();
                    $('.product-price-input').show();
                } else {
                    $('#discount_amount').closest('.mb-4').show();
                    $('.product-price-input').hide();
                }
                $('#productSelect').val([]).trigger('change');
                selectedProducts = [];
                $('#selectedProductsList').empty();
                $('#selectedProductsCard').hide();
            });

            if ($('#productDiscountSelect').val() === 'amount') {
                $('#discount_amount').closest('.mb-4').hide();
                $('.product-price-input').show();
            }

            $('#addProductBtn').click(function() {
                var selectedOption = $('#productSelect').val();
                var discountType = $('#productDiscountSelect').val();

                if (selectedOption && selectedOption.length > 0) {
                    selectedOption.forEach(function(productId) {
                        var productText = $('#productSelect option[value="' +
                                productId + '"]')
                            .text();

                        if (!selectedProducts.includes(productId)) {
                            selectedProducts.push(productId);
                            var priceInputHtml = discountType === 'amount' ?
                                `<input type="text" class="form-control product-price-input" id="price-${productId}" name="price[${productId}]" placeholder="Enter price">` :
                                '';
                            $('#selectedProductsList').append(
                                `<tr class="selected-product" id="product-${productId}">
                        <td><input type="checkbox" class="product-checkbox" data-product-id="${productId}"></td>
                        <td>${productText}</td>
                        <td>${priceInputHtml}</td>
                        <td><button type="button" class="btn btn-icon btn-outline-danger border-0 btn-delete" data-product-id="${productId}">
                            <i class="bx bx-trash"></i></button></td>
                    </tr>`
                            );
                        }
                    });
                    updateHiddenInput();
                    $('#productSelect').val(null).trigger('change');
                    $('#productSelect').val([]).trigger('change');

                    $('#selectedProductsCard').show();
                } else {
                    Swal.fire({
                        text: 'Please select at least one product',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });

            function updateHiddenInput() {
                $('#form-create').find('.hidden-product').remove();
                selectedProducts.forEach(function(productId, index) {
                    let $hiddenInput = $('<input>', {
                        type: 'hidden',
                        name: `product_id[${index}]`,
                        class: 'hidden-product',
                        value: productId
                    });
                    $('#form-create').append($hiddenInput);
                });
            }

            $(document).on('click', '.btn-delete', function() {
                var productId = $(this).data('product-id');
                var productText = $(this).closest('.selected-product').find('td').text();

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to remove ${productText}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).closest('.selected-product').remove();
                        selectedProducts = selectedProducts.filter(id => id !=
                            productId);
                        updateHiddenInput();
                        Swal.fire('Removed!', `${productText} has been removed.`,
                            'success');
                    }
                });
            });

            $('#selectAll').change(function() {
                var isChecked = $(this).prop('checked');
                $('.product-checkbox').prop('checked', isChecked);
            });

            $('#deleteSelectedBtn').click(function() {
                var selectedIds = [];
                $('.product-checkbox:checked').each(function() {
                    selectedIds.push($(this).data('product-id'));
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        text: 'Please select at least one product to delete.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to delete the selected products?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        selectedIds.forEach(function(productId) {
                            $('#product-' + productId).remove();
                        });
                        selectedProducts = selectedProducts.filter(function(id) {
                            return !selectedIds.includes(id);
                        });
                        updateHiddenInput();
                        Swal.fire('Deleted!',
                            'The selected products have been deleted.',
                            'success');
                    }
                });
            });
        });
    </script>
@endsection
