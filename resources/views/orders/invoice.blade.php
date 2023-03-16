<!DOCTYPE html>
<html>
<head>
	<title>Invoice</title>
    <style>
	.invoice {
		padding: 30px;
	}

	.invoice-header {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-orders: center;
		margin-bottom: 30px;
	}

	.invoice-header h2 {
		margin: 0;
	}

	.invoice-body table {
		width: 100%;
		border-collapse: collapse;
	}

	.invoice-body table th,
	.invoice-body table td {
		padding: 10px;
		border: 1px solid #ddd;
		text-align: left;
	}

	.invoice-body table th {
		background-color: #f5f5f5;
	}

	.invoice-footer {
		margin-top: 30px;
		text-align: right;
	}

	.invoice-footer p {
		margin: 0;
		font-weight: bold;
	}
</style>

</head>
<body>
	<div class="invoice">
		<div class="invoice-header">
			<h2>Invoice #{{ $order->id }}</h2>
			<p>Date: {{ $order->created_at->format('d M Y') }}</p>
		</div>
		<div class="invoice-body">
			<table>
				<thead>
					<tr>
						<th>Product</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Subtotal</th>
					</tr>
				</thead>
				<tbody>
					
					<tr>
						
							<td>@foreach($order_detail as $order_det){{ $order_det->product_name }}<br>@endforeach</td>
						
						<td>@foreach($order_detail as $order_det){{ $order_det->product_price }}<br>@endforeach</td>
						<td>@foreach($order_detail as $order_det){{ $order_det->quantity }}<br>@endforeach</td>
						<td>{{ $order->net_amount }}</td>

					</tr>
					
				</tbody>
			</table>
		</div>
		<div class="invoice-footer">
			<p>Net Amount: {{ $order->net_amount }}</p>
		</div>
	</div>
</body>
</html>
