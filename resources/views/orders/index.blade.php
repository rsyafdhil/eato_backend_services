<!DOCTYPE html>
<html>

<head>
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Orders</h2>


        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order Code</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('fe.orders.show', $order->id) }}" class="btn btn-primary btn-sm">Show</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
