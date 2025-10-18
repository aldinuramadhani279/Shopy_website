<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-details, .customer-details {
            width: 45%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .company-info {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>Order #{{ $order->order_number }}</p>
    </div>

    <div class="invoice-info">
        <div class="invoice-details">
            <h3>Invoice To:</h3>
            <p>{{ $order->user->name ?? 'N/A' }}</p>
            @php
                $shippingAddress = json_decode($order->shipping_address);
            @endphp
            <p>{{ $shippingAddress->address ?? 'N/A' }}</p>
            <p>{{ $shippingAddress->city ?? 'N/A' }}, {{ $shippingAddress->province ?? 'N/A' }}</p>
            <p>{{ $shippingAddress->postal_code ?? 'N/A' }}</p>
        </div>
        
        <div class="customer-details">
            <h3>Invoice Details:</h3>
            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Product not found' }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p><strong>Subtotal:</strong> ${{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
    </div>

    <div class="company-info">
        <p><strong>Shopy Inc.</strong></p>
        <p>123 Business Street, Suite 100</p>
        <p>Business City, BC 12345</p>
        <p>Email: info@shopy.com | Phone: (123) 456-7890</p>
    </div>
</body>
</html>