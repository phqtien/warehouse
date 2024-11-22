<!DOCTYPE html>
<html>
<head>
    <title>New Order Notification</title>
</head>
<body>
    <h1>A new order has been created</h1>
    <p><strong>Order ID:</strong> {{ $saleOrder->id }}</p>

    <h3>Customer Information:</h3>
    <p><strong>Customer:</strong> {{ $saleOrder->customer_id }}</p>
    <p><strong>Status:</strong> {{ $saleOrder->status }}</p>

    <h3>Order Details:</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Warehouse</th>
                <th>Shelf</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saleOrderDetails as $detail)
                <tr>
                    <td>{{ $detail->product_id }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->warehouse_id }}</td>
                    <td>{{ $detail->shelf_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Thank you for using our service!</p>
</body>
</html>
