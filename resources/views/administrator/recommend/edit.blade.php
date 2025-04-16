@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
    <style>
        .select2-selection__arrow {
            display: none;

        }
    </style>
@endsection

@section('content')
    <x-bread-crumb />

    <form id="form-update" action="{{ route('administrator.recommend.update', $recommend->id) }}" method="POST"
        class="container">
        @csrf
        <div class="row justify-content-end mb-4">
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('administrator.recommend') }}" class="btn btn-danger">Cancel</a>
            </div>
        </div>

        <div class="card p-4">
            <!-- Select Product Model -->
            <div class="mb-4 row">
                <label for="product_model" class="col-md-2 col-form-label">Select Product Model:</label>
                <div class="col-md-10">
                    <select name="product_model_id" id="productModelSelect" class="form-control">
                        <option value="{{ $recommend->product_model_id }}"></option>
                    </select>
                    @error('product_model_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <!-- Status Checkbox -->
            <div class="row mb-4">
                <label class="col-md-2 col-form-label" for="status">Status</label>
                <div class="col-md-10 d-flex align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" value="1" name="status"
                            {{ $recommend->status ? 'checked' : '' }} />
                    </div>
                </div>
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
                window.location.href = '{{ route('administrator.recommend') }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            var selectedProductModelId = '{{ $recommend->product_model_id }}';
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
