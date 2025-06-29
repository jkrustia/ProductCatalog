@include('partials._delete')
@extends('layouts.superadmin-layout')

@section('title', 'Users')

@section('content')
    <section class="container admin-list mt-3">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'superadmin.dashboard'])
            <h1 class="mx-auto">Users</h1>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="permission-col">Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>
                                <span class="badge" style="background-color: 
                                    @if ($user['role'] == 'Super Admin') #28a745 !important; /* Green */
                                    @elseif ($user['role'] == 'Admin') #007bff !important; /* Blue */
                                    @elseif ($user['role'] == 'Product Manager') #fd7e14 !important; /* Orange */
                                    @elseif ($user['role'] == 'User') #ffc107 !important; /* Yellow */
                                    @else #6c757d !important; /* Default for 'No Role' or other unlisted roles */ @endif
                                ">{{ $user['role'] }}</span>
                            </td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>
                                <div class="d-flex flex-wrap flex-grow-1 justify-content-center align-items-center gap-2 px-3">
                                    @forelse ($user['permissions'] as $permission)
                                        <span class="badge bg-primary p-1 my-1">{{ $permission }}</span>
                                    @empty
                                        <span class="text-muted">No permissions</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2 icon-col">
                                    <a href="{{ route('users.show', $user['id']) }}" title="View">
                                        <img class="icon" src="{{ asset('images/view.png') }}" alt="View">
                                    </a>
                                    <a href="{{ route('users.edit', $user['id']) }}" title="Edit">
                                        <img class="icon" src="{{ asset('images/edit.png') }}" alt="Edit">
                                    </a>
                                    <button type="button" title="Delete"
                                        onclick="openDeleteModal('{{ route('users.destroy', $user['id']) }}')">
                                        <img class="icon" src="{{ asset('images/delete.png') }}" alt="Delete">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <p>No users found.</p>
                                    <a href="{{ route('users.create') }}" class="btn btn-primary">Create First User</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-2 text-end">
            <a href="{{ route('users.create') }}" role="button" class="add-btn btn btn-success">
                <i class="fas fa-plus me-2"></i>Create User
            </a>
        </div>
    </section>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(function() {
                            alert.remove();
                        }, 150);
                    }
                }, 5000);
            });
        });
    </script>
@endsection