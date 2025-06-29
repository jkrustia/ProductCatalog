@extends('layouts.superadmin-layout')

@section('title', 'Dashboard')

@section('content')
    <h2><br>&nbsp;&nbsp;&nbsp;Welcome, {{ Auth::user()->name }}!</h2>
    <section class="container sa-overview">
        <h1>SuperAdmin</h1>
        <a class="ms-1" href="{{ route('users.index') }}">
            <img class="buttons" src="{{ asset('images/manage_users.png') }}" alt="Manage Users">
        </a>
        <div class="d-flex flex-column flex-lg-row justify-content-center">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <img class="admin-img mb-auto" src="{{ asset('images/admin.png') }}" alt="Admin Icon">
                    <div>
                        <h2 class="admin-title text-center">ADMIN</h2>
                        <a href="{{ route('admin.index') }}">
                            <img class="buttons admin" src="{{ asset('images/manage_admin.png') }}" alt="Manage Admin Button">
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <img class="pm-img mb-auto" src="{{ asset('images/pm.png') }}" alt="PM Icon">
                    <div>
                        <h2 class="text-center">Product <br> Manager</h2>
                        <a href="{{ route('productmanager.index') }}">
                            <img class="buttons" src="{{ asset('images/manage_pm.png') }}" alt="Manage PM Button">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
