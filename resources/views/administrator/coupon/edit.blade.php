@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
            background-size: 16px;
            padding-right: 30px;
            width: 100%;
            padding: 0.543rem 0.9375rem;
            font-size: 0.9375rem;
            font-weight: 400;
            line-height: 1.375;
            color: #384551;
            background-color: transparent;
            border: var(--bs-border-width, 1px) solid #ced1d5;
            border-radius: var(--bs-border-radius, 5px);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, background 0.3s ease;
        }

        .custom-select:focus {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black"><path d="M7 14l5-5 5 5z"/></svg>') no-repeat right 10px center;
            background-size: 16px;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb />
    <form id="form-create" action="{{ route('administrator.coupon.update', $coupon->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.coupon') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card ">
            <div class="nav-align-top nav-tabs-shadow mb-6">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-main" aria-controls="tab-main" aria-selected="false">
                            Main
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-settings" aria-controls="tab-settings" aria-selected="false">
                            Product
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-main" role="tabpanel">
                        <div class="mb-4 row" id="nameRow">
                            <label for="name" class="col-md-2 col-form-label">Name:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input Name" id="name"
                                    name="name" value="{{ $coupon->name }}" />
                                @error('name')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="product" class="col-md-2 col-form-label">Select Coupon Type:</label>
                            <div class="col-md-10">
                                <select name="coupon_type" id="couponDiscountSelect"class="custom-select">
                                    <option value="">-- Select Discount Type --</option>
                                    @foreach ($couponTypes as $type)
                                        <option value="{{ $type->value }}"
                                            {{ $coupon->coupon_type == $type->value ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('coupon_type')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


                        <div class="mb-4 row">
                            <label for="product" class="col-md-2 col-form-label">Select Discount Type:</label>
                            <div class="col-md-6">
                                <select name="discount_type" id="productDiscountSelect" class="form-control">
                                    <option value="">-- Select Discount Type --</option>
                                    @foreach ($discountTypes as $type)
                                        <option value="{{ $type->value }}"
                                            {{ $coupon->discount_type == $type->value ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('discount_type')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div><label for="limit" class="col-md-1 col-form-label">Limit:</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" placeholder="Input Limit" id="limit"
                                    name="limit" min="1" value="{{ $coupon->limit }}" />
                                @error('limit')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">DISCOUNT AMOUNT:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input Discount" id="discount_amount"
                                    name="discount_amount"
                                    value="{{ old('discount_amount', $coupon->discount_amount) }}" />
                                @error('discount_amount')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Max DISCOUNT:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input MaxDiscount"
                                    id="max_discount" name="max_discount"
                                    value="{{ old('max_discount', $coupon->max_discount) }}" />
                                @error('max_discount')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Base Price:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input Discount" id="base_price"
                                    name="base_price" value="{{ old('base_price', $coupon->base_price) }}" />
                                @error('base_price')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">START DATE:</label>
                            <div class="col-md-10">
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                    value="{{ old('start_date', $coupon->start_date) }}">
                                @error('start_date')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">END DATE:</label>
                            <div class="col-md-10">
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                                    value="{{ old('end_date', $coupon->end_date) }}">
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
                                    <input class="form-check-input" type="checkbox" id="status" value="1"
                                        name="status" {{ old('status', $coupon->status) ? 'checked' : '' }} />
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($language as $index => $languages)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        data-bs-toggle="tab" data-bs-target="#tab-lang-{{ $languages->code }}"
                                        role="tab">{{ strtoupper($languages->code) }}</button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($language as $index => $languages)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="tab-lang-{{ $languages->code }}" role="tabpanel">
                                    <div class="mb-4 row">
                                        <label for="name-{{ $languages->id }}"
                                            class="col-md-2 col-form-label">Name</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text"
                                                value="{{ $couponContents[$languages->id]->name ?? '' }}"
                                                id="name-{{ $languages->id }}"
                                                name="nameContent[{{ $languages->id }}]" />
                                            @error('name.' . $languages->id)
                                                <div class="text-danger col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4 row">
                                        <label for="description-{{ $languages->id }}"
                                            class="col-md-2 col-form-label">Description</label>
                                        <div class="col-md-10">
                                            <textarea class="areaEditor form-control" id="description-{{ $languages->id }}"
                                                name="description[{{ $languages->id }}]">{{ $couponContents[$languages->id]->description ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-settings" role="tabpanel">
                        <div class="card p-4 mt-4">
                            <div class="mb-3 row">
                                <label for="productSelect" class="col-md-2 col-form-label">Product</label>
                                <div class="col-md-8">
                                    <select name="product_id[]" id="productSelect" class="form-control"
                                        multiple="multiple">
                                        @foreach ($product as $products)
                                            @if ($products->product)
                                                <option value="{{ $products->product->id }}">
                                                    {{ $products->product->sku }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <input type="checkbox" id="statusProduct" value="{{ \App\Enum\Type::All->value }}"
                                        name="statusProduct"
                                        {{ $product->first() && $product->first()->type === \App\Enum\Type::All->value ? 'checked' : '' }} />
                                    All Product
                                    @error('statusProduct')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" id="addProductBtn">Add
                                        Product</button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card p-4 mt-4">
                            <div class="mb-3 row">
                                <label for="brandSelect" class="col-md-2 col-form-label">Brand</label>
                                <div class="col-md-8">
                                    <select name="brand_id[]" id="brandSelect" class="form-control" multiple="multiple">
                                        @foreach ($brand as $products)
                                            @if ($products->brand)
                                                <option value="{{ $products->brand->id }}">
                                                    {{ $products->brand->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <input type="checkbox" id="statusBrand" value="{{ \App\Enum\Type::All->value }}"
                                        name="statusBrand"
                                        {{ $brand->first() && $brand->first()->type === \App\Enum\Type::All->value ? 'checked' : '' }} />
                                    All Brand
                                    @error('statusBrand')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card p-4 mt-4">
                            <div class="mb-3 row">
                                <label for="categorySelect" class="col-md-2 col-form-label">Category</label>
                                <div class="col-md-8">
                                    <select name="category_id[]" id="categorySelect" class="form-control"
                                        multiple="multiple">
                                        @foreach ($category as $products)
                                            @if ($products->category)
                                                <option value="{{ $products->category->id }}">
                                                    {{ $products->category->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <input type="checkbox" id="statusCategory" value="{{ \App\Enum\Type::All->value }}"
                                        name="statusCategory"
                                        {{ $category->first() && $category->first()->type === \App\Enum\Type::All->value ? 'checked' : '' }} />
                                    All Category
                                    @error('statusCategory')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" id="addProductBtn">Add
                                            Product</button>
                                    </div> --}}
                            </div>

                            {{-- <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" id="addProductBtn">Add
                                            Product</button>
                                    </div> --}}
                            {{-- <div class="selected-products mt-4 card p-4" style="display: none;"
                                id="selectedProductsCard">
                                <div class="d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-danger" id="deleteSelectedBtn">Delete
                                        Selected</button>
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
                            </div> --}}

                        </div>
                    </div> <!-- ปิด tab-content -->
                </div> <!-- ปิด nav-align-top -->
            </div> <!-- ปิด card -->
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
                window.location.href = '{{ route('administrator.coupon') }}';
            });
        </script>
    @endif
    <script>
        window.csrfToken = @json(csrf_token());
    </script>
    <script type="module" src="{{ asset('administrator/js/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#statusProduct').change(function() {
                if ($(this).is(':checked')) {
                    $('#productSelect').prop('disabled', true).val([]);
                    $('#productSelect').empty();
                    setTimeout(function() {
                        $('#productSelect').append(
                            '<option value="" disabled selected>เลือกสินค้าทั้งหมด</option>');
                    }, 50);
                } else {
                    $('#productSelect').prop('disabled', false);
                    $('#productSelect option:disabled').remove();
                }
            }).change();

            $('#statusBrand').change(function() {
                if ($(this).is(':checked')) {
                    $('#brandSelect').prop('disabled', true).val([]);
                    $('#brandSelect').empty();
                    setTimeout(function() {
                        $('#brandSelect').append(
                            '<option value="" disabled selected>เลือกแบรนทั้งหมด</option>');
                    }, 50);
                } else {
                    $('#brandSelect').prop('disabled', false);
                    $('#brandSelect option:disabled').remove();
                }
            }).change();

            $('#statusCategory').change(function() {
                if ($(this).is(':checked')) {
                    $('#categorySelect').prop('disabled', true).val([]);
                    $('#categorySelect').empty();
                    setTimeout(function() {
                        $('#categorySelect').append(
                            '<option value="" disabled selected>เลือกหมวดหมู่ทั้งหมด</option>');
                    }, 50);
                } else {
                    $('#categorySelect').prop('disabled', false);
                    $('#categorySelect option:disabled').remove();
                }
            }).change();
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedProducts = @json($product->map(fn($b) => $b->product->id ?? null)->filter());
            let selectedBrands = @json($brand->map(fn($b) => $b->brand->id ?? null)->filter());
            let selectedCategory = @json($category->map(fn($b) => $b->category->id ?? null)->filter());
            console.log(selectedProducts, selectedBrands, selectedCategory);
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
                                    text: item.sku + " - " + item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            $('#productSelect').val(selectedProducts).trigger('change');

            $('#brandSelect').select2({
                allowClear: true,
                closeOnSelect: false,
                ajax: {
                    url: '/get-brands',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            selected_ids: selectedBrands
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            $('#brandSelect').val(selectedBrands).trigger('change');

            $('#categorySelect').select2({
                allowClear: true,
                closeOnSelect: false,
                ajax: {
                    url: '/get-category',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            selected_ids: selectedCategory
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
            $('#categorySelect').val(selectedCategory).trigger('change');
            $('#couponDiscountSelect').change(function() {
                var discountType = $(this).val();
                if (discountType === 'free_shipping') {
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

            if ($('#couponDiscountSelect').val() === 'free_shipping') {
                $('#discount_amount').closest('.mb-4').hide();
                $('.product-price-input').show();
            }
            $('#productDiscountSelect').change(function() {
                var discountType = $(this).val();
                if (discountType === 'amount') {
                    $('#max_discount').closest('.mb-4').hide();
                    $('.product-price-input').show();
                } else {
                    $('#max_discount').closest('.mb-4').show();
                    $('.product-price-input').hide();
                }
                $('#productSelect').val([]).trigger('change');
                selectedProducts = [];
                $('#selectedProductsList').empty();
                $('#selectedProductsCard').hide();
            });

            if ($('#productDiscountSelect').val() === 'amount') {
                $('#max_discount').closest('.mb-4').hide();
                $('.product-price-input').show();
            }
        });
    </script>
@endsection
{{-- <script>
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
            const couponDiscount = @json($couponDiscount);
            couponDiscount.forEach(function(item) {

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
    </script> --}}
