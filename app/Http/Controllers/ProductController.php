<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /* Function for listing all products */
    public function index() {
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    /* for adding new product */
    public function store(Request $request)
    {
        //dd($request->all());
        $inputs = $request->all();
        if(!$request->file('image')) {
            return redirect()->backWithInput();
        }
        $image_name = $this->fileUpload($request->file('image'), 'image', '/image');
        $product = new Product();
        $product->name = $inputs['name'];
        $product->category_id = $inputs['category_id'];
        $product->image = $image_name;
        $product->price = $inputs['price'];
        $product->save();
    
        return redirect()->route('products.index');
    }
    

    public function create()
    {
        $categories = Category::all();
        return view('products.form', ['categories' => $categories]);
    }

    public function edit($id) {
        $categories = Category::all();
        $product = Product::find($id);
        return view('products.form', ['categories' => $categories, 'product' => $product]);
    }

    public function destroy($id) {
        return 'destroy';
    }

    public function update(Request $request, $id) {
        dd($id);
        dd($request->all());
        $validator = $request->validate([
            'name'=>'required|max:20',
            'category_id' => 'required',
            'price' => 'required|number'
        ],[
            'name.required'=>'Name field is required',
            'name.max' => 'Name cannot be more than 20 charecters',
            'price.required' => 'Price field is required',
            'price.number' => 'Price must be a Number'
        ]);

        if ($validator->fails())
        {
          return redirect()->back()->withInput()->withErrors($v->errors());
        }
        $inputs = $request->all();
        $product = [];
        $product['name'] = $inputs['name'];
        $product['category_id'] = $inputs['category_id'];
        $product['price'] = $inputs['price'];
        if(!$request->file('image')) {
            $image_name = $this->fileUpload($request->file('image'), 'image', '/image');
            $product['image'] = $image_name;
        }
        
        Product::where('id', $id)->update($product);

        // return redirect()->route('products.index');

    }

    public static function fileUpload($requestFile, $fileName, $path)
    {
        $fileName = date('Ymdhis') . '-' . $fileName . '.' . $requestFile->getClientOriginalExtension();
        \Storage::disk('public')->put($path . '/' . $fileName, file_get_contents($requestFile->getRealPath()), 'public');
        return $fileName;
    }

    public function show() {
        dd("hi");
        //
        // return redirect()->route('products.index');

    }
}
