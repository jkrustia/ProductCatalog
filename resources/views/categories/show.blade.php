@extends('layouts.dashboard-layout')

@section('title', 'View Category')

@section('content')
    <section class="d-category-show">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'categories.index'])
            <h1 class="mx-auto">Products in {{ $category->name }}</h1> {{-- Display category name --}}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->subCategory->name ?? 'N/A' }}</td> {{-- Handle cases where subCategory might be null --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No products found for this category.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection