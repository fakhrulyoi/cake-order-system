<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - {{ $storeSetting->store_name ?? 'Cake Shop' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --theme-color: {{ $storeSetting->theme_color ?? '#14532D' }};
        }

        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .header {
            background: var(--theme-color);
            color: white;
            padding: 1rem;
        }

        .success-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 600px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 5rem;
            color: #10B981;
            margin-bottom: 1rem;
        }

        .order-details {
            background: #F9FAFB;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #E5E7EB;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .item-list {
            margin: 1rem 0;
        }

        .item {
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: var(--theme-color);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            margin: 0.5rem;
        }

        .btn-primary:hover {
            background: #0f3f23;
        }

        .whatsapp-btn {
            background: #25D366;
        }

        .whatsapp-btn:hover {
            background: #20BA5A;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--theme-color);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <h5 class="mb-0">
                <i class="bi bi-check-circle-fill"></i> Order Confirmed
            </h5>
        </div>
    </div>

    <div class="container">
        <div class="success-card">
            <i class="bi bi-check-circle-fill success-icon"></i>
            <h2>Order Successful!</h2>
            <p class="text-muted">Thank you for your order. Your order has been received.</p>

            <div class="order-details">
                <h5 class="mb-3">Order Details</h5>

                <div class="detail-row">
                    <span class="text-muted">Order Number:</span>
                    <strong>{{ $order->order_number }}</strong>
                </div>

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
                    <span class="text-muted">Notes:</span>
                    <strong>{{ $order->notes }}</strong>
                </div>
                @endif

                <hr class="my-3">

                <h6 class="mb-2">Order Items:</h6>
                <div class="item-list">
                    @foreach($order->orderItems as $item)
                    <div class="item">
                        <div class="d-flex justify-content-between">
                            <span>{{ $item->quantity }}x {{ $item->cake->name }}</span>
                            <strong>RM {{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                        @if($item->special_notes)
                        <small class="text-muted">Note: {{ $item->special_notes }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="detail-row mt-3">
                    <span class="fs-5">Total Amount:</span>
                    <span class="total-amount">RM {{ number_format($order->total_amount, 2) }}</span>
                </div>

                <div class="detail-row">
                    <span class="text-muted">Payment Status:</span>
                    <span class="badge bg-warning">{{ ucfirst($order->payment_status) }}</span>
                </div>

                <div class="detail-row">
                    <span class="text-muted">Order Status:</span>
                    <span class="badge bg-info">{{ ucfirst($order->order_status) }}</span>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i>
                <strong>Next Steps:</strong><br>
                Please proceed with payment via FPX. We will confirm your order once payment is received.
            </div>

            <div class="mt-4">
                @if($storeSetting && $storeSetting->whatsapp_number)
                <a href="https://wa.me/{{ $storeSetting->whatsapp_number }}?text=Hi, I just placed order {{ $order->order_number }}"
                   class="btn btn-primary whatsapp-btn" target="_blank">
                    <i class="bi bi-whatsapp"></i> Contact via WhatsApp
                </a>
                @endif

                <a href="{{ route('customer.index') }}" class="btn btn-primary">
                    <i class="bi bi-house-door"></i> Back to Home
                </a>
            </div>

            <div class="mt-4 text-muted small">
                <p>A confirmation will be sent to you shortly. Please save your order number for reference.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
