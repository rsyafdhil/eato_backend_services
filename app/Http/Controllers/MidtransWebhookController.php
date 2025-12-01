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

            try {
                $notification = new \Midtrans\Notification();
                Log::info('Midtrans signature verification succeeded.');
            } catch (\Exception $e) {
                Log::warning('Midtrans signature verification failed: ' . $e->getMessage());
                return response()->json(['message' => 'Signature verification failed'], 401);
            }

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            // Extract the real order ID from the Midtrans order ID
            // Format in OrderController: $order->order_code . '-' . time();
            // Example: ORD-ABC12345-1630000000
            // We need to remove the last part (timestamp) to get the order_code (ORD-ABC12345)
            
            // Use regex to robustly remove the last hyphen followed by digits (timestamp)
            $orderCode = preg_replace('/-\d+$/', '', $orderId);

            $order = Order::where('order_code', $orderCode)->first();
            
            // Fallback: Try exact match just in case
            if (!$order) {
                $order = Order::where('order_code', $orderId)->first();
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
