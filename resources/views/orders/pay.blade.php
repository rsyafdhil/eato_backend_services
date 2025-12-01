<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment - Order {{ $order->order_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .payment-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .order-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .order-code {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .total-amount {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin: 0;
        }

        .btn-pay {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <div class="text-center">
            <div class="icon">💳</div>
            <h2 class="mb-4">Pembayaran</h2>
        </div>

        <div class="order-info">
            <div class="order-code">Order Code</div>
            <h5 class="mb-3">{{ $order->order_code }}</h5>

            <div class="order-code">Total Pembayaran</div>
            <p class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>

        <div class="text-center mb-4">
            <p class="text-muted mb-0">Klik tombol di bawah untuk melanjutkan pembayaran</p>
        </div>

        <button id="pay-button" class="btn btn-primary btn-pay">
            Bayar Sekarang 🚀
        </button>

        <div class="text-center mt-4">
            <small class="text-muted">Powered by Midtrans Sandbox</small>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('success', result);
                    updateStatus('paid');
                },
                onPending: function(result) {
                    console.log('pending', result);
                    updateStatus('pending');
                },
                onError: function(result) {
                    console.log('error', result);
                    updateStatus('failed');
                },
                onClose: function() {
                    console.log('customer closed the popup without finishing the payment');
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran!');
                }
            });
        };

        function updateStatus(status) {
            fetch("{{ route('orders.updateStatus', $order->id) }}", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    status: status
                })
            }).then(() => {
                window.location.href = "{{ route('fe.orders.show', $order->id) }}";
            });
        }
    </script>
</body>

</html>
