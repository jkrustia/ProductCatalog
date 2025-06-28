@extends('layouts.dashboard-layout')

@section('title', 'Edit Category')

@section('content')
    <section class="d-category-edit">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'categories.index'])
            <h1 class="mx-auto">Edit Sub Category</h1> {{-- Changed to Sub Category --}}
        </div>
        <div class="card mx-auto p-4">
            <form action="{{ route('categories.update', $subCategory->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}
                <div class="mb-4">
                    <label for="category_id" class="form-label">Category Name</label>
                    <div class="position-relative">
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option selected disabled>Choose Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <span>
                            <svg viewBox="0 0 24 24">
                                <polygon points="6,9 12,15 18,9" />
                            </svg>
                        </span>
                    </div>
                    @error('category_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="name" class="form-label">Sub Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $subCategory->name) }}" placeholder="Write Sub Category Name" required>
                    @error('name')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection