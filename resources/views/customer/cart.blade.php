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
            /* Using the theme color from index */
            --theme-color: #A32938;
        }

        body {
            background-color: #F8F9FA;
            padding-bottom: 150px;
            font-family: 'Poppins', sans-serif;
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

        .cart-item {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .item-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Reduced gap */
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid var(--theme-color);
            background: white;
            color: var(--theme-color);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .quantity-btn:hover {
            background: var(--theme-color);
            color: white;
        }

        .delete-btn {
            color: #EF4444;
            cursor: pointer;
            font-size: 1.8rem; /* Larger icon for easier tap */
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .delete-btn:hover {
            opacity: 1;
        }

        .checkout-section {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 -6px 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .btn-checkout {
            background: var(--theme-color);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            width: 100%;
            font-weight: 700;
            font-size: 1.1rem;
            transition: background 0.2s;
        }

        .btn-checkout:hover {
            background: #8e2330;
        }

        .modal-content {
            border-radius: 20px;
        }

        /* Modal specific button for better contrast */
        #checkoutModal .btn-checkout {
            background: #10B981; /* Use a distinct success color for final action */
        }

        #checkoutModal .btn-checkout:hover {
            background: #0d8f63;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem;
        }

        .notes-input {
            border: 1px solid #D1D5DB; /* Solid border for clarity */
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            display: none;
        }

        .add-note-btn {
            color: var(--theme-color);
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 500;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem 1rem;
            background: white;
            border-radius: 15px;
            margin-top: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .empty-cart i {
            font-size: 5rem;
            color: #D1D5DB;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-start align-items-center gap-3">
                <a href="{{ route('customer.index') }}" class="text-white">
                    <i class="bi bi-arrow-left" style="font-size: 1.5rem;"></i>
                </a>
                <h5 class="mb-0 fw-bold">🛒 Your Order Cart</h5>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div id="cartItems"></div>

        <div id="emptyCart" class="empty-cart" style="display: none;">
            <i class="bi bi-bag-x-fill"></i>
            <h4 class="mt-3 fw-bold">Your cart is empty</h4>
            <p class="text-muted">No sweet treats yet! Add some delicious cakes to get started.</p>
            <a href="{{ route('customer.index') }}" class="btn btn-primary" style="background: var(--theme-color); border: none; border-radius: 10px;">
                <i class="bi bi-arrow-left-circle"></i> Browse Cakes
            </a>
        </div>
    </div>

    <div class="checkout-section" id="checkoutSection" style="display: none;">
        <div class="container">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted fw-bold">Total Items:</span>
                <span id="totalItems" class="fw-bold">0</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <span class="fs-4 fw-bold">Grand Total:</span>
                <span id="totalAmount" class="fs-3 fw-bold" style="color: var(--theme-color);">RM 0.00</span>
            </div>
            <button class="btn-checkout" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                <i class="bi bi-wallet2"></i> Proceed to Checkout
            </button>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"> <div class="modal-content">
                <div class="modal-header" style="background: var(--theme-color); color: white; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-receipt"></i> Complete Your Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm">
                        <h6 class="fw-bold mb-3" style="color: var(--theme-color);"><i class="bi bi-person-lines-fill"></i> Contact Details</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name *</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number *</label>
                            <input type="tel" class="form-control" name="customer_phone" placeholder="0123456789" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">WhatsApp Number (For easy communication)</label>
                            <input type="tel" class="form-control" name="customer_whatsapp" placeholder="60123456789">
                            <small class="text-muted">Format: 60123456789 (with country code)</small>
                        </div>

                        <h6 class="fw-bold mb-3" style="color: var(--theme-color);"><i class="bi bi-clock-fill"></i> Pickup Time</h6>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pickup Date & Time *</label>
                            <input type="datetime-local" class="form-control" name="pickup_datetime" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Special Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Any special requests or instructions for the entire order..."></textarea>
                        </div>

                        <div class="alert alert-info mt-3" style="border-radius: 10px;">
                            <i class="bi bi-bank2"></i> <strong>Payment Method:</strong> Online Payment (FPX) is required for order confirmation.
                        </div>

                        <div class="mb-4 p-3 bg-light" style="border-radius: 10px;">
                            <h6 class="fw-bold mb-3">Order Summary</h6>
                            <div id="checkoutSummary"></div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fs-5 fw-bold pt-2">
                                <span>Total Payment Due:</span>
                                <span class="fs-4 fw-bold" style="color: var(--theme-color);" id="modalTotal">RM 0.00</span>
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
        // ... (Keep existing JS functions: renderCart, updateCheckoutSummary, updateQuantity, removeItem, toggleNotes, updateNotes, Form Submission Logic) ...

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
                                <h6 class="mb-1 fw-bold">${item.name}</h6>
                                <div class="fw-bold mb-2" style="color: var(--theme-color);">RM ${item.price.toFixed(2)}</div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="quantity-control">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(${index}, -1)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span class="fw-bold mx-2">${item.quantity}</span>
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(${index}, 1)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <span class="fw-bold fs-5">RM ${subtotal.toFixed(2)}</span>
                                </div>

                                <div class="mt-2">
                                    <a href="#" class="add-note-btn" onclick="toggleNotes(${index}); return false;">
                                        <i class="bi bi-pencil-square"></i> ${item.notes ? 'Edit note' : 'Add a specific note for this cake'}
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
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                `;
            });

            cartContainer.innerHTML = html;
            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalAmount').textContent = 'RM ' + totalAmount.toFixed(2);
            document.getElementById('modalTotal').textContent = 'RM ' + totalAmount.toFixed(2);

            updateCheckoutSummary();
        }

        function updateCheckoutSummary() {
            let html = '';
            cart.forEach(item => {
                html += `
                    <div class="d-flex justify-content-between mb-1">
                        <span>${item.quantity}x ${item.name}</span>
                        <span class="fw-bold">RM ${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `;
                if (item.notes) {
                    html += `<div class="text-muted small mb-2 ps-3">↳ Note: ${item.notes}</div>`;
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
            if (confirm('Are you sure you want to remove ' + cart[index].name + ' from your cart?')) {
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

        // Handle checkout form submission (Keep original fetch logic)
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
