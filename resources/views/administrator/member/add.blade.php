@extends('administrator.layouts.main')

@section('title')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.member') }}">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
    <form id="form-update" method="POST" action="{{ route('administrator.member.submit') }}" class="mx-1 mx-md-4">
        @csrf
        <div class="demo-inline-spacing">
            <div class="text-end">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.member') }}"> <button type="button"
                        class="btn btn-danger">Cancel</button></a>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Member</h5>

                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="username" name="username" class="form-control" required
                                value="" />
                        </div>
                        @error('username')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div class="mb-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="first_name" name="first_name" class="form-control" required
                                value="" />
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
                                value="" />
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
                            <input type="email" id="email" name="email" class="form-control" value=""
                                required />
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
                                value="" />
                        </div>
                        @error('mobile_phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" id="password" name="password" class="form-control" value="" />
                        </div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Password Confirmation</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" id="password_confirmation" name="password_confirmation"
                                class="form-control" value="" />
                        </div>
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- VAT Number -->
                    <div class="mb-4">
                        <label for="vat_number" class="form-label">VAT Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" id="vat_number" name="vat_number" class="form-control"
                                value="" />
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
                                @foreach ($memberGroups as $group)
                                    @if ($group->status == 1)
                                        <option value="{{ $group->id }}">
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
                                <option value="government">
                                    Government
                                </option>
                                <option value="private">
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
                            <input class="form-check-input" type="checkbox" id="status" name="status"
                                value="1" />
                        </div>
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
                window.location.href = '{{ route('administrator.member') }}';
            });
        </script>
    @endif
@endsection
