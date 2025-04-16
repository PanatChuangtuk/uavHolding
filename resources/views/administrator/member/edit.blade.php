@extends('administrator.layouts.main')

@section('title')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.member') }}">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
    <form id="form-update" method="POST" action="{{ route('administrator.member.update', $member->id) }}" class="mx-1 mx-md-4">
        @csrf
        <div class="demo-inline-spacing">
            <div class="text-end">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.member') }}"> <button type="button"
                        class="btn btn-danger">Cancel</button></a>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Member</h5>

                    <!-- First Name -->
                    <div class="mb-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="first_name" name="first_name" class="form-control" required
                                value="{{ $member->info->first_name ?? '' }}" />
                        </div>
                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="last_name" name="last_name" class="form-control" required
                                value="{{ $member->info->last_name ?? '' }}" />
                        </div>
                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ $member->email }}" required />
                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mobile Phone -->
                    <div class="mb-4">
                        <label for="mobile_phone" class="form-label">Mobile Phone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" id="mobile_phone" name="mobile_phone" class="form-control"
                                value="{{ $member->mobile_phone }}" />
                        </div>
                        @error('mobile_phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- VAT Number -->
                    <div class="mb-4">
                        <label for="vat_number" class="form-label">VAT Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" id="vat_number" name="vat_number" class="form-control"
                                value="{{ $member->info->vat_register_number ?? '' }}" />
                        </div>
                        @error('vat_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Customer Group Dropdown -->
                    <div class="mb-4">
                        <label for="customer_group" class="form-label">Customer Group</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            <select id="customer_group" name="customer_group" class="form-control" required>
                                @foreach($memberGroups as $group)
                                    @if($group->status == 1)
                                        <option value="{{ $group->id }}" 
                                            {{ $member->memberGroups->contains('id', $group->id) ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @error('customer_group')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Account Type Dropdown -->
                    <div class="mb-4">
                        <label for="account_type" class="form-label">Account Type</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <select id="account_type" name="account_type" class="form-control" required>
                                <option value="Government" 
                                    {{ strtolower($member->info->account_type ?? '') == 'government' ? 'selected' : '' }}>
                                    Government
                                </option>
                                <option value="Private" 
                                    {{ strtolower($member->info->account_type ?? '') == 'private' ? 'selected' : '' }}>
                                    Private
                                </option>
                            </select>
                        </div>
                        @error('account_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4 d-flex align-items-center gap-2">
                        <label for="status" class="form-label mb-0">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $member->status) == 1 ? 'checked' : '' }} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection