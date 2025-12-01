<!DOCTYPE html>
<html>

<head>
    <title>Bayar</title>
</head>

<body>
    <h2>Processing Payment...</h2>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        snap.pay("{{ $snapToken }}", {
            onSuccess: function(result) {
                window.location.href = "/payment/status/{{ $orderId }}";
            },
            onPending: function(result) {
                window.location.href = "/payment/status/{{ $orderId }}";
            },
            onError: function(result) {
                window.location.href = "/payment/status/{{ $orderId }}";
            }
        });
    </script>
</body>

</html>
