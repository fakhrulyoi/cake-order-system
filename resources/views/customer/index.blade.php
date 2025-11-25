{{-- resources/views/customer/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $storeSetting->store_name ?? 'Yummy Kuantan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            /* Using a more vibrant color for a cake shop */
            --theme-color: {{ $storeSetting->theme_color ?? '#A32938' }}; /* Deep Berry Red */
            --theme-light: {{ $storeSetting->theme_color ?? '#A32938' }}33;
        }

        body {
            background-color: #F8F9FA; /* Lightest gray */
            font-family: 'Poppins', sans-serif; /* A more modern font-stack */
        }

        .header {
            background: var(--theme-color);
            color: white;
            padding: 1rem 0; /* Adjusted padding */
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .store-logo {
            width: 50px;
            height: 50px;
            /* Change background to a sweet, complementary color */
            background: #FFDAB9; /* Peach-puff */
            border-radius: 50%; /* Circle for a softer look */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            color: var(--theme-color);
            border: 2px solid white;
        }

        .store-status {
            /* Subtle change for better contrast */
            background: #10B981;
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            font-weight: 500;
        }

        .store-status.closed {
            background: #EF4444;
        }

        .cake-card {
            background: white;
            border-radius: 18px;
            padding: 0; /* Remove padding here to let image fill */
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            overflow: hidden; /* Important for border-radius on image */
        }

        .cake-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .cake-image {
            width: 100%;
            height: 220px; /* Slightly taller image */
            object-fit: cover;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            margin-bottom: 0;
            transition: transform 0.3s;
        }

        .cake-card:hover .cake-image {
            transform: scale(1.03);
        }

        .cake-info {
            padding: 1rem;
        }

        .cake-name {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }

        .cake-category {
            background: var(--theme-light);
            color: var(--theme-color);
            padding: 0.2rem 0.6rem;
            border-radius: 5px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .cake-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--theme-color);
        }

        .btn-add {
            background: var(--theme-color);
            color: white;
            border: none;
            padding: 0.7rem;
            border-radius: 12px;
            width: 100%;
            font-weight: 600;
            transition: background 0.3s, transform 0.3s;
        }

        .btn-add:hover {
            background: #8e2330; /* Darker theme color */
            transform: scale(1.01);
        }

        .btn-add:disabled {
            background: #ADB5BD; /* Grayed out */
        }

        .cart-badge {
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: #FFDAB9; /* Complementary color for badge */
            color: var(--theme-color);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 999;
            transition: transform 0.2s;
        }

        .cart-badge:hover {
            transform: scale(1.05);
        }

        .cart-count {
            position: absolute;
            top: 0;
            right: 0;
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
            border: 2px solid white;
        }

        .bottom-nav {
            /* ... (keep bottom nav styling) ... */
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-around;
            padding: 0.6rem 0; /* Slightly reduced padding */
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
            font-size: 1.4rem; /* Slightly smaller icon */
            display: block;
            margin-bottom: 0.1rem;
        }

        .status-badge {
            font-weight: 600;
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
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="store-logo">YK</div>
                    <div>
                        <h5 class="mb-0">{{ $storeSetting->store_name ?? 'Yummy Kuantan' }}</h5>
                        <span class="store-status {{ $storeSetting->store_status ?? 'open' }}">
                            {{ strtoupper($storeSetting->store_status ?? 'OPEN') }}
                        </span>
                    </div>
                </div>
                </div>
        </div>
    </div>

    <div class="container" style="padding-top: 1.5rem; padding-bottom: 120px;">
        @if($storeSetting && $storeSetting->description)
        <div class="alert alert-info mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-info-circle-fill"></i> {{ $storeSetting->description }}
        </div>
        @endif

        <h5 class="mb-4 fw-bold">🍰 Cakes Menu ({{ $cakes->count() }} items)</h5>

        <div class="row">
            @forelse($cakes as $cake)
            <div class="col-6 col-md-4 col-lg-3 mb-4"> <div class="cake-card">
                    @if($cake->image)
                    <img src="{{ asset('storage/' . $cake->image) }}" alt="{{ $cake->name }}" class="cake-image">
                    @else
                    <div class="cake-image bg-light d-flex align-items-center justify-content-center">
                        <i class="bi bi-image" style="font-size: 3rem; color: #D1D5DB;"></i>
                    </div>
                    @endif

                    <div class="cake-info">
                        @if($cake->category)
                        <span class="cake-category">{{ $cake->category }}</span>
                        @endif

                        <div class="cake-name">{{ $cake->name }}</div>

                        @if($cake->size)
                        <div class="text-muted small mb-1">{{ $cake->size }}</div>
                        @endif

                        @if($cake->description)
                        <p class="text-muted small mb-2">{{ Str::limit($cake->description, 35) }}</p>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="cake-price">RM {{ number_format($cake->price, 2) }}</span>
                            <span class="status-badge {{ $cake->status }}">
                                @if($cake->status === 'available')
                                    <i class="bi bi-check-circle-fill"></i> Available
                                @else
                                    <i class="bi bi-x-octagon-fill"></i> Unavailable
                                @endif
                            </span>
                        </div>

                        <button
                            class="btn btn-add"
                            onclick="addToCart({{ $cake->id }}, '{{ $cake->name }}', {{ $cake->price }}, '{{ $cake->image }}')"
                            {{ $cake->status === 'unavailable' ? 'disabled' : '' }}>
                            @if($cake->status === 'available')
                                <i class="bi bi-cart-plus"></i> Add to Order
                            @else
                                Sold Out
                            @endif
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-warning text-center" style="border-radius: 10px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> No delicious cakes available at the moment. Please check back later!
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="cart-badge" onclick="window.location.href='{{ route('customer.cart') }}'">
        <i class="bi bi-bag-fill" style="font-size: 1.8rem;"></i>
        <span class="cart-count" id="cartCount">0</span>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('customer.index') }}" class="nav-item active">
            <i class="bi bi-house-door-fill"></i>
            <div style="font-size: 0.7rem;">Home</div>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-search"></i>
            <div style="font-size: 0.7rem;">Search</div>
        </a>
        <a href="{{ route('customer.cart') }}" class="nav-item">
            <i class="bi bi-bag"></i>
            <div style="font-size: 0.7rem;">Cart</div>
        </a>
        <a href="#" class="nav-item">
            <i class="bi bi-person"></i>
            <div style="font-size: 0.7rem;">Account</div>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cart Management (Kept the original logic)
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

            // Replace alert with a better notification (or keep the simple alert for now)
            // Ideally, replace with a toast/snackbar notification
            // For now, keeping simple alert:
            alert('✓ ' + name + ' added to cart!');
        }

        // Initialize cart count
        updateCartCount();
    </script>
</body>
</html>
