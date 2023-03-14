<!DOCTYPE html>
<html>
    <head>
        <title>Create Product</title>

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
        
        <h1>{{isset($product) ? 'Edit' : 'Add'}} Product</h1>
        
            @if(isset($product))
            <form action="{{ route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put" />
            @else 
            <form action="{{ route('products.store')}}" method="POST" enctype="multipart/form-data">
            @endif
            @csrf

            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value = "{{isset($product)?$product->name : old('name')}}">
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('name') }}</span>
            </div>
            <br>

            <div>
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id">
                    <option value=''>Select Category </option>
                    @foreach ($categories as $category)
                    @if(isset($product) && $product->category_id == $category->id)
                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                    @else
                        <option value="{{ $category->id }}" >{{ $category->name }}</option>
                    @endif
                    @endforeach
                </select>
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('category_id') }}</span>
            </div>
            <br>

            <div>
                <label for="image">Image:</label>
                <input type="file" name="image" class="form-control-file" id="image" required>
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('image') }}</span>
            </div>

            <br>

            <div>
                <label for="price">Price:</label>
                <input type="text" name="price" id="price" value = "{{isset($product)?$product->price : old('price')}}">
                <span class="text-danger" style="color:#e03b3b">{{ $errors->first('price') }}</span>
            </div>
            <br>

            <button class='button' type="submit">Create</button>
            <a class='cancel-button' href="{{route('products.index')}}">Cancel</a>
        </form>
    </body>
</html>
