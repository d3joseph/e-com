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

            <div id="products">
                
                <?php $j = 1; ?>
                @if(isset($order_detail))
                @foreach($order_detail as $order_det)
                    <div id='product_{{$j}}'>
                        <div>
                    <label for="product_id_{{$j}}">Product:{{$j}}</label>
                    <select name="product_id[]" id="product_id_{{$j}}">
                        <option value=''>Select Product </option>
                        @foreach ($products as $product)
                            @if($product->id == $order_det->product_id)
                            <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                            @else
                            <option value="{{ $product->id }}" >{{ $product->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="text-danger" style="color:#e03b3b">{{ $errors->first('product_id_'.$j) }}</span>
                    </div>
                    <br>
            
                    <div>
                        <label for="quantity[]">Quantity:</label>
                        <select name="quantity[]" id="quantity_{{$j}}">
                            @for ($i = 1; $i <= 5; $i++)
                        
                                @if($i == $order_det->quantity)
                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                @else
                                    <option value="{{ $i }}" >{{ $i }}</option>
                                @endif
                        
                            @endfor
                        </select>
                        <span class="text-danger" style="color:#e03b3b">{{ $errors->first('quantity_'.$j) }}</span>
                    </div>
                    </div>
                    <?php $j++;?>
                    @endforeach
                @endif
            </div>

            <div>
            <button class='button' id="add_prod">Add Product</button>
            <button class='cancel-button' id="remove_prod">Remove Product</button>

            </div>
            <br><br>
            <div>
                <button class='button' type="submit">Submit</button>
                <a class='cancel-button' href="{{route('orders.index')}}">Cancel</a>
            </div>

        </form>
    </body>
    <script>
        // Get the Add Product and Remove Product buttons
        let addButton = document.getElementById("add_prod");
        let removeButton = document.getElementById("remove_prod");

        // Get the Products div where new products will be added
        let productsDiv = document.getElementById("products");

        // Keep track of the number of products added
        let productCount = {{ $j ?? 1 }};

        // Add a new product input fields when the Add Product button is clicked
        addButton.addEventListener("click", function(e) {
            e.preventDefault();
            // Create a new div to hold the product inputs
            let newProductDiv = document.createElement("div");
            newProductDiv.id = "product_" + productCount;

            // Add the product inputs to the new div
            newProductDiv.innerHTML = `
                <div>
                    <label for="product_id_${productCount}">Product:${productCount}</label>
                    <select name="product_id[]" id="product_id_${productCount}">
                        <option value=''>Select Product </option>
                        @foreach ($products as $product)
                            @if(isset($order) && $product->id == $order->product_id)
                            <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                            @else
                            <option value="{{ $product->id }}" >{{ $product->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="text-danger" style="color:#e03b3b">{{ $errors->first('product_id_${productCount}') }}</span>
                </div>
                <br>
        
                <div>
                    <label for="quantity[]">Quantity:</label>
                    <select name="quantity[]" id="quantity_${productCount}">
                        @for ($i = 1; $i <= 5; $i++)
                    
                            @if(isset($order) && $i == $order->quantity)
                                <option value="{{ $i }}" selected>{{ $i }}</option>
                            @else
                                <option value="{{ $i }}" >{{ $i }}</option>
                            @endif
                    
                        @endfor
                    </select>
                    <span class="text-danger" style="color:#e03b3b">{{ $errors->first('quantity_${productCount}') }}</span>
                </div>
            `;

            // Add the new div to the Products div
            productsDiv.appendChild(newProductDiv);

            // Increment the product count
            productCount++;
        });

        // Remove the last product input fields when the Remove Product button is clicked
        removeButton.addEventListener("click", function(e) {
            e.preventDefault();
            // Decrement the product count
            productCount--;

            // Get the last product div and remove it
            let lastProductDiv = document.getElementById("product_" + productCount);
            productsDiv.removeChild(lastProductDiv);
        });
    </script>

</html>
