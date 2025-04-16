@extends('administrator.layouts.main')

@section('title')

@section('content')
<!-- Profile Image CSS -->
<style>
.preview-container {
    width: 150px;
    height: 150px;
    position: relative;
    margin: 10px 0;
}

.profile-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #3498db;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    padding: 3px;
    background-color: white;
}
</style>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('administrator.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('administrator.users') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
    </ol>
    <form id="form-create" method="POST" action="{{ route('administrator.users.submit') }}" 
    class="mx-1 mx-md-4" enctype="multipart/form-data">
        @csrf
        <div class="demo-inline-spacing">
            <div class="text-end">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('administrator.users') }}"> <button type="button"
                        class="btn btn-danger">Cancel</button></a>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    <div class="mb-4">
                        <label for="profile_image" class="form-label">Profile Picture</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                            <input type="file" id="profile_image" name="profile_image" class="form-control" 
                                accept="image/*" onchange="previewImage(event)" />
                        </div>
                        @error('profile_image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="preview-container" style="display: none;">
                            <img id="imagePreview" src="#" alt="Preview" class="profile-preview">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label">Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="name" name="name" class="form-control" required />
                        </div>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email" class="form-control" required />
                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" required />
                        </div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmation your password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" required />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                            <select id="role" name="role_id" class="form-control" required>
                                <option value="">-- Select Role --</option>
                                @foreach ($roles->where('status', 1) as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-4 d-flex align-items-center gap-2">
                        <label for="status" class="form-label mb-0">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" value="1" name="status" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('script')
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const container = document.querySelector('.preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            container.style.display = 'none';
        }
    }
</script>
@endsection