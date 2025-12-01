<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            Log::info('Midtrans Webhook Received:');
            Log::info($request->all());

            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $notification = new \Midtrans\Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            // Extract the real order ID from the Midtrans order ID (e.g., "ORDER-123-TIMESTAMP" -> "ORDER-123")
            // Assuming order_code in DB matches the prefix of Midtrans order_id
            $orderCode = explode('-', $orderId)[0]; 
            
            // Or if the order_code IS the order_id passed to Midtrans:
            // $order = Order::where('order_code', $orderId)->first();
            
            // Based on previous code: $order = Order::where('order_code', 'like', '%' . explode('-', $orderId)[0] . '%')->first();
            // This suggests order_code might be just the ID part. Let's try to find it more robustly.
            
            $order = Order::where('order_code', $orderId)->first();
            
            if (!$order) {
                 // Try splitting if direct match fails, assuming format like "CODE-TIMESTAMP"
                 $possibleOrderCode = explode('-', $orderId)[0];
                 $order = Order::where('order_code', $possibleOrderCode)->first();
            }

            if (!$order) {
                Log::error("Order not found for Midtrans Order ID: $orderId");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status
            $status = null;
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $status = 'paid';
                }
            } else if ($transactionStatus == 'settlement') {
                $status = 'paid';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $status = 'cancelled';
            } else if ($transactionStatus == 'pending') {
                $status = 'pending';
            }

            if ($status) {
                $order->update(['status' => $status]);
                Log::info("Order {$order->order_code} status updated to $status");
            }

            return response()->json(['message' => 'Notification handled'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
