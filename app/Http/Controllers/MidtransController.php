<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Generate Snap Token
    public function createTransaction(Request $request)
    {
        $orderId = 'ORDER-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.pay', [
            'snapToken' => $snapToken,
            'orderId' => $orderId
        ]);
    }

    // User akan diarahkan ke halaman ini setelah Snap selesai
    public function checkStatus($orderId)
    {
        $status = Transaction::status($orderId);

        return view('payment.status', [
            'status' => $status
        ]);
    }
}
