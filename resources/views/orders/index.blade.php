@extends('layouts.app')
@extends('layouts.nav')
@section('content') 
    <div class="container mt-5">
        <h1 class="mb-4">Order List</h1>
        <div>
            <a href="{{ route('orders.create') }}" class="btn btn-primary" >Add Order</a>
        </div>
        <br>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Net Amount</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->phone }}</td>

                        <td>{{ $order->net_amount }}</td>
                        
                        <td>{{ date('d/m/Y',strtotime($order->created_at)) }}</td>
                        <td>
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
                            <a href="{{ route('invoices.show', ['orderId' => $order->id]) }}" class="btn btn-info">View Invoice</a>

                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
