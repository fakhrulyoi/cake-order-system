@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manage Cakes</h4>
                    <a href="{{ route('cakes.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add New Cake
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cakes as $cake)
                                <tr>
                                    <td>
                                        @if($cake->image)
                                        <img src="{{ asset('storage/' . $cake->image) }}"
                                             alt="{{ $cake->name }}"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                        <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $cake->name }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($cake->description, 40) }}</small>
                                    </td>
                                    <td>
                                        @if($cake->category)
                                        <span class="badge bg-info">{{ $cake->category }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $cake->size ?? '-' }}</td>
                                    <td><strong>RM {{ number_format($cake->price, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $cake->status === 'available' ? 'success' : 'danger' }}">
                                            {{ ucfirst($cake->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cakes.edit', $cake) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('cakes.destroy', $cake) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete {{ $cake->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2">No cakes found. Add your first cake!</p>
                                        <a href="{{ route('cakes.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Add New Cake
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
