{{-- resources/views/admin/settings/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-gear"></i> Store Settings</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Store Name -->
                        <div class="mb-3">
                            <label for="store_name" class="form-label fw-bold">Store Name *</label>
                            <input type="text"
                                   class="form-control @error('store_name') is-invalid @enderror"
                                   id="store_name"
                                   name="store_name"
                                   value="{{ old('store_name', $setting->store_name ?? '') }}"
                                   required>
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label fw-bold">WhatsApp Number *</label>
                            <input type="text"
                                   class="form-control @error('whatsapp_number') is-invalid @enderror"
                                   id="whatsapp_number"
                                   name="whatsapp_number"
                                   value="{{ old('whatsapp_number', $setting->whatsapp_number ?? '') }}"
                                   placeholder="60123456789"
                                   required>
                            <small class="text-muted">Format: Country code + number (e.g., 60142153722)</small>
                            @error('whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Store Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Store Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description about your store...">{{ old('description', $setting->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Theme Color -->
                        <div class="mb-3">
                            <label for="theme_color" class="form-label fw-bold">Theme Color *</label>
                            <div class="input-group">
                                <input type="color"
                                       class="form-control form-control-color @error('theme_color') is-invalid @enderror"
                                       id="theme_color"
                                       name="theme_color"
                                       value="{{ old('theme_color', $setting->theme_color ?? '#A32938') }}"
                                       style="width: 80px;"
                                       required>
                                <input type="text"
                                       class="form-control"
                                       id="theme_color_text"
                                       value="{{ old('theme_color', $setting->theme_color ?? '#A32938') }}"
                                       readonly>
                            </div>
                            <small class="text-muted">Choose your store's primary color</small>
                            @error('theme_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Store Status -->
                        <div class="mb-3">
                            <label for="store_status" class="form-label fw-bold">Store Status *</label>
                            <select class="form-select @error('store_status') is-invalid @enderror"
                                    id="store_status"
                                    name="store_status"
                                    required>
                                <option value="open" {{ old('store_status', $setting->store_status ?? 'open') === 'open' ? 'selected' : '' }}>
                                    Open - Accepting Orders
                                </option>
                                <option value="closed" {{ old('store_status', $setting->store_status ?? '') === 'closed' ? 'selected' : '' }}>
                                    Closed - Not Accepting Orders
                                </option>
                            </select>
                            @error('store_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Banner Image -->
                        <div class="mb-4">
                            <label for="banner_image" class="form-label fw-bold">Store Banner Image</label>

                            @if($setting->banner_image ?? false)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $setting->banner_image) }}"
                                     alt="Current Banner"
                                     class="img-fluid rounded"
                                     style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                <p class="text-muted small mt-2">Current banner (upload new to replace)</p>
                            </div>
                            @endif

                            <input type="file"
                                   class="form-control @error('banner_image') is-invalid @enderror"
                                   id="banner_image"
                                   name="banner_image"
                                   accept="image/jpeg,image/png,image/jpg"
                                   onchange="previewBanner(event)">
                            <small class="text-muted">Max size: 2MB. Recommended: 1200x400px</small>

                            <!-- Banner Preview -->
                            <div id="bannerPreview" class="mt-3" style="display: none;">
                                <img id="preview"
                                     src=""
                                     alt="Preview"
                                     class="img-fluid rounded"
                                     style="max-width: 100%; max-height: 200px; object-fit: cover;">
                            </div>

                            @error('banner_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quick Stats -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Store Statistics</h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4>{{ App\Models\Cake::count() }}</h4>
                                    <small>Total Cakes</small>
                                </div>
                                <div class="col-4">
                                    <h4>{{ App\Models\Order::count() }}</h4>
                                    <small>Total Orders</small>
                                </div>
                                <div class="col-4">
                                    <h4>RM {{ number_format(App\Models\Order::sum('total_amount'), 2) }}</h4>
                                    <small>Total Revenue</small>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Save Settings
                            </button>
                        </div>
                    </form>

                    <!-- Preview Customer View -->
                    <div class="mt-4 pt-4 border-top">
                        <h5><i class="bi bi-eye"></i> Preview</h5>
                        <a href="{{ route('customer.index') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right"></i> View Customer Store
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Color picker sync
document.getElementById('theme_color').addEventListener('input', function(e) {
    document.getElementById('theme_color_text').value = e.target.value;
});

// Banner preview
function previewBanner(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('bannerPreview');
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
