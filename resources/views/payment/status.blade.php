<!DOCTYPE html>
<html>

<head>
    <title>Status Pembayaran</title>
</head>

<body>
    <h2>Status Pembayaran</h2>

    <p>Order ID: {{ $status->order_id }}</p>
    <p>Status: {{ $status->transaction_status }}</p>
    <p>Gross Amount: {{ $status->gross_amount }}</p>
</body>

</html>
