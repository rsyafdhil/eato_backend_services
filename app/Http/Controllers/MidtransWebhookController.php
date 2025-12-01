<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;

        try {
            $notification = new \Midtrans\Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            // Cari order berdasarkan order_code yang mengandung midtrans order ID
            $order = Order::where('order_code', 'like', '%' . explode('-', $orderId)[0] . '%')->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status
            $status = null;
            if ($transactionStatus == 'settlement') {
                $status = 'paid';
            } else if ($transactionStatus == 'pending') {
                $status = 'pending';
            } else if ($transactionStatus == 'expire') {
                $status = 'cancelled';
            }

            if ($status) {
                Order::where('order_id', $orderId)->update(['status' => $status]);
            }

            return response()->json(['message' => 'Notification handled'], 200);
            Log::info('Risif: ');
            Log::info($request->all());
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
