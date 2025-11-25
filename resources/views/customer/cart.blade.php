{{-- resources/views/customer/cart.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order - Cake Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --theme-color: #14532D;
        }

        body {
            background-color: #f5f5f5;
            padding-bottom: 150px;
        }

        .header {
            background: var(--theme-color);
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .cart-item {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            background: white;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background: #F3F4F6;
        }

        .delete-btn {
            color: #EF4444;
            cursor: pointer;
            font-size: 1.5rem;
        }

        .checkout-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
        }

        .btn-checkout {
            background: var(--theme-color);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 10px;
            width: 100%;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .modal-content {
            border-radius: 20px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem;
        }

        .notes-input {
            border: 1px dashed #D1D5DB;
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            display: none;
        }

        .add-note-btn {
            color: var(--theme-color);
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-cart i {
            font-size: 5rem;
            color: #D1D5DB;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('customer.index') }}" class="text-white">
                        <i class="bi bi-arrow-left" style="font-size: 1.5rem;"></i>
                    </a>
                    <h5 class="mb-0">Your Order</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <div id="cartItems"></div>

        <div id="emptyCart" class="empty-cart" style="display: none;">
            <i class="bi bi-bag-x"></i>
            <h5 class="mt-3">Your cart is empty</h5>
            <p class="text-muted">Add some delicious cakes to get started!</p>
            <a href="{{ route('customer.index') }}" class="btn btn-primary">Browse Cakes</a>
        </div>
    </div>

    <!-- Checkout Section -->
    <div class="checkout-section" id="checkoutSection" style="display: none;">
        <div class="container">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Total Items:</span>
                <span id="totalItems" class="fw-bold">0</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="fs-5 fw-bold">Total Amount:</span>
                <span id="totalAmount" class="fs-4 fw-bold text-success">RM 0.00</span>
            </div>
            <button class="btn-checkout" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                <i class="bi bi-check-circle"></i> Proceed to Checkout
            </button>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--theme-color); color: white;">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name *</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number *</label>
                            <input type="tel" class="form-control" name="customer_phone" placeholder="0123456789" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">WhatsApp Number (Optional)</label>
                            <input type="tel" class="form-control" name="customer_whatsapp" placeholder="60123456789">
                            <small class="text-muted">Format: 60123456789 (with country code)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pickup Date & Time *</label>
                            <input type="datetime-local" class="form-control" name="pickup_datetime" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Special Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Any special requests or instructions..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i> <strong>Note:</strong> This is a takeaway order. Online payment required (FPX).
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold mb-3">Order Summary</h6>
                            <div id="checkoutSummary"></div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span>Total Payment:</span>
                                <span class="text-success" id="modalTotal">RM 0.00</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-checkout">
                            <i class="bi bi-credit-card"></i> Place Order & Pay (FPX)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function renderCart() {
            const cartContainer = document.getElementById('cartItems');
            const emptyCart = document.getElementById('emptyCart');
            const checkoutSection = document.getElementById('checkoutSection');

            if (cart.length === 0) {
                cartContainer.innerHTML = '';
                emptyCart.style.display = 'block';
                checkoutSection.style.display = 'none';
                return;
            }

            emptyCart.style.display = 'none';
            checkoutSection.style.display = 'block';

            let html = '';
            let totalAmount = 0;
            let totalItems = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                totalAmount += subtotal;
                totalItems += item.quantity;

                html += `
                    <div class="cart-item">
                        <div class="d-flex gap-3">
                            <img src="${item.image ? '/storage/' + item.image : '/images/placeholder.png'}"
                                 class="item-image" alt="${item.name}">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${item.name}</h6>
                                <div class="text-success fw-bold mb-2">RM ${item.price.toFixed(2)}</div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="quantity-control">
                                        <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span class="fw-bold">${item.quantity}</span>
                                        <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <span class="fw-bold">RM ${subtotal.toFixed(2)}</span>
                                </div>

                                <div class="mt-2">
                                    <a href="#" class="add-note-btn" onclick="toggleNotes(${index}); return false;">
                                        <i class="bi bi-pencil"></i> ${item.notes ? 'Edit note' : 'Add note'}
                                    </a>
                                    <input type="text"
                                           class="form-control notes-input"
                                           id="notes-${index}"
                                           placeholder="e.g., Less sweet, extra decoration..."
                                           value="${item.notes || ''}"
                                           onchange="updateNotes(${index}, this.value)"
                                           style="display: ${item.notes ? 'block' : 'none'}">
                                </div>
                            </div>
                            <div class="delete-btn" onclick="removeItem(${index})">
                                <i class="bi bi-trash"></i>
                            </div>
                        </div>
                    </div>
                `;
            });

            cartContainer.innerHTML = html;
            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalAmount').textContent = 'RM ' + totalAmount.toFixed(2);
            document.getElementById('modalTotal').textContent = 'RM ' + totalAmount.toFixed(2);

            // Update checkout summary
            updateCheckoutSummary();
        }

        function updateCheckoutSummary() {
            let html = '';
            cart.forEach(item => {
                html += `
                    <div class="d-flex justify-content-between mb-2">
                        <span>${item.quantity}x ${item.name}</span>
                        <span class="fw-bold">RM ${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `;
                if (item.notes) {
                    html += `<div class="text-muted small mb-2">Note: ${item.notes}</div>`;
                }
            });
            document.getElementById('checkoutSummary').innerHTML = html;
        }

        function updateQuantity(index, change) {
            cart[index].quantity += change;

            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        function removeItem(index) {
            if (confirm('Remove this item from cart?')) {
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
            }
        }

        function toggleNotes(index) {
            const notesInput = document.getElementById('notes-' + index);
            notesInput.style.display = notesInput.style.display === 'none' ? 'block' : 'none';
            if (notesInput.style.display === 'block') {
                notesInput.focus();
            }
        }

        function updateNotes(index, value) {
            cart[index].notes = value;
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        // Handle checkout form submission
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('cart', JSON.stringify(cart));

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

            try {
                const response = await fetch('{{ route("customer.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Clear cart
                    localStorage.removeItem('cart');
                    window.location.href = data.redirect;
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-credit-card"></i> Place Order & Pay (FPX)';
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-credit-card"></i> Place Order & Pay (FPX)';
            }
        });

        // Set minimum pickup datetime to 2 hours from now
        const pickupInput = document.querySelector('input[name="pickup_datetime"]');
        const minDate = new Date();
        minDate.setHours(minDate.getHours() + 2);
        pickupInput.min = minDate.toISOString().slice(0, 16);

        // Initialize
        renderCart();
    </script>
</body>
</html>
