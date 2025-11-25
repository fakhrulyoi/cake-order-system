{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Orders Management</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-filter="all">All Orders</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-filter="pending">Pending</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-filter="confirmed">Confirmed</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-filter="completed">Completed</button>
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Pickup Date</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr data-status="{{ $order->order_status }}">
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>
                                        <a href="https://wa.me/{{ $order->customer_whatsapp }}"
                                           target="_blank"
                                           class="text-success">
                                            <i class="bi bi-whatsapp"></i> {{ $order->customer_phone }}
                                        </a>
                                    </td>
                                    <td>{{ $order->pickup_datetime->format('d M Y, h:i A') }}</td>
                                    <td><strong>RM {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{
                                            $order->order_status === 'pending' ? 'warning' : (
                                            $order->order_status === 'confirmed' ? 'info' : (
                                            $order->order_status === 'completed' ? 'success' : 'danger')) }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}"
                                           class="btn btn-sm btn-primary"
                                           title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-cart-x" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2">No orders yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;

        // Update active tab
        document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // Filter rows
        document.querySelectorAll('tbody tr[data-status]').forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection

{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order Details: {{ $order->order_number }}</h4>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Orders
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="border-bottom pb-2"><i class="bi bi-person-circle"></i> Customer Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $order->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <th>WhatsApp:</th>
                                    <td>
                                        <a href="https://wa.me/{{ $order->customer_whatsapp }}"
                                           target="_blank"
                                           class="btn btn-success btn-sm">
                                            <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Order Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="border-bottom pb-2"><i class="bi bi-calendar-check"></i> Order Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Order Date:</th>
                                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Pickup Date:</th>
                                    <td><strong>{{ $order->pickup_datetime->format('d M Y, h:i A') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td><strong class="text-primary fs-5">RM {{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><i class="bi bi-basket"></i> Order Items</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cake</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Special Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->cake->name }}</strong><br>
                                            <small class="text-muted">{{ $item->cake->size }}</small>
                                        </td>
                                        <td>RM {{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>RM {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                                        <td>{{ $item->special_notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2"><strong class="text-primary fs-5">RM {{ number_format($order->total_amount, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Special Notes -->
                    @if($order->notes)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><i class="bi bi-sticky"></i> Special Instructions</h5>
                        <div class="alert alert-info">{{ $order->notes }}</div>
                    </div>
                    @endif

                    <!-- Update Status Form -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><i class="bi bi-gear"></i> Update Order Status</h5>
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Order Status</label>
                                    <select name="order_status" class="form-select" required>
                                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Payment Status</label>
                                    <select name="payment_status" class="form-select" required>
                                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
