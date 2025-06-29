@extends('layouts.superadmin-layout')

@section('title', 'Edit User')

@section('content')
    <section class="container admin-list mt-3">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'users.index'])
            <h1 class="mx-auto">Edit User</h1>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mx-auto p-4 mb-5" style="max-width: 600px; border-radius: 24px;">
            <form action="{{ route('users.update', $user['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <input type="text" class="form-control fw-bold mb-4 @error('name') is-invalid @enderror"
                        style="font-size: 2rem; border: 1px solid #444; border-radius: 4px; padding: 0.2em 0.5em; display: inline-block;"
                        id="name" name="name" value="{{ old('name', $user['name']) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Email:</span>
                        <input type="email" class="form-control tamad-nunito ms-2 d-inline-block @error('email') is-invalid @enderror"
                            style="width: auto; min-width: 180px;" id="email" name="email"
                            value="{{ old('email', $user['email']) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Username:</span>
                        <input type="text" class="form-control tamad-nunito ms-2 d-inline-block"
                            style="width: auto; min-width: 120px;" id="username" name="username"
                            value="{{ old('username', $user['username']) }}">
                        <small class="text-muted ms-2">Optional</small>
                    </div>
                    
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Password:</span>
                        <input type="password" class="form-control tamad-nunito ms-2 d-inline-block @error('password') is-invalid @enderror"
                            style="width: auto; min-width: 120px;" id="password" name="password" 
                            placeholder="Leave blank to keep current password">
                        <span class="ms-2" style="font-size: 1.2em; cursor:pointer;" id="toggle-password">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                fill="#222" viewBox="0 0 24 24">
                                <path
                                    d="M12 5c-7.633 0-11 7-11 7s3.367 7 11 7 11-7 11-7-3.367-7-11-7zm0 12c-4.418 0-8-3.582-8-5s3.582-5 8-5 8 3.582 8 5-3.582 5-8 5zm0-8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 4c-.552 0-1-.447-1-1s.448-1 1-1 1 .447 1 1-.448 1-1 1z" />
                            </svg>
                        </span>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Role:</span>
                        <select class="form-select tamad-nunito ms-2 d-inline-block @error('role') is-invalid @enderror" 
                            style="width: auto; min-width: 120px;" id="role" name="role" required>
                            <option value="Super Admin" {{ old('role', $user['role']) == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="Admin" {{ old('role', $user['role']) == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Product Manager" {{ old('role', $user['role']) == 'Product Manager' ? 'selected' : '' }}>
                                Product Manager</option>
                            <option value="User" {{ old('role', $user['role']) == 'User' ? 'selected' : '' }}>User</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="tamad-baloo fw-bold mt-4 mb-2" style="font-size: 1.1rem;">Permissions:</div>
                    <div id="permissions-list" class="d-flex flex-wrap gap-2">
                        @foreach ($user['permissions'] as $permission)
                            <span class="badge rounded-pill px-4 py-2"
                                style="background:#f7bcbc; color:#222; font-weight:500;">{{ $permission }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn"
                        style="background:#34d399; color:#fff; font-weight:600; font-size:1.2rem; border-radius:12px; min-width:200px;">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </section>
    
    <script>
        // Permissions by role
        const rolePermissions = {
            'Super Admin': ['full-access', 'manage-all-users', 'manage-settings'],
            'Admin': ['create-product', 'edit-product', 'delete-product', 'view-users', 'edit-users'],
            'Product Manager': ['create-product', 'edit-product', 'view-users', 'assign-tasks'],
            'User': ['view-products', 'view-tasks']
        };

        const roleColors = {
            'Super Admin': '#28a745', // Green
            'Admin': '#007bff',      // Blue
            'Product Manager': '#fd7e14', // Orange
            'User': '#ffc107',       // Yellow
        };
        
        const roleSelect = document.getElementById('role');
        const permissionsList = document.getElementById('permissions-list');

        function renderPermissions(perms, role) {
            permissionsList.innerHTML = '';
            const color = roleColors[role] || '#6c757d'; // Default gray if no specific color
            perms.forEach(function(perm) {
                const span = document.createElement('span');
                span.className = 'badge rounded-pill px-4 py-2';
                span.style.background = color;
                span.style.color = '#fff'; // White text for better contrast
                span.style.fontWeight = '500';
                span.textContent = perm;
                permissionsList.appendChild(span);
            });
        }
        
        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value;
            const permissions = rolePermissions[selectedRole] || [];
            renderPermissions(permissions, selectedRole);
        });
        
        // Set initial permissions based on current role
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = roleSelect.value;
            const permissions = rolePermissions[selectedRole] || [];
            renderPermissions(permissions, selectedRole);
        });
    </script>
    <script defer src="{{ asset('js/password.js') }}"></script>
@endsection