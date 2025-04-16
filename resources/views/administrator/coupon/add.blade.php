@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <x-bread-crumb />
    <form id="form-create" action="{{ route('administrator.coupon.submit') }}" method="POST" enctype="multipart/form-data"
        class="container">
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
                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Name:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input Name" id="name"
                                    name="name" value="{{ old('name') }}" />
                                @error('name')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="product" class="col-md-2 col-form-label">Select Coupon Type:</label>
                            <div class="col-md-10">
                                <select name="coupon_type" id="couponDiscountSelect" class="form-control">
                                    <option value="">-- Select Discount Type --</option>
                                    @foreach ($couponTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ old('coupon_type') == $type->value ? 'selected' : '' }}>
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
                            <label for="discount_type" class="col-md-2 col-form-label">Select Discount Type:</label>
                            <div class="col-md-6">
                                <select name="discount_type" id="productDiscountSelect" class="form-control">
                                    <option value="">-- Select Discount Type --</option>
                                    @foreach ($discountTypes as $type)
                                        <option class="product-option" value="{{ $type }}"
                                            data-model="{{ $type }}"
                                            {{ old('discount_type') == $type->value ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('discount_type')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="limit" class="col-md-2 col-form-label">Limit usage:</label>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Input Limit" id="limit"
                                    name="limit" min="1" value="{{ old('limit') }}" />
                                @error('limit')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="discount_amount" class="col-md-2 col-form-label">Discount Amount:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input Discount" id="discount_amount"
                                    name="discount_amount" value="{{ old('discount_amount') }}" />
                                @error('discount_amount')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Max DISCOUNT:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input MaxDiscount" id="max_discount"
                                    name="max_discount" value="{{ old('max_discount') }}" />
                                @error('max_discount')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="name" class="col-md-2 col-form-label">Base Price:</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" placeholder="Input Discount" id="base_price"
                                    name="base_price" value="{{ old('base_price') }}" />
                                @error('base_price')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="start_date" class="col-md-2 col-form-label">Start Date:</label>
                            <div class="col-md-10">
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                    value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="end_date" class="col-md-2 col-form-label">End Date:</label>
                            <div class="col-md-10">
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                                    value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="text-danger col-form-label">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-md-2 col-form-label" for="status">Status</label>
                            <div class="col-md-10 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" value="1"
                                        name="status" {{ old('status') ? 'checked' : '' }} />
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
                                        <label for="name_{{ $languages->code }}"
                                            class="col-md-2 col-form-label">Name</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="name_{{ $languages->code }}"
                                                name="nameContent[{{ $languages->id }}]"
                                                value="{{ old('nameContent.' . $languages->id) }}" />
                                            @error('nameContent.' . $languages->id)
                                                <div class="text-danger col-form-label">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4 row">
                                        <label for="description_{{ $languages->code }}"
                                            class="col-md-2 col-form-label">Description</label>
                                        <div class="col-md-10">
                                            <textarea class="areaEditor form-control" id="description_{{ $languages->code }}"
                                                name="description[{{ $languages->id }}]"></textarea>
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
                                        <option value=""></option>
                                    </select>
                                    @error('product_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror

                                    <input type="checkbox" id="statusProduct" value="{{ \App\Enum\Type::All->value }}"
                                        name="statusProduct"
                                        {{ old('statusProduct') === \App\Enum\Type::All->value ? 'checked' : '' }} />
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
                                        <option value=""></option>
                                    </select>
                                    @error('brand_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <input type="checkbox" id="statusBrand" value="{{ \App\Enum\Type::All->value }}"
                                        name="statusBrand"
                                        {{ old('statusBrand') === \App\Enum\Type::All->value ? 'checked' : '' }} /> All
                                    Brand
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
                                        <option value=""></option>
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    <input type="checkbox" id="statusCategory" value="{{ \App\Enum\Type::All->value }}"
                                        name="statusCategory"
                                        {{ old('statusCategory') === \App\Enum\Type::All->value ? 'checked' : '' }} /> All
                                    Category
                                    @error('statusCategory')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

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
        $('#statusProduct').change(function() {
            if ($(this).is(':checked')) {
                $('#productSelect').prop('disabled', true).val([]);
                $('#productSelect').empty();
                $('#productSelect').append(
                    '<option value="" disabled selected>เลือกสินค้าทั้งหมด</option>');
            } else {
                $('#productSelect').prop('disabled', false);
                $('#productSelect option:disabled').remove();
            }
        });
        $('#statusBrand').change(function() {
            if ($(this).is(':checked')) {
                $('#brandSelect').prop('disabled', true).val([]);
                $('#brandSelect').empty();
                $('#brandSelect').append(
                    '<option value="" disabled selected>เลือกแบรนทั้งหมด</option>');
            } else {
                $('#brandSelect').prop('disabled', false);
                $('#brandSelect option:disabled').remove();
            }
        });
        $('#statusCategory').change(function() {
            if ($(this).is(':checked')) {
                $('#categorySelect').prop('disabled', true).val([]);
                $('#categorySelect').empty();
                $('#categorySelect').append(
                    '<option value="" disabled selected>เลือกหมวดหมู่ทั้งหมด</option>');
            } else {
                $('#categorySelect').prop('disabled', false);
                $('#categorySelect option:disabled').remove();
            }
        });
    </script>
    <script>
        $(document).ready(function() {

            let selectedProducts = [];
            let selectedBrands = [];
            let selectedCategory = [];
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
                $('#max_discount').closest('.mb-4').hide();
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
