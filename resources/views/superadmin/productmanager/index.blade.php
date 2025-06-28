@include('partials._delete')
@extends('layouts.superadmin-layout')

@section('title', 'Product Manager')

@section('content')
    <section class="container admin-list mt-3">
        <div class="d-flex mb-3">
            @include('partials._return', ['route' => 'superadmin.dashboard'])
            <h1 class="mx-auto">Product Managers</h1>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>PMID</th>
                        <th>Name</th>
                        <th class="permission-col">Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prodman as $pm)
                        <tr>
                            <td>{{ $pm['id'] }}</td>
                            <td>{{ $pm['name'] }}</td>
                            <td>
                                <div
                                    class="d-flex flex-wrap flex-grow-1 justify-content-center align-items-center gap-2 px-3">
                                    @foreach ($pm['permissions'] as $permission)
                                        <span class="badge bg-primary p-1 my-1">{{ $permission }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="icon-col">
                                        <a href="{{ route('productmanager.show', $pm['id']) }}" title="View">
                                            <img class="icon" src="{{ asset('images/view.png') }}" alt="View">
                                        </a>
                                        <a href="{{ route('productmanager.edit', $pm['id']) }}" title="Edit">
                                            <img class="icon" src="{{ asset('images/edit.png') }}" alt="Edit">
                                        </a>
                                        <a href="#"
                                            onclick="openDeleteModal('{{ route('productmanager.destroy', $pm['id']) }}'); return false;">
                                            <img class="icon" src="{{ asset('images/delete.png') }}" alt="Delete">
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No product manager found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-2 text-end">
            <a href="{{ route('productmanager.create') }}" role="button" class="add-btn">
                Create Product Manager
            </a>
        </div>
    </section>
@endsection
