@extends('layouts.dashboard-layout')
@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <p>This is your dashboard.</p>
@endsection