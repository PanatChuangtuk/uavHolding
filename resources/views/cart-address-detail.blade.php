@extends('main')

@section('title')
    @lang('messages.my_address')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <div class="section section-cart bg-light pt-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/cart') }}">@lang('messages.cart')</a></li>
                <li class="breadcrumb-item active">@lang('messages.update_address')</li>
            </ol>

            <div class="hgroup py-3 w-100">
                <h1 class="h2 text-underline">@lang('messages.update_address')</h1>
            </div>

            <form class="card-info" id="form-update"
                action="{{ route('profile.address.update', ['lang' => app()->getLocale(), 'id' => $address->id]) }}"
                method="POST">
                @csrf
                <div class="card-body px-md-4 py-2">
                    <h2 class="text-secondary mb-3">@lang('messages.my_address')</h2>
                    <h3 class="fs-18 mb-5">@lang('messages.personal_info')</h3>
                    <div class="row form-row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.firstname')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_firstname')" name="first_name"
                                    value="{{ old('first_name', $address->first_name) }}">
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.lastname')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_lastname')" name="last_name"
                                    value="{{ old('last_name', $address->last_name) }}">
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.mobile_phone')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_phone')"
                                    pattern="[0-9]{10}" maxlength="10" name="mobile_phone"
                                    title="Please enter a 10-digit phone number"
                                    value="{{ old('mobile_phone', $address->mobile_phone) }}">
                                @error('mobile_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.email')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_email')" name="email"
                                    value="{{ old('email', $address->email) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.province')</label>
                                <select id="provinceSelect" name="province_id" class="form-select"
                                    data-lang="{{ App::getLocale() }}">
                                    <option value="{{ $address->province_id }}"></option>
                                </select>
                                @error('province_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.district')</label>
                                <select id="districtSelect" name="district_id" class="form-select"
                                    data-lang="{{ App::getLocale() }}">
                                    <option value="{{ $address->district_id }}"></option>
                                </select>
                                @error('district_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.subdistrict')</label>
                                <select id="subdistrictSelect" name="subdistrict_id" class="form-select"
                                    data-lang="{{ App::getLocale() }}">
                                    <option value="{{ $address->subdistrict_id }}">
                                    </option>
                                </select>
                                @error('subdistrict_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="postalCodeSelect">@lang('messages.postal_code')</label>
                                <select id="postalCodeSelect" name="postal_code" class="form-select">
                                    <option value=""></option>
                                </select>
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.detailed_address')</label>
                                <textarea class="form-control h-145" placeholder="@lang('messages.enter_address_details')" name="detail">{{ old('detail', $address->detail) }}</textarea>
                                @error('detail')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check mt-3">
                                <input type="hidden" name="is_default" value="0">
                                <input class="form-check-input" type="checkbox" value="1" id="check1"
                                    name="is_default" {{ $address->is_default ? 'checked' : '' }}>
                                <label class="form-check-label" for="check1">
                                    <strong>@lang('messages.set_as_default')</strong><br>
                                    <span class="fs-14">@lang('messages.automatic_setting')</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="buttons button-confirm justify-content-lg-end mb-4">
                                <a class="btn btn-outline-red"
                                    href="{{ route('profile.address', ['lang' => app()->getLocale()]) }}">@lang('messages.cancel')</a>
                                <button class="btn btn-secondary" type="submit">@lang('messages.update')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @if (session('success'))
        <script>
            Swal.fire({
                title: '@lang('messages.success')',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: '@lang('messages.ok')',
                timer: 3000,
            }).then(function() {
                window.location.href = '{{ route('profile.address', ['lang' => app()->getLocale()]) }}';
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            var initialProvinceId = $('#provinceSelect').val();
            var initialDistrictId = $('#districtSelect').val();
            var initialSubdistrictId = $('#subdistrictSelect').val();
            var initialPostalCode = $('#postalCodeSelect').val();
            $.ajax({
                url: '/get-provinces',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var select = $('#provinceSelect');
                    select.empty();
                    select.append('<option value="">@lang('messages.select_province')</option>');
                    data.forEach(function(province) {
                        var provinceName = (select.data('lang') == 'en') ? province.name_en :
                            province.name_th;
                        var selected = (province.id == initialProvinceId) ? 'selected' : '';
                        select.append('<option value="' + province.id + '" ' + selected + '>' +
                            provinceName + '</option>');
                    });
                },
            });
            $('#provinceSelect').change(function() {
                var provinceId = $(this).val();
                var districtSelect = $('#districtSelect');
                var subdistrictSelect = $('#subdistrictSelect');
                var postalCodeSelect = $('#postalCodeSelect');
                if (provinceId) {
                    $.ajax({
                        url: '/get-districts/' + provinceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            districtSelect.empty();
                            districtSelect.append(
                                '<option value="">@lang('messages.select_district')</option>');
                            data.forEach(function(district) {
                                var selected = (district.id == initialDistrictId) ?
                                    'selected' : '';
                                districtSelect.append('<option value="' + district.id +
                                    '" ' + selected + '>' + district.name +
                                    '</option>');
                            });
                        }
                    });
                    subdistrictSelect.empty().append('<option value="">@lang('messages.select_subdistrict')</option>');
                    postalCodeSelect.empty().append('<option value="">@lang('messages.select_postal_code')</option>');
                } else {
                    districtSelect.empty().append('<option value="">@lang('messages.select_district')</option>');
                    subdistrictSelect.empty().append('<option value="">@lang('messages.select_subdistrict')</option>');
                    postalCodeSelect.empty().append('<option value="">@lang('messages.select_postal_code')</option>');
                }
            });
            $('#districtSelect').change(function() {
                var districtId = $(this).val();
                var subdistrictSelect = $('#subdistrictSelect');
                var postalCodeSelect = $('#postalCodeSelect');
                if (districtId) {
                    $.ajax({
                        url: '/get-subdistricts/' + districtId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            subdistrictSelect.empty();
                            subdistrictSelect.append(
                                '<option value="">@lang('messages.select_subdistrict')</option>');
                            data.forEach(function(subdistrict) {
                                var selected = (subdistrict.id ==
                                    initialSubdistrictId) ? 'selected' : '';
                                subdistrictSelect.append('<option value="' + subdistrict
                                    .id + '" data-postcode="' + subdistrict
                                    .zip_code + '" ' + selected + '>' + subdistrict
                                    .name + '</option>');
                            });
                        }
                    });
                    postalCodeSelect.empty().append('<option value="">@lang('messages.select_postal_code')</option>');
                } else {
                    subdistrictSelect.empty().append('<option value="">@lang('messages.select_subdistrict')</option>');
                    postalCodeSelect.empty().append('<option value="">@lang('messages.select_postal_code')</option>');
                }
            });
            $('#subdistrictSelect').change(function() {
                var selectedOption = $(this).find('option:selected');
                var postalCode = selectedOption.data('postcode');
                var postalCodeSelect = $('#postalCodeSelect');
                if (postalCode) {
                    postalCodeSelect.empty().append('<option value="' + postalCode + '">' + postalCode +
                        '</option>');
                } else {
                    postalCodeSelect.empty().append(
                        '<option value="{{ $address->postal_code }}">{{ $address->postal_code }}</option>'
                    );
                }
            });
        });
    </script>
@endsection
