
{{-- resources/views/customer/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $storeSetting->store_name ?? 'Cake Shop' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --theme-color: {{ $storeSetting->theme_color ?? '#14532D' }};
            --theme-light: {{ $storeSetting->theme_color ?? '#14532D' }}33;
        }

        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .header {
            background: var(--theme-color);
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .store-logo {
            width: 50px;
            height: 50px;
            background: #FCD34D;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            color: var(--theme-color);
        }

        .store-status {
            background: #10B981;
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
        }

        .store-status.closed {
            background: #EF4444;
        }

        .cake-card {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .cake-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .cake-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 0.8rem;
        }

        .cake-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }

        .cake-category {
            background: #FEF3C7;
            color: #92400E;
            padding: 0.2rem 0.6rem;
            border-radius: 5px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .cake-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--theme-color);
        }

        .btn-add {
            background: var(--theme-color);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-add:hover {
            background: #0f3f23;
            transform: scale(1.02);
        }

        .btn-add:disabled {
            background: #9CA3AF;
        }

        .cart-badge {
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: var(--theme-color);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            cursor: pointer;
            z-index: 999;
        }

        .cart-count {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #EF4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-around;
            padding: 0.8rem 0;
            z-index: 998;
        }

        .nav-item {
            text-align: center;
            color: #6B7280;
            text-decoration: none;
            flex: 1;
        }

        .nav-item.active {
            color: var(--theme-color);
        }

        .nav-item i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 0.2rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-badge.available {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-badge.unavailable {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="store-logo">K</div>
                    <div>
                        <h5 class="mb-0">{{ $storeSetting->store_name ?? 'Cake Shop' }}</h5>
                        <span class="store-status {{ $storeSetting->store_status ?? 'open' }}">
                            {{ strtoupper($storeSetting->store_status ?? 'OPEN') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('customer.cart') }}" class="text-white">
                    <i class="bi bi-house-door" style="font-size: 1.5rem;"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" style="padding-top: 1rem; padding-bottom: 100px;">
        @if($storeSetting && $storeSetting->description)
        <div class="alert alert-info mb-3">
            <i class="bi bi-info-circle"></i> {{ $storeSetting->description }}
        </div>
        @endif

        <h6 class="mb-3 text-muted">Available Cakes ({{ $cakes->count() }} items)</h6>

        <div class="row">
            @forelse($cakes as $cake)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="cake-card">
                    @if($cake->image)
                    <img src="{{ asset('storage/' . $cake->image) }}" alt="{{ $cake->name }}" class="cake-image">
                    @else
                    <div class="cake-image bg-light d-flex align-items-center justify-content-center">
                        <i class="bi bi-image" style="font-size: 3rem; color: #D1D5DB;"></i>
                    </div>
                    @endif

                    <div class="cake-name">{{ $cake->name }}</div>

                    @if($cake->category)
                    <span class="cake-category">{{ $cake->category }}</span>
                    @endif

                    @if($cake->size)
                    <div class="text-muted small mb-2">{{ $cake->size }}</div>
                    @endif

                    @if($cake->description)
                    <p class="text-muted small mb-2">{{ Str::limit($cake->description, 60) }}</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="cake-price">RM {{ number_format($cake->price, 2) }}</span>
                        <span class="status-badge {{ $cake->status }}">
                            @if($cake->status === 'available')
                                <i class="bi bi-check-circle"></i> Available
                            @else
                                <i class="bi bi-x-circle"></i> Unavailable
                            @endif
                        </span>
                    </div>

                    <button
                        class="btn btn-add"
                        onclick="addToCart({{ $cake->id }}, '{{ $cake->name }}', {{ $cake->price }}, '{{ $cake->image }}')"
                        {{ $cake->status === 'unavailable' ? 'disabled' : '' }}>
                        <i class="bi bi-plus-circle"></i> Add to Order
                    </button>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle"></i> No cakes available at the moment.
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Cart Badge -->
    <div class="cart-badge" onclick="window.location.href='{{ route('customer.cart') }}'">
        <i class="bi bi-bag" style="font-size: 1.8rem;"></i>
        <span class="cart-count" id="cartCount">0</span>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="{{ route('customer.index') }}" class="nav-item active">
            <i class="bi bi-house-door-fill"></i>
            <div style="font-size: 0.75rem;">Live</div>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-graph-up"></i>
            <div style="font-size: 0.75rem;">Orders</div>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-list"></i>
            <div style="font-size: 0.75rem;">Menu</div>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-gear"></i>
            <div style="font-size: 0.75rem;">Settings</div>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cart Management
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = count;
        }

        function addToCart(id, name, price, image) {
            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    image: image,
                    quantity: 1,
                    notes: ''
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();

            // Show notification
            alert('✓ ' + name + ' added to cart!');
        }

        // Initialize cart count
        updateCartCount();
    </script>
</body>
</html>
