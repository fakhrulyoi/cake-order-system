{{-- resources/views/admin/cakes/create.blade.php and edit.blade.php use the same form --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ isset($cake) ? 'Edit Cake' : 'Add New Cake' }}</h4>
                    <a href="{{ route('cakes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($cake) ? route('cakes.update', $cake) : route('cakes.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($cake))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Cake Name -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold">Cake Name *</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $cake->name ?? '') }}"
                                       placeholder="e.g., Red Velvet Cake"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-bold">Category</label>
                                <input type="text"
                                       class="form-control @error('category') is-invalid @enderror"
                                       id="category"
                                       name="category"
                                       value="{{ old('category', $cake->category ?? '') }}"
                                       placeholder="e.g., Birthday Cake, Wedding Cake"
                                       list="category-suggestions">
                                <datalist id="category-suggestions">
                                    <option value="Birthday Cake">
                                    <option value="Wedding Cake">
                                    <option value="Kek Batik">
                                    <option value="Cheesecake">
                                    <option value="Brownies">
                                    <option value="Red Velvet">
                                    <option value="Chocolate">
                                    <option value="Buttercake">
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Size -->
                            <div class="col-md-6 mb-3">
                                <label for="size" class="form-label fw-bold">Size</label>
                                <input type="text"
                                       class="form-control @error('size') is-invalid @enderror"
                                       id="size"
                                       name="size"
                                       value="{{ old('size', $cake->size ?? '') }}"
                                       placeholder="e.g., 8 inch, 6 & 8 inch">
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-bold">Price (RM) *</label>
                                <input type="number"
                                       step="0.01"
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="price"
                                       name="price"
                                       value="{{ old('price', $cake->price ?? '') }}"
                                       placeholder="0.00"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                        required>
                                    <option value="available" {{ old('status', $cake->status ?? 'available') == 'available' ? 'selected' : '' }}>
                                        Available
                                    </option>
                                    <option value="unavailable" {{ old('status', $cake->status ?? '') == 'unavailable' ? 'selected' : '' }}>
                                        Unavailable
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          placeholder="Describe your cake...">{{ old('description', $cake->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label fw-bold">Cake Image</label>

                                @if(isset($cake) && $cake->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $cake->image) }}"
                                         alt="{{ $cake->name }}"
                                         style="max-width: 200px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                    <p class="text-muted small mt-2">Current image (upload new to replace)</p>
                                </div>
                                @endif

                                <input type="file"
                                       class="form-control @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImage(event)">
                                <small class="text-muted">Max size: 2MB. Formats: JPG, JPEG, PNG</small>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="preview"
                                         src=""
                                         alt="Preview"
                                         style="max-width: 200px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                </div>

                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> {{ isset($cake) ? 'Update Cake' : 'Add Cake' }}
                                </button>
                                <a href="{{ route('cakes.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
