@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.product') }}">Product</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>

    <form id="form-update" action="{{ route('administrator.product.update', $product->id) }}" method="POST"
        enctype="multipart/form-data" class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.product') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card p-4">
            <div class="mb-4 row">
                <label for="name" class="col-md-2 col-form-label">Name</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" value="{{ $product->name }}" id="name" name="name" />
                    @error('name')
                        <div class="text-danger col-form-label">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4 row">
                <label for="sku" class="col-md-2 col-form-label">Sku</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="sku" name="sku" value="{{ $product->sku }}">
                </div>
            </div>

            <div class="mb-4 row">
                <label for="size" class="col-md-2 col-form-label">Size</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="size" name="size"
                        value="{{ optional(optional($product->productSizes->first())->productUnitValue)->name }}">
                </div>
            </div>


            <div class="mb-4 row">
                <label for="product_model" class="col-md-2 col-form-label">Select Product Model:</label>
                <div class="col-md-10">
                    <select name="product_model_id" id="productModelSelect" class="form-control">
                        <option value="{{ $product->product_model_id }}"></option>
                    </select>
                    @error('product_model_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card p-4">
                        @php
                            $defaultPrices = [];

                            foreach ($memberGroups as $group) {
                                $defaultPrices[$group->id] = [
                                    'name' => $group->name,
                                    'price' => 0,
                                ];
                            }

                            foreach ($product_price as $item) {
                                if (isset($defaultPrices[$item->member_group_id])) {
                                    $defaultPrices[$item->member_group_id]['price'] = $item->price;
                                }
                            }
                        @endphp

                        @foreach ($defaultPrices as $id => $data)
                            <div class="mb-4 row">
                                <div class="col-md-2">
                                    <label for="price[{{ $id }}]"
                                        class="col-form-label">{{ $data['name'] }}</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="price[{{ $id }}]"
                                        name="price[{{ $id }}]"
                                        value="{{ old("price.$id", number_format($data['price'], 2)) }} ฿">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    </form>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var selectedProductModelId = '{{ $product->product_model_id }}';
            $('#productModelSelect').select2({
                ajax: {
                    url: '/get-model-product',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(model) {
                                return {
                                    id: model.id,
                                    text: model.name
                                };
                            })
                        };
                    },
                    cache: true
                },
                placeholder: "-- Select Product Model --",
            });

            if (selectedProductModelId) {
                $.ajax({
                    url: '/get-model-product',
                    dataType: 'json',
                    data: {
                        query: selectedProductModelId
                    },
                    success: function(data) {
                        if (data.results && data.results.length > 0) {
                            var selectedModel = data.results.find(
                                (model) => model.id == selectedProductModelId
                            );
                            if (selectedModel) {
                                var option = new Option(selectedModel.name, selectedModel.id, true,
                                    true);
                                $('#productModelSelect').append(option).trigger('change');
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
