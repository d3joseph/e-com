<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\carbon;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('deleted_at', null)->get();
        return view('orders.index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('orders.form', ['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required|min:10|max:10',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    

        $product = Product::find($inputs['product_id']);
        $net_amount = $product->price * $inputs['quantity'];
        $order = new Order();
        $order->order_id = rand(10000, 99999);
        $order->customer_name = $inputs['customer_name'];
        $order->phone = $inputs['phone'];
        $order->net_amount = $net_amount;
        $order->quantity = $inputs['quantity'];
        $order->product_id = $inputs['product_id'];

    
    
        $order->save();
    
        return redirect()->route('orders.index');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::find($id);
        $products = Product::all();
        return view('orders.form', ['products' => $products, 'order'=>$order]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inputs = $request->all();
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    

        $product = Product::find($inputs['product_id']);
        $net_amount = $product->price * $inputs['quantity'];
        $order = [];
        $order['customer_name'] = $inputs['customer_name'];
        $order['phone'] = $inputs['phone'];
        $order['net_amount'] = $net_amount;
        $order['quantity'] = $inputs['quantity'];
        $order['product_id'] = $inputs['product_id'];
 
        Order::where('id', $id)->update($order);
    
        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if($order) {
            Order::where('id', $id)->update(['deleted_at' => Carbon::now()]);
            return redirect()->back();
        }
    }

    public function showInvoice($orderId) {
        $order = Order::where('orders.id', $orderId)
                        ->select('orders.*', 'products.name as product_name', 'products.price')
                        ->join('products', 'products.id', '=', 'orders.product_id')->first();
        return view('orders.invoice', compact('order'));
    }
}
