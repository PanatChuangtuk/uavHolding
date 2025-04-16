@extends('administrator.layouts.main')

@section('title')
@endsection

@section('stylesheet')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-bread-crumb />
            <div class="card">
                <div class="card-body">
                    <h1>Set Access Control for Roles</h1>

                    <form id="permissionForm" action="{{ route('administrator.permissions.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    @foreach ($roles as $role)
                                        @if ($role->status == 1)
                                            @notSuperAdmin($role->id)
                                                <th class="text-center">{{ $role->name }}</th>
                                            @endnotSuperAdmin
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                        @foreach ($roles as $role)
                                            @if ($role->status == 1)
                                                @notSuperAdmin($role->id)
                                                    <td class="text-center align-middle">
                                                        <div class="form-check d-flex justify-content-center align-items-center"
                                                            style="font-size: 1rem;">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="permissions[{{ $permission->id }}][]"
                                                                value="{{ $role->id }}"
                                                                {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label"></label>
                                                        </div>
                                                    </td>
                                                @endnotSuperAdmin
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" id="saveButton" class="btn btn-primary mt-3">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/delete.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#saveButton').click(function() {
                $.ajax({
                    url: $('#permissionForm').attr('action'),
                    type: 'PUT',
                    data: $('#permissionForm').serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: response.status,
                            title: response.title,
                            text: response.message,
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update permissions: ' + xhr.responseJSON
                                .message,
                        });
                    }
                });
            });
        });
    </script>
@endsection
