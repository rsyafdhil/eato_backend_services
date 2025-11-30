<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);


        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($request->items as $cartItem) {
                $item = Item::find($cartItem['item_id']);
                $total += $item->price * $cartItem['quantity'];
            }

            $order = Order::create([
                'user_id' => $request->user_id,
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => 'qris'
            ]);

            // dd($order->order_code);

            // simpan order items
            foreach ($request->items as $cartItem) {
                $item = Item::find($cartItem['item_id']);

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'quantity' => $cartItem['quantity'],
                    'price' => $item->price,
                    'subtotal' => $item->price * $cartItem['quantity']
                ]);
            }

            // dd($orderItem->item_name);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order->load('items'),
                'order_code' => $order->order_code
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function show(Order $order)
    {
        $order->load(['order_items', 'order_items.item']);


        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('orders.index', compact('orders'));
    }
}
