<!DOCTYPE html>
<html>
    <head>
        <title>Create Order</title>

        <style>
            body {
                font-family: Arial, sans-serif;
            }

            form {
                width: 400px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }

            h1 {
                text-align: center;
            }

            label {
                display: block;
                margin-bottom: 10px;
                font-weight: bold;
            }

            input[type="text"],
            select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                margin-bottom: 20px;
                box-sizing: border-box;
            }

            .button {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .cancel-button {
                background-color: #e03b3b;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .button:hover {
                background-color: #3e8e41;
            }

            .button:hover {
                background-color: #3e8e41;
            }
        </style>
    </head>
    <body>
        
        <h1>{{isset($order) ? 'Edit' : 'Add'}} Order</h1>
        
            @if(isset($order))
            <form action="{{ route('orders.update', $order->id)}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put" />
            @else 
            <form action="{{ route('orders.store')}}" method="POST" enctype="multipart/form-data">
            @endif
            @csrf

            <div>
                <label for="name">Customer Name:</label>
                <input type="text" name="customer_name" id="customer_name" value = "{{isset($order)?$order->customer_name : old('customer_name')}}">
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('customer_name') }}</span>
            </div>
            <br>

            <div>
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" value = "{{isset($order)?$order->phone : old('phone')}}">
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('phone') }}</span>
            </div>

            <div>
                <label for="product_id">Product:</label>
                <select name="product_id" id="product_id">
                    <option value=''>Select Product </option>
                    @foreach ($products as $product)
                        @if(isset($order) && $product->id == $order->product_id)
                        <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                        @else
                        <option value="{{ $product->id }}" >{{ $product->name }}</option>
                        @endif
                    @endforeach
                </select>
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('product_id') }}</span>
            </div>
            <br>

            <div>
                <label for="quantity">Quantity:</label>
                <select name="quantity" id="quantity">
                    @for ($i = 1; $i <= 5; $i++)
                 
                        @if(isset($order) && $i == $order->quantity)
                            <option value="{{ $i }}" selected>{{ $i }}</option>
                        @else
                            <option value="{{ $i }}" >{{ $i }}</option>
                        @endif
                  
                    @endfor
                </select>
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('quantity') }}</span>
            </div>
            <div>
                <button class='button' type="submit">Submit</button>
                <a class='cancel-button' href="{{route('orders.index')}}">Cancel</a>
            </div>

        </form>
    </body>
</html>
