@extends('main')

@section('title')
    @lang('messages.full_tax_invoice')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <div class="section section-cart bg-light pt-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="my-account.html">@lang('messages.profile')</a>
                </li>
                <li class="breadcrumb-item active">@lang('messages.full_tax_invoice')</li>
            </ol>
            <div class="hgroup py-3 w-100">
                <h1 class="h2 text-underline">@lang('messages.profile')</h1>
            </div>
            <form class="card-info" action="{{ route('tax.submit', ['lang' => app()->getLocale()]) }}" method="POST">
                @csrf
                <div class="card-body px-md-4 py-2">
                    <h2 class="text-secondary mb-3">@lang('messages.request_full_tax_invoice')</h2>
                    <h3 class="fs-18">@lang('messages.personal_info')</h3>
                    <div class="d-flex gap-5 py-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="personal" id="personalCheck"
                                name="type" checked />
                            <label class="form-check-label fs-15 text-black" for="personalCheck">
                                @lang('messages.personal')
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="company" id="companyCheck"
                                name="type" />
                            <label class="form-check-label fs-15 text-black" for="companyCheck">
                                @lang('messages.company_data')
                            </label>
                        </div>
                    </div>
                    <div class="row form-row g-4">
                        <div class="col-md-6" id="form-company">
                            <div class="form-group">
                                <label class="title"> @lang('messages.company')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_company')"
                                    name="name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title"> @lang('messages.firstname')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_firstname')"
                                    name="first_name" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title"> @lang('messages.lastname')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_lastname')" name="last_name"
                                    value="{{ old('last_name') }}">
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title"> @lang('messages.mobile_phone')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_phone')"
                                    pattern="[0-9]{10}" maxlength="10" name="mobile_phone"
                                    title="Please enter a 10-digit phone number" value="{{ old('mobile_phone') }}">
                                @error('mobile_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title"> @lang('messages.email')</label>
                                <input type="text" class="form-control" placeholder="@lang('messages.input_email')" name="email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <h3 class="fs-18 mb-3 mt-5">@lang('messages.tax_info')</h3>
                    <div class="row form-row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.tax_id')</label>
                                <input type="text" maxlength="13" class="form-control" name="tax_id"
                                    placeholder="@lang('messages.input_tax_id')" value="{{ old('tax_id') }}">
                                @error('tax_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.province')</label>
                                <select id="provinceSelect" name="province_id" class="form-select"
                                    data-lang="{{ App::getLocale() }}">
                                    <option value="">@lang('messages.select_province')</option>
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
                                    <option value="">@lang('messages.select_district')</option>
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
                                    <option value="">@lang('messages.select_subdistrict')
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
                                <select id="postalCodeSelect" name="postal_code" class="form-select" readonly>
                                    <option value="">@lang('messages.select_postal_code')</option>
                                </select>
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label class="title">@lang('messages.detailed_address')</label>
                                <textarea class="form-control h-145" placeholder="@lang('messages.enter_address_details')" name="detail">{{ old('detail') }}</textarea>
                                @error('detail')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check mt-3">
                                <input type="hidden" name="is_default" value="0">
                                <input class="form-check-input" type="checkbox" value="1" id="check1"
                                    name="is_default" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="check1">
                                    <strong>@lang('messages.set_as_default')</strong><br>
                                    <span class="fs-14">@lang('messages.automatic_setting')</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="buttons button-confirm justify-content-lg-end mb-4">
                                <a class="btn btn-outline-red"
                                    href="{{ route('tax', ['lang' => app()->getLocale()]) }}">@lang('messages.cancel')</a>
                                <button type="submit" class="btn btn-secondary">@lang('messages.submit')</button>
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
                window.location.href = '{{ route('tax', ['lang' => app()->getLocale()]) }}';
            });
        </script>
    @endif
    <script src="{{ asset('js/address-add.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#form-company').hide();
            $('input[name="type"]').change(function() {
                if ($(this).val() === 'personal') {
                    $('#form-company').hide();
                } else {
                    $('#form-company').show();
                }
            });
        });
    </script>
@endsection
