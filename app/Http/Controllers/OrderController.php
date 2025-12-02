<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            // Hitung total
            $total = 0;
            foreach ($request->items as $cartItem) {
                $item = Item::find($cartItem['item_id']);
                $total += $item->price * $cartItem['quantity'];
            }

            // Bikin order
            $order = Order::create([
                'user_id' => $request->user_id,
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => 'qris',
                'status_pemesanan' => 'dipesan'
            ]);

            // Simpan order items
            foreach ($request->items as $cartItem) {
                $item = Item::find($cartItem['item_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'quantity' => $cartItem['quantity'],
                    'price' => $item->price,
                    'subtotal' => $item->price * $cartItem['quantity']
                ]);
            }

            // ===== MIDTRANS QRIS (Core API) =====
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;

            $midtransOrderId = $order->order_code . '-' . time();
            $user = \App\Models\User::find($request->user_id);

            $params = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ];

            // Charge pakai Core API
            $charge = \Midtrans\CoreApi::charge($params);
            $qrString = $charge->qr_string;

            // Ambil QR Code URL
            $qrCodeUrl = $charge->actions[0]->url ?? null;

            // Update order
            $order->update([
                'payment_url' => $qrCodeUrl,
                'qr_string' => $qrString,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_code' => $order->order_code,
                    'total_amount' => $total,
                    'qr_code_url' => $qrCodeUrl,
                    'status' => 'pending',
                    'qr_string' => $qrString
                ],
                'order' => $order
            ]);

            // dd($qrCodeUrl);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getUserCred()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    public function checkStatus($id)
    {
        try {
            $order = Order::findOrFail($id);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'status' => $order->status,
                'order_code' => $order->order_code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }

    public function show(Order $order)
    {
        $order->load(['order_items', 'order_items.item']);


        return view('orders.show', compact('order'));
    }

    public function getOrderDetails($id)
    {
        $user = Auth::user();

        // Cari order
        $order = Order::with(['order_items.item'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Authorization: User hanya bisa lihat order miliknya
        // Admin bisa lihat semua order
        if ($user->role->name === 'user' && $order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Format response
        $orderData = [
            'id' => $order->id,
            'order_code' => $order->order_code,
            'user_id' => $order->user_id,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
            'items' => $order->order_items->map(function ($item) {
                return [
                    'item_id' => $item->item_id,
                    'item_name' => $item->item->item_name ?? 'Unknown',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $orderData
        ]);
    }

    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // OrderController.php
    public function getUserOrders(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::with('role')->find(Auth::id());

        $roleName = strtolower($user->role->name);

        if ($roleName === 'user') {
            // User: hanya order miliknya
            $orders = Order::where('user_id', $user->id)
                ->with(['order_items.item'])
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($roleName === 'admin') {
            // Admin: semua order
            $orders = Order::with(['order_items.item'])
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($roleName === 'owner') {
            // Owner: hanya order yang berisi item dari merchant miliknya
            $merchantId = $user->tenant_id;

            if (!$merchantId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant ID not found for this owner'
                ], 403);
            }

            // Ambil orders yang memiliki items dari merchant ini
            $orders = Order::whereHas('order_items.item', function ($query) use ($merchantId) {
                $query->where('tenant_id', $merchantId);
            })
                ->with(['order_items.item'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role'
            ], 403);
        }

        // Format response
        $formattedOrders = $orders->map(function ($order) use ($roleName, $user) {
            // Untuk owner, filter hanya items dari merchant mereka
            $items = $order->order_items;

            if ($roleName === 'owner') {
                $items = $order->order_items->filter(function ($orderItem) use ($user) {
                    return $orderItem->item && $orderItem->item->tenant_id === $user->tenant_id;
                });
            }

            return [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'status_pemesanan' => $order->status_pemesanan ?? 'dipesan',
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at->format('d M Y H:i'),
                'items_count' => $items->count(),
                'items' => $items->map(function ($item) {
                    return [
                        'item_id' => $item->item_id,
                        'item_name' => $item->item->item_name ?? 'Unknown',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                })->values() // Reset array keys
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedOrders
        ]);
    }


    public function updateStatusPemesanan(Request $request, $orderId)
    {

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // dd([
        //     'user' => Auth::user(),
        //     'role' => Auth::user()->role ?? 'NO ROLE',
        //     'role_name' => Auth::user()->role->name ?? 'NO ROLE NAME',
        // ]);

        $user = User::with('role')->find(Auth::id());
        $roleName = strtolower($user->role->name);

        if ($roleName !== 'owner') {
            return response()->json([
                'success' => false,
                'message' => 'Only owner can update order status'
            ], 403);
        }

        // match ENUM database!
        $request->validate([
            'status_pemesanan' =>
            'required|in:dipesan,dimasak,diantarkan,selesai,batal'
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $hasOwnerItem = $order->order_items()->whereHas('item', function ($query) use ($user) {
            $query->where('tenant_id', $user->tenant_id);
        })->exists();

        if (!$hasOwnerItem) {
            return response()->json([
                'success' => false,
                'message' => 'You can only update orders from your merchant'
            ], 403);
        }

        $order->status_pemesanan = $request->status_pemesanan;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => [
                'order_id' => $order->id,
                'status_pemesanan' => $order->status_pemesanan
            ]
        ]);
    }


    public function updateStatusMerchant(Request $request, $id, $merchantId)
    {
        $request->validate([
            'status_pemesanan' => 'required|in:dipesan,dimask,diantarkan',
        ]);

        try {
            $order = Order::whereHas('order_items.item', function ($q) use ($merchantId) {
                $q->where('tenant_id', $merchantId);
            })->findOrFail($id);

            $order->update([
                'status_pemesanan' => $request->status_pemesanan,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pemesanan berhasil diupdate',
                'data' => [
                    'order_id' => $order->id,
                    'status_pemesanan' => $order->status_pemesanan,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan atau bukan milik merchant ini',
            ], 404);
        }
    }

    public function getMerchantOrders($merchantId)
    {
        $orders = Order::whereHas('order_items.item', function ($query) use ($merchantId) {
            $query->where('tenant_id', $merchantId);
        })
            ->with(['order_items', 'order_items.item', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedOrders = $orders->map(function ($order) use ($merchantId) {
            return [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'status' => $order->status,
                'status_pemesanan' => $order->status_pemesanan,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at->format('d M Y, H:i'),
                'items' => $order->order_items->filter(function ($item) use ($merchantId) {
                    return $item->item->tenant_id == $merchantId;
                })->map(function ($item) {
                    return [
                        'item_name' => $item->item_name,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedOrders
        ]);
    }

    public function updateStatusItem(Request $request, $orderItemId)
    {
        $request->validate([
            'status_pemesanan' => 'required|in:dipesan,dimask,diantarkan',
        ]);

        try {
            $orderItem = OrderItem::with('item')->findOrFail($orderItemId);

            // pastikan item ini milik tenant yang mengupdate
            if ($orderItem->item->tenant_id != auth()->user()->tenant_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah item ini'
                ], 403);
            }

            $orderItem->update([
                'status_pemesanan' => $request->status_pemesanan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pemesanan berhasil diupdate',
                'data' => [
                    'order_item_id' => $orderItem->id,
                    'status_pemesanan' => $orderItem->status_pemesanan
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Item order tidak ditemukan'
            ], 404);
        }
    }
}
