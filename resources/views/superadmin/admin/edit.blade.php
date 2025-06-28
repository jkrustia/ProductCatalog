@extends('layouts.superadmin-layout')

@section('title', 'Edit Admin')

@section('content')
    <section class="container admin-list mt-3">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'admin.index'])
            <h1 class="mx-auto">Edit Admin</h1>
        </div>
        <div class="card mx-auto p-4" style="max-width: 600px; border-radius: 24px;">
            <form>
                <div class="mb-4">
                    <input type="text" class="form-control fw-bold mb-4"
                        style="font-size: 2rem; border: 1px solid #444; border-radius: 4px; padding: 0.2em 0.5em; display: inline-block;"
                        id="name" name="name" value="{{ $admin['name'] }}">
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Email:</span>
                        <input type="email" class="form-control tamad-nunito ms-2 d-inline-block"
                            style="width: auto; min-width: 180px;" id="email" name="email"
                            value="{{ $admin['email'] }}">
                    </div>
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Username:</span>
                        <input type="text" class="form-control tamad-nunito ms-2 d-inline-block"
                            style="width: auto; min-width: 120px;" id="username" name="username"
                            value="{{ $admin['username'] }}">
                    </div>
                    <div class="mb-2 d-flex align-items-center">
                        <span class="tamad-baloo fw-bold" style="font-size: 1rem;">Password:</span>
                        <input type="password" class="form-control tamad-nunito ms-2 d-inline-block"
                            style="width: auto; min-width: 120px;" id="password" name="password"
                            value="{{ $admin['password'] }}">
                        <span class="ms-2" style="font-size: 1.2em; cursor:pointer;" id="toggle-password">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                fill="#222" viewBox="0 0 24 24">
                                <path
                                    d="M12 5c-7.633 0-11 7-11 7s3.367 7 11 7 11-7 11-7-3.367-7-11-7zm0 12c-4.418 0-8-3.582-8-5s3.582-5 8-5 8 3.582 8 5-3.582 5-8 5zm0-8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 4c-.552 0-1-.447-1-1s.448-1 1-1 1 .447 1 1-.448 1-1 1z" />
                            </svg>
                        </span>
                    </div>
                    <div class="tamad-baloo fw-bold mt-4 mb-2" style="font-size: 1.1rem;">Permissions:</div>
                    <div id="permissions-list" class="d-flex flex-wrap gap-2">
                        @foreach ($admin['permissions'] as $permission)
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
    <script defer src="{{ asset('js/password.js') }}"></script>
@endsection
