@extends('layouts.superadmin-layout')

@section('title', 'View User')

@section('content')
    <section class="container admin-list mt-3">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'users.index'])
            <h1 class="mx-auto">View User</h1>
        </div>
        <div class="card mx-auto p-4 mb-5" style="max-width: 600px; border-radius: 24px;">
            <div class="mb-4">
                <h2 class="fw-bold mb-4" style="font-size: 2rem;">{{ $user['name'] }}</h2>
                <div class="mb-2">
                    <span class="tamad-baloo fw-bold">Email:</span> 
                    <span class="tamad-nunito text-muted">{{ $user['email'] ?? 'N/A' }}</span>
                </div>
                <div class="mb-2">
                    <span class="tamad-baloo fw-bold">Username:</span> 
                    <span class="tamad-nunito text-muted">{{ $user['username'] ?? 'N/A' }}</span>
                </div>
                <div class="mb-2 d-flex align-items-center">
                    <span class="tamad-baloo fw-bold">Password:</span>
                    <span class="tamad-nunito text-muted ms-2" id="password-field" style="letter-spacing:2px;">
                        ••••••••
                    </span>
                    <input type="hidden" id="real-password" value="[Hidden for security]">
                    <span class="ms-2" style="font-size: 1.2em; cursor:pointer;" id="toggle-password">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#222" viewBox="0 0 24 24">
                            <path d="M12 5c-7.633 0-11 7-11 7s3.367 7 11 7 11-7 11-7-3.367-7-11-7zm0 12c-4.418 0-8-3.582-8-5s3.582-5 8-5 8 3.582 8 5-3.582 5-8 5zm0-8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 4c-.552 0-1-.447-1-1s.448-1 1-1 1 .447 1 1-.448 1-1 1z"/>
                        </svg>
                    </span>
                    <small class="text-muted ms-2">Password hidden for security</small>
                </div>
                <div class="mb-2">
                    <span class="tamad-baloo fw-bold">Role:</span> 
                    <span class="tamad-nunito text-muted">{{ $user['role'] }}</span>
                </div>
                <div class="mb-2">
                    <span class="tamad-baloo fw-bold">Created:</span> 
                    <span class="tamad-nunito text-muted">{{ isset($user['created_at']) ? $user['created_at']->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="tamad-baloo fw-bold mt-4 mb-2" style="font-size: 1.1rem;">Permissions:</div>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $roleColors = [
                            'Super Admin' => '#28a745', // Green
                            'Admin' => '#007bff',      // Blue
                            'Product Manager' => '#fd7e14', // Orange
                            'User' => '#ffc107',       // Yellow
                        ];
                        $permissionColor = $roleColors[$user['role']] ?? '#6c757d'; // Default gray
                    @endphp
                    @forelse ($user['permissions'] as $permission)
                        <span class="badge rounded-pill px-4 py-2" style="background:{{ $permissionColor }}; color:#fff; font-weight:500;">{{ $permission }}</span>
                    @empty
                        <span class="text-muted">No permissions assigned</span>
                    @endforelse
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('users.edit', $user['id']) }}" class="btn" 
                   style="background:#34d399; color:#fff; font-weight:600; font-size:1.2rem; border-radius:12px; min-width:200px;">
                    Edit User
                </a>
            </div>
        </div>
    </section>

    <script>
        // Override the password toggle to show security message
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('toggle-password');
            const passwordField = document.getElementById('password-field');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    alert('Password is hidden for security reasons. Use the edit form to change the password.');
                });
            }
        });
    </script>
@endsection