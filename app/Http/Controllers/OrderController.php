<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\carbon;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;


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
            'quantity' => 'required',
        ]);
        $net_amount = 0;

        $order = new Order();
        $order->order_id = $this->uniqid();
        $order->customer_name = $inputs['customer_name'];
        $order->phone = $inputs['phone'];
        $order->net_amount = $net_amount;
    
        $order->save();
    
        $i=0;
        foreach($inputs['product_id'] as $product) {
            $order_detail = new OrderDetail;
            $order_detail->order_id = $order->id;
            $order_detail->product_id = $product;
            $order_detail->quantity = $inputs['quantity'][$i];
            $order_detail->save();
            $product = Product::find($product);
            $net_amount += $product->price * $inputs['quantity'][$i];
            $i++;
        }

        Order::where('id', $order->id)->update(['net_amount'=>$net_amount]);

        return redirect()->route('orders.index');
    }

    public function uniqid() {
        $id = rand(10000, 99999);
        if(Order::where('order_id', $id)->first()) {
            return $this->uniqid();
        }
        return $id;
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
        $order_detail = OrderDetail::where('order_id', $id)->get();
        //dd($order_detail);
        return view('orders.form', ['products' => $products, 'order'=>$order, 'order_detail'=> $order_detail]);
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
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
    

        $order = [];
        $order['customer_name'] = $inputs['customer_name'];
        $order['phone'] = $inputs['phone'];
        

        OrderDetail::where('order_id', $id)->delete();
        $i=0;
        $net_amount = 0;
        foreach($inputs['product_id'] as $product) {
            $order_detail = new OrderDetail;
            $order_detail->order_id = $id;
            $order_detail->product_id = $product;
            $order_detail->quantity = $inputs['quantity'][$i];
            $order_detail->save();
            $product = Product::find($product);
            $net_amount += $product->price * $inputs['quantity'][$i];
            $i++;
        }

        $order['net_amount'] = $net_amount;
 
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
        $order = Order::where('orders.id', $orderId)->first();
        $order_detail = OrderDetail::where('order_id', $orderId)
                                ->select('products.name as product_name','products.price as product_price', 'order_details.*')
                                ->join('products', 'products.id','=', 'order_details.product_id')->get();
        //dd($order_detail);
        return view('orders.invoice', compact('order', 'order_detail'));
    }
}
