<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - {{ $storeSetting->store_name ?? 'Yummy Kuantan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            /* Using the theme color from index */
            --theme-color: #A32938;
            --success-color: #10B981;
        }

        body {
            background-color: #F8F9FA;
            font-family: 'Poppins', sans-serif;
        }

        .header {
            background: var(--theme-color);
            color: white;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .success-card {
            background: white;
            border-radius: 25px; /* More rounded corners */
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 600px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15); /* Stronger shadow */
            text-align: center;
        }

        .success-icon {
            font-size: 6rem; /* Larger icon */
            color: var(--success-color);
            margin-bottom: 1rem;
        }

        h2 {
            font-weight: 700;
            color: var(--theme-color);
        }

        .order-details {
            background: #F0F4F7; /* Light background for the details box */
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: left;
            border: 1px solid #E0E7ED;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px dotted #D1D5DB; /* Dotted line for separation */
        }

        .detail-row:last-of-type {
            border-bottom: none;
        }

        .detail-row strong {
            font-weight: 600;
            color: #343A40;
        }

        .item-list {
            margin: 1rem 0;
            border-top: 1px dashed #CED4DA;
            padding-top: 1rem;
        }

        .item {
            padding: 0.75rem;
            background: white;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .btn-primary-theme {
            background: var(--theme-color);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            margin: 0.5rem;
            color: white;
            transition: background 0.2s;
        }

        .btn-primary-theme:hover {
            background: #8e2330;
        }

        .whatsapp-btn {
            background: #25D366;
            color: white;
        }

        .whatsapp-btn:hover {
            background: #20BA5A;
        }

        .total-amount {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--theme-color);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-gift-fill"></i> Yummy Kuantan
            </h5>
        </div>
    </div>

    <div class="container">
        <div class="success-card">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h2>Order Confirmed!</h2>
            <p class="text-muted fs-5">Your order has been successfully received and is being processed.</p>

            <div class="order-details">
                <h5 class="mb-3 fw-bold text-center" style="color: var(--theme-color);">Order Summary: {{ $order->order_number }}</h5>

                <div class="detail-row">
                    <span class="text-muted">Customer Name:</span>
                    <strong>{{ $order->customer_name }}</strong>
                </div>

                <div class="detail-row">
                    <span class="text-muted">Phone:</span>
                    <strong>{{ $order->customer_phone }}</strong>
                </div>

                <div class="detail-row">
                    <span class="text-muted">Pickup Date & Time:</span>
                    <strong>{{ $order->pickup_datetime->format('d M Y, h:i A') }}</strong>
                </div>

                @if($order->notes)
                <div class="detail-row">
                    <span class="text-muted">Order Notes:</span>
                    <strong>{{ $order->notes }}</strong>
                </div>
                @endif

                <h6 class="mb-2 mt-3 fw-bold">Items:</h6>
                <div class="item-list">
                    @foreach($order->orderItems as $item)
                    <div class="item">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">{{ $item->quantity }}x {{ $item->cake->name }}</span>
                            <strong style="color: var(--theme-color);">RM {{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                        @if($item->special_notes)
                        <small class="text-muted"><i class="bi bi-info-circle"></i> Note: {{ $item->special_notes }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="detail-row mt-3">
                    <span class="fs-5 fw-bold" style="color: var(--theme-color);">Total Amount:</span>
                    <span class="total-amount">RM {{ number_format($order->total_amount, 2) }}</span>
                </div>

                <div class="detail-row">
                    <span class="text-muted">Payment Status:</span>
                    <span class="badge rounded-pill bg-warning text-dark fw-bold">{{ ucfirst($order->payment_status) }}</span>
                </div>

                <div class="detail-row">
                    <span class="text-muted">Order Status:</span>
                    <span class="badge rounded-pill bg-info text-dark fw-bold">{{ ucfirst($order->order_status) }}</span>
                </div>
            </div>

            <div class="alert alert-info mt-4 p-3" style="border-radius: 10px;">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <strong>Action Required:</strong> Please proceed with your FPX payment now. We will begin preparing your order once payment is verified.
            </div>

            <div class="mt-4 d-flex flex-column flex-md-row justify-content-center gap-2">
                @if($storeSetting && $storeSetting->whatsapp_number)
                <a href="https://wa.me/{{ $storeSetting->whatsapp_number }}?text=Hi, I just placed order {{ $order->order_number }}. My total is RM {{ number_format($order->total_amount, 2) }}. Can you confirm payment details?"
                   class="btn whatsapp-btn" target="_blank">
                    <i class="bi bi-whatsapp"></i> Chat with Us
                </a>
                @endif

                <a href="{{ route('customer.index') }}" class="btn btn-primary-theme">
                    <i class="bi bi-house-door-fill"></i> Continue Shopping
                </a>
            </div>

            <div class="mt-4 text-muted small">
                <p>You can refer to order number <strong>{{ $order->order_number }}</strong> for any inquiries.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
