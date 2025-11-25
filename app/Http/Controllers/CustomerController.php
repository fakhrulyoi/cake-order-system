<?php

namespace App\Http\Controllers;

use App\Models\Cake;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $storeSetting = StoreSetting::first();
        $cakes = Cake::where('status', 'available')->get();

        return view('customer.index', compact('cakes', 'storeSetting'));
    }

    public function cart()
    {
        return view('customer.cart');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_whatsapp' => 'nullable|string|max:20',
            'pickup_datetime' => 'required|date|after:now',
            'notes' => 'nullable|string',
            'cart' => 'required|json'
        ]);

        $cart = json_decode($validated['cart'], true);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty!');
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            // Calculate total
            foreach ($cart as $item) {
                $cake = Cake::findOrFail($item['id']);
                $totalAmount += $cake->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_whatsapp' => $validated['customer_whatsapp'] ?? $validated['customer_phone'],
                'pickup_datetime' => $validated['pickup_datetime'],
                'notes' => $validated['notes'],
                'total_amount' => $totalAmount,
                'payment_status' => 'unpaid',
                'order_status' => 'pending'
            ]);

            // Create order items
            foreach ($cart as $item) {
                $cake = Cake::findOrFail($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'cake_id' => $cake->id,
                    'quantity' => $item['quantity'],
                    'price' => $cake->price,
                    'special_notes' => $item['notes'] ?? null
                ]);
            }

            DB::commit();

            // Generate WhatsApp message
            $whatsappMessage = $this->generateWhatsAppMessage($order);
            $adminWhatsApp = '60142153722'; // Admin WhatsApp number with country code

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'whatsapp_url' => "https://wa.me/{$adminWhatsApp}?text=" . urlencode($whatsappMessage),
                'redirect' => route('customer.order-success', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Order failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateWhatsAppMessage($order)
    {
        $message = "🎂 *NEW CAKE ORDER* 🎂\n\n";
        $message .= "📋 *Order Number:* {$order->order_number}\n";
        $message .= "👤 *Customer:* {$order->customer_name}\n";
        $message .= "📱 *Phone:* {$order->customer_phone}\n";
        $message .= "📅 *Pickup:* " . $order->pickup_datetime->format('d M Y, h:i A') . "\n\n";

        $message .= "🍰 *Items Ordered:*\n";
        foreach ($order->orderItems as $item) {
            $message .= "• {$item->quantity}x {$item->cake->name} - RM " . number_format($item->price * $item->quantity, 2) . "\n";
            if ($item->special_notes) {
                $message .= "  _(Note: {$item->special_notes})_\n";
            }
        }

        $message .= "\n💰 *Total Amount:* RM " . number_format($order->total_amount, 2) . "\n";

        if ($order->notes) {
            $message .= "\n📝 *Special Instructions:*\n{$order->notes}\n";
        }

        $message .= "\n✅ Please confirm this order!";

        return $message;
    }

    public function orderSuccess($orderId)
    {
        $order = Order::with('orderItems.cake')->findOrFail($orderId);
        $storeSetting = StoreSetting::first();

        return view('customer.order-success', compact('order', 'storeSetting'));
    }
}
