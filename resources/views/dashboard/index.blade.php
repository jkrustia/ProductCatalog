@extends('layouts.dashboard-layout')

@section('title', 'Dashboard')

@section('content')
    <section class="d-home">
        <h1>Dashboard</h1>
        <div class="d-grid">
            <div class="d-item d-flex text-center">
                <img class="me-2" src="{{ asset('images/dashboard-01.png') }}" alt="Products Icon">
                <div class="text">
                    <span class="num">{{ $totalProducts }}</span> {{-- Display total products from database --}}
                    <span class="desc">Total Products</span>
                </div>
            </div>
            <div class="d-item d-flex text-center">
                <img class="me-2" src="{{ asset('images/dashboard-02.png') }}" alt="Items Icon">
                <div class="text">
                    <span class="num">{{ $totalItems }}</span> {{-- Display total items (not out of stock) --}}
                    <span class="desc">Total Items</span>
                </div>
            </div>
            <div class="d-item d-flex text-center">
                <img class="me-2" src="{{ asset('images/dashboard-03.png') }}" alt="Sales Icon">
                <div class="text">
                    <span class="num">Php 10,000</span>
                    <span class="desc">Total Sales</span>
                </div>
            </div>
            <div class="d-item d-flex text-center">
                <img class="me-2" src="{{ asset('images/dashboard-04.png') }}" alt="Orders Icon">
                <div class="text">
                    <span class="num">1000</span>
                    <span class="desc">Total Orders</span>
                </div>
            </div>
        </div>
    </section>
    <section class="d-overview mt-5">
        <h2>Overview</h2>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>
                                <img src="{{ $product->imageUrl }}" alt="Product Image" width="40"> {{-- Use imageUrl attribute from Product model --}}
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td> {{-- Access category name --}}
                            <td>₱ {{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock_status }}</td> {{-- Use stock_status from Product model --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <script src="{{ asset('js/sidebar-height.js') }}"></script>
@endsection