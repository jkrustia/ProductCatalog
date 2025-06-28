{{-- filepath: resources/views/prices/index.blade.php --}}
@extends('layouts.dashboard-layout')

@section('title', 'Prices')

@section('content')
    <section class="d-prices">
        <div class="d-flex mb-3">
            <h1>Prices</h1>
            <div class="product-search-bar ms-auto">
                <form action="{{ route('prices.index') }}" method="GET" class="search-form">
                    <div class="search-input-wrapper">
                        <img src="{{ asset('images/search.png') }}" alt="Search" class="search-icon">
                        <input type="text" name="query" class="search-input"
                            placeholder="Search Product Name, Price" value="{{ request('query') }}">
                    </div>
                    <button type="submit" class="search-btn ms-3">SEARCH</button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through the 'prices' collection, which are actually Product objects --}}
                    @foreach ($prices as $price)
                        <tr>
                            <td>{{ $price->name }}</td> {{-- Access name using object property --}}
                            <td>{{ $price->price }}</td> {{-- Access price using object property --}}
                            <td class="icon-col">
                                {{-- Use $price->id for the route parameter for route model binding --}}
                                @php
                                    $role = auth()->user()?->getRoleNames()->first();
                                @endphp
                                @if ($role === 'Admin')
                                    <a href="{{ route('prices.edit', $price->id) }}" title="Edit">
                                        <img class="icon" src="{{ asset('images/edit.png') }}" alt="Edit">
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled title="Only Admin can edit">
                                        <img class="icon" src="{{ asset('images/edit.png') }}" alt="Edit">
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection